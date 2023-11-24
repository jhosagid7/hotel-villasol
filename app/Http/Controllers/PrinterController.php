<?php

namespace App\Http\Controllers;

use App\Caja;


use App\Articulo;
use App\Servicio;
use Carbon\Carbon;
use App\Detalle_credito;
use Mike42\Escpos\Printer;

use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrinterController extends Controller
{

    public $print_error     = 0;
    public $print_name      = '';
    protected $machine_user = '';
    protected $machine_pass = '';
    protected $machine_name = '';
    protected $network      = false;
    protected $print_route  = '';
    // Metodo para imprimir Ticket de Servicios

    public function ticketServicio($tipoOperasion, $numeroServisio, $nombreHabitacion, $detalleHabitacion, $modo_pago, $tipo_pago, $total_costo, $operador, $tipo)
    {

        //generamos el codigo de barra a 7 caracteres
        // $folio = str_pad($request->id,7,'0',STR_PAD_LEFT);//Ej; 0000001

        /*
            Aquí, en lugar de "POS" (que es el nombre de mi impresora)
            escribe el nombre de la tuya. Recuerda que debes compartirla
            desde el panel de control
        */
        // $this->print_name = "AnyDesk-Printer";
        // $this->print_name = "POS5890";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;

        try {
            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);
            #Mando un numero de respuesta para saber que se conecto correctamente.
            echo 1;
            /*
            Vamos a imprimir un logotipo
            opcional. Recuerda que esto
            no funcionará en todas las
            impresoras

            Pequeña nota: Es recomendable que la imagen no sea
            transparente (aunque sea png hay que quitar el canal alfa)
            y que tenga una resolución baja. En mi caso
            la imagen que uso es de 250 x 250
        */

            # Vamos a alinear al centro lo próximo que imprimamos
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            /*
            Intentaremos cargar e imprimir
            el logo
        */
            try {
                $logo = EscposImage::load("geek.png", false);
                $printer->bitImage($logo);
            } catch (\Exception $e) {/*No hacemos nada si hay error*/
            }

            /*
            Ahora vamos a imprimir un encabezado
        */

            $printer->text("\n" . $tipoOperasion . " N°: " . $numeroServisio . "\n");
            $printer->text("Modo: " . $modo_pago . " Tipo: " . $tipo_pago . "\n");
            $printer->text($operador . "\n");
            #La fecha también
            date_default_timezone_set("America/Caracas");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("N°Hb DESCRIPCION    P.U   .\n");
            $printer->text("-----------------------------" . "\n");
            /*
            Ahora vamos a imprimir los
            productos
        */
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("" . $nombreHabitacion . " " . $detalleHabitacion . " $." . number_format($total_costo, 2) . " \n");
            // $printer->text( "2  pieza    ".$total_costo."   \n");

            /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: $." . number_format($total_costo, 2) . "\n");
            // $printer->text("IVA: $16.00\n");
            $printer->text("TOTAL: $." . number_format($total_costo, 2) . "\n");


            /*
            Podemos poner también un pie de página
        */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($tipo . "\n");



            /*Alimentamos el papel 3 veces*/
            $printer->feed(3);

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
            $printer->pulse();

            /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }

    public function ticketConsumo($tipoOperasion, $articulo_id, $serie_comprobante, $precio_venta_unidad, $cantidad, $modo_pago, $tipo_pago, $total_venta, $operador)
    {

        // $this->print_name = "AnyDesk-Printer";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;

        try {
            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);

            echo 1;

            $printer->setJustification(Printer::JUSTIFY_CENTER);


            try {
                $logo = EscposImage::load("geek.png", false);
                $printer->bitImage($logo);
            } catch (\Exception $e) {/*No hacemos nada si hay error*/
            }


            $printer->text("\n" . $tipoOperasion . " N°: " . $serie_comprobante . "\n");
            $printer->text("Modo: " . $modo_pago . " Tipo: " . $tipo_pago . "\n");
            $printer->text($operador . "\n");

            date_default_timezone_set("America/Caracas");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT DESCRIPCION    P.U   .\n");
            $printer->text("-----------------------------" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //creamos un contador
            $cont = 0;
            $nombre = '';

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {

                $articulo = Articulo::findOrFail($articulo_id[$cont]);
                $nombre = $articulo->nombre;
                // dd($articulo->nombre);
                $printer->text("" . $cantidad[$cont] . " " . $articulo->descripcion . " $." . number_format($cantidad[$cont] * $precio_venta_unidad[$cont], 2) . " \n");

                $cont = $cont + 1;
            }

            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: $." . number_format($total_venta, 2) . "\n");

            $printer->text("TOTAL: $." . number_format($total_venta, 2) . "\n");



            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Gracias por su compra \n");




            $printer->feed(3);


            $printer->cut();


            $printer->pulse();


            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }


    public function ticketCreditos($tipoOperasion, $facturas_pagadas_id, $nombreCliente, $modo_pago, $tipo_pago, $monto_dejado, $operador, $facturas_pagadas, $idFacturasPagadas)
    {

        // $this->print_name = "AnyDesk-Printer";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;
        $this->network = false;

        try {
            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);

            echo 1;

            $printer->setJustification(Printer::JUSTIFY_CENTER);


            try {
                $logo = EscposImage::load("geek.png", false);
                $printer->bitImage($logo);
            } catch (\Exception $e) {/*No hacemos nada si hay error*/
            }


            $printer->text("\n " . $tipoOperasion . " \n");
            $printer->text("Modo: " . $modo_pago . " Tipo: " . $tipo_pago . "\n");
            $printer->text($operador . "\n");

            date_default_timezone_set("America/Caracas");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT DESCRIPCION    P.U   .\n");
            $printer->text("-----------------------------" . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //Buscamos todos los ides de la tabla detalle_credito que pertenecen al la tabla credito por medio del id



            if ($facturas_pagadas == 'una') {


                // Llenamos la tabla Creditos_pagados
                $detalle_credito_datos = Detalle_credito::findOrFail($facturas_pagadas_id);


                $printer->text("" . $detalle_credito_datos->tipo_operacion . " " . $detalle_credito_datos->numero_factura . " $." . number_format($detalle_credito_datos->monto, 2) . " \n");
            }

            if ($facturas_pagadas == 'todas') {

                //Buscamos todos los ides de la tabla detalle_credito que pertenecen al la tabla credito por medio del id

                // $creditos_ids = Detalle_credito::where('id',$idFacturasPagadas)->get();

                // return $creditos_ids;

                foreach ($idFacturasPagadas as $ids) {


                    $detalle_credito_datos = Detalle_credito::findOrFail($ids);
                    $printer->text("" . $detalle_credito_datos->tipo_operacion . " " . $detalle_credito_datos->numero_factura . " $." . number_format($detalle_credito_datos->monto, 2) . " \n");
                }
            }


            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: $." . number_format($monto_dejado, 2) . "\n");

            $printer->text("TOTAL: $." . number_format($monto_dejado, 2) . "\n");



            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("CLiente: " . $nombreCliente->nombre . " \n");




            $printer->feed(3);


            $printer->cut();


            $printer->pulse();


            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }

    public function ticketServicioCambio($titulo, $tipoOperasion, $numeroServisio, $nombreHabitacionCambio, $nombreHabitacion, $detalleHabitacion, $modo_pago, $tipo_pago, $total_costo, $operador, $tipo)
    {

        //generamos el codigo de barra a 7 caracteres
        // $folio = str_pad($request->id,7,'0',STR_PAD_LEFT);//Ej; 0000001

        /*
            Aquí, en lugar de "POS" (que es el nombre de mi impresora)
            escribe el nombre de la tuya. Recuerda que debes compartirla
            desde el panel de control
        */

        // $this->print_name = "AnyDesk-Printer";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;

        try {
            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);
            #Mando un numero de respuesta para saber que se conecto correctamente.
            echo 1;
            /*
            Vamos a imprimir un logotipo
            opcional. Recuerda que esto
            no funcionará en todas las
            impresoras

            Pequeña nota: Es recomendable que la imagen no sea
            transparente (aunque sea png hay que quitar el canal alfa)
            y que tenga una resolución baja. En mi caso
            la imagen que uso es de 250 x 250
        */

            # Vamos a alinear al centro lo próximo que imprimamos
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            /*
            Intentaremos cargar e imprimir
            el logo
        */
            try {
                $logo = EscposImage::load("geek.png", false);
                $printer->bitImage($logo);
            } catch (\Exception $e) {/*No hacemos nada si hay error*/
            }

            /*
            Ahora vamos a imprimir un encabezado
        */

            $printer->text("\n" . $titulo . " " . $nombreHabitacionCambio . "\n");
            $printer->text("" . $tipoOperasion . " N°: " . $numeroServisio . "\n");
            $printer->text("Modo: " . $modo_pago . " Tipo: " . $tipo_pago . "\n");
            $printer->text($operador . "\n");
            #La fecha también
            date_default_timezone_set("America/Caracas");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("N°Hb DESCRIPCION    P.U   .\n");
            $printer->text("-----------------------------" . "\n");
            /*
            Ahora vamos a imprimir los
            productos
        */
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("" . $nombreHabitacion . " " . $detalleHabitacion . " $." . number_format($total_costo, 2) . " \n");
            // $printer->text( "2  pieza    ".$total_costo."   \n");

            /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: $." . number_format($total_costo, 2) . "\n");
            // $printer->text("IVA: $16.00\n");
            $printer->text("TOTAL: $." . number_format($total_costo, 2) . "\n");


            /*
            Podemos poner también un pie de página
        */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($tipo . "\n");



            /*Alimentamos el papel 3 veces*/
            $printer->feed(3);

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
            $printer->pulse();

            /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }


    public function ticketPagoExtra($titulo, $tipoOperasion, $verificarHorasExtras)
    {

        //generamos el codigo de barra a 7 caracteres
        // $folio = str_pad($request->id,7,'0',STR_PAD_LEFT);//Ej; 0000001

        /*
            Aquí, en lugar de "POS" (que es el nombre de mi impresora)
            escribe el nombre de la tuya. Recuerda que debes compartirla
            desde el panel de control
        */
        $operador = Auth::user()->name;
        // $this->print_name = "AnyDesk-Printer";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;

        try {
            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);
            #Mando un numero de respuesta para saber que se conecto correctamente.
            echo 1;
            /*
            Vamos a imprimir un logotipo
            opcional. Recuerda que esto
            no funcionará en todas las
            impresoras

            Pequeña nota: Es recomendable que la imagen no sea
            transparente (aunque sea png hay que quitar el canal alfa)
            y que tenga una resolución baja. En mi caso
            la imagen que uso es de 250 x 250
        */

            # Vamos a alinear al centro lo próximo que imprimamos
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            /*
            Intentaremos cargar e imprimir
            el logo
        */
            try {
                $logo = EscposImage::load("geek.png", false);
                $printer->bitImage($logo);
            } catch (\Exception $e) {/*No hacemos nada si hay error*/
            }

            /*
            Ahora vamos a imprimir un encabezado
        */

            $printer->text("\n" . $titulo . " " . $verificarHorasExtras->nombre_habitacion . "\n");
            $printer->text("" . $tipoOperasion . " N°: " . $verificarHorasExtras->num_servicio . "\n");
            $printer->text("Modo: " . $verificarHorasExtras->modo_pago . " Tipo: " . $verificarHorasExtras->tipo_pago . "\n");
            $printer->text($operador . "\n");
            #La fecha también
            date_default_timezone_set("America/Caracas");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("N° DESCRIPCION    P.U   .\n");
            $printer->text("-----------------------------" . "\n");
            /*
            Ahora vamos a imprimir los
            productos
        */
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            if ($verificarHorasExtras->monto_total_hora_extra > 0) {
                $printer->text("" . $verificarHorasExtras->cantidad_hora_extra . " Horas Extras a $" . $verificarHorasExtras->precio_hora_extra . " $." . number_format($verificarHorasExtras->monto_total_hora_extra, 2) . " \n");
            }

            if ($verificarHorasExtras->otros_montos > 0) {
                $printer->text("" . $verificarHorasExtras->detalle_otros_montos . " $." . number_format($verificarHorasExtras->otros_montos, 2) . " \n");
            }
            //$printer->text("".$nombreHabitacion." ".$detalleHabitacion." $.".number_format($total_costo,2)." \n");
            // $printer->text( "2  pieza    ".$total_costo."   \n");

            /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: $." . number_format($verificarHorasExtras->total_horas_extras_otros_montos, 2) . "\n");
            // $printer->text("IVA: $16.00\n");
            $printer->text("TOTAL: $." . number_format($verificarHorasExtras->total_horas_extras_otros_montos, 2) . "\n");


            /*
            Podemos poner también un pie de página
        */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->text($tipo."\n");



            /*Alimentamos el papel 3 veces*/
            $printer->feed(3);

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
            $printer->pulse();

            /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }

    public function ticketResumenCaja($id)
    {
        // return $id;




        // $this->print_name = "AnyDesk-Printer";
        $this->print_name = "POS-58-Series";
        $this->machine_user = "Administrador";
        $this->machine_pass = "pass";
        $this->machine_name = "INTEL";
        $this->network = false;

        try {

            if ($this->network) {
                $this->print_route = "smb://$this->machine_user:$this->machine_pass@$this->machine_name/$this->print_name";
            } else {
                $this->print_route = $this->print_name;
            }

            // $nombre_impresora = "POS5890";


            $connector = new WindowsPrintConnector($this->print_route);
            $printer = new Printer($connector);

            // echo 1;

            $printer->setJustification(Printer::JUSTIFY_CENTER);


            try {
                $cajaCierre =  Caja::where('id', $id)->with('servicios', 'ventas', 'excedente_actual', 'horas_extras', 'historial_vueltos_pendientes', 'historialExcedentes')->first();
                // return $cajaCierre;
                date_default_timezone_set("America/Caracas");
                // $date = strtotime($cajaCierre->created_at);
                // $fechaInicioCaja = date_format($date, 'Y-m-d H:i:s');
                // $date = strtotime($cajaCierre->updated_at);
                // $fechaCierreCaja = date_format($date,'Y-m-d H:i:s');
                $fechaInicioCaja = date('d-m-Y h:i:s', strtotime($cajaCierre->created_at));
                $fechaCierreCaja = date('d-m-Y h:i:s',strtotime($cajaCierre->updated_at));
                $numeroCaja = $cajaCierre->codigo;
                $operadorCaja = $cajaCierre->user->name;


                //Servicios
                $numServiciosContadoCaja = $cajaCierre->servicios->where('estado', 'Aceptada')->where('modo_pago', 'Contado')->count();
                // dd($numServiciosContadoCaja);
                $numServiciosCreditoCaja = $cajaCierre->servicios->where('estado', 'Aceptada')->where('modo_pago', 'Credito')->count();
                $numServiciosCortesiaCaja = $cajaCierre->servicios->where('estado', 'Aceptada')->where('modo_pago', 'Cortesía')->count();
                $totalNumServiciosCortesiaCaja = $cajaCierre->servicios->where('estado', 'Aceptada')->count();
                $ventaTotalServiciosCaja = $cajaCierre->servicios->where('estado', 'Aceptada')->where('modo_pago', 'Contado')->sum('total_venta');
                $ventaTotalConsumosCaja = $cajaCierre->ventas->where('estado', 'Aceptada')->where('status', 'Pagado')->where('modo_pago', 'Contado')->sum('total_venta');
                $totalPagosExtrasCaja = $cajaCierre->horas_extras->where('status', 'Pagado')->sum('total_horas_extras_otros_montos');

                if ($cajaCierre->estado == 'Cerrada') {
                    $totalVueltosPagarOficina = $cajaCierre->historial_vueltos_pendientes->where('Estado', 'PagarOficina')->sum('MontoDolar');
                } else {
                    $totalVueltosPagarOficina = $cajaCierre->excedente_actual->where('Estado', 'PagarOficina')->sum('MontoDolar');
                }

                //Reportado por sistema
                $dolar_sistema = $cajaCierre->dolar_sistema;
                // return $dolar_sistema;
                $peso_sistema = $cajaCierre->peso_sistema;
                $punto_sistema = $cajaCierre->punto_sistema;
                $trans_sistema = $cajaCierre->trans_sistema;
                $efectivo_sistema = $cajaCierre->efectivo_sistema;


                //Reportado por operador
                $dolar_dolar_operador = $cajaCierre->dolar_dolar_operador;
                $peso_dolar_operador = $cajaCierre->peso_dolar_operador;
                $efectivo_dolar_operador = $cajaCierre->efectivo_dolar_operador;
                $punto_dolar_operador = $cajaCierre->punto_dolar_operador;
                $trans_dolar_operador = $cajaCierre->trans_dolar_operador;


                //Totales
                $total_sistema_reg = $cajaCierre->total_sistema_reg;
                $total_operador_reg = $cajaCierre->total_operador_reg;
                $total_reintegro_reg = $cajaCierre->historialExcedentes->where('tipo_registro', 'Pago_por_oficina')->where('status', 'Pendiente')->where('status', 'Pendiente')->where('tipo_operacion', 'Egreso')->where('modo_pago', 'Por caja')->sum('saldo_operacion');

                $observaciones = $cajaCierre->Observaciones;
                $total_diferencia = $cajaCierre->total_diferencia;

            } catch (\Exception $e) {/*No hacemos nada si hay error*/
                dd($e);
            }

            $printer->text('RESUMEN DE CAJA' . "\n");
            $printer->text($numeroCaja . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            date_default_timezone_set("America/Caracas");
            $printer->text("Apertura: " . $fechaInicioCaja. "\n");
            $printer->text("Cierre: " . $fechaCierreCaja. "\n");
            $printer->text("Operador:"                   ."\n");
            $printer->text($operadorCaja. "\n");
            $printer->feed(1);
            $printer->text("-----------------------------" . "\n");
            $printer->text("SERVICIOS   .\n");
            $printer->text("-----------------------------" . "\n");
            $printer->text("Contado:             " . $numServiciosContadoCaja . "\n");
            $printer->text("Cresito:             " . $numServiciosCreditoCaja . "\n");
            $printer->text("Cortesia:            " . $numServiciosCortesiaCaja . "\n");
            $printer->text("           -------------------" . "\n");
            $printer->text("Total Servicios:     " . $totalNumServiciosCortesiaCaja . "\n");
            $printer->feed(1);
            $printer->text("-----------------------------" . "\n");
            $printer->text("VENTAS   .\n");
            $printer->text("-----------------------------" . "\n");
            $printer->text("Total Consumo:      $." . number_format($ventaTotalConsumosCaja, 2) . "\n");
            $printer->text("Total Servicios:    $." . number_format($ventaTotalServiciosCaja, 2) . "\n");
            $printer->text("Total P/Extras:     $." . number_format($totalPagosExtrasCaja, 2) . "\n");
            $printer->text("           -------------------" . "\n");
            $printer->text("Venta Total   :     $." . (number_format($ventaTotalConsumosCaja, 2) + number_format($ventaTotalServiciosCaja, 2) + number_format($totalPagosExtrasCaja, 2)) . "\n");
            $printer->text("Vuel/P/Oficina:     $." . number_format($totalVueltosPagarOficina, 2) . "\n");
            $printer->text("Reintegro/Oficina:  $." . number_format($total_reintegro_reg, 2) . "\n");
            $printer->text("           -------------------" . "\n");
            $printer->text("Total:              $." . number_format($total_sistema_reg, 2) . "\n");
            $printer->feed(1);
            $printer->text("-----------------------------" . "\n");
            $printer->text("REPORTADO POR OPERADOR   .\n");
            $printer->text("-----------------------------" . "\n");
            $printer->text("Dolar:              $." . number_format($dolar_dolar_operador, 2) . "\n");
            $printer->text("Peso:               $." . number_format($peso_dolar_operador, 2) . "\n");
            $printer->text("Punto:              $." . number_format($punto_dolar_operador, 2) . "\n");
            $printer->text("Transf:             $." . number_format($trans_dolar_operador, 2) . "\n");
            $printer->text("Efectivo:           $." . number_format($efectivo_dolar_operador, 2) . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->text("Total:              $." . number_format($total_operador_reg, 2) . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->feed(1);
            $printer->text("Totales   .\n");
            $printer->text("-----------------------------" . "\n");
            $printer->text("Total sistema:      $." . number_format($total_sistema_reg, 2) . "\n");
            $printer->text("Total operador:     $." . number_format($total_operador_reg, 2) . "\n");
            $printer->text("Total diferencia:   $." . number_format($total_diferencia, 2) . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->feed(1);
            $printer->text("-----------------------------" . "\n");
            $printer->text("TOTALES CONTABLES   .\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Total Consumo:      $." . number_format($ventaTotalConsumosCaja, 2) . "\n");
            $printer->text("Total Servicios:    $." . number_format($ventaTotalServiciosCaja, 2) . "\n");
            $printer->text("Total P/Extras:     $." . number_format($totalPagosExtrasCaja, 2) . "\n");
            $printer->text("Reintegro/Oficina:  $." . number_format($total_reintegro_reg, 2) . "\n");
            $printer->text("           -------------------" . "\n");
            $printer->text("Total   :           $." . (number_format($ventaTotalConsumosCaja, 2) + number_format($ventaTotalServiciosCaja, 2) + number_format($totalPagosExtrasCaja, 2) + number_format($total_reintegro_reg, 2)) . "\n");
            $printer->feed(3);
            $printer->text("-----------------------------" . "\n");
            $printer->text("Firma   .\n");
            $printer->feed(1);
            $printer->text("Observaciones:                      ". "\n");
            $printer->text($observaciones . "\n");
            $printer->feed(1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Gracias por su dedicacion! \n");

            $printer->feed(3);


            $printer->cut();


            $printer->pulse();


            $printer->close();
            $this->print_error = 1;
        } catch (\Exception $e) {
            $this->print_error = 0;
        }
    }

}
