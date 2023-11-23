<?php

namespace App\Http\Controllers;

use App\Articulo;


use App\Servicio;
use App\Detalle_credito;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Mike42\Escpos\EscposImage;
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
        $this->print_name = "POS5890";
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
        $this->print_name = "POS5890";
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
        $this->print_name = "POS5890";
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
        $this->print_name = "POS5890";
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
        $this->print_name = "POS5890";
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
}
