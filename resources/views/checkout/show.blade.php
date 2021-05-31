@extends ('layouts.admin3')
@section('contenido')

<?php
date_default_timezone_set('America/Caracas');
     $hoy = date("Y-m-d");
   $hora = date("H:i:s");

   $dia24 = strtotime('+1 day', strtotime($hoy));
   $dia24 = date('Y-m-d', $dia24);

   $diaComercial = strtotime('+1 day', strtotime($hoy));
   $diaComercial = date('Y-m-d', $diaComercial);
//    $hora24 = $dia24->format('H:i:s A');

   $hora24 = strtotime('+24 hour', strtotime($hora));
   $hora24 = date('H:i:s', $hora24);
?>

<style type="text/css">
    .bootstrap-select { width: 400px !important; }
    </style>



        <div class="box">

            <div style="background-color: #e7eaeb" class="box-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs pull-right">




                            <li class="pull-left header"><i class="fa fa-th"></i> @isset($title)
                                {{$title}}
                                @else
                                {!!"Sistema"!!}
                            @endisset</li>
                          </ul>
                          <div style="background-color: #e7eaeb" class="tab-content">


                              {{-- <b>How to use: {{$level->id}}</b> --}}
{{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

<?php
// $clientes = PersonaData::getAll();

date_default_timezone_set('America/Caracas');
$hoy = date("Y-m-d");
$hora = date("H:i:s");

?>



<style type="text/css">

	.list-group-item {
    position: relative;
    display: block;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: #ecf0f5;
    border: 1px solid #ddd;
}

</style>
{{-- <body onload="document.getElementById('numero2').focus();"> --}}
<body>
{{-- <div class="row">

 <section class="content-header">
      <h1 >
       <i class='fa fa-sign-out'></i> PROCESO CHECK OUT
        <small>Avance</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?view=reserva"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="#">Check out</a></li>
        <li class="active">Proceso check out</li>
      </ol>
</section>
</div> --}}

<?php
if(isset($servicio->id)){
    // $habitacion = ProcesoData::getById($_GET['id']);
    if($servicio->id){ ?>
        <div class="row">
            @include('custom.message')

	        <input type="hidden" name="id_operacion" value="<?php echo $servicio->habitacion_id; ?>">
	        <section>
                {{-- <span class="info-box-number text-success"><a href="#" data-toggle="modal" data-target="#disponible<?php //echo $habitacion->id.''.$habitacion->level->id; ?>"  class="small small-box-footer text-success">Cambiar Habitación <i class="small small-box-footer fa fa-clock-o"></i></a></span> --}}
                {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".bd-example-modal-lg #disponible">Cambiar Habitación</button> --}}
                <div >
                    <a hidden id="cambiarHabitacionBtn" href="#" data-toggle="modal" data-target="#disponible<?php echo $servicio->habitacion_id; ?>"  class="btn btn-danger">Cambiar Habitación</a>
                </div>
                <div class="row">

		            <div class="col-md-4">
			            <br>

                        <div class="box-body box-profile">

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="border-top: 2px solid black;">
                                    <b>Nombre habitación</b> <a class="pull-right">{{$servicio->nombre_habitacion}}</a><input type="hidden" id="nombreHabitacionBarcode" value="{{str_pad($servicio->nombre_habitacion,7,'0',STR_PAD_LEFT) ?? ''}}">
                                </li>
                                <li class="list-group-item">
                                    <b>Tipo habitación</b> <a class="pull-right">{{$servicio->tipo_habitacion}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Costo por dia</b> <a class="pull-right"><b>$  <?php echo number_format($servicio->precio_costo,2,'.',','); ?></b></a>
                                </li>

                            </ul>
                        </div>
                        <!-- /.box-body -->

                    </div>
                    <div class="col-md-4">
		                <br>

                        <div class="box-body box-profile">

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="border-top: 2px solid black;">
                                    <b>Nombre cliente</b> <a class="pull-right">{{$servicio->nombre_cliente}}</a>
                                    <?php $is_creditos = "App\Persona"::where('id',$servicio->persona_id)->first(); ?>
                                    <input type="hidden" id="isCredito" value="{{$is_creditos->isCredito}}">
                                    <input type="hidden" id="isCortesia" value="{{$is_creditos->isCortesia}}">
                                    <input type="hidden" id="cliente_id" value="{{$servicio->persona_id}}">
                                </li>
                                <li class="list-group-item">
                                    <b>Documento cliente</b> <a class="pull-right">{{$servicio->cedula_cliente}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Modo Pago</b> <a class="pull-right">{{$servicio->modo_pago}}</a>
                                </li>

                            </ul>
                        </div>
                        <!-- /.box-body -->

                    </div>

                    <div class="col-md-4">
		                <br>
                        <?php
                        $fecha1 = new DateTime($servicio->fecha_salida.' '.$servicio->hora_salida);//fecha inicial
                        $fecha2 = new DateTime($hoy.' '.$hora);//fecha de cierre


                        $horaf = $fecha1->diff($fecha2);
                        $minutos = $fecha1->diff($fecha2);

                        $contar_dias=$horaf->format('%d');
                        $contar_hora=$horaf->format('%H');
                        $contar_minutos=$horaf->format('%i');
                        $contar_horas=$contar_hora+($contar_dias*24);

                        if($contar_minutos > $cajas->tiempoMinutosExtraSis){
                            $t_minutos = 1;
                        }else{
                            $t_minutos = 0;
                        }

                        if($cajas->criterio == 'Faltan: '){
                            $total_horas = 0;
                        }else{

                            if($cajas->difHorasExtraRegPagadas){
                                $total_horas = (($contar_dias * 24) + $contar_hora + $t_minutos - $cajas->difHorasExtraRegPagadas);
                            }else{
                            $total_horas = (($contar_dias * 24) + $contar_hora + $t_minutos);
                        }
                        }


                        ?>

                        <div class="box-body box-profile">

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item" style="border-top: 2px solid black;">
                                    <b>Fecha y Hora entrada</b> <b><a class="pull-right" style="color: #dd4b39;"><?php echo $servicio->fecha_entrada.' '.$servicio->hora_entrada; ?></a></b>
                                </li>
                                <li class="list-group-item">
                                    <b>Fecha y Hora salida</b> <b><a class="pull-right" style="color: #dd4b39;"><?php echo $servicio->fecha_salida.' '.$servicio->hora_salida; ?></a></b>
                                </li>
                                <li class="list-group-item">
                                    <b>{{$cajas->criterio ?? ''}} <a ><?php echo $contar_dias.' Dias '.$contar_hora.' Horas y '.$contar_minutos.' Minutos'; ?></a></b>
                                    <b class="pull-right">Total horas extras <a >{{$total_horas ?? ''}}</a></b>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->

                    </div>


                </div>


                <?php

                    $total_alojamiento=0;
                    // if($contar_horas<=24 and $contar_dias==0 ){

                    //     $total_alojamiento=$servicio->precio_costo * 1;

                    // }else if($contar_dias!=0 and $contar_hora<=12){

                    //     $total_alojamiento=$servicio->precio_costo * $contar_dias;


                    // }else if($contar_dias!=0 and $contar_hora>12 ){

                    //     $total_alojamiento=$servicio->precio_costo * ($contar_dias + 1);

                    // };


                ?>




   		        <div class="col-md-12">
                    <div class="box box-default" >

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr style="background-color: #dcd6d6;">
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;"></th>
                                    <th colspan="5" style="border-right:1px solid #a09e9e;">Costo del alojamiento</th>
                                    <th style="width: 100px"></th>
                                </tr>
                                <tr>
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;">#</th>

                                    <th>Precio Servicio </th>
                                    <th>Dinero dejado</th>
                                    <th>Total horas extras</th>
                                    <th>Otros</th>
                                    <th style="border-right: 1px solid #a09e9e;">Detalle</th>
                                    <th style="width: 40px"></th>
                                </tr>
                                {{-- <form action="index.php?view=addsalida" method="post" name="sumar"> --}}
                                {{-- <form action="{{ route('checkout.update', $servicio->habitacion_id)}}" id="form2" method="POST" autocomplete="off" role="buscar" name="sumar"> --}}
                                <form class="form-horizontal" id="form2" role="form" action="{{ route('checkout.store')}}" method="POST" name="sumar">
                                @csrf

                                <tr>
                                    <td style="border-right: 1px solid #a09e9e;">1.</td>

                                    <td >$  <?php echo number_format($servicio->precio_costo,2,'.',','); ?></td>
                                    <td ><b>$  <?php echo number_format($servicio->dinero_dejado,2,'.',','); ?></b></td>
                                    <script>
                                    function fncSumar(){
                                        caja=document.forms["sumar"].elements;
                                        var numero = Number(caja["numero"].value);
                                        var numero1 = Number(caja["numero1"].value);
                                        var numero2 = Number(caja["numero2"].value);
                                        var numero3 = Number(caja["numero3"].value);
                                        var subtotal = Number(caja["subtotal"].value);
                                        resultado=(numero-numero1)+numero2+numero3;
                                        total=resultado+subtotal;
                                        // console.log(total);
                                        if(!isNaN(resultado)){
                                            caja["resultado"].value=(numero-numero1)+numero2+numero3;
                                        }
                                        if(!isNaN(total)){
                                            caja["total"].value=resultado+subtotal;
                                        }
                                    }

                                    </script>


                                    <input type="hidden" name="numero" size="2" value="0" onKeyUp="fncSumar()">

                                    <input type="hidden" name="numero1" size="2" value="0" onKeyUp="fncSumar()">
                                    <input type="hidden" name="numero3" size="2" value="<?php echo $total_horas * $cajas->precioHorasExtraSis; ?>" onKeyUp="fncSumar()">

                                    <input type="hidden" name="dataCantHorasExtras" id="dataCantHorasExtras" value="{{$total_horas ?? ''}}">
                                    <input type="hidden" name="dataPrecioHorasExtras" id="dataPrecioHorasExtras" value="{{$cajas->precioHorasExtraSis ?? ''}}">
                                    <input type="hidden" name="dataMontoTotalHorasExtras" id="dataMontoTotalHorasExtras" value="{{$total_horas * $cajas->precioHorasExtraSis ?? ''}}">



                                    <td><b>$ {{$total_horas * $cajas->precioHorasExtraSis ?? ''}}</b></td>

                                    <td><input type="text"  name="numero2" id="numero2" size="2"  onKeyUp="fncSumar()"></td>

                                    <td style="border-right: 1px solid #a09e9e;" ><textarea name="observacionOtros" id="observacionOtros" cols="40" rows="2"></textarea></td>
                                    <td><input type="text" value="<?php echo ($total_horas * $cajas->precioHorasExtraSis); ?>" style="border-color: red;" readonly="readonly" name="resultado"/></td>
                                </tr>

                                <tr style="background-color: #dcd6d6;">
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;"></th>
                                    <th colspan="5" style="border-right: 1px solid #a09e9e;">Servicio al cuarto</th>
                                    <th style="width: 100px"></th>
                                </tr>

                                <tr>
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;">#</th>
                                    <th>Descripción</th>
                                    <th>Precio unitario</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th style="border-right:1px solid #a09e9e;">Tipo pago</th>
                                    {{-- <th style="border-right:1px solid #a09e9e;">Estado</th> --}}
                                    <th style="width: 40px"></th>
                                </tr>
                                @if (isset($verificarHorasExtras))

                                    @foreach ($verificarHorasExtras as $verifica)
                                        @if ($verifica->monto_total_hora_extra > 0)
                                            <tr>
                                                <td style="width: 10px;border-right: 1px solid #a09e9e;">#</th>
                                                <td>Horas Extras</td>
                                                <td>{{$verifica->precio_hora_extra ?? '0'}}</td>
                                                <td>{{$verifica->cantidad_hora_extra ?? '0'}}</td>
                                                <td>{{$verifica->monto_total_hora_extra}}</td>
                                                <td style="border-right:1px solid #a09e9e;">{{$verifica->modo_pago}}</td>
                                                {{-- <td style="border-right:1px solid #a09e9e;">Estado</td> --}}
                                                <td style="widtd: 40px"></td>
                                            </tr>
                                         @endif
                                    @endforeach
                                @endif

                                @if (isset($verificarHorasExtras))

                                    @foreach ($verificarHorasExtras as $verifica)
                                        @if ($verifica->otros_montos > 0)
                                        <tr>
                                            <td style="width: 10px;border-right: 1px solid #a09e9e;">#</th>
                                            <td>{{$verifica->detalle_otros_montos}}</td>
                                            <td>{{$verifica->otros_montos ?? '0'}}</td>
                                            <td>-</td>
                                            <td>{{$verifica->otros_montos ?? '0'}}</td>
                                            <td style="border-right:1px solid #a09e9e;">{{$verifica->modo_pago}}</td>
                                            {{-- <td style="border-right:1px solid #a09e9e;">Estado</td> --}}
                                            <td style="widtd: 40px"></td>
                                        </tr>
                                        @endif

                                    @endforeach
                                @endif


                                <?php $total=0;?>

                                <?php foreach($servicio->servicios_ventas as $producto):?>
                                    {{-- {{$producto}} --}}
                                <tr>
                                    <td style="border-right: 1px solid #a09e9e;">1.</td>

                                    <td>{{$producto->articulo->nombre}}</td>
                                    <td><b>$  {{number_format($producto->precio_venta_unidad,2,'.',',')}}</b></td>
                                    <td >{{$producto->cantidad}}</td>
                                    <?php if($producto->estado_pago == 'Falta pagar'){ ?>
                                        <?php
                                        $sub_total=0;
                                        $subProdc = $producto->precio_venta_unidad*$producto->cantidad;

                                         //$sub_total=$producto->precio_venta_unidad*$producto->cantidad;

                                         ?>
                                        <?php }else{ ?>
                                            <?php $sub_total=0;
                                            $subProdc = $producto->precio_venta_unidad*$producto->cantidad;
                                            ?>
                                    <?php }; ?>
                                    <td>{{$subProdc}}</td>
                                    <td style="border-right:1px solid #a09e9e;">
                                        @if ($producto->estado_pago == 'Exonerado')
                                            Cortesía
                                        @elseif($producto->estado_pago == 'Falta pagar')
                                            Crédito
                                        @elseif($producto->estado_pago == 'Pagado')
                                            Contado
                                        @endif
                                    </td>
                                <?php if($producto->estado_pago == 'Falta pagar' || $producto->estado_pago == 'Exonerado'){ ?>
                                    {{-- <td style="border-right: 1px solid #a09e9e;"><p class="text-red">{{$producto->estado_pago}}</p></td> --}}
                                    <?php }else{ ?>
                                        {{-- <td style="border-right: 1px solid #a09e9e;"><p class="text-green">{{$producto->estado_pago}}</p></td> --}}
                                <?php }; ?>



                                <td></td>
                                    {{-- <td><span class="badge"><b>$  <?php  //echo number_format($sub_total,2,'.',','); ?></b></span></td> --}}
                                </tr>
                                <?php $total=$sub_total+$total; ?>
                                <?php endforeach; ?>


                                <?php }else{


                                    };
                                ?>


                                <tr style="background-color: #dcd6d6;">
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;"></th>
                                    <th colspan="5" style="border-right: 1px solid #a09e9e;"><p style="float: right;font-size: 18px;">Total $ </p></th>
                                    <input type="hidden" name="subtotal" value="<?php echo $total; ?>" onKeyUp="fncSumar()">
                                    <th style="width: 100px;"><b><input type="text" style="border-color: green;" readonly id="total" name="total" value="<?php echo ($total_horas * $cajas->precioHorasExtraSis) +$total; ?>"></b></th>
                                </tr>


                                <tr style="background-color: #dcd6d6;">
                                    <th style="width: 10px;border-right: 1px solid #a09e9e;"></th>
                                    <th colspan="5" style="border-right: 1px solid #a09e9e;"><p style="float: right;font-size: 14px;">Tipo de pago</p></th>

                                    <th style="width: 100px;">
                                        <b>
                                            <select class="form-control" name="id_tipo_pago">
                                                <option value="1">Efectivo</option>
                                                <option value="2">Depósito / Tarjeta</option>
                                            </select></b>
                                    </th>
                                </tr>






                            </table>

                        </div>

                        <div class="box-footer clearfix">

                            {{-- <a href="index.php?view=pre_salida" class="btn btn-danger"><i class='fa fa-sign-out'></i> Cancelar</a> --}}

                            <input type="hidden" name="id_operacion" value="<?php echo $servicio->id; ?>">
                            <input type="hidden" name="fecha_salida" value="<?php echo $hoy.' '.$hora; ?>">
                            <input type="hidden" name="id_habitacion" value="{{$servicio->habitacion_id ?? ''}}">

	                        {{-- <button type="submit"  name="boleta"  id="imprimirBoleta" class="btn btn-success pull-right"><i class='fa fa-print'></i> Imprimir Boleta</button>
                            <button type="submit"  name="factura" id="imprimirFactura" class="btn btn-warning pull-right" style="margin-right: 10px;"><i class='fa fa-print'></i> Imprimir Factura</button> --}}
                            <a id="pagoPendienteBtn" href="#" data-toggle="modal" data-target="#modalPagoPendiente"  class="btn btn-danger hidden">Procesar pago pendiente</a>
                            <a id="modalPagoPendienteOpcionesBtn" href="#" data-toggle="modal" data-target="#modalPagoPendienteOpciones"  class="btn btn-danger hidden">Procesar pago pendiente</a>
                            <!-- <button type="submit"  name="pagar"  id="pagar" class="btn btn-success pull-right"><i class='fa fa-print'></i> Procesar pago pendiente</button> -->
                            <!-- <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a> -->
                        </div>
                        </form>
                        <form action="{{ route('checkout.update', $servicio->habitacion_id)}}" id="form1" method="POST" autocomplete="off" role="buscar" name="sumar">
                            @csrf
                            @method('PUT')
                            {{-- <input type="hidden" name="quetal" value="{{$servicio->habitacion_id ?? ''}}"> --}}
                            <input type="hidden" name="id_habitacion" value="{{$servicio->habitacion_id ?? ''}}">
                            <button type="botton"  name="boleta"  id="imprimirBoleta" class="btn btn-success pull-right"><i class='fa fa-print'></i> Imprimir Boleta</button>
                            <button type="submit"  name="factura" id="imprimirFactura" class="btn btn-warning pull-right" style="margin-right: 10px;"><i class='fa fa-print'></i> Imprimir Factura</button>
                        </form>

                    </div>
                </div>

                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                <div class="modal fade bs-example-modal-xm refrescar" id="modalPagoPendiente" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-danger">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                {{-- <form id="form2" action="{{route('proceso')}}" method="post">
                                    @csrf --}}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><span class="fa fa-spinner"></span>PROCESAR PAGO PENDIENTE </h4>
                                </div>
                                <div class="modal-body" style="background-color:#fff !important;">

                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">



                                            <div class="text-black detalle" id="detalle2">
                                            </div>


                                    </div>
                                    <div id="infoPago2">
                                        <div class="col-md-12">
                                            <div class="box box-danger">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Debe cancelar la deuda pendiente...! (<b class="text-danger" id="diferenciaPrecio2">$0.00</b>)</h3>
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="btnPago2">
                                                    <div id="contado2" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a>
                                                        {{-- <button type="botton"  name="devolverVueltos"  id="devolverVueltos" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small"> Contado</button> --}}
                                                    </div>

                                                    <div id="precortesia2" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        <a href="#" data-toggle="modal" data-target="#precortesiamodal"  class="btn btn-sm btn-warning btn-block col-lg-pull-2 small">Cortesía</a>
                                                    </div>

                                                    {{-- <div id="cortesia"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"   class="btn btn-xs btn-warning btn-block col-lg-pull-2 small">Cortesía</a>

                                                    </div> --}}

                                                    {{-- <div id="creditoa"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Crédito</a>

                                                    </div> --}}

                                                    <div id="precredito2" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        <a href="#" data-toggle="modal" data-target="#precreditomodal"  class="btn btn-sm btn-success btn-block col-lg-pull-2 small">Crédito</a>
                                                    </div>

                                                </div>
                                            </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                            {{-- <button name="procesarServicioPendiente" id="procesarServiciopendiente" class="btn btn-outline ocular" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio Pendientes</button> --}}
                            {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn btn-outline">Procesar Servicio</a> --}}
                        </div>
                        {{-- </form> --}}
                        </div>
                        <!-- /.modal-content -->
                        </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                <div class="modal fade bs-example-modal-xm refrescar" id="modalPagoPendienteOpciones" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-danger">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                {{-- <form id="form2" action="{{route('proceso')}}" method="post">
                                    @csrf --}}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><span class="fa fa-spinner"></span>PROCESAR VUELTOS PENDIENTE </h4>
                                </div>
                                <div class="modal-body" style="background-color:#fff !important;">

                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">



                                            <div class="text-black detalle" id="detalle2">
                                            </div>


                                    </div>
                                    <div id="infoPago2Opciones">
                                        <div class="col-md-12">
                                            <div class="box box-danger">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Debe devolver al cliente (<b class="text-danger" id="countVueltosPendientes">$0.00</b>). (De vueltos pendiente)...!</h3>
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="btnPago2Opciones">
                                                    <div id="contado2Opciones" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a> --}}
                                                        <button type="botton"  name="devolverVueltos"  id="devolverVueltos" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small"> Contado</button>
                                                    </div>

                                                    <div id="precortesia2Opciones" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        <button type="botton"  name="pagarPorOficinaBtn"  id="pagarPorOficinaBtn" class="btn btn-sm btn-warning btn-block col-lg-pull-2 small"> Pagar Por Oficina</button>
                                                        {{-- <a href="#" data-toggle="modal" data-target="#precortesiamodal"  class="btn btn-sm btn-warning btn-block col-lg-pull-2 small">Pagar Por Oficina</a> --}}
                                                    </div>

                                                    {{-- <div id="cortesia"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"   class="btn btn-xs btn-warning btn-block col-lg-pull-2 small">Cortesía</a>

                                                    </div> --}}

                                                    {{-- <div id="creditoa"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Crédito</a>

                                                    </div> --}}

                                                    <div id="precredito2Opciones" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        {{-- <a href="#" data-toggle="modal" data-target="#precreditomodal"  class="btn btn-sm btn-success btn-block col-lg-pull-2 small">Crear Cuenta</a> --}}
                                                        <button type="botton"  name="crearCuentaBtn"  id="crearCuentaBtn" class="btn btn-sm btn-success btn-block col-lg-pull-2 small"> Crear Cuenta</button>
                                                    </div>

                                                </div>
                                            </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        </div>
                                    </div>
                                    <div id="contentPagarOficina">
                                        <div class="col-md-12">
                                            <div id="box_PagarCrear" class="box box-warning">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"><b class="text-warning" id="tituloPagarCrear">Pagar Por Oficina</b></h3><br>
                                                Datos de cliente:
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="formPagarOficina">

                                                    <form id="form4" action="{{ route('excedente.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">

                                                        @csrf
                                                        <div class="row">

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="Banco">Seleccione Cliente</label>
                                                                    <select name="selec_cliente" id="selec_cliente" class="form-control selectpicker" data-live-search="true">
                                                                        <option value="default" selected="selected">Seleccione Cliente</option>
                                                                        @foreach ($clientes as $dcliente)
                                                                    <option value="{{$dcliente->id}}_{{$dcliente->nombre}}_{{$dcliente->num_documento}}_{{$dcliente->direccion}}_{{$dcliente->telefono}}_{{$dcliente->email}}">{{$dcliente->nombre}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre">Nombre del cliente</label>
                                                                    <input required type="text" id="nombre" name="nombre" class="form-control titulo" value="{{old('nombre')}}" placeholder="Nombre...">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="tipo_documento">Tipo Documento</label>

                                                                    <select required class="form-control" id="tipo_documento" name="tipo_documento">
                                                                        <option value="0">Seleccione tipo de documento</option>
                                                                        <option value="CI">CI.V-</option>
                                                                        <option value="CI">CI.E-</option>
                                                                        <option value="RIF">RIF</option>
                                                                        <option value="PAS">PAS</option>
                                                                    </select>

                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="num_documento">Número de Documento</label>
                                                                    <input required type="number" id="num_documento" name="num_documento" class="form-control enteros" value="{{old('num_documento')}}" placeholder="Número de Documento...">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="direccion">Dirección</label>
                                                                    <input required type="text" id="direccion" name="direccion" class="form-control mayuscula" value="{{old('direccion')}}" placeholder="Dirección...">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="telefono">Teléfono</label>
                                                                    <input required type="text" id="telefono" name="telefono" class="form-control"  data-inputmask='"mask": "(9999) 999-9999"' data-mask value="{{old('telefono')}}" placeholder="Teléfono...">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="email">Email</label>
                                                                    <input required type="email" id="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Email...">
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="imagen">Imagen</label>
                                                                    <input required type="file" name="imagen" class="form-control" accept="image/*">
                                                                </div>
                                                            </div> --}}

                                                        </div>
                                                        <div id="datosBanco" class="box box-default">
                                                            <div class="box-header with-border">
                                                                {{-- <h3 class="box-title">Conceder Privilegios</h3> --}}
                                                                <br>
                                                                Datos Bancarios:
                                                            </div>
                                                            <!-- /.box-header -->
                                                            <div class="box-body">
                                                                <div class="row">

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="selec_banco">Seleccione Banco</label>
                                                                            <select name="selec_banco" id="selec_banco" class="form-control selectpicker" data-live-search="true">
                                                                                <option value="default" selected="selected">Seleccione Banco</option>
                                                                                @foreach ($bancos as $banco)
                                                                            <option value="{{$banco->id}}_{{$banco->nombre_banco}}_{{$banco->codigo}}">{{$banco->nombre_banco}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="nombre_banco">Nombre Banco</label>
                                                                            <input required type="text" id="nombre_banco" name="nombre_banco" class="form-control titulo" value="{{old('nombre')}}" placeholder="Nombre Banco...">
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="num_documento">Código</label>
                                                                            <input required type="number" id="codigo" name="codigo" class="form-control enteros" value="{{old('codigo')}}" placeholder="Código...">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="direccion">Número de cuenta</label>
                                                                            <input required type="text" id="num_cuenta" name="num_cuenta" class="form-control mayuscula" value="{{old('num_cuenta')}}" placeholder="Número de cuenta...">
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="tipo_cuenta">Tipo de cuenta</label>
                                                                            <select required class="form-control" id="tipo_cuenta" name="tipo_cuenta">
                                                                                <option value="0">Seleccione tipo de cuenta</option>
                                                                                <option value="Corriente">Corriente</option>
                                                                                <option value="Ahorro">Ahorro</option>
                                                                            </select>

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="telefono">Teléfono pago Mobil</label>
                                                                            <input required type="text" name="telefono" class="form-control"  data-inputmask='"mask": "(9999) 999-9999"' data-mask value="{{old('telefono')}}" placeholder="Teléfono...">
                                                                        </div>
                                                                    </div>



                                                                    {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label for="imagen">Imagen</label>
                                                                            <input required type="file" name="imagen" class="form-control" accept="image/*">
                                                                        </div>
                                                                    </div> --}}

                                                                </div>
                                                                <!-- /.table-responsive -->
                                                            </div>


                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <input class="text-black hidden" type="text" id="dcliente_id" name="dcliente_id">
                                                                <input class="text-black hidden" type="text" id="banco_id" name="banco_id">
                                                                <input class="text-black hidden" type="text" id="bandera" name="bandera">
                                                                <input class="text-black hidden" type="text" id="excedente" name="excedente">
                                                                <input class="text-black hidden" type="text" id="servicio_id" name="servicio_id" value="{{$servicio->id ?? ''}}">
                                                                <input class="text-black hidden" type="text" id="num_servicio" name="num_servicio" value="{{$servicio->num_servicio ?? ''}}">
                                                                <input class="text-black hidden" type="text" id="motivo" name="motivo" value="servicio">
                                                                <input class="text-black hidden" type="text" id="caja_id" name="caja_id" value="{{$servicio->caja_id ?? ''}}">
                                                                <button class="btn btn-primary" id="guardarFormaPago" type="button">Guardar</button>
                                                                {{-- <a class="btn btn-danger" href="{{ url()->previous() }}">{{__('Regresar')}}</a> --}}
                                                            </div>
                                                        </div>
                                                        <!-- /.box -->
                                                        <!-- /.box-body -->
                                                        <div class="box-footer">
                                                            {{-- Footer --}}
                                                        </div>
                                                        <!-- /.box-footer-->
                                                    </form>
                                                </div>
                                            </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        </div>
                                    </div>


                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                            {{-- <button name="procesarServicioPendiente" id="procesarServiciopendiente" class="btn btn-outline ocular" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio Pendientes</button> --}}
                            {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn btn-outline">Procesar Servicio</a> --}}
                        </div>
                        {{-- </form> --}}
                        </div>
                        <!-- /.modal-content -->
                        </div>
                    <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

<!-- Large modal -->
{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
<div class="modal fade bs-example-modal-xm refrescar" id="disponible{{$servicio->habitacion_id ?? ''}}" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-danger">
        <div class="modal-dialog">
            <div class="modal-content">
                {{-- <form id="form2" action="{{route('proceso')}}" method="post">
                    @csrf --}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span class="fa fa-spinner"></span>SELECCIONE HABITACION A CAMBIAR </h4>
                </div>
                <div class="modal-body" style="background-color:#fff !important;">

                    <div class="row">
                        <div class="col-md-offset-1 col-md-10">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Habitacion</span>
                                    {{-- <select id="segr_name" name="segr_name" data-size="2" data-width="100%" class="selectpicker" multiple data-value="{{segr_name}}" title="Seleccione Grupo de Servicio"> --}}
                                      {{-- <option id="0" value="0">0</option> --}}
                                    <select   data-size="2" data-width="100%" palceholder="hola" data-id="" title="Seleccione Servicio" name="buscarHabitacion" id="buscarHabitacion" class="selval form-control select2">
                                        <option value="0"></option>
                                        <option value="{{$mismaHabitacion->id}}_{{$mismaHabitacion->nombre}}_{{$mismaHabitacion->cat_id}}_{{$mismaHabitacion->cat->nombre}}_{{$mismaHabitacion->cat->descripcion}}_{{str_pad($mismaHabitacion->nombre,7,'0',STR_PAD_LEFT) ?? ''}}">{{$mismaHabitacion->nombre}} - {{$mismaHabitacion->cat->nombre}} - {{$mismaHabitacion->cat->descripcion}}</option>
                                        @foreach($habitacionese as $habitacion)
                                                <option value="{{$habitacion->id}}_{{$habitacion->nombre}}_{{$habitacion->cat_id}}_{{$habitacion->cat->nombre}}_{{$habitacion->cat->descripcion}}_{{str_pad($habitacion->nombre,7,'0',STR_PAD_LEFT) ?? ''}}">{{$habitacion->nombre}} - {{$habitacion->cat->nombre}} - {{$habitacion->cat->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    <input class="text-black" id="tasaDolar" name="tasaDolar" value="{{$tasaDolarHabitacion->tasa}}" type="hidden">
                                    <input class="text-black" id="tasaPeso" name="tasaPeso" value="{{$tasaPesoHabitacion->tasa}}" type="hidden">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"> HABITACIÓN </span>
                                <input type="text" class="form-control col-md-8" name="nombre_nueva" id="nombre_nueva" readonly value=""  placeholder="Ingrese nombre">
                                <input type="hidden" class="form-control col-md-8" name="habitacion_id_nueva"  value=""  placeholder="Ingrese nombre">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"> TIPO </span>
                                <input type="text" class="form-control" name="categoria_nueva" id="categoria_nueva"  disabled value=""  placeholder="Ingrese nombre">
                                <input type="hidden" class="form-control catid ver" name="categoria_id_nueva" id="categoria_id_nueva" data-id="" value=""  placeholder="Ingrese nombre">
                                <input type="hidden" class="form-control catid ver" name="precio_id_nueva" id="precio_id_nueva" data-id="" value=""  placeholder="Ingrese nombre">


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"> DETALLES </span>
                                <input type="text" class="form-control col-md-8" name="categoria_dest_nueva" id="categoria_dest_nueva" disabled value=""  placeholder="Ingrese nombre">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">SERVICIOS</span>
                                    {{-- <select id="segr_name" name="segr_name" data-size="2" data-width="100%" class="selectpicker" multiple data-value="{{segr_name}}" title="Seleccione Grupo de Servicio"> --}}
                                    {{-- <option id="0" value="0">0</option> --}}
                                    <select   data-size="2" data-width="100%" palceholder="hola" data-id="{{$habitacion->cat->id}}" title="Seleccione Servicio" name="horario" id="horario" class="precio form-control select2">
                                        <option value="0"></option>
                                        @foreach($horarios as $horario)
                                                <option value="{{$horario->id}}">{{$horario->nombre}}</option>
                                            @endforeach
                                        </select>
                                    <input class="text-black" id="tasaDolar" name="tasaDolar" value="{{$tasaDolarHabitacion->tasa}}" type="hidden">
                                    <input class="text-black" id="tasaPeso" name="tasaPeso" value="{{$tasaPesoHabitacion->tasa}}" type="hidden">
                                </div>
                            </div>


                            <div class="text-black detalle" id="detalle">

                            </div>
                        <div id="infoQR">
                            <div class="col-md-12">
                                <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ingrese Código Qr para procesar el cambio...!</h3>
                                </div><!-- /.box-header -->

                                </div><!-- /.box -->
                            </div>
                        </div>
                        <div id="infoPago">
                            <div class="col-md-12">
                                <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Debe cancelar la diferencia de precio...! (<b class="text-danger" id="diferenciaPrecio">$0.00</b>)</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div id="btnPago">
                                        <div id="contado" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                            <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a>
                                        </div>

                                        <div id="precortesia" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                            {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                            <a href="#" data-toggle="modal" data-target="#precortesiamodal"  class="btn btn-sm btn-warning btn-block col-lg-pull-2 small">Cortesía</a>
                                        </div>

                                        {{-- <div id="cortesia"
                                            class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                            <a id="modalPago" href="#"   class="btn btn-xs btn-warning btn-block col-lg-pull-2 small">Cortesía</a>

                                        </div> --}}

                                        {{-- <div id="creditoa"
                                            class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                            <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Crédito</a>

                                        </div> --}}

                                        <div id="precredito" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                            {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                            <a href="#" data-toggle="modal" data-target="#precreditomodal"  class="btn btn-sm btn-success btn-block col-lg-pull-2 small">Crédito</a>
                                        </div>

                                    </div>
                                </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div>




                    </div>
                </div>

        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
            <button name="procesarServicio" id="procesarServicio" class="btn btn-outline ocular" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio</button>
            {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn btn-outline">Procesar Servicio</a> --}}
          </div>
        {{-- </form> --}}
        </div>
        <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
  {{-- Modal pago cambio de habitacion --}}

  <div class="modal fade bd-example-modal-lg refrescar" id="dolar" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-primary">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form3" action="{{route('servicio.update', $servicio->id)}}" method="post">
                    @csrf
                    @method('PUT')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span class="fa fa-spinner"></span>PAGO DE SELECCIONE SERVICIO </h4>
                </div>
                <div class="modal-body" style="background-color:#fff !important;">

                    <div class="row small-box">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 small-box">

                            <div id="gestionpago_boton">
                                <label>Forma de pago:</label>
                                    {{-- <div class="input-group"> --}}

                                        <div class="container-fluit">
                                            <div class="row">
                                                <div
                                                    class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                    <button id='bt_addD' type='button'
                                                        class='btn btn-sm btn-primary btn-block col-lg-pull-2'>Dolar</button>
                                                </div>
                                                <div
                                                    class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                    <button id='bt_addP' type='button'
                                                        class='btn btn-sm btn-primary btn-block col-lg-pull-2'>Peso</button>
                                                </div>
                                                <div
                                                    class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                    <button id='bt_addTP' type='button'
                                                        class='btn btn-sm btn-primary btn-block col-lg-pull-2'>Punto/Trans</button>
                                                </div>
                                                <div
                                                    class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                    <button id='bt_addM' type='button'
                                                        class='btn btn-sm btn-primary btn-block col-lg-pull-2'>Mixto</button>
                                                </div>
                                                <div
                                                    class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                    <button id='bt_addE' type='button'
                                                        class='btn btn-sm btn-primary btn-block col-lg-pull-2'>Efectivo</button>
                                                </div>
                                            </div>
                                        </div>

                                    {{-- </div> --}}
                                    </div>
                                    <div id="gestionpago">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h2 id="gestionPago" class="panel-title">Gestion de pagos efectivo
                                        </h2>
                                    </div>
                                    <div class="row">
                                        <div id="excedente" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 text-black">
                                            <label for="pagoConExcedente"><h2 class="text-blue">Exedente disponible: <b id="dispExcedenteShow">$.0.00</b></h2></label>
                                            <input class="form-control" type="text" id="pagoConExcedente" name="pagoConExcedente" >
                                            <input class="form-control" type="hidden" id="dispExcedente" name="dispExcedente" >
                                        </div>
                                        <div id="Vueltosexcedente" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 text-black">
                                            <label for="VueltospagoConExcedente"><h2 class="text-blue">Vueltos pendientes: <b id="VueltosdispExcedenteShow">$.0.00</b></h2></label>
                                            <input class="form-control" type="text" id="VueltospagoConExcedente" name="VueltospagoConExcedente" >
                                            <input class="form-control" type="hidden" id="VueltosdispExcedente" name="VueltosdispExcedente" >
                                        </div>
                                        <div id="nocredito" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 text-black hidden">
                                            {{-- <label for="pagoConCredito"><h2 class="text-blue">Crédito disponible: <b id="dispCreditoShow">$.0.00</b></h2></label> --}}
                                            <input class="form-control" type="text" id="pagoConCredito" name="pagoConCredito" >
                                            <input class="form-control" type="hidden" id="dispCredito" name="dispCredito" >
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="pagos"
                                                class="table table-striped table-borderd table-condensed table-hover">
                                                <thead>
                                                    <th>Divisa</th>
                                                    <th>Monto</th>

                                                    <th>Tasa</th>
                                                    <th>Divisa a dolar</th>
                                                    <th>Resta</th>
                                                    <th>Subtotal</th>
                                                </thead>
                                                <tbody>
                                                    <tr id="trD">
                                                        <td>
                                                            <h4 class="text-bold text-primary">Dolar</h4>
                                                        </td><input name="divisa[]" value="Dolar"
                                                            type="hidden">
                                                        <td><input name="MontoDivisa[]" size="10px" class="decimal"
                                                                type="texto" id="DMontoDolar">
                                                                <button type="button" id="cargarDolar" class="btn btn-primary btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                        </td>

                                                        <td><input name="TasaTike[]"  size="10px" type="texto" readonly
                                                                id="TasaDolar"
                                                                value="{{ $tasaDolar->tasa }}">
                                                        </td>
                                                        <td><input name="MontoDolar[]"  size="10px" type="text" readonly
                                                                id="DolarToDolar" class="monto"
                                                                onchange="sumar();"></td>
                                                        <td><input name="Veltos[]"  size="10px" type="text" readonly
                                                                id="RestaDolar"></td>
                                                        <td id="DsubTotal"></td>
                                                    </tr>
                                                    <tr id="trP">
                                                        <td>
                                                            <h4 class="text-bold text-primary">Peso</h4>
                                                        </td>
                                                        </th><input name="divisa[]" value="Peso"
                                                            type="hidden">
                                                        <td><input name="MontoDivisa[]"  size="10px" class="decimal"
                                                                type="texto" id="DMontoPeso">
                                                                <button type="button" id="cargarPeso" class="btn btn-info btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                        </td>
                                                        <td><input name="TasaTike[]"  size="10px" type="texto" readonly
                                                                id="TasaPeso" value="{{ $tasaPeso->tasa }}">
                                                        </td>
                                                        <td><input name="MontoDolar[]"  size="10px" type="text" readonly
                                                                id="PesoToDolar" class="monto"
                                                                onchange="sumar();"></td>
                                                        <td><input name="Veltos[]"  size="10px" type="text" readonly
                                                                id="RestaPeso"></td>
                                                        <td id="PeSubTotal"></td>
                                                    </tr>
                                                    <tr id="trE">
                                                        <td>
                                                            <h4 class="text-bold text-primary">Efectivo</h4>
                                                        </td>
                                                        </th><input name="divisa[]" value="Bolivar"
                                                            type="hidden">
                                                        <td><input name="MontoDivisa[]" class="decimal"
                                                                type="texto"  size="10px" id="DMontoBolivar">
                                                                <button type="button" id="cargarBolivar" class="btn btn-warning btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                        </td>
                                                        <td><input name="TasaTike[]"  size="10px" type="texto" readonly
                                                                id="TasaBolivar"
                                                                value="{{ $tasaTransferenciaPunto->tasa }}">
                                                        </td>
                                                        <td><input name="MontoDolar[]"  size="10px" type="texto" readonly
                                                                id="BolivarToDolar" class="monto"
                                                                onchange="sumar();"></td>
                                                        <td><input name="Veltos[]"  size="10px" type="text" readonly
                                                                id="RestaBolivar"></td>
                                                        <td id="BoSubTotal"></td>
                                                    </tr>
                                                    <tr id="trTP">
                                                        <td>
                                                            <h4 class="text-bold text-primary">Punto</h4>
                                                        </td>
                                                        </th><input name="divisa[]" value="Punto"
                                                            type="hidden">
                                                        <td><input name="MontoDivisa[]" class="decimal"
                                                                type="texto"  size="10px" id="DMontoPunto">
                                                                <button type="button" id="cargarPunto" class="btn btn-danger btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                            </td>
                                                        <td>
                                                            <input
                                                            type="text" size="10px" name="num_Punto" id="num_Punto" placeholder="N° de Punto..."
                                                            value="">
                                                            <input name="TasaTike[]"  size="10px" type="texto"
                                                                class="enteros" id="TasaPunto" readonly value="{{ $tasaTransferenciaPunto->tasa }}">
                                                        </td>
                                                        <td><input name="MontoDolar[]"  size="10px" readonly type="texto"
                                                                id="PuntoToDolar" class="monto"
                                                                onchange="sumar();"></td>
                                                        <td><input name="Veltos[]" readonly  size="10px" type="text"
                                                                id="RestaPunto"></td>
                                                        <td id="PuSubTotal"></td>
                                                    </tr>
                                                    <tr id="trT">
                                                        <td>
                                                            <h4 class="text-bold text-primary">Transf
                                                            </h4>
                                                        </td>
                                                        </th><input name="divisa[]" value="Transferencia"
                                                            type="hidden">
                                                        <td><input name="MontoDivisa[]" class="decimal"
                                                                class="" type="texto"  size="10px" id="DMontoTrans">
                                                                <button type="button" id="cargarTrans" class="btn btn-success btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                            </td>
                                                        <td>
                                                            <input
                                                            type="text"  size="10px" name="num_Trans" id="num_Trans" placeholder="N° de Transferencia..."
                                                            value="">
                                                            <input name="TasaTike[]"  size="10px" type="texto" readonly
                                                                class="enteros" id="TasaTrans" value="{{ $tasaTransferenciaPunto->tasa }}">

                                                        </td>
                                                        <td><input name="MontoDolar[]"  size="10px" type="texto" readonly
                                                                id="TransToDolar" class="monto"
                                                                onchange="sumar();"></td>
                                                        <td><input name="Veltos[]"  size="10px" type="text" readonly
                                                                id="RestaTrans"></td>
                                                        <td id="TrSubTotal"></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>
                                                        <h4 id="tp" class="text-bold">TOTAL PAGADO</h4>
                                                        <h4 id="Vueltosex" class="text-bold">TOTAL VTOS/DISP</h4>
                                                        <h4 id="ex" class="text-bold">TOTAL EXCEDENTE</h4>
                                                        <h4 id="r" class="text-bold">RESTA</h4>
                                                        <h4 id="tap" class="text-bold">TOTAL A PAGAR</h4>
                                                        <input id="isVueltos" name="isVueltos" type="hidden" value="0">
                                                        <input id="banderaHorasExtras" name="banderaHorasExtras" type="hidden" value="">
                                                        <input id="cantHorasExtras" name="cantHorasExtras" type="hidden" value="">
                                                        <input id="precioHorasExtras" name="precioHorasExtras" type="hidden" value="">
                                                        <input id="montoTotalHorasExtras" name="montoTotalHorasExtras" type="hidden" value="">
                                                        <input id="otrosMontos" name="OtrosMontos" type="hidden" value="">
                                                        <input id="observacionOtrosMontos" name="observacionOtrosMontos" type="hidden" value="">
                                                        <input id="monto_dejado" name="monto_dejado" type="hidden" value="">
                                                        <input id="base_vuelto_monto_dejado" name="base_vuelto_monto_dejado" type="text" value="">
                                                        <input id="monto_dejadoResta" name="monto_dejadoResta" type="text" value="">
                                                        <input id="cantidad" name="cantidad" type="hidden" value="">
                                                        <input id="operador" name="operador" type="hidden" value="{{$UserName}}">
                                                        <input id="total_costo" name="total_costo" type="hidden" value="">
                                                        <input id="precio_costo" name="precio_costo" type="hidden" value="">
                                                        <input id="tipo_pago" name="tipo_pago" type="hidden" value="">
                                                        <input id="modo_pago" name="modo_pago" type="hidden" value="">
                                                        <input id="caja_id" name="caja_id" type="hidden" value="{{$caja->id}}">
                                                        <input id="user_id" name="user_id" type="hidden" value="{{$UserId}}">
                                                        <input type="hidden" name="id_operacion" value="<?php echo $servicio->id; ?>">
                                                        <input id="num_servicio" name="num_servicio" type="hidden" value="{{$num_servicio}}">
                                                        <input id="num_servicio_vieja" name="num_servicio_vieja" type="hidden" value="{{$servicio->num_servicio}}">

                                                        <input type="hidden" class="form-control" name="categoria_nueva2" id="categoria_nueva2"  readonly value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control catid ver" name="categoria_id_nueva2" id="categoria_id_nueva2" data-id="" value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control catid ver" name="precio_id_nueva2" id="precio_id_nueva2" data-id="" value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control catid ver" name="precio_nueva" id="precio_nueva" data-id="" value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control catid ver" name="precio_vieja" id="precio_vieja" data-id="" value="{{floatval($servicio->precio_costo)}}"  placeholder="Ingrese nombre">

                                                        <input type="hidden" id="precioDolarHabitacio" name="precioDolarHabitacion" value="">

                                                        {{-- <input type="hidden" class="form-control" name="horario_tipo" id="horario_tipo" data-id="" value="{{$servicio->tipo_habitacion ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="horario" id="horario" data-id="" value="{{$servicio->horario ?? ''}}"  placeholder="Ingrese nombre"> --}}
                                                        <input type="hidden" class="form-control" name="fecha_entrada" id="fecha_entrada" data-id="" value="{{$servicio->fecha_entrada ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="hora_entrada" id="hora_entrada" data-id="" value="{{$servicio->hora_entrada ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="fecha_salida" id="fecha_salida" data-id="" value="{{$servicio->fecha_salida ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="hora_salida" id="hora_salida" data-id="" value="{{$servicio->hora_salida ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="cantidad" id="cantidad" data-id="" value="{{$servicio->cantidad ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="nombre" id="nombre" data-id="" value="{{$servicio->nombre_cliente ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="num_documento" id="num_documento" data-id="" value="{{$servicio->cedula_cliente ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="direccion" id="direccion" data-id="" value="{{$servicio->direccion_cliente ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="telefono" id="telefono" data-id="" value="{{$servicio->telefono_cliente ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="limite_fecha" id="limite_fecha" data-id="" value="{{$servicio->limite_fecha ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="limite_monto" id="limite_monto" data-id="" value="{{$servicio->limite_monto ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="cliente_id" id="cliente_id" data-id="" value="{{$servicio->persona_id ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="horario_id" id="horario_id" data-id="" value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control" name="precio_id" id="precio_id" data-id="" value=""  placeholder="Ingrese nombre">

                                                        <input type="hidden" class="form-control col-md-8" name="categoria_dest_nueva2" id="categoria_dest_nueva2" readonly value=""  placeholder="Ingrese nombre">

                                                        <input type="hidden" id="nombreHabitacionBarcodeNueva" value="">
                                                        <input type="hidden" class="form-control col-md-8" name="nombre_nueva2" id="nombre_nueva2" readonly value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control col-md-8" name="nombre_vieja" id="nombre_vieja" readonly value="{{$servicio->nombre_habitacion ?? ''}}"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control col-md-8" name="habitacion_id_nueva2" id="habitacion_id_nueva2"  value=""  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control col-md-8" name="habitacion_id_vieja" id="habitacion_id_vieja"  value="{{$servicio->habitacion_id ?? ''}}"  placeholder="Ingrese nombre">
                                                    <th>
                                                        <h4 class="text-bold" id="spTotal">0.00</h4>
                                                        <h4 class="text-bold" id="Vueltosexcdt">0.00</h4>
                                                        <h4 class="text-bold" id="excdt">0.00</h4>
                                                        <h4 class="text-bold" id="RestaTtotal">0.00</h4>
                                                        <h4 class="text-bold" id="PagoTtotal">0.00</h4>
                                                    </th>
                                                </tfoot>
                                            </table>
                                        </div>


                                        <div id="vueltos" class="vueltos">

                                            <div class="box  text-black">
                                                <div class="box-header">
                                                <h3 class="box-title">Procesar Vueltos</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">

                                                    <div class="table-responsive">
                                                        <table id="pagosV"
                                                            class="table table-striped table-borderd table-condensed table-hover">
                                                            <thead>
                                                                <th>Divisa</th>
                                                                <th>En Caja</th>
                                                                <th>Monto</th>

                                                                <th>Tasa</th>
                                                                <th>Divisa a dolar</th>
                                                                <th>Resta</th>
                                                                <th>Subtotal</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr id="trDV">
                                                                    <td>
                                                                        <h4 class="text-bold text-primary">Dolar</h4>
                                                                    </td><input name="divisaV[]" value="Dolar"
                                                                        type="hidden">
                                                                        <td><h5 class="description-header text-bold">$. {{ number_format($dolarDisponible,2,',','.') ?? ' 0,00' }}</h5></td>
                                                                    <td><input name="MontoDivisaV[]" size="10px" class="decimal"
                                                                            type="texto" id="DMontoDolarV">
                                                                            <button type="button" id="cargarDolarV" class="btn btn-primary btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                                    </td>

                                                                    <td><input name="TasaTikeV[]"  size="10px" type="texto" readonly
                                                                            id="TasaDolarV"
                                                                            value="{{ $tasaDolar->tasa }}">
                                                                    </td>
                                                                    <td><input name="MontoDolarV[]"  size="10px" type="text" readonly
                                                                            id="DolarToDolarV" class="montoV"
                                                                            onchange="sumarV();"></td>
                                                                    <td><input name="VeltosV[]"  size="10px" type="text" readonly
                                                                            id="RestaDolarV"></td>
                                                                    <td id="DsubTotalV"></td>
                                                                </tr>
                                                                <tr id="trPV">
                                                                    <td>
                                                                        <h4 class="text-bold text-primary">Peso</h4>
                                                                    </td>
                                                                    </th><input name="divisaV[]" value="Peso"
                                                                        type="hidden">
                                                                        <td><h5 class="description-header text-bold">$. {{ number_format($pesoDisponible,2,',','.') ?? ' 0,00' }}</h5></td>
                                                                    <td><input name="MontoDivisaV[]"  size="10px" class="decimal"
                                                                            type="texto" id="DMontoPesoV">
                                                                            <button type="button" id="cargarPesoV" class="btn btn-info btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                                    </td>
                                                                    <td><input name="TasaTikeV[]"  size="10px" type="texto" readonly
                                                                            id="TasaPesoV" value="{{ $tasaPeso->tasa }}">
                                                                    </td>
                                                                    <td><input name="MontoDolarV[]"  size="10px" type="text" readonly
                                                                            id="PesoToDolarV" class="montoV"
                                                                            onchange="sumarV();"></td>
                                                                    <td><input name="VeltosV[]"  size="10px" type="text" readonly
                                                                            id="RestaPesoV"></td>
                                                                    <td id="PeSubTotalV"></td>
                                                                </tr>
                                                                <tr id="trEV">
                                                                    <td>
                                                                        <h4 class="text-bold text-primary">Efectivo</h4>
                                                                    </td>
                                                                    </th><input name="divisaV[]" value="Bolivar"
                                                                        type="hidden">
                                                                        <td><h5 class="description-header  text-bold">Bs. {{ number_format($bolivarDisponible,2,',','.') ?? ' 0,00' }}</h5></td>
                                                                    <td><input name="MontoDivisaV[]" class="decimal"
                                                                            type="texto"  size="10px" id="DMontoBolivarV">
                                                                            <button type="button" id="cargarBolivarV" class="btn btn-warning btn-sm"> <i class="fa fa-exchange" aria-hidden="true"> </i></button>
                                                                    </td>
                                                                    <td><input name="TasaTikeV[]"  size="10px" type="texto" readonly
                                                                            id="TasaBolivarV"
                                                                            value="{{ $tasaTransferenciaPunto->tasa }}">
                                                                    </td>
                                                                    <td><input name="MontoDolarV[]"  size="10px" type="texto" readonly
                                                                            id="BolivarToDolarV" class="montoV"
                                                                            onchange="sumarV();"></td>
                                                                    <td><input name="VeltosV[]"  size="10px" type="text" readonly
                                                                            id="RestaBolivarV"></td>
                                                                    <td id="BoSubTotalV"></td>
                                                                </tr>


                                                            </tbody>
                                                            <tfoot>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>
                                                                    <h4 id="tpV" class="text-bold">TOTAL PAGADO</h4>
                                                                    <h4 id="rV" class="text-bold">RESTA</h4>
                                                                    <h4 id="tapV" class="text-bold">TOTAL A PAGAR</h4>
                                                                <th>
                                                                    <h4 class="text-bold" id="spTotalV">0.00</h4>
                                                                    <h4 class="text-bold" id="RestaTtotalV">0.00</h4>
                                                                    <h4 class="text-bold" id="PagoTtotalV">0.00</h4>
                                                                </th>
                                                            </tfoot>
                                                        </table>
                                                    </div>


                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                    </div>
                                    <div class="panel-footer" id="guardar1">
                                        <div class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12"
                                            >
                                            <input name="tasaDolar" value="{{ $tasaDolar->tasa }}" type="hidden">
                                            <input name="porDolar" value="{{ $tasaDolar->porcentaje_ganancia }}" type="hidden">
                                            <input name="tasaPeso" value="{{ $tasaPeso->tasa }}" type="hidden">
                                            <input name="porPeso" value="{{ $tasaPeso->porcentaje_ganancia }}" type="hidden">
                                            <input name="tasaTransPunto" value="{{ $tasaTransferenciaPunto->tasa }}" type="hidden">
                                            <input name="porTransPunto" value="{{ $tasaTransferenciaPunto->porcentaje_ganancia }}" type="hidden">
                                            <input name="tasaMixto" value="{{ $tasaMixto->tasa }}" type="hidden">
                                            <input name="porMixto" value="{{ $tasaMixto->porcentaje_ganancia }}" type="hidden">
                                            <input name="tasaEfectivo" value="{{ $tasaEfectivo->tasa }}" type="hidden">
                                            <input name="porEfectivo" value="{{ $tasaEfectivo->porcentaje_ganancia }}" type="hidden">
                                            <input id="tasaDolarHabitacion" name="tasaDolarHabitacion" value="{{ $tasaDolarHabitacion->tasa }}" type="hidden">
                                            <input name="porDolarHabitacion" value="{{ $tasaDolarHabitacion->porcentaje_ganancia }}" type="hidden">
                                            <input id="tasaPesoHabitacion" name="tasaPesoHabitacion" value="{{ $tasaPesoHabitacion->tasa }}" type="hidden">
                                            <input name="porPesoHabitacion" value="{{ $tasaPesoHabitacion->porcentaje_ganancia }}" type="hidden">
                                            <input id="vtosPendientes" name="vtosPendientes" value="{{ $cajas->excedenteCLiente ?? '' }}" type="text">
                                            <input id="VueltosvtosPendientes" name="VueltosvtosPendientes" value="{{ $cajas->TotalSumaVueltosPendientesClienteDolar ?? '' }}" type="text">

                                        </div>

                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
            <div class=""
            id="">
            <button id="enviar" class="btn btn-outline" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio</button>
        </div>
            {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn btn-outline">Procesar Servicio</a> --}}
          </div>
        </form>
        </div>
        <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
  {{-- fin Modal pago cambiao habitacion --}}

  {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
  {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

  {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}


<div class="modal fade bs-example-modal-xm" id="precreditomodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-success">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><span class="fa fa-warning"></span>CREDITO ACTIVADO... ¡Favor escanear el codigo QR!</h4>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
            <a id="credito" href="#" class="btn btn-outline">Procesar credito</a>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>

{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}


<div class="modal fade bs-example-modal-xm" id="precortesiamodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-warning">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><span class="fa fa-warning"></span>CORTESÍA ACTIVADA... ¡Favor escanear el codigo QR!</h4>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
            <a id="cortesia" href="#" class="btn btn-outline">Procesar cortesía</a>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>

{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
  {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
  {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
	</section>

</div>
<?php }else{
	echo "<h4 class='alert alert-success'>NECESITA SELECCIONAR UNA HABITACIÓN CON HUESPED</h4>";
}; ?>





		<!-- Carga los datos ajax -->

			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">


				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">ACEPTAR</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
				  </div>
				   <div class="modal-body">


					<div id="loader" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->

                   </div>


				</div>
			  </div>
			</div>









{{-- <script src="plugins/select2/select2.full.min.js"></script> --}}
<script src="{{asset('dist/js/onscan.js')}}"></script>


{{-- <script>
  $(function () {
    Initialize Select2 Elements
    $(".select2").select2();


  });
</script> --}}


{{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                            </div>
                            <!-- /.tab-pane -->


                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->

                </div>
        </div>
        </div>

    </section>
  <!-- /.content -->
  <div class="clearfix"></div>
@push('sciptsMain')
<script>
// alert('hola');
$('#cambiarHabitacionBtn').hide();
    var nombreVieja = $('#nombre_vieja').val();
    // alert(nombreVieja);
const sentence = nombreVieja;

const word = '/';
var btnCambio = 0;
var procesoCambioSalida = 0;
var procesoPagoPendientealida = 0;
// sentence.includes(word) ? btnCambio == 1 : btnCambio == 2;
// ${sentence.includes(word) ? btnCambio = 1 : btnCambio = 0} ;

// alert('total '+$total);
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Inicio de metodos para gestionar los pagos extras


        // alert('total pendiente '+$totalPendietne);

        // if ($totalPendiente > 0) {
        //     pagoExtraPendiente();
        // }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function pagoVueltosPendiente(vt = 0){
            totalPendiente = vt;
            procesoCambioSalida = 7;


            $("#banderaHorasExtras").val('pagarVueltosPendientes');
            // alert('total pendiente '+totalPendiente);

            const RestaTotalV    = document.getElementById('RestaTtotalV');

            let totalPendt = new Decimal(totalPendiente);

            RestaTotalV.innerHTML = numDecimal(totalPendt); //se llena el campo resta
            PagoTtotalV.innerHTML = numDecimal(totalPendt);
            // verify();

            $("#modalPago").click();

            $("#total_costo").val('');
            // $("#total_costo").val(totalPendt.toFixed(2));
            // $('#diferenciaPrecio2').html(totalPendt.toFixed(2));
            // $("#pagoPendienteBtn").click();



            let isCortesia = $("#isCortesia").val();
            let isCredito = $("#isCredito").val();
            if(isCortesia){
                // console.log('tiene Cortesia '+isCortesia);
                let precio = totalPendt.toFixed(2);
                $("#precio_costo").val(precio);
                $('#cortesia2').show();
                $('#cortesia').show();
                $('#precortesia2').show();
                // console.log('Este cliente puede tener Cortesia');
            }else{
                $('#cortesia2').hide();
                $('#precortesia2').hide();
            }
            if(isCredito){
                // console.log('tiene credito '+isCredito);
                let precio = totalPendt.toFixed(2);
                $("#precio_costo").val(precio);
                // console.log('Este cliente puede tener credito');
                $('#credito2').show();
                $('#credito').show();
                $('#precredito2').show();

            }else{

                $('#credito2').hide();
                $('#precredito2').hide();
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        function pagoExtraPendiente(){
            totalPendiente = $('#total').val();
            // alert('total pendiente '+totalPendiente);

            let totalPendt = new Decimal(totalPendiente);


            let dataCantHorasExtras = $("#dataCantHorasExtras").val();
            let dataPrecioHorasExtras = $("#dataPrecioHorasExtras").val();
            let dataMontoTotalHorasExtras = $("#dataMontoTotalHorasExtras").val();
            let numero2 = $("#numero2").val();
            let observacionOtros = $("#observacionOtros").val();

            $("#banderaHorasExtras").val('PagoHorasExtras');
            $("#cantHorasExtras").val(dataCantHorasExtras);
            $("#precioHorasExtras").val(dataPrecioHorasExtras);
            $("#montoTotalHorasExtras").val(dataMontoTotalHorasExtras);
            $("#otrosMontos").val(numero2);
            $("#observacionOtrosMontos").val(observacionOtros);


            $("#total_costo").val('');
            $("#total_costo").val(totalPendt.toFixed(2));
            $('#diferenciaPrecio2').html(totalPendt.toFixed(2));
            // $("#pagoPendienteBtn").click();

            let isCortesia = $("#isCortesia").val();
            let isCredito = $("#isCredito").val();
            if(isCortesia){
                // console.log('tiene Cortesia '+isCortesia);
                let precio = totalPendt.toFixed(2);
                $("#precio_costo").val(precio);
                $('#cortesia2').show();
                $('#cortesia').show();
                $('#precortesia2').show();
                // console.log('Este cliente puede tener Cortesia');
            }else{
                $('#cortesia2').hide();
                $('#precortesia2').hide();
            }
            if(isCredito){
                // console.log('tiene credito '+isCredito);
                let precio = totalPendt.toFixed(2);
                $("#precio_costo").val(precio);
                // console.log('Este cliente puede tener credito');
                $('#credito2').show();
                $('#credito').show();
                $('#precredito2').show();

            }else{

                $('#credito2').hide();
                $('#precredito2').hide();
            }
        }

        $("#pagoPendienteBtn").on('click', function() {
            fncSumar();
            pagoExtraPendiente();
            procesoCambioSalida = 3;
            // alert('boton '+procesoCambioSalida);

        });

        // $("#procesarServicio").on('click', function() {
        //     addHabitacion();
        //     $("#monto_dejado").val(0);
        //     $('#modo_pago').val('cambio');
        //     $("#form3").submit();
        // });

        $("#contado2").on('click', function() {
            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $("#bt_addD").click();

            $('#modo_pago').val('contado');
        });
        $("#precortesia2").on('click', function() {
            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $('#modo_pago').val('cortesia');

        });
        $("#cortesia2").on('click', function() {
            // addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('cortesia');
            $("#form3").submit();
        });
        $("#precredito2").on('click', function() {

            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $('#modo_pago').val('credito');

        });
        $("#credito2").on('click', function() {
            let estado_credito = $("#estado_credito").val();
            if (estado_credito == 'Moroso') {
                alert('Cliente se encuentra suspendido por Incumplimiento de pago! Favor pasar por Oficina a realizar el respectivo pago...');
            } else {


            // addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('credito');
            let costo = $("#total_costo").val();
            // alert('total costo '+costo);
            let deuda_credito_pendiente = $("#total_credito_pendiente").val();
            // alert(deuda_credito_pendiente);
            let limite_fecha_credito = $("#limite_fecha").val();
            let limite_monto_credito = $("#limite_monto").val();

            if (deuda_credito_pendiente) {
                let credito_disponible = limite_monto_credito - deuda_credito_pendiente;
                // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                if (credito_disponible > 0) {
                    // alert('es mayor puede continuar costo '+ costo);
                    let credito_disponible_total_operacion = credito_disponible - costo;

                    if (credito_disponible_total_operacion >= 0) {
                        // alert('puede seguir');
                        $("#form3").submit();
                    }else{
                        alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                    }

                }else{
                    alert('El cliente no tiene Credito...');
                }
            } else {
                // alert('no hay deuda pendiente');
                let credito_disponible = limite_monto_credito;
                // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                if (credito_disponible > 0) {
                    // alert('es mayor puede continuar costo '+ costo);
                    let credito_disponible_total_operacion = credito_disponible - costo;

                    if (credito_disponible_total_operacion >= 0) {
                        // alert('puede seguir');
                        $("#form3").submit();
                    }else{
                        alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                    }

                }else{
                    alert('El cliente no tiene Credito...');
                }
            }


            }
        });

        // $("#imprimirBoleta").click(function() {
        //     let $valorDeuda = $('#total').val();

        //     if($valorDeuda > 0){
        //         $("#pagoPendienteBtn").click();
        //         // console.log('tienes deuda pendiente'+$valorDeuda);
        //         return false;
        //     }else{
        //         // console.log('todo bien');
        //         if (VueltosvtosPendientes > 0) {
        //             pagoVueltosPendiente(VueltosvtosPendientes);
        //         alert('VueltosvtosPendientes '+VueltosvtosPendientes);
        //             return false;
        //         } else {
        //             $("#form1").submit();
        //             // return false;
        //         }

        //     }
        // });

        $("#imprimirBoleta").click(function() {
            let $valorDeuda = $('#total').val();

            if($valorDeuda > 0){


                $("#pagoPendienteBtn").click();
                // console.log('tienes deuda pendiente'+$valorDeuda);
                return false;
            }else{
                // console.log('todo bien');
                if (VueltosvtosPendientes > 0) {
                    $("#modalPagoPendienteOpcionesBtn").click();
                    $("#countVueltosPendientes").html('$'+ VueltosvtosPendientes);




                    return false;
                } else {
                    $("#form1").submit();
                    // return false;
                }

            }
        });


        $("#devolverVueltos").click(function() {

            $("#selec_cliente").val('default').selectpicker("refresh");
            $("#selec_banco").val('default').selectpicker("refresh");
            $("#form4")[0].reset();

            $("#contentPagarOficina").hide();
            $("#contentCrearCuenta").hide();

            // alert('VueltosvtosPendientes '+VueltosvtosPendientes);

                // console.log('todo bien');
                if (VueltosvtosPendientes > 0) {

                    pagoVueltosPendiente(VueltosvtosPendientes);
                    // alert('VueltosvtosPendientes '+VueltosvtosPendientes);
                    return false;
                }


        });

    //Fin de metodos para gestionar los pagos extras
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#selec_cliente").change(showValuesCliente);

$("#selec_cliente").on("change", function () {
            document.getElementById("tipo_documento").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });
$("#tipo_documento").on("change", function () {
            document.getElementById("selec_banco").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });

focusMethod = function getFocus() {
            document.getElementById("selec_banco").focus();
            $("#selec_cliente").val('default');
            $("#selec_cliente").selectpicker("refresh");
        }

        function showValuesCliente() {
            // alert('show');
            datosArticulo = document.getElementById('selec_cliente').value.split('_');
            // $("#jprecio_venta").val(datosArticulo[2]);
            $("#dcliente_id").val(datosArticulo[0]);
            $("#nombre").val(datosArticulo[1]);
            $("#num_documento").val(datosArticulo[2]);
            $("#direccion").val(datosArticulo[3]);
            $("#telefono").val(datosArticulo[4]);
            $("#email").val(datosArticulo[5]);
            // $("#jstock").val(datosArticulo[2]);


            // $("#jmarjen_venta_dolar").val(12);


        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $("#contentPagarOficina").hide();
        $("#contentCrearCuenta").hide();
        const tituloPagarCrear    = document.getElementById('tituloPagarCrear');
        const box_PagarCrear    = document.getElementById('box_PagarCrear');

        $("#pagarPorOficinaBtn").on('click', function() {

            $("#selec_cliente").val('default').selectpicker("refresh");
            $("#selec_banco").val('default').selectpicker("refresh");
            $("#form4")[0].reset();

            // const RestaTotal    = document.getElementById('RestaTtotal');

            tituloPagarCrear.classList.remove('text-success');
            tituloPagarCrear.classList.add('text-warning');
            box_PagarCrear.classList.remove('box-success');
            box_PagarCrear.classList.add('box-warning');

            // $("#tituloPagarCrear").remove('text-success');
            // $("#tituloPagarCrear").add('text-warnning');
            $("#excedente").val(VueltosvtosPendientes);
            $("#tituloPagarCrear").html('Pagar por Oficina');
            $("#contentPagarOficina").show('swing');
            $("#datosBanco").show('swing');
            $("#contentCrearCuenta").hide('swing');
            // alert('boton '+procesoCambioSalida);
            $("#bandera").val('pagarPorOficina');

        });

        $("#crearCuentaBtn").on('click', function() {


            $("#selec_cliente").val('default').selectpicker("refresh");
            $("#selec_banco").val('default').selectpicker("refresh");
            $("#form4")[0].reset();
        $("#datosBanco").hide('swing');
            // const tituloPagarCrear    = document.getElementById('tituloPagarCrear');

            tituloPagarCrear.classList.remove('text-warning');
            tituloPagarCrear.classList.add('text-success');
            box_PagarCrear.classList.remove('box-warning');
            box_PagarCrear.classList.add('box-success');
            $("#tituloPagarCrear").html('Crear cuenta');

        // $("#contentCrearCuenta").show('swing');
        $("#contentPagarOficina").show('swing');
        // alert('boton '+procesoCambioSalida);
        $("#excedente").val(VueltosvtosPendientes);
        $("#bandera").val('crearCuenta');

        });


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $("#guardarFormaPago").on('click', function() {
            // alert('enviar');
            $("#form4").submit();
            return false;
        });
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#selec_banco").change(showValuesBanco);

$("#selec_banco").on("change", function () {
            // document.getElementById("tipo_documento").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });
$("#selec_banco").on("change", function () {
    $("#num_cuenta").val('');
            document.getElementById("num_cuenta").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });

focusMethod = function getFocus() {
            document.getElementById("selec_banco").focus();


            $("#num_cuenta").val('default');
            $("#num_cuenta").selectpicker("refresh");
        }

        function showValuesBanco() {
            // alert('show');
            datosArticulo = document.getElementById('selec_banco').value.split('_');
            // $("#jprecio_venta").val(datosArticulo[2]);
            $("#banco_id").val(datosArticulo[0]);
            $("#nombre_banco").val(datosArticulo[1]);
            $("#codigo").val(datosArticulo[2]);
            // $("#jstock").val(datosArticulo[2]);


            // $("#jmarjen_venta_dolar").val(12);


        }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#cambiarHabitacionBtn").on('click', function() {
    procesoCambioSalida = 1;
    $("#banderaHorasExtras").val('');
    // alert('boton '+procesoCambioSalida);

    });

if (sentence.includes(word)) {
    btnCambio = 1;
} else {
    btnCambio = 2;
}

// alert(btnCambio);

if (btnCambio == 1) {
    $('#cambiarHabitacionBtn').hide('swing');
} else {
    $('#cambiarHabitacionBtn').show('swing');
}

// console.log(`The word "${word}" ${sentence.includes(word) ? 'is' : 'is not'} in the sentence`);
// expected output: "The word "fox" is in the sentence"
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    var cont            = 0;
    var total           = parseFloat(0.00);
    var porEspecial     = null;
    var isDolar         = null;
    var isPeso          = null;
    var isTransPunto    = null;
    var isMixto         = null;
    var isEfectivo      = null;
    var isKilo          = null;
    var stock            = 0;


    // var totalp=parseFloat(0.00);
    // var totaltp=parseFloat(0.00);
    // var totalm=parseFloat(0.00);
    // var totale=parseFloat(0.00);

    var total_d         = parseFloat(0.00);
    var total_p         = parseFloat(0.00);
    var total_tp        = parseFloat(0.00);
    var total_m         = parseFloat(0.00);
    var total_e         = parseFloat(0.00);
    var total_costo     = parseFloat(0.00);

    var precio_costo    = parseFloat(0.00);
    var jporEspecial    = parseFloat(0.00);
    var precio_venta    = parseFloat(0.00);
    var precio_compra   = parseFloat(0.00);

    subtotal    = [];

    subtotalPC  = [];
    subtotald   = [];
    subtotalp   = [];
    subtotaltp  = [];
    subtotalm   = [];
    subtotale   = [];
    subtotalc   = [];

    var tasaD   = parseFloat($("#TasaDolar").val());
    var tasaP   = parseFloat($("#TasaPeso").val());
    var tasaTP  = parseFloat($("#tasaTransPunto").val());
    var tasaM   = parseFloat($("#tasaMixto").val());
    var tasaE   = parseFloat($("#tasaEfectivo").val());
    // var tasaPH   = parseFloat($("#tasaEfectivo").val());
    // var tasaDH   = parseFloat($("#tasaEfectivo").val());

    var vcargar = 0;
    var vcargarp = 0;
    var vcargarb = 0;
    var vcargarpto = 0;
    var vcargart = 0;

    var vcargarV = 0;
    var vcargarpV = 0;
    var vcargarbV = 0;

    $("#guardar").show();
    $("#gestionpago").hide();
    $("#gestionpago_boton").show();
    // $("#jidarticulo").change(showValues);
    $("#jidarticulo").change(por);
    $('#bt_addD').show();
    $('#bt_addP').show();
    $('#bt_addTP').show();
    $('#bt_addM').show();
    $('#bt_addE').show();
    $("#procesarkilos").hide();
    $("#vueltos").hide();

    $("#precortesia").hide();
    $("#cortesia").hide();
    $("#precredito").hide();
    $("#credito").hide();

    function is_negative_number(number=0){

        if( (is_numeric(number)) && (number>0) ){
            return true;
        }else{
            return false;
        }
    }

    $(document).ready(function() {
        $("#enviar").on('click', function() {
            $("#form3").submit();
        });

        $("#procesarServicio").on('click', function() {
            addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('cambio');
            $("#form3").submit();
        });

        $("#contado").on('click', function() {
            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $("#bt_addD").click();

            $('#modo_pago').val('contado');
        });
        $("#precortesia").on('click', function() {
            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $('#modo_pago').val('cortesia');

        });
        $("#cortesia").on('click', function() {
            addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('cortesia');
            $("#form3").submit();
        });
        $("#precredito").on('click', function() {

            let cliente_id = $("#cliente_id").val();
            if(cliente_id == 0 || cliente_id == null){
                alert('No has seleccionado un cliente...!');
                return false;
            }
            $('#modo_pago').val('credito');

        });
        $("#credito").on('click', function() {
            let estado_credito = $("#estado_credito").val();
            if (estado_credito == 'Moroso') {
                alert('Cliente se encuentra suspendido por Incumplimiento de pago! Favor pasar por Oficina a realizar el respectivo pago...');
            } else {


            addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('credito');
            let costo = $("#total_costo").val();
            // alert('total costo '+costo);
            let deuda_credito_pendiente = $("#total_credito_pendiente").val();
            // alert(deuda_credito_pendiente);
            let limite_fecha_credito = $("#limite_fecha").val();
            let limite_monto_credito = $("#limite_monto").val();

            if (deuda_credito_pendiente) {
                let credito_disponible = limite_monto_credito - deuda_credito_pendiente;
                // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                if (credito_disponible > 0) {
                    // alert('es mayor puede continuar costo '+ costo);
                    let credito_disponible_total_operacion = credito_disponible - costo;

                    if (credito_disponible_total_operacion >= 0) {
                        // alert('puede seguir');
                        $("#form3").submit();
                    }else{
                        alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                    }

                }else{
                    alert('El cliente no tiene Credito...');
                }
            } else {
                // alert('no hay deuda pendiente');
                let credito_disponible = limite_monto_credito;
                // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                if (credito_disponible > 0) {
                    // alert('es mayor puede continuar costo '+ costo);
                    let credito_disponible_total_operacion = credito_disponible - costo;

                    if (credito_disponible_total_operacion >= 0) {
                        // alert('puede seguir');
                        $("#form3").submit();
                    }else{
                        alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                    }

                }else{
                    alert('El cliente no tiene Credito...');
                }
            }


            }
        });

        $("#cargarDolar").on('click', function() {
            if(vcargar == 0){
                vcargar = 1;
                vcargarp = 0;
                vcargarb = 0;
                vcargarpto = 0;
                vcargart = 0;
                $("#isVueltos").val('');
                $("#deuda").val('');
                $("#deuda2").val('');
                $("#deudaPeso").val('');
                $("#deuda2Bolivar").val('');
                $("#DMontoPeso").val('');
                DMontoPeso();
                $("#DMontoBolivar").val('');
                DMontoBolivar();
                $("#DMontoPunto").val('');
                DMontoPunto();
                $("#DMontoTrans").val('');
                DMontoTrans();
                let Rd    = document.getElementById('RestaDolar').value;
                $("#DMontoDolar").val(Rd);
                DMontoDolar();
            }else{
                vcargar = 0;
                $("#DMontoDolar").val('');
                DMontoDolar();
            }
        });

        $("#cargarPeso").on('click', function() {
            if(vcargarp == 0){
                vcargarp = 1;
                vcargar = 0;
                vcargarb = 0;
                vcargarpto = 0;
                vcargart = 0;
                $("#isVueltos").val('');
                $("#deuda").val('');
                $("#deuda2").val('');
                $("#deudaPeso").val('');
                $("#deuda2Bolivar").val('');
                $("#DMontoPunto").val('');
                DMontoPunto();
                $("#DMontoTrans").val('');
                DMontoTrans();
                $("#DMontoDolar").val('');
                DMontoDolar();
                $("#DMontoBolivar").val('');
                DMontoBolivar();
                let Rp    = document.getElementById('RestaPeso').value;
                $("#DMontoPeso").val(Rp);
                DMontoPeso();
            }else{
                vcargarp = 0;
                $("#DMontoPeso").val('');
                DMontoPeso();
            }
        });

        $("#cargarBolivar").on('click', function() {
            if(vcargarb == 0){
                vcargarb = 1;
                vcargar = 0;
                vcargarp = 0;
                vcargarpto = 0;
                vcargart = 0;
                $("#isVueltos").val('');
                $("#deuda").val('');
                $("#deuda2").val('');
                $("#deudaPeso").val('');
                $("#deuda2Bolivar").val('');
                $("#DMontoPunto").val('');
                DMontoPunto();
                $("#DMontoTrans").val('');
                DMontoTrans();
                $("#DMontoPeso").val('');
                DMontoPeso();
                $("#DMontoDolar").val('');
                DMontoDolar();
                let Rb    = document.getElementById('RestaBolivar').value;
                $("#DMontoBolivar").val(Rb);
                DMontoBolivar();
            }else{
                vcargarb = 0;
                $("#DMontoBolivar").val('');
                DMontoBolivar();
            }
        });

        $("#cargarPunto").on('click', function() {
            if(vcargarpto == 0){
                vcargarpto = 1;
                vcargar = 0;
                vcargarp = 0;
                vcargarb = 0;
                vcargart = 0;
                $("#isVueltos").val('');
                $("#deuda").val('');
                $("#deuda2").val('');
                $("#deudaPeso").val('');
                $("#deuda2Bolivar").val('');
                $("#DMontoTrans").val('');
                DMontoTrans();
                $("#DMontoBolivar").val('');
                DMontoBolivar();
                $("#DMontoPeso").val('');
                DMontoPeso();
                $("#DMontoDolar").val('');
                DMontoDolar();
                let Rpto    = document.getElementById('RestaPunto').value;
                $("#DMontoPunto").val(Rpto);
                DMontoPunto();
            }else{
                vcargarpto = 0;
                $("#DMontoPunto").val('');
                DMontoPunto();
            }
        });

        $("#cargarTrans").on('click', function() {
            if(vcargart == 0){
                vcargart = 1;
                vcargar = 0;
                vcargarp = 0;
                vcargarb = 0;
                vcargarpto = 0;
                $("#isVueltos").val('');
                $("#deuda").val('');
                $("#deuda2").val('');
                $("#deudaPeso").val('');
                $("#deuda2Bolivar").val('');
                $("#DMontoPunto").val('');
                DMontoPunto();
                $("#DMontoBolivar").val('');
                DMontoBolivar();
                $("#DMontoPeso").val('');
                DMontoPeso();
                $("#DMontoDolar").val('');
                DMontoDolar();
                let Rt    = document.getElementById('RestaTrans').value;
                $("#DMontoTrans").val(Rt);
                DMontoTrans();
            }else{
                vcargart = 0;
                $("#DMontoTrans").val('');
                DMontoTrans();
            }
        });



    });

    function numDecimal(valor){
        let result = Number(valor).toFixed(3);

        return result;
    }

    $(document).ready(function() {
        // calculo();

        $("#bt_add").click(function() {
            add_article();

        });



        $(function() {
            $('.enteros').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        $('.decimal').on('keypress', function(e) {
            // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
            var field = $(this);
            key = e.keyCode ? e.keyCode : e.which;

            if (key == 8) return true;
            if (key > 47 && key < 58) {
                if (field.val() === "") return true;
                var existePto = (/[.]/).test(field.val());
                if (existePto === false) {
                    regexp = /.[0-9]{10}$/;
                } else {
                    regexp = /.[0-9]{9}$/;
                }

                return !(regexp.test(field.val()));
            }
            if (key == 46) {
                if (field.val() === "") return false;
                regexp = /^[0-9]+$/;
                return regexp.test(field.val());
            }
            return false;
        });

        function prepara() {
            $("#DMontoDolar").val('');
            $("#DMontoPeso").val('');
            $("#DMontoBolivar").val('');
            $("#DMontoPunto").val('');
            $("#DMontoTrans").val('');

            $("#RestaDolar").val('');
            $("#RestaPeso").val('');
            $("#RestaBolivar").val('');
            $("#RestaPunto").val('');
            $("#RestaTrans").val('');

            $("#DolarToDolar").val('');
            $("#DsubTotal").html('');
            $("#RestaDolar").val();

            $("#PesoToDolar").val('');
            $("#PeSubTotal").html('');
            $("#RestaPeso").val();

            $("#BolivarToDolar").val('');
            $("#BoSubTotal").html('');
            $("#RestaBolivar").val();

            $("#PuntoToDolar").val('');
            $("#PuSubTotal").html('');
            $("#RestaPunto").val();

            $("#TransToDolar").val('');
            $("#TrSubTotal").html('');
            $("#RestaTrans").val();

            $("#DMontoDolar").keyup();
            $("#DMontoPeso").keyup();
            $("#DMontoBolivar").keyup();
            $("#DMontoPunto").keyup();
            $("#DMontoTrans").keyup();

            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $("#DMontoDolarV").val('');
            $("#DMontoPesoV").val('');
            $("#DMontoBolivarV").val('');


            $("#RestaDolarV").val('');
            $("#RestaPesoV").val('');
            $("#RestaBolivarV").val('');
            $("#RestaPuntoV").val('');
            $("#RestaTransV").val('');

            $("#DolarToDolarV").val('');
            $("#DsubTotaVl").html('');
            $("#RestaDolarV").val();

            $("#PesoToDolarV").val('');
            $("#PeSubTotalV").html('');
            $("#RestaPesoV").val();

            $("#BolivarToDolarV").val('');
            $("#BoSubTotalV").html('');
            $("#RestaBolivarV").val();


            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

        }

        $("#bt_addD").click(function() {
            addHabitacion();
            vcargar = 0;
            vcargarp = 0;
            vcargarb = 0;
            vcargarpto = 0;
            vcargart = 0;
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Dolar</h4>");
            $("#PagoTtotal").html(numDecimal(total_d)); //aqui
            $("#RestaTtotal").html(numDecimal(total_d)); //aqui
            $("#total_venta").val(numDecimal(total_d));
            $("#tipo_pago").val('');
            $("#tipo_pago").val('Dolar');
            $("#spTotal").html('0.00'); //aqui
            $('#monto_dejado').val(0.00);
            $('#base_vuelto_monto_dejado').val(0.00);
            $('#monto_dejadoResta').val(0.00);


            prepara();


            $('#trD').show("linear");
            $('#trP').hide("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");
            $('#vueltos').hide("linear");

            verify();
            resta();
        });


        $("#bt_addP").click(function() {
            addHabitacion();
            vcargar = 0;
            vcargarp = 0;
            vcargarb = 0;
            vcargarpto = 0;
            vcargart = 0;
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Peso</h4>");
            $("#PagoTtotal").html(numDecimal(total_p)); //aqui
            $("#RestaTtotal").html(numDecimal(total_p)); //aqui
            $("#total_venta").val(numDecimal(total_p));
            $("#tipo_pago").val('');
            $("#tipo_pago").val('Peso');
            $("#spTotal").html('0.00'); //aqui
            $('#monto_dejado').val(0.00);
            $('#base_vuelto_monto_dejado').val(0.00);
            $('#monto_dejadoResta').val(0.00);

            $("#DMontoDolar").val('');
            $("#DMontoPeso").val('');
            $("#DMontoBolivar").val('');
            $("#DMontoPunto").val('');
            $("#DMontoTrans").val('');

            $("#RestaDolar").val('');
            $("#RestaPeso").val('');
            $("#RestaBolivar").val('');
            $("#RestaPunto").val('');
            $("#RestaTrans").val('');

            $("#DolarToDolar").val('');
            $("#DsubTotal").html('');

            $("#PesoToDolar").val('');
            $("#PeSubTotal").html('');
            $("#RestaPeso").val();

            $("#BolivarToDolar").val('');
            $("#BoSubTotal").html('');
            $("#RestaBolivar").val();

            $("#PuntoToDolar").val('');
            $("#PuSubTotal").html('');
            $("#RestaPunto").val();

            $("#TransToDolar").val('');
            $("#TrSubTotal").html('');
            $("#RestaTrans").val();

            $("#DMontoDolar").keyup();
            $("#DMontoPeso").keyup();
            $("#DMontoBolivar").keyup();
            $("#DMontoPunto").keyup();
            $("#DMontoTrans").keyup();

            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $("#DMontoDolarV").val('');
            $("#DMontoPesoV").val('');
            $("#DMontoBolivarV").val('');


            $("#RestaDolarV").val('');
            $("#RestaPesoV").val('');
            $("#RestaBolivarV").val('');
            $("#RestaPuntoV").val('');
            $("#RestaTransV").val('');

            $("#DolarToDolarV").val('');
            $("#DsubTotaVl").html('');
            $("#RestaDolarV").val();

            $("#PesoToDolarV").val('');
            $("#PeSubTotalV").html('');
            $("#RestaPesoV").val();

            $("#BolivarToDolarV").val('');
            $("#BoSubTotalV").html('');
            $("#RestaBolivarV").val();


            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $('#trD').hide("linear");
            $('#trP').show("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");
            $('#vueltos').hide("linear");

            verify();
            resta();
        });

        $("#bt_addTP").click(function() {
            addHabitacion();
            vcargar = 0;
            vcargarp = 0;
            vcargarb = 0;
            vcargarpto = 0;
            vcargart = 0;
            $("#gestionPago").html(
                "<h4 class'bold'> Gestion de Pago en Punto o Transferencia</h4>");
            $("#PagoTtotal").html(numDecimal(total_tp)); //aqui
            $("#RestaTtotal").html(numDecimal(total_tp)); //aqui
            $("#total_venta").val(numDecimal(total_tp));
            $("#tipo_pago").val('');
            $("#tipo_pago").val('Trans/Punto');
            $("#spTotal").html('0.00'); //aqui
            $('#monto_dejado').val(0.00);
            $('#base_vuelto_monto_dejado').val(0.00);
            $('#monto_dejadoResta').val(0.00);

            $("#DMontoDolar").val('');
            $("#DMontoPeso").val('');
            $("#DMontoBolivar").val('');
            $("#DMontoPunto").val('');
            $("#DMontoTrans").val('');

            $("#RestaDolar").val('');
            $("#RestaPeso").val('');
            $("#RestaBolivar").val('');
            $("#RestaPunto").val('');
            $("#RestaTrans").val('');

            $("#DolarToDolar").val('');
            $("#DsubTotal").html('');

            $("#PesoToDolar").val('');
            $("#PeSubTotal").html('');
            $("#RestaPeso").val();

            $("#BolivarToDolar").val('');
            $("#BoSubTotal").html('');
            $("#RestaBolivar").val();

            $("#PuntoToDolar").val('');
            $("#PuSubTotal").html('');
            $("#RestaPunto").val();

            $("#TransToDolar").val('');
            $("#TrSubTotal").html('');
            $("#RestaTrans").val();

            $("#DMontoDolar").keyup();
            $("#DMontoPeso").keyup();
            $("#DMontoBolivar").keyup();
            $("#DMontoPunto").keyup();
            $("#DMontoTrans").keyup();

            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $("#DMontoDolarV").val('');
            $("#DMontoPesoV").val('');
            $("#DMontoBolivarV").val('');


            $("#RestaDolarV").val('');
            $("#RestaPesoV").val('');
            $("#RestaBolivarV").val('');
            $("#RestaPuntoV").val('');
            $("#RestaTransV").val('');

            $("#DolarToDolarV").val('');
            $("#DsubTotaVl").html('');
            $("#RestaDolarV").val();

            $("#PesoToDolarV").val('');
            $("#PeSubTotalV").html('');
            $("#RestaPesoV").val();

            $("#BolivarToDolarV").val('');
            $("#BoSubTotalV").html('');
            $("#RestaBolivarV").val();


            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////


            $('#trD').hide("linear");
            $('#trP').hide("linear");
            $('#trTP').show("linear");
            $('#trT').show("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");
            $('#vueltos').hide("linear");

            verify();
            resta();
        });


        $("#bt_addM").click(function() {
            addHabitacion();
            vcargar = 0;
            vcargarp = 0;
            vcargarb = 0;
            vcargarpto = 0;
            vcargart = 0;
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago Mixto</h4>");
            $("#PagoTtotal").html(numDecimal(total_m)); //aqui
            $("#RestaTtotal").html(numDecimal(total_m)); //aqui
            $("#total_venta").val(numDecimal(total_m));
            $("#tipo_pago").val('');
            $("#tipo_pago").val('Mixto');
            $("#spTotal").html('0.00'); //aqui
            $('#monto_dejado').val(0.00);
            $('#base_vuelto_monto_dejado').val(0.00);
            $('#monto_dejadoResta').val(0.00);

            $("#DMontoDolar").val('');
            $("#DMontoPeso").val('');
            $("#DMontoBolivar").val('');
            $("#DMontoPunto").val('');
            $("#DMontoTrans").val('');

            $("#RestaDolar").val('');
            $("#RestaPeso").val('');
            $("#RestaBolivar").val('');
            $("#RestaPunto").val('');
            $("#RestaTrans").val('');

            $("#DolarToDolar").val('');
            $("#DsubTotal").html('');

            $("#PesoToDolar").val('');
            $("#PeSubTotal").html('');
            $("#RestaPeso").val();

            $("#BolivarToDolar").val('');
            $("#BoSubTotal").html('');
            $("#RestaBolivar").val();

            $("#PuntoToDolar").val('');
            $("#PuSubTotal").html('');
            $("#RestaPunto").val();

            $("#TransToDolar").val('');
            $("#TrSubTotal").html('');
            $("#RestaTrans").val();

            $("#DMontoDolar").keyup();
            $("#DMontoPeso").keyup();
            $("#DMontoBolivar").keyup();
            $("#DMontoPunto").keyup();
            $("#DMontoTrans").keyup();

            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $("#DMontoDolarV").val('');
            $("#DMontoPesoV").val('');
            $("#DMontoBolivarV").val('');


            $("#RestaDolarV").val('');
            $("#RestaPesoV").val('');
            $("#RestaBolivarV").val('');
            $("#RestaPuntoV").val('');
            $("#RestaTransV").val('');

            $("#DolarToDolarV").val('');
            $("#DsubTotaVl").html('');
            $("#RestaDolarV").val();

            $("#PesoToDolarV").val('');
            $("#PeSubTotalV").html('');
            $("#RestaPesoV").val();

            $("#BolivarToDolarV").val('');
            $("#BoSubTotalV").html('');
            $("#RestaBolivarV").val();


            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $('#trD').show("linear");
            $('#trP').show("linear");
            $('#trTP').show("linear");
            $('#trT').show("linear");
            $('#trM').show("linear");
            $('#trE').show("linear");
            $('#vueltos').hide("linear");


            verify();
            resta();
        });

        $("#bt_addE").click(function() {
            addHabitacion();
            vcargar = 0;
            vcargarp = 0;
            vcargarb = 0;
            vcargarpto = 0;
            vcargart = 0;
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Efectivo</h4>");
            $("#PagoTtotal").html(numDecimal(total_e)); //aqui
            $("#RestaTtotal").html(numDecimal(total_e)); //aqui
            $("#total_venta").val(numDecimal(total_e));
            $("#tipo_pago").val('');
            $("#tipo_pago").val('Efectivo');
            $("#spTotal").html('0.00'); //aqui
            $('#monto_dejado').val(0.00);
            $('#base_vuelto_monto_dejado').val(0.00);
            $('#monto_dejadoResta').val(0.00);

            $("#DMontoDolar").val('');
            $("#DMontoPeso").val('');
            $("#DMontoBolivar").val('');
            $("#DMontoPunto").val('');
            $("#DMontoTrans").val('');

            $("#RestaDolar").val('');
            $("#RestaPeso").val('');
            $("#RestaBolivar").val('');
            $("#RestaPunto").val('');
            $("#RestaTrans").val('');

            $("#DolarToDolar").val('');
            $("#DsubTotal").html('');

            $("#PesoToDolar").val('');
            $("#PeSubTotal").html('');
            $("#RestaPeso").val();

            $("#BolivarToDolar").val('');
            $("#BoSubTotal").html('');
            $("#RestaBolivar").val();

            $("#PuntoToDolar").val('');
            $("#PuSubTotal").html('');
            $("#RestaPunto").val();

            $("#TransToDolar").val('');
            $("#TrSubTotal").html('');
            $("#RestaTrans").val();

            $("#DMontoDolar").keyup();
            $("#DMontoPeso").keyup();
            $("#DMontoBolivar").keyup();
            $("#DMontoPunto").keyup();
            $("#DMontoTrans").keyup();

            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////

            $("#DMontoDolarV").val('');
            $("#DMontoPesoV").val('');
            $("#DMontoBolivarV").val('');


            $("#RestaDolarV").val('');
            $("#RestaPesoV").val('');
            $("#RestaBolivarV").val('');
            $("#RestaPuntoV").val('');
            $("#RestaTransV").val('');

            $("#DolarToDolarV").val('');
            $("#DsubTotaVl").html('');
            $("#RestaDolarV").val();

            $("#PesoToDolarV").val('');
            $("#PeSubTotalV").html('');
            $("#RestaPesoV").val();

            $("#BolivarToDolarV").val('');
            $("#BoSubTotalV").html('');
            $("#RestaBolivarV").val();


            //////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////


            $('#trD').hide("linear");
            $('#trP').hide("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').show("linear");
            $('#vueltos').hide("linear");

            verify();
            resta()
        });

        // unionEnteroDecimalaEnteros('125,999');
        // divisorEnteroDecimala('125995');

        $("#modalPago").click(function() {
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Dolar</h4>");
            addHabitacion();
            $('#trD').show("linear");
            $('#trP').hide("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");

        });
    });

    function divisorEnteroDecimala(entero)
    {

        // alert("Numero: "+entero);
        entero = entero / 1000;
        entero = entero.toString();
        // alert("listo: "+entero);
        arr = entero.split('.');
        var resEntero = arr[0];
        var resDecimal = arr[1];
        if(resDecimal > 0 ){
            resDecimal = resDecimal;
        }else{
            resDecimal = 0;
        }

        var result = [resEntero, resDecimal];
        // alert(result);
        return result;


    }

    function unionEnteroDecimalaEnteros(entero = null,decimal = null){
        var result = 0;
        if ( entero.indexOf(".") > -1 )
        {
            // alert( "found it ." );
            entero = parseFloat(entero).toFixed(3);
            // alert(entero);
            arr = entero.split('.');
            var resEntero = arr[0];
            var resDecimal = arr[1];


            if (resDecimal.length > 0) {
                resDecimal = fijaLargoDerecha(resDecimal)
                resDecimal = parseInt(resDecimal);
                resEntero = parseInt(resEntero);
                resEntero = resEntero * 1000;
                // alert(resEntero);
                result = resEntero + resDecimal;


                return result;

            }
        }else if ( entero.indexOf(",") > -1 )
        {
            // alert( "found it ," );
            entero = entero.replace(',','.');
            entero = parseFloat(entero).toFixed(3);
            // alert(entero);
            arr = entero.split('.');
            var resEntero = arr[0];
            var resDecimal = arr[1];


            if (resDecimal.length > 0) {
                resDecimal = fijaLargoDerecha(resDecimal)
                resDecimal = parseInt(resDecimal);
                resEntero = parseInt(resEntero);
                resEntero = resEntero * 1000;
                // alert(resEntero);
                result = resEntero + resDecimal;


                return result;

            }
         }else
        {
            if (entero > 0 && decimal > 0) {
                entero = entero * 1000;
                decimal = fijaLargoDerecha(decimal);

                entero = parseInt(entero);
                decimal = parseInt(decimal);

                // alert(resEntero);
                result = entero + decimal;
                // alert('resultado '+result);
                return result;
            }else if (entero == '' || entero == null) {
                decimal = fijaLargoDerecha(decimal);
                // alert('decimal '+decimal+'sin enteros');
                return decimal;
            }else if (decimal == '' || decimal == null) {

                entero = entero * 1000;
                // alert('entero '+entero+'sin decimal');
            return entero;
            }


        }

    }

    function fijaLargoIzquierda(T) {

        var numero = T;
        var caracteres = 3;
        // T.setAttribute("maxlength", caracteres);

        while(numero.length < caracteres){
            numero = "0"+numero;
        }
        return numero;
    }

    function fijaLargoIzquierdaBarcode(T) {

        var numero = T;
        var caracteres = 7;
        // T.setAttribute("maxlength", caracteres);

        while(numero.length < caracteres){
            numero = "0"+numero;
        }
        return numero;
    }



    function fijaLargoDerecha(T) {

        var numero = T;
        var caracteres = 3;
        // T.setAttribute("maxlength", caracteres);

        while(numero.length < caracteres){
            numero = numero+"0";
        }

        result = parseInt(numero);
        return result;
    }

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g,
                "$1" +
                thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };
    function numDecimalExp(valor){
        let result = Number((valor)).toFixed(3);
        return result;
    }

    function por() {
        var precio_compra   = parseFloat($("#jprecio_compra").val());
        var porDolar        = parseFloat($("#jmarjen_ganancia_dolar").val());
        var porPeso         = parseFloat($("#jmarjen_ganancia_peso").val());
        var porTP           = parseFloat($("#jmarjen_ganancia_trans_punto").val());
        var porM            = parseFloat($("#jmarjen_ganancia_mixto").val());
        var porE            = parseFloat($("#jmarjen_ganancia_Efectivo").val());


        var tasaD           = parseFloat($("#TasaDolar").val());
        var tasaP           = parseFloat($("#TasaPeso").val());
        var tasaTP          = parseFloat($("#tasaTransPunto").val());
        var tasaM           = parseFloat($("#tasaMixto").val());
        var tasaE           = parseFloat($("#tasaEfectivo").val());
        // alert(tasaM);
        // alert('estoy en por '+porEspecial);

        if (isKilo) {
            $('#exampleModalCenter').modal('toggle');
            // alert('Venta por kilo');
            $("#cantidadj").hide();
            $("#cantidadj").addClass('readonly');
            $("#procesarkilos").show();
            pre = precio_compra*1000;
            $("#campoPrecio").html(pre.toFixed(2));
            $("#precio").val(precio_compra);
            // precio_compra = precio_compra/1000

        // alert('por kilo '+precio_compra);
            // c]antidad.classList.add('readonly');




        }else{
            $("#cantidadj").show();
            $("#procesarkilos").hide();
        }

        if(porEspecial){
            if (isDolar) {
                var margenD = precio_compra * porEspecial / 100;
                $("#porEspecial").val(porEspecial);
            }else{
                var margenD = precio_compra * porDolar / 100;
                // $("#porEspecial").val(porDolar);
            }
            if (isPeso) {
                var margenP = precio_compra * porEspecial / 100;
                $("#porEspecial").val(porEspecial);
            }else{
                var margenP = precio_compra * porPeso / 100;
                // $("#porEspecial").val(porPeso);
            }
            if (isTransPunto) {
                var margenTP = precio_compra * porEspecial / 100;
                $("#porEspecial").val(porEspecial);
            }else{
                var margenTP = precio_compra * porTP / 100;
                // $("#porEspecial").val(porTP);
            }
            if (isMixto) {
                var margenM = precio_compra * porEspecial / 100;
                $("#porEspecial").val(porEspecial);
            }else{
                var margenM = precio_compra * porM / 100;
                // $("#porEspecial").val(porM);
            }
            if (isEfectivo) {
                var margenE = precio_compra * porEspecial / 100;
                $("#porEspecial").val(porEspecial);
            }else{
                var margenE = precio_compra * porE / 100;
                // $("#porEspecial").val(porE);
            }
        }else{
            var margenD     = precio_compra * porDolar / 100;
            var margenP     = precio_compra * porPeso / 100;
            var margenTP    = precio_compra * porTP / 100;
            var margenM     = precio_compra * porM / 100;
            var margenE     = precio_compra * porE / 100;
        }






        precio_compraD = precio_compra + margenD;
        precio_compraD = precio_compraD;
        $("#jprecio_venta_dolar").val(precio_compraD);
        $("#jprecio_venta_d_dolar").val(precio_compraD);
        $("#jprecio_venta").val(precio_compraD * tasaD);
        $("#vprecio_venta_dolar").html("<h4>$. " + precio_compraD * tasaD + "</h4>");

        precio_compraP = precio_compra + margenP;
        precio_compraP = precio_compraP;
        $("#jprecio_venta_p_dolar").val(precio_compraP);
        $("#jprecio_venta_peso").val(precio_compraP * tasaP);
        $("#vprecio_venta_peso").html("<h4>$. " + formatMoney(precio_compraP * tasaP, 2, ',', '.') + "</h4>");

        precio_compraTP = precio_compra + margenTP;
        precio_compraTP = precio_compraTP;
        $("#jprecio_venta_tp_dolar").val(precio_compraTP);
        $("#jprecio_venta_trans_punto").val(precio_compraTP * tasaTP);
        $("#vprecio_venta_trans_punto").html("<h4>Bs. " + formatMoney(precio_compraTP * tasaTP, 2, ',', '.') +
            "</h4>");

        precio_compraM = precio_compra + margenM;
        precio_compraM = precio_compraM;
        $("#jprecio_venta_m_dolar").val(precio_compraM);
        $("#jprecio_venta_mixto").val(precio_compraM * tasaM);
        $("#vprecio_venta_mixto").html("<h4>Bs. " + formatMoney(precio_compraM * tasaM, 2, ',', '.') + "</h4>");

        precio_compraE = precio_compra + margenE;
        precio_compraE = precio_compraE;
        $("#jprecio_venta_e_dolar").val(precio_compraE);
        $("#jprecio_venta_Efectivo").val(precio_compraE * tasaE);
        $("#vprecio_venta_Efectivo").html("<h4>Bs. " + formatMoney(precio_compraE * tasaE, 2, ',', '.') + "</h4>");

        precio_costoc = precio_compra;
        precio_costoc = precio_costoc;
        $("#precio_costo").val(formatMoney(precio_costoc, 9, '.', ','));


    }

    function addHabitacion(){
        totalcosto = $("#total_costo").val();
        var is24 = $('#is24').val();
        if (totalcosto == '') {
            total = $("#precioDolarHabitacio").val();
            totalcosto = total;
            $("#total_costo").val(total);
            $('#cantidad').val(1);


        } else {
            // alert('no 24');
            total = $("#total_costo").val();
            totalr = $("#precioDolarHabitacio").val();
            $("#precio_costo").val(totalr);
        }


                total_costo = total_costo + subtotalPC[cont];
                total_d     = total;
                total_p     = total;
                total_tp    = total;
                total_m     = total;
                total_e     = total;


        // $("#PagoTtotal").html(numDecimal(total)); //aqui
        // $("#RestaTtotal").html(numDecimal(total)); //aqui
        // $("#total_venta").val(numDecimal(total));

        // $("#PagoTtotal").html(numDecimal(total_e)); //aqui
        //     $("#RestaTtotal").html(numDecimal(total_e)); //aqui
        //     $("#total_venta").val(numDecimal(total_e));

        $("#PagoTtotal").html(numDecimal(total));
        // $("#PagoTtotalV").html(numDecimal(total));






        // DMontoDolar();
        // DMontoPeso();
        // DMontoBolivar();
        // DMontoPunto();
        // DMontoTrans();
        // sumar();
        // resta();

        mostrarBonotesPago();
        // $('#gestionpago').hide("linear");
        // verify();
        // $("#PagoTtotal").html(numDecimal(total));
    }

    function clear() {
        $("#jcantidad").val("");
        $("#jstock").val("");
        $("#jdescuento").val("");
        $("#jprecio_venta").val("");

        $("#jprecio_venta_d_dolar").val("");
        $("#jprecio_venta_p_dolar").val("");
        $("#jprecio_venta_tp_dolar").val("");
        $("#jprecio_venta_m_dolar").val("");
        $("#jprecio_venta_e_dolar").val("");

        $("#jprecio_venta_dolar").val("");
        $("#jprecio_venta_peso").val("");
        $("#jprecio_venta_trans_punto").val("");
        $("#jprecio_venta_mixto").val("");
        $("#jprecio_venta_Efectivo").val("");

        $("#vprecio_venta_dolar").html("<h4>$. 0.00</h4>");
        $("#vprecio_venta_peso").html("<h4>$. 0.00</h4>");
        $("#vprecio_venta_trans_punto").html("<h4>Bs. 0.00</h4>");
        $("#vprecio_venta_mixto").html("<h4>Bs. 0.00</h4>");
        $("#vprecio_venta_Efectivo").html("<h4>Bs. 0.00</h4>");
        focusMethod();
    }

    function verify() {

        if (total > 0) {
            $('#gestionpago').show("linear");
        } else {
            let = banderaDarVueltos = $("#banderaHorasExtras").val();
            if(banderaDarVueltos = 'pagarVueltosPendientes'){
                $('#gestionpago').show("linear");
                $('#vueltos').show("linear");
                return false;
            }
            $('#gestionpago').hide("linear");
        }
    }

    function mostrarBonotesPago() {
            // alert('Mostrar botones '+total);
            if (total > 0) {
                $('#bt_addD').show("linear");
                $('#bt_addP').show("linear");
                $('#bt_addTP').show("linear");
                $('#bt_addM').show("linear");
                $('#bt_addE').show("linear");
            } else {
                $('#bt_addD').hide("linear");
                $('#bt_addP').hide("linear");
                $('#bt_addTP').hide("linear");
                $('#bt_addM').hide("linear");
                $('#bt_addE').hide("linear");
            }
        }

    function eliminar(index) {

        // $("#gestionpago").hide("linear");
        total_costo = total_costo - subtotalPC[index];
        total       = total - subtotal[index];
        //  alert('total '+total);
        total_d     = total_d - subtotald[index];
        //  alert('total d '+total_d);
        total_p     = total_p - subtotalp[index];
        // alert('total p '+total_p);
        total_tp    = total_tp - subtotaltp[index];
        // alert('total tp '+total_tp);
        total_m     = total_m - subtotalm[index];
        // alert('total m '+total_m);
        total_e     = total_e -subtotale[index];
        // alert('total e '+total_e);
        //precosto =  $("#precio_costo").val();
        //total_costo = precosto - subtotalc[index];
        // alert(total_costo.toFixed(2));
        //alert(total_costo);
        $("#precio_costo").val(numDecimal(total_costo));

        // formatMoney(total_d*tasaD,2,',','.')

        $("#total").html("$/. " + numDecimal(total));
        $("#PagoTtotal").html(numDecimal(total));
        //$("#precio_costo").html(total.toFixed(2));
        // $("#total_venta").val(total.toFixed(2));
        // ddç




        $("#bt_addP").click();
        $("#bt_addTP").click();
        $("#bt_addM").click();
        $("#bt_addE").click();
        $("#bt_addD").click();


        $("#total_ventad").val(numDecimal(total_d));
        $("#total_ventap").val(numDecimal(total_p));
        $("#total_ventatp").val(numDecimal(total_tp));
        $("#total_ventam").val(numDecimal(total_m));
        $("#total_ventae").val(numDecimal(total_e));

        $("#totald").html("<h4 class='text-bold text-primary'>$. " + numDecimal(total_d *tasaD) + "</h4>");
        $("#totalp").html("<h4 class='text-bold text-primary'>$. " + formatMoney(numDecimal(total_p) * tasaP, 2, ',', '.') + "</h4>");
        $("#totaltp").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_tp) * tasaTP, 2, ',', '.') + "</h4>");
        $("#totalm").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_m) * tasaM, 2, ',', '.') + "</h4>");
        $("#totale").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_e) * tasaE, 2, ',', '.') + "</h4>");

        $("#fila" + index).remove();
        mostrarBonotesPago();
        // verify();
        resta();
        $('#gestionpago').hide("linear");

    };

    function multiplicar(decimal) {
        return decimal*1000;
    }

    function dividir(decimal) {
        return decimal/1000;
    }

    // Gestion de pagos

    $('.Can_Dolar').keyup(function() {

        var limite = parseInt($("#deuda").val());
        var nuevo_valor =  $(this).val();
        var importe_total = 0;

        $(".Can_Dolar").each(
            function(index, value) {
                if ( $.isNumeric($(this).val()) ){
                    importe_total += parseInt($(this).val());
                }
            }
            );

            let tasaDolarHabitacion = $('#tasaDolarHabitacion').val();
            let tasaPesoHabitacion = $('#tasaPesoHabitacion').val();

            let precioDolarReal = limite + importe_total;


            $("#deuda2").val(precioDolarReal);
            $("#deuda2Peso").val(precioDolarReal * tasaPesoHabitacion);
        $("#deuda2Bolivar").val(precioDolarReal * tasaDolarHabitacion);
    });

    $('.Can_Peso').keyup(function() {

        var limite = parseInt($("#deuda").val());
        var nuevo_valor =  $(this).val();
        var importe_total = 0;

        $(".Can_Peso").each(
            function(index, value) {
                if ( $.isNumeric($(this).val()) ){
                importe_total += parseInt($(this).val());
            }
            }
        );

        let tasaDolarHabitacion = $('#tasaDolarHabitacion').val();
        let tasaPesoHabitacion = $('#tasaPesoHabitacion').val();

            let montoBase = importe_total;
            let montoBaseA = importe_total / tasaPesoHabitacion;
            let precioDolarReal = limite + (importe_total / tasaPesoHabitacion);



        $("#deuda2").val(precioDolarReal);
        $("#deuda2Peso").val(precioDolarReal * tasaPesoHabitacion);
        $("#deuda2Bolivar").val(precioDolarReal * tasaDolarHabitacion);
    });

    $('.Can_Bolivar').keyup(function() {

        var limite = parseInt($("#deuda").val());
        var nuevo_valor =  $(this).val();
        var importe_total = 0;

        $(".Can_Bolivar").each(
            function(index, value) {
                if ( $.isNumeric($(this).val()) ){
                importe_total += parseInt($(this).val());
            }
            }
        );

        let tasaDolarHabitacion = $('#tasaDolarHabitacion').val();
        let tasaPesoHabitacion = $('#tasaPesoHabitacion').val();

            let precioDolarReal = limite + importe_total;


        $("#deuda2").val(precioDolarReal);
        $("#deuda2Peso").val(precioDolarReal * tasaPesoHabitacion);
        $("#deuda2Bolivar").val(precioDolarReal * tasaDolarHabitacion);
    });


    $("#excedente").hide();
    $("#ex").hide();
    $("#excdt").hide();

    var vtosPendientes = $('#vtosPendientes').val();

    // alert(vtosPendientes);
    $("#dispExcedente").val(vtosPendientes);
    // var verCajaExcedente = vtosPendientes;
    if(vtosPendientes > 0){
        alert('vtosPendientes '+vtosPendientes);
    $("#excedente").show();
    $("#ex").show();
    $("#excdt").show();

    }else{
        $("#excedente").hide();
        $("#ex").hide();
        $("#excdt").hide();
    }


    $("#Vueltosexcedente").hide();
    $("#Vueltosex").hide();
    $("#Vueltosexcdt").hide();

    var VueltosvtosPendientes = $('#VueltosvtosPendientes').val();

    // alert(VueltosvtosPendientes);
    $("#VueltosdispExcedente").val(VueltosvtosPendientes);
        // var verCajaExcedente = vtosPendientes;
        if(VueltosvtosPendientes > 0){
            // alert('VueltosvtosPendientes '+VueltosvtosPendientes);
        $("#Vueltosexcedente").show();
        $("#Vueltosex").show();
        $("#Vueltosexcdt").show();

        }else{
            $("#Vueltosexcedente").hide();
            $("#Vueltosex").hide();
            $("#Vueltosexcdt").hide();
        }
        // $("#vueltos").show();
        function resta() {
            // alert('resta');
            const RestaTotal    = document.getElementById('RestaTtotal');
            const Excdt    = document.getElementById('excdt');
            const VueltosExcdt    = document.getElementById('Vueltosexcdt');
            const PagoTtotal    = document.getElementById('PagoTtotal');
            const spTotal       = document.getElementById('spTotal');
            const tp            = document.getElementById('tp');
            const r             = document.getElementById('r');
            const tap           = document.getElementById('tap');
            const RestaTotalV    = document.getElementById('RestaTtotalV');
            // const PagoTtotalV    = document.getElementById('PagoTtotalV');
            // const spTotalV       = document.getElementById('spTotalV');
            // const tpV            = document.getElementById('tp');
            // const rV             = document.getElementById('r');
            // const tapV           = document.getElementById('tap');



            let Exc = $('#pagoConExcedente').val();


            if(Exc > 0){
                pagoExc = Exc;

                pagoExced = Exc;

                if(pagoExced > 0){

                    var dispExcedente = $("#dispExcedente").val();

                    var dispExced = dispExcedente - pagoExced;

                    // 0.1 <= (0.3 - 0.2)                              // false
                    dispExced = new Decimal(dispExced);
                    // dispExced.lessThanOrEqualTo(Decimal(0.3).minus(0.2))    // true
                    // new Decimal(-1).lte(x)
                    var validarDispExced = dispExced.isNeg();
                    // alert(validarDispExced);

                    $("#dispExcedenteShow").html('$'+dispExced.toFixed(2));
                }
                if (validarDispExced){
                    alert('El montoddd disponible no supera el monto a pagar... Credito disponible es de: $'+dispExcedente+ ' y el monto que decea pagar es de: $'+dispExced.toFixed(2));
                    pagoExc = 0;
                    excedenteDispSet = $("#dispExcedente").val();
                    $('#pagoConExcedente').val('');
                    $("#dispExcedenteShow").html('$'+excedenteDispSet);
                }

            }else{
                pagoExc = 0;
                excedenteDispSet = $("#dispExcedente").val();
                $('#pagoConExcedente').val('');
                $("#dispExcedenteShow").html('$'+excedenteDispSet);
            }


            let VueltosExc = $('#VueltospagoConExcedente').val();


            if(VueltosExc > 0){
                VueltospagoExc = VueltosExc;

                VueltospagoExced = VueltosExc;

                if(VueltospagoExced > 0){

                    var VueltosdispExcedente = $("#VueltosdispExcedente").val();

                    var VueltosdispExced = VueltosdispExcedente - VueltospagoExced;

                    // 0.1 <= (0.3 - 0.2)                              // false
                    VueltosdispExced = new Decimal(VueltosdispExced);
                    // dispExced.lessThanOrEqualTo(Decimal(0.3).minus(0.2))    // true
                    // new Decimal(-1).lte(x)
                    var VueltosvalidarDispExced = VueltosdispExced.isNeg();
                    // alert(validarDispExced);

                    $("#VueltosdispExcedenteShow").html('$'+VueltosdispExced.toFixed(2));
                }
                if (VueltosvalidarDispExced){
                    alert('El monto disponible no supera el monto a pagar... Credito disponible es de: $'+VueltosdispExcedente+ ' y el monto que decea pagar es de: $'+VueltosdispExced.toFixed(2));
                    VueltospagoExc = 0;
                    VueltosexcedenteDispSet = $("#VueltosdispExcedente").val();
                    $('#VueltospagoConExcedente').val('');
                    $("#VueltosdispExcedenteShow").html('$'+VueltosexcedenteDispSet);
                }

            }else{
                VueltospagoExc = 0;
                VueltosexcedenteDispSet = $("#VueltosdispExcedente").val();
                $('#VueltospagoConExcedente').val('');
                $("#VueltosdispExcedenteShow").html('$'+VueltosexcedenteDispSet);
            }


            // let Cred = $('#pagoConCredito').val();


            // if(Cred > 0){

            //     pagoCred = Cred;

            //     if(pagoCred > 0){
            //         let deuda_credito_pendiente = $("#total_credito_pendiente").val();
            //         // alert(deuda_credito_pendiente);
            //         let limite_fecha_credito = $("#limite_fecha").val();
            //         let limite_monto_credito = $("#limite_monto").val();
            //         let credito_disponible = 0;

            //         if (deuda_credito_pendiente) {
            //             credito_disponible = limite_monto_credito - deuda_credito_pendiente;
            //         }else{
            //             credito_disponible = limite_monto_credito;
            //         }

            //         $("#dispCredito").val(credito_disponible);


            //         let dispCredito = credito_disponible - pagoCred;

            //         $("#dispCreditoShow").html('$'+dispCredito);

            //         // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

            //         if (credito_disponible > 0) {
            //             // alert('es mayor puede continuar costo '+ costo);
            //             credito_disponible_total_operacion = credito_disponible - pagoCred;

            //             if (credito_disponible_total_operacion >= 0) {
            //                 // DMontoDolar();


            //                 // $("#form1").submit();
            //             }else{
            //                 alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' y el monto que decea pagar es de: $'+pagoCred);
            //                 pagoCred = 0;
            //                 $('#pagoConCredito').val('');
            //                 $("#dispCreditoShow").html('$'+credito_disponible);
            //             }

            //         }

            //     }
            // }else{
            //     pagoCred = 0;
            //     creditoDispSet = $("#dispCredito").val();
            //     $('#pagoConCredito').val('');
            //     $("#dispCreditoShow").html('$'+creditoDispSet);
            // }

            var valor           = PagoTtotal.innerHTML;
            var PagoTotal = PagoTtotal.innerHTML;

            valor = valor - pagoExc - VueltospagoExc;
            var valor_restar    = spTotal.innerHTML;
            var resta           = valor - valor_restar;
            if(Exc > 0){
                x1 = new Decimal(pagoExc);
                x2 = new Decimal(resta);
                x3 = new Decimal(PagoTotal);
                x4 = x1.plus(x2).equals(x3);
                // alert(x1);
                // alert(x2);
                // alert(x3);
                // alert(x4);
                if(x2.isNegative()){



                    pagoExc = 0;
                    excedenteDispSet = $("#dispExcedente").val();
                    $('#pagoConExcedente').val('');
                    $("#dispExcedenteShow").html('$'+excedenteDispSet);
                    resta = x3 - valor_restar;
                    Exc = numDecimal(pagoExc);
                    alert('Error! Debe ingresar un valor menor o igual al monto que resta....');



                // }else{
                //     if(x4){
                //         alert('puede ...');
                //     }
                }

            }

            if(VueltosExc > 0){
                x1 = new Decimal(VueltospagoExc);
                x2 = new Decimal(resta);
                x3 = new Decimal(PagoTotal);
                x4 = x1.plus(x2).equals(x3);
                // alert(x1);
                // alert(x2);
                // alert(x3);
                // alert(x4);
                // if(x2.isNegative()){



                //     VueltospagoExc = 0;
                //     VueltosexcedenteDispSet = $("#VueltosdispExcedente").val();
                //     $('#VueltospagoConExcedente').val('');
                //     $("#VueltosdispExcedenteShow").html('$'+VueltosexcedenteDispSet);
                //     resta = x3 - valor_restar;
                //     VueltosExc = numDecimal(VueltospagoExc);
                //     alert('Error! Debe ingresar un valor menor o igual al monto que resta....');



                // // }else{
                // //     if(x4){
                // //         alert('puede ...');
                // //     }
                // }

            }
            // var valorV           = PagoTtotalV.innerHTML;
            // var valor_restarV    = spTotalV.innerHTML;
            // var restaV           = numDecimal(valorV -valor_restarV);
            valor = parseFloat(valor);
            valor_restar = parseFloat(valor_restar);
            resta = parseFloat(resta);

            VueltosExcdt.innerHTML = numDecimal(VueltosExc); //se llena el campo resta
            Excdt.innerHTML = numDecimal(Exc); //se llena el campo resta
            RestaTotal.innerHTML = numDecimal(resta); //se llena el campo resta

            // alert('valor resta = '+ valor_restar + ' valor = ' + valor + 'resta = ' + resta);

            if (valor_restar > valor) {
               let tasaDolarHabitacion = $('#tasaDolarHabitacion').val();
               let tasaPesoHabitacion = $('#tasaPesoHabitacion').val();

                RestaTotalV.innerHTML = numDecimal(resta); //se llena el campo resta
                PagoTtotalV.innerHTML = numDecimal(resta); //se llena el campo resta

                DMontoDolarV();

            } else {
                $("#vueltos").hide();

            }



            if (RestaTotal.innerHTML <= -1) {
            // alert('soy menor');
            $("#vueltos").show("linear");
            $("#guardar").hide("linear");
            }


            if (RestaTotal.innerHTML <= 0) {
                // alert('soy menor');
                RestaTotal.classList.remove('text-primary');
                RestaTotal.classList.add('text-danger');
                r.classList.remove('text-primary');
                r.classList.add('text-danger');

                PagoTtotal.classList.add('text-success');
                tap.classList.add('text-success');

                $("#tap").html("MONTO COMPLETO...");
                $("#r").html("VUELTOS...");
                // verify();
                // TODO  boton enviar lo escondemos para usar el lector qr
                // $("#guardar").show("linear");
                $("#guardar").show("linear");





            }

            // if (RestaTotal.innerHTML < 0) {
            //     // alert('soy menor');
            //     RestaTotal.classList.remove('text-primary');
            //     RestaTotal.classList.add('text-danger');
            //     r.classList.remove('text-primary');
            //     r.classList.add('text-danger');

            //     PagoTtotal.classList.add('text-success');
            //     tap.classList.add('text-success');

            //     $("#tap").html("MONTO COMPLETO...");
            //     $("#r").html("VUELTOS...");
            //     // verify();
            //     $("#guardar").show("linear");;




            // }
            if (RestaTotal.innerHTML > 0) {

                // alert('soy mayor');
                RestaTotal.classList.remove('text-danger');
                RestaTotal.classList.add('text-primary');
                r.classList.remove('text-danger');
                r.classList.add('text-primary');

                PagoTtotal.classList.remove('text-success');
                tap.classList.remove('text-success');

                $("#r").html("RESTA");
                $("#tap").html("TOTAL A PAGAR");
                $("#guardar").hide("linear");

            }



            // document.getElementById('RestaTtotal').addClass('btn btn-primary');
        }

        /* Sumar dos números. */
        function sumar() {
                var total_suma = 0;
                $(".monto").each(function() {
                    if (isNaN(parseFloat($(this).val()))) {
                        total_suma += 0;
                    } else {
                        total_suma += parseFloat($(this).val());
                    }
                });
                // alert(total_suma);

                let result = new Decimal(total_suma);
                document.getElementById('spTotal').innerHTML = numDecimal(result.toFixed(2));
                $('#monto_dejado').val(result.toFixed(2));
                $('#base_vuelto_monto_dejado').val(result.toFixed(2));



        }

        function DMontoDolar(){
                // alert('clic');
                Mdolar      = $("#DMontoDolar").val();
                Tdolar      = $("#TasaDolar").val();
                Tpeso       = $("#TasaPeso").val();
                Tbolivar    = $("#TasaBolivar").val();
                Tpunto      = $("#TasaPunto").val();
                Ttrans      = $("#TasaTrans").val();

                DsupTotal = Mdolar * Tdolar;
                $("#DolarToDolar").val(DsupTotal);
                $("#DsubTotal").html(DsupTotal);

                sumar();
                resta();
                const Resta     = document.getElementById('RestaTtotal');
                var valor       = Resta.innerHTML;
                var RmultD      = valor * Tdolar;
                $("#RestaDolar").val(RmultD);
                var RmultP      = valor * Tpeso;
                $("#RestaPeso").val(RmultP);
                var RmultB      = valor * Tbolivar;
                $("#RestaBolivar").val(RmultB);
                var RmultPu     = valor * Tpunto;
                $("#RestaPunto").val(RmultPu);
                var RmultT      = valor * Ttrans;
                $("#RestaTrans").val(RmultT);

            }

        function DMontoPeso(){
            Mpeso       = $("#DMontoPeso").val();
            Tdolar      = $("#TasaDolar").val();
            Tpeso       = $("#TasaPeso").val();
            Tbolivar    = $("#TasaBolivar").val();
            Tpunto      = $("#TasaPunto").val();
            Ttrans      = $("#TasaTrans").val();

            PsupTotal = Mpeso / Tpeso;
            $("#PesoToDolar").val(PsupTotal);
            $("#PeSubTotal").html(PsupTotal);
            $("#RestaPeso").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor   = Resta.innerHTML;
            var RmultD  = valor * Tdolar;
            $("#RestaDolar").val(RmultD);
            var RmultP  = valor * Tpeso;
            $("#RestaPeso").val(RmultP);
            var RmultB  = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB);
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu);
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT);
        }

        function DMontoBolivar(){
            Mbolivar = $("#DMontoBolivar").val();
            Tdolar   = $("#TasaDolar").val();
            Tpeso    = $("#TasaPeso").val();
            Tbolivar = $("#TasaBolivar").val();
            Tpunto   = $("#TasaPunto").val();
            Ttrans   = $("#TasaTrans").val();

            peso = $("#RestaPeso").val();
            // 10767280  alert(Mpeso);
            BsupTotal = Mbolivar / Tbolivar;
            $("#BolivarToDolar").val(BsupTotal);
            $("#BoSubTotal").html(BsupTotal);
            $("#RestaBolivar").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD);
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP);
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB);
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu);
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT);
        }

        function DMontoPunto(){
            MPunto = $("#DMontoPunto").val();
            Tdolar = $("#TasaDolar").val();
            Tpeso = $("#TasaPeso").val();
            Tbolivar = $("#TasaBolivar").val();
            Tpunto = $("#TasaPunto").val();
            Ttrans = $("#TasaTrans").val();

            peso = $("#RestaPeso").val();
            // 10767280  alert(Mpeso);
            PusupTotal = MPunto / Tpunto;
            $("#PuntoToDolar").val(PusupTotal);
            $("#PuSubTotal").html(PusupTotal);
            $("#RestaPunto").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD);
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP);
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB);
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu);
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT);
        }

        function DMontoTrans(){
            Mtrans = $("#DMontoTrans").val();
            Tdolar = $("#TasaDolar").val();
            Tpeso = $("#TasaPeso").val();
            Tbolivar = $("#TasaBolivar").val();
            Tpunto = $("#TasaPunto").val();
            Ttrans = $("#TasaTrans").val();

            peso = $("#RestaPeso").val();
            // 10767280  alert(Mpeso);
            TsupTotal = Mtrans / Ttrans;
            $("#TransToDolar").val(TsupTotal);
            $("#TrSubTotal").html(TsupTotal);
            $("#RestaTrans").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD);
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP);
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB);
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu);
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT);

        }

        $(document).ready(function() {

            var aprovMontoDolar = 0;
            $("#DMontoDolar").keyup(function() {
                aprovMontoDolar = 1;
                $("#isVueltos").val('');
                DMontoDolar();
            });

            $("#DMontoPeso").keyup(function() {
                aprovMontoDolar = 1;
                $("#isVueltos").val('');
                DMontoPeso();
            });

            $("#DMontoBolivar").keyup(function() {
                aprovMontoDolar = 1;
                $("#isVueltos").val('');
                DMontoBolivar();
            });

            $("#DMontoPunto").keyup(function() {
                aprovMontoDolar = 1;
                $("#isVueltos").val('');
                DMontoPunto();
            });

            $("#DMontoTrans").keyup(function() {
                aprovMontoDolar = 1;
                $("#isVueltos").val('');
                DMontoTrans();
            });

            $("#pagoConExcedente").keyup(function() {
                DMontoDolar();

            });
            $("#pagoConCredito").keyup(function() {


                DMontoDolar();





            });
            $("#VueltospagoConExcedente").keyup(function() {
                DMontoDolar();

            });


        });



        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /* Restar dos números. */
        function restaV() {
            // alert('resta');
            const RestaTotalV    = document.getElementById('RestaTtotalV');
            const PagoTtotalV    = document.getElementById('PagoTtotalV');
            const spTotalV       = document.getElementById('spTotalV');
            const tpV            = document.getElementById('tpV');
            const rV             = document.getElementById('rV');
            const tapV           = document.getElementById('tapV');



            var valorV           = PagoTtotalV.innerHTML;
            var valor_restarV    = spTotalV.innerHTML;
            var restaV           = numDecimal(valorV - valor_restarV);

            RestaTotalV.innerHTML = numDecimal(restaV); //se llena el campo resta



            if (RestaTotalV.innerHTML >= 0) {
                // alert('soy menor');
                RestaTotalV.classList.remove('text-primary');
                RestaTotalV.classList.add('text-danger');
                rV.classList.remove('text-primary');
                rV.classList.add('text-danger');

                PagoTtotalV.classList.add('text-success');
                tapV.classList.add('text-success');

                $("#tapV").html("MONTO COMPLETO...");
                $("#rV").html("VUELTOS...");
                // verify();
                $("#guardar").show("linear");;




            }
            if (RestaTotalV.innerHTML < 0) {

                // alert('soy mayor');
                RestaTotalV.classList.remove('text-danger');
                RestaTotalV.classList.add('text-primary');
                rV.classList.remove('text-danger');
                rV.classList.add('text-primary');

                PagoTtotalV.classList.remove('text-success');
                tapV.classList.remove('text-success');

                $("#rV").html("RESTA");
                $("#tapV").html("TOTAL A PAGAR");
                $("#guardar").hide("linear");
            }



                // document.getElementById('RestaTtotal').addClass('btn btn-primary');
        }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // /* Sumar dos números. */
        // function sumarV() {
        //     var total_sumaV = 0;
        //     var tsV = 0;
        //     $(".montoV").each(function() {
        //         if (isNaN(parseFloat($(this).val()))) {
        //             total_sumaV -= 0;
        //             tsV += 0;
        //         } else {
        //             total_sumaV -= parseFloat($(this).val());
        //             tsV += parseFloat($(this).val());
        //         }
        //     });
        //     // alert(total_suma);
        //     // let md = $('#monto_dejado').val();
        //     // rmd = total_sumaV - md;
        //     // $('#monto_dejado').val(rmd);


        //     if(tsV > 0){
        //     let md = $('#monto_dejado').val();
        //     rmd =  md - tsV;
        //     $('#isVueltos').val(tsV);
        //     let rmdresult = new Decimal(rmd);
        //     $('#monto_dejado').val(rmdresult.toFixed(2));
        //     }

        //     let result = new Decimal(total_sumaV);
        //     document.getElementById('spTotalV').innerHTML = numDecimal(result.toFixed(2));


        // }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            /* Sumar dos números. */
            function sumarV() {
                var total_sumaV = 0;
                var tsV = 0;
                $(".montoV").each(function() {
                    if (isNaN(parseFloat($(this).val()))) {
                        total_sumaV -= 0;
                        tsV += 0;
                    } else {
                        total_sumaV -= parseFloat($(this).val());
                        tsV += parseFloat($(this).val());
                    }
                });
                // alert(total_suma);
                // let md = $('#monto_dejado').val();
                // rmd = total_sumaV - md;
                // $('#monto_dejado').val(rmd);

                if(tsV > 0){
                // let md = $('#monto_dejado').val();
                // rmd =  md - tsV;
                $('#isVueltos').val(tsV);
                // let rmdresult = new Decimal(rmd);
                let rmdresult = new Decimal(tsV);
                // $('#monto_dejado').val(rmdresult.toFixed(2));
                $('#monto_dejadoResta').val(rmdresult.toFixed(2));

                }
                let montoBase = $('#base_vuelto_monto_dejado').val();
                let restaMontoDejadoBase = $('#monto_dejadoResta').val();

                // console.log(montoBase);
                // console.log(restaMontoDejadoBase);
                x = new Decimal(montoBase)
                y = new Decimal(restaMontoDejadoBase)
                let r = x.sub(y)                  // '0.2'
                // console.log(r.toFixed(2));

                $('#monto_dejado').val(r.toFixed(2));


                // $('#monto_dejado').val(rmdresult.toFixed(2));
                let result = new Decimal(total_sumaV);
                document.getElementById('spTotalV').innerHTML = numDecimal(result.toFixed(2));


            }


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        function DMontoDolarV(){
            // alert('clic');
            MdolarV      = $("#DMontoDolarV").val();
            TdolarV      = $("#TasaDolarV").val();
            TpesoV       = $("#TasaPesoV").val();
            TbolivarV    = $("#TasaBolivarV").val();


            DsupTotalV = MdolarV * TdolarV;
            $("#DolarToDolarV").val(DsupTotalV);
            $("#DsubTotalV").html(DsupTotalV);

            sumarV();
            restaV();
            const RestaV     = document.getElementById('RestaTtotalV');
            var valorV       = RestaV.innerHTML;
            var RmultDV      = valorV * TdolarV;
            $("#RestaDolarV").val(RmultDV);
            var RmultPV      = valorV * TpesoV;
            $("#RestaPesoV").val(RmultPV);
            var RmultBV      = valorV * TbolivarV;
            $("#RestaBolivarV").val(RmultBV);


        }

        function DMontoPesoV(){
            MpesoV       = $("#DMontoPesoV").val();
            TdolarV      = $("#TasaDolarV").val();
            TpesoV      = $("#TasaPesoV").val();
            TbolivarV   = $("#TasaBolivarV").val();


            PsupTotalV = MpesoV / TpesoV;
            $("#PesoToDolarV").val(PsupTotalV);
            $("#PeSubTotalV").html(PsupTotalV);
            $("#RestaPesoV").val();
            sumarV();
            restaV();
            const RestaV = document.getElementById('RestaTtotalV');
            var valorV   = RestaV.innerHTML;
            var RmultDV  = valorV * TdolarV;
            $("#RestaDolarV").val(RmultDV);
            var RmultPV  = valorV * TpesoV;
            $("#RestaPesoV").val(RmultPV);
            var RmultBV  = valorV * TbolivarV;
            $("#RestaBolivarV").val(RmultBV);


        }

        function DMontoBolivarV(){
            MbolivarV = $("#DMontoBolivarV").val();
            TdolarV   = $("#TasaDolarV").val();
            TpesoV    = $("#TasaPesoV").val();
            TbolivarV = $("#TasaBolivarV").val();


            pesoV = $("#RestaPesoV").val();
            // 10767280  alert(Mpeso);
            BsupTotalV = MbolivarV / TbolivarV;
            $("#BolivarToDolarV").val(BsupTotalV);
            $("#BoSubTotalV").html(BsupTotalV);
            $("#RestaBolivarV").val();
            sumarV();
            restaV();
            const RestaV = document.getElementById('RestaTtotalV');
            var valorV = RestaV.innerHTML;
            var RmultDV = valorV * TdolarV;
            $("#RestaDolarV").val(RmultDV);
            var RmultPV = valorV * TpesoV;
            $("#RestaPesoV").val(RmultPV);
            var RmultBV = valorV * TbolivarV;
            $("#RestaBolivarV").val(RmultBV);


        }





        $(document).ready(function() {


            $("#DMontoDolarV").keyup(function() {
                $("#isVueltos").val('');
                $("#monto_dejadoResta").val(0.00);
                DMontoDolarV();
            });

            $("#DMontoPesoV").keyup(function() {
                $("#isVueltos").val('');
                $("#monto_dejadoResta").val(0.00);
                DMontoPesoV();
            });

            $("#DMontoBolivarV").keyup(function() {
                $("#isVueltos").val('');
                $("#monto_dejadoResta").val(0.00);
                DMontoBolivarV();
            });





        });


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $("#cargarDolarV").on('click', function() {
            // alert('2');
            if(vcargarV == 0){
                // alert('0');
                vcargarV = 1;
                vcargarpV = 0;
                vcargarbV = 0;


                $("#isVueltos").val('');
                $("#DMontoPesoV").val('');
                DMontoPesoV();
                $("#DMontoBolivarV").val('');
                DMontoBolivarV();

                let RdV    = document.getElementById('RestaDolarV').value;

                RdV = -1 * RdV;
                // alert('valor = '+RdV);
                $("#DMontoDolarV").val(RdV);
                DMontoDolarV();
            }else{
                // alert('1');
                vcargarV = 0;
                $("#DMontoDolarV").val('');
                $("#isVueltos").val('');
                DMontoDolarV();
            }
        });

        $("#cargarPesoV").on('click', function() {

            if(vcargarpV == 0){
                vcargarpV = 1;
                vcargarV = 0;
                vcargarbV = 0;

                $("#isVueltos").val('');


                $("#DMontoDolarV").val('');
                DMontoDolarV();
                $("#DMontoBolivarV").val('');
                DMontoBolivarV();
                let RpV    = document.getElementById('RestaPesoV').value;

                RpV = -1 * RpV;
                $("#DMontoPesoV").val(RpV);
                DMontoPesoV();
            }else{
                vcargarpV = 0;
                $("#DMontoPesoV").val('');
                $("#isVueltos").val('');
                DMontoPesoV();
            }
        });

        $("#cargarBolivarV").on('click', function() {
            if(vcargarbV == 0){
                vcargarbV = 1;
                vcargarV = 0;
                vcargarpV = 0;

                $("#isVueltos").val('');


                $("#DMontoPesoV").val('');
                DMontoPesoV();
                $("#DMontoDolarV").val('');
                DMontoDolarV();
                let RbV    = document.getElementById('RestaBolivarV').value;

                RbV = -1 * RbV;
                $("#DMontoBolivarV").val(RbV);
                DMontoBolivarV();
            }else{
                vcargarbV = 0;
                $("#DMontoBolivarV").val('');
                $("#isVueltos").val('');
                DMontoBolivarV();
            }
        });

    //FORMATO LISTO HASTA AQUÍ
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<!-- <script>

        document.addEventListener('DOMContentLoaded', function() {

            $('#imprimirFactura').show();
            $('#imprimirBoleta').show();

            try {

                onScan.attachTo(document, {
                    //configuración del sufijo/ tecla esperada al finalizar la lectura del scan, esto indica a onScan la finalización del evento
                    suffixKeyCodes: [13],
                    minLength: 7,
                    onScan: function(barcode) { //función callback que se dispara después de una lectura
                        console.log(barcode)

                        if (procesoCambioSalida == 0) {

                            // alert('cerrar '+ procesoCambioSalida);
                            $('#imprimirFactura').hide();
                            $('#imprimirBoleta').hide();

                            let nombreVieja = $('#nombre_vieja').val();
                                // alert(nombreVieja);

                            let nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
                            // alert('leer '+nombreHabitacionBarcode);
                            let sentence1 = nombreHabitacionBarcode;

                            let word1 = '/';
                            if (sentence1.includes(word)) {
                                datosHabitacionNueva1 = document.getElementById('nombreHabitacionBarcode').value.split('/');
                                // alert(datosHabitacionNueva1[0]);

                                // nombreHabitacionBarcode = datosHabitacionNueva1[0];
                                nombreHabitacionBarcode = fijaLargoIzquierdaBarcode(datosHabitacionNueva1[0]);

                                // alert(nombreHabitacionBarcode);
                                // return false;
                            } else {
                                nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
                            }
                            // alert(nombreHabitacionBarcode);
                            var n = barcode;
                            // alert(n);

                            if (barcode == nombreHabitacionBarcode) {

                                // alert('Todo va bien');
                                let $valorDeuda = $('#total').val();

                                if($valorDeuda){
                                    console.log($valorDeuda);
                                    return false;
                                }else{
                                    // console.log('todo bien');
                                    $("#form1").submit();
                                    // return false;
                                }

                            }else{
                                alert('¡Error al Ingresar el QR!... Por favor Ingrese el QR correcto. (llave incorrecta)');
                                return false;
                            }

                        }

                        // alert(procesoCambioSalida);
                        if (procesoCambioSalida == 1) {

                            $('#imprimirFactura').hide();
                            $('#imprimirBoleta').hide();

                            let nombreHabitacionBarcodeNueva = $("#nombreHabitacionBarcodeNueva").val();
                            // alert(nombreHabitacionBarcode);
                            var n = barcode;

                            if (barcode == nombreHabitacionBarcodeNueva) {
                                // alert('es igual barcode');
                                // return false;
                                modoPagoOn = $('#modo_pago').val();
                                tipoPago = $('#tipo_pago').val();
                                monto_dejadoR = $('#monto_dejado').val();
                                // alert(monto_dejadoR);

                                if (modoPagoOn == 'contado') {
                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    if(tipoPago == 0 || tipoPago == null){
                                        alert('No has seleccionado el tipo de pago...! (Ej: Dolar, Peso, Trans, Punto, Mixto...)');
                                        return false;
                                    }

                                    if(monto_dejadoR == 0 || monto_dejadoR == null){
                                        alert('No has ingresado el monto a pagar...!');
                                        return false;
                                    }
                                    // alert('contado');
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'cambio'){
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'cortesia'){
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'credito') {
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    let estado_credito = $("#estado_credito").val();
                                    if (estado_credito == 'Moroso') {
                                        alert('Cliente se encuentra suspendido por Incumplimiento de pago! Favor pasar por Oficina a realizar el respectivo pago...');
                                    } else {

                                        addHabitacion();
                                        $("#monto_dejado").val(0);
                                        $("#base_vuelto_monto_dejado").val(0);
                                        $('#modo_pago').val('credito');
                                        let costo = $("#total_costo").val();
                                        // alert('total costo '+costo);
                                        let deuda_credito_pendiente = $("#total_credito_pendiente").val();
                                        // alert(deuda_credito_pendiente);
                                        let limite_fecha_credito = $("#limite_fecha").val();
                                        let limite_monto_credito = $("#limite_monto").val();

                                        if (deuda_credito_pendiente) {
                                            let credito_disponible = limite_monto_credito - deuda_credito_pendiente;
                                            // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                                            if (credito_disponible > 0) {
                                                // alert('es mayor puede continuar costo '+ costo);
                                                let credito_disponible_total_operacion = credito_disponible - costo;

                                                if (credito_disponible_total_operacion >= 0) {
                                                    // alert('puede seguir');
                                                    $("#form3").submit();
                                                }else{
                                                    alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                                                }

                                            }else{
                                                alert('El cliente no tiene Credito...');
                                            }
                                        } else {
                                            // alert('no hay deuda pendiente');
                                            let credito_disponible = limite_monto_credito;
                                            // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                                            if (credito_disponible > 0) {
                                                // alert('es mayor puede continuar costo '+ costo);
                                                let credito_disponible_total_operacion = credito_disponible - costo;

                                                if (credito_disponible_total_operacion >= 0) {
                                                    // alert('puede seguir');
                                                    $("#form3").submit();
                                                }else{
                                                    alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                                                }

                                            }else{
                                                alert('El cliente no tiene Credito...');
                                            }
                                        }

                                    }
                                }else{
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    alert('No has seleccionado un modo de pago! ... (Contado, Crédito o Cortesía.)');
                                }
                            }else{
                                alert('¡Error al Ingresar el QR!... Por favor Ingrese el QR correcto. (llave incorrecta)');
                                return false;
                            }
                        }

                    },

                    onScanError: function(err) {
                        var sFormatedErrorString = "Error Details: {\n";
                        for (var i in err) {
                            sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
                        }
                        sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
                        console.log("[onScanError]: " + sFormatedErrorString);
                    }

                })

            } catch (e) { //captura de errores generales de inicialización de onscan.js
                alert('Error OnScan' + e);
                // toastr.error('', 'Error OnScan' + e)
            }

        });


    </script>
    <script>

        function numDecimal(valor){
            let result = Number(valor).toFixed(2);

            return result;
        }

        // focusMethod = function getFocus() {
            //document.getElementById(".selval").focus();
            //$(".selval").val('default');
            //$(".selval").selectpicker("refresh");
        //}

        $(function() {
            $('.refrescar').on('click', function() {
                $(".selval").val('default');
                    $(".selval").selectpicker("refresh");
                $('.detalle').hide('swing');
                $('#procesarServicio').hide("swing");
                $('#btnPago').hide("swing");
                $('#infoPago').hide("swing");
                $('#infoQR').hide("swing");

            });
        });

        $(function() {
            $('.precio').on('change', function() {
                // alert('limpiesa');
                let catID = $("#categoria_id_nueva").val();

                let valor = this.value;
                let catid = catID;
                let texto = this.options[this.selectedIndex].text;
                console.log(valor + ' '+catid + ' '+texto+ ' - _-' +catID);


                $('.horario').val(valor);


                if ($.trim(valor != '')) {
                    let tasaDolar = $('#tasaDolar').val();
                    let tasaPeso = $('#tasaPeso').val();
                    $.ajax({
                        type: 'get',
                        url: '{{ url ("precio") }}',
                        data: "cat_id=" + catid+"&horario_id=" + valor,
                        success: function (precioData) {
                            alert(precioData[0].precio);
                            if (precioData.length) {
                                $('.precio').val(precioData[0].id);
                                console.log(precioData);
                                // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');
                                $("#horario_id").val(valor);
                                $("#precio_id").val(precioData[0].id);
                                $("#precio_id_nueva").val(precioData[0].precio);
                                $("#precio_id_nueva2").val(precioData[0].precio);
                                // $("#horario_tipo").val(precioData[0].precio);
                                // $("#horario").val(precioData[0].precio);

                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                let precio = precioData[0].precio;

                                let isCortesia = $("#isCortesia").val();
                                let isCredito = $("#isCredito").val();

                                precio = numDecimal(precio);
                                $("#precio_nueva").val(precio);


                                let precioVieja = $("#precio_vieja").val();

                                precioVieja = numDecimal(precioVieja);
                                let diferencia = precio - precioVieja;

                                $("#precioDolarHabitacio").val(diferencia);
                                $("#diferenciaPrecio").html('($'+diferencia+')');
                                $("#total_costo").val(diferencia);
                                $("#precio_costo").val(diferencia);

                                if( (is_numeric(diferencia)) && (diferencia>0) ){
                                    // return true;
                                }else{
                                    $('#modo_pago').val('cambio');
                                    $("#precioDolarHabitacio").val(diferencia);
                                    $("#diferenciaPrecio").html('($0.00)');
                                    $("#total_costo").val(0.00);
                                    $("#precio_costo").val(0.00);
                                }

                                // if(){
                                //     $('#modo_pago').val('cambio');
                                // }

                                // let str = datosHabitacionNueva[1].padStart(8,'0');
                                // alert(str);

                                // $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[7]);


                                // $('#modo_pago').val('cambio');


                                // procesoCambioSalida = 1;

                                // alert('precio = ' + precio + ' precio vieja = ' + precioVieja);

                                if(isCortesia){
                                    let precio = $("#precioDolarHabitacio").val();
                                    $("#precio_costo").val(precio);
                                    $('#cortesia').show();
                                    $('#precortesia').show();
                                    // console.log('Este cliente puede tener credito');
                                }else{
                                    $('#cortesia').hide();
                                    $('#precortesia').hide();
                                }
                                if(isCredito){
                                    let precio = $("#precioDolarHabitacio").val();
                                    $("#precio_costo").val(precio);
                                    // console.log('Este cliente puede tener credito');
                                    $('#credito').show();
                                    $('#precredito').show();

                                }else{

                                    $('#credito').hide();
                                    $('#precredito').hide();
                                }

                                // let valor = this.value;
                                // let catid = $(this).attr('data-id');
                                // let texto = this.options[this.selectedIndex].text;

                                // console.log('valor = '+valor+' catid= '+catid);

                                // $('.horario').val(valor);



                                let tasaDolar = $('#tasaDolar').val();
                                let tasaPeso = $('#tasaPeso').val();



                                if (precio) {
                                    $('.precio').val(precio);
                                    // console.log(precio);
                                    // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                                    // $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio*tasaDolar)+'</h5></div></div>');





                                    // TODO utilizamos la libreria decimal.js para realizar operaciones de comparacion en el precio de las habitaciones


                                    $('.detalle').show("swing");

                                    x1 = new Decimal(precio);

                                    y1 = new Decimal(precioVieja);


                                    if (x1.greaterThan(y1)) {
                                        // alert('mayor');
                                        $('#btnPago').show("swing");
                                        $('#infoQR').hide("swing");
                                        $('#infoPago').show("swing");
                                    }
                                    if (x1.lessThanOrEqualTo(y1)) {
                                        // alert('menor o igual');
                                        $('#btnPago').hide("swing");
                                        $('#infoQR').show("swing");
                                        $('#infoPago').hide("swing");

                                    }

                                    $('#procesarServicio').show();
                                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                                            // // alert(origens[0].nombre);
                                            // for(var i = 0; i < origens.length; i++){
                                            // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                                            // }
                                }else{
                                    // alert('no precio');
                                    $('.detalle').hide("swing");
                                    $('#procesarServicio').hide("swing");
                                    $('#btnPago').hide("swing");
                                    $('#infoQR').hide("swing");
                                    $('#infoPago').hide("swing");

                                }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precioData[0].precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precioData[0].precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precioData[0].precio*tasaDolar)+'</h5></div></div>');

                                $('.detalle').show("swing");
                                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                                    // // alert(origens[0].nombre);
                                    // for(var i = 0; i < origens.length; i++){
                                    // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                                    // }

                                $('.ocular').show('swing');
                            }else{
                                $('.ocular').hide('swing');
                            }
                        }
                    });

                }

            });
        });

        $('.detalle').hide();
        $('#procesarServicio').hide();
        $('#btnPago').hide();
        $('#infoQR').hide();
        $('#infoPago').hide();

        $(function() {
            $('.selval').on('change', function() {
                $('.detalle').hide("swing");
                $('#procesarServicio').hide("swing");
                $('#btnPago').hide("swing");
                $('#infoQR').hide("swing");
                $('#infoPago').hide("swing");

                // procesoCamb = 0;

                datosHabitacionNueva = document.getElementById('buscarHabitacion').value.split('_');
                    // alert(datosHabitacionNueva[3]);
                    // return false;
                    // $("#jprecio_venta").val(datosArticulo[2]);
                    $("#habitacion_id_nueva").val(datosHabitacionNueva[0]);
                    $("#habitacion_id_nueva2").val(datosHabitacionNueva[0]);
                    $("#nombre_nueva").val(datosHabitacionNueva[1]);
                    $("#nombre_nueva2").val(datosHabitacionNueva[1]);
                    $("#categoria_id_nueva").val(datosHabitacionNueva[2]);
                    $("#categoria_id_nueva2").val(datosHabitacionNueva[2]);
                    $("#categoria_nueva").val(datosHabitacionNueva[3]);
                    $("#categoria_nueva2").val(datosHabitacionNueva[3]);
                    $("#categoria_dest_nueva").val(datosHabitacionNueva[4]);
                    $("#categoria_dest_nueva2").val(datosHabitacionNueva[4]);

                    $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[5]);
                    // $("#precio_id_nueva").val(datosHabitacionNueva[5]);
                    // $("#precio_id_nueva2").val(datosHabitacionNueva[5]);


                //     let precio = $("#precio_id_nueva").val();

                //     let isCortesia = $("#isCortesia").val();
                //     let isCredito = $("#isCredito").val();

                //     precio = numDecimal(precio);
                //     $("#precio_nueva").val(precio);


                //     let precioVieja = $("#precio_vieja").val();

                //     precioVieja = numDecimal(precioVieja);
                //     let diferencia = precio - precioVieja;

                //     $("#precioDolarHabitacio").val(diferencia);
                //     $("#diferenciaPrecio").html('($'+diferencia+')');
                //     $("#total_costo").val(diferencia);
                //     $("#precio_costo").val(diferencia);

                //     if( (is_numeric(diferencia)) && (diferencia>0) ){
                //         // return true;
                //     }else{
                //         $('#modo_pago').val('cambio');
                //         $("#precioDolarHabitacio").val(diferencia);
                //         $("#diferenciaPrecio").html('($0.00)');
                //         $("#total_costo").val(0.00);
                //         $("#precio_costo").val(0.00);
                //     }

                //     // if(){
                //     //     $('#modo_pago').val('cambio');
                //     // }

                //     // let str = datosHabitacionNueva[1].padStart(8,'0');
                //     // alert(str);

                //     $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[5]);


                //     // $('#modo_pago').val('cambio');


                //     // procesoCambioSalida = 1;

                //     // alert('precio = ' + precio + ' precio vieja = ' + precioVieja);

                //     if(isCortesia){
                //         let precio = $("#precioDolarHabitacio").val();
                //         $("#precio_costo").val(precio);
                //         $('#cortesia').show();
                //         $('#precortesia').show();
                //         // console.log('Este cliente puede tener credito');
                //     }else{
                //         $('#cortesia').hide();
                //         $('#precortesia').hide();
                //     }
                //     if(isCredito){
                //         let precio = $("#precioDolarHabitacio").val();
                //         $("#precio_costo").val(precio);
                //         // console.log('Este cliente puede tener credito');
                //         $('#credito').show();
                //         $('#precredito').show();

                //     }else{

                //         $('#credito').hide();
                //         $('#precredito').hide();
                //     }

                // // let valor = this.value;
                // // let catid = $(this).attr('data-id');
                // // let texto = this.options[this.selectedIndex].text;

                // // console.log('valor = '+valor+' catid= '+catid);

                // // $('.horario').val(valor);

                //     let tasaDolar = $('#tasaDolar').val();
                //     let tasaPeso = $('#tasaPeso').val();

                //     if (precio) {
                //         $('.precio').val(precio);
                //         // console.log(precio);
                //         // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                //         $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio*tasaDolar)+'</h5></div></div>');


                //     // TODO utilizamos la libreria decimal.js para realizar operaciones de comparacion en el precio de las habitaciones


                //         $('.detalle').show("swing");

                //         x1 = new Decimal(precio);

                //         y1 = new Decimal(precioVieja);


                //         if (x1.greaterThan(y1)) {
                //             // alert('mayor');
                //             $('#btnPago').show("swing");
                //             $('#infoQR').hide("swing");
                //             $('#infoPago').show("swing");
                //         }
                //         if (x1.lessThanOrEqualTo(y1)) {
                //             // alert('menor o igual');
                //             $('#btnPago').hide("swing");
                //             $('#infoQR').show("swing");
                //             $('#infoPago').hide("swing");

                //         }

                //         $('#procesarServicio').show();
                //         // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                //                 // // alert(origens[0].nombre);
                //                 // for(var i = 0; i < origens.length; i++){
                //                 // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                //                 // }
                //     }else{
                //         // alert('no precio');
                //         $('.detalle').hide("swing");
                //         $('#procesarServicio').hide("swing");
                //         $('#btnPago').hide("swing");
                //         $('#infoQR').hide("swing");
                //         $('#infoPago').hide("swing");

                //     }

            });

            //     $(function(){
            //   $(".ver").click(function(){
            //     var valor = $(this).attr('data-id')
            //     alert(valor);
            //     // $("#resultado").html(valor)
            //   })
            // })


        });

        function is_numeric( mixed_var ) {
            // Returns true if value is a number or a numeric string

            return !isNaN(mixed_var * 1);
        }

        function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g,
                    "$1" +
                    thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        };

        function addHabitacion(){
            totalcosto = $("#total_costo").val();
            var is24 = $('#is24').val();
            if (totalcosto == '') {
                total = $("#precioDolarHabitacio").val();
                totalcosto = total;
                $("#total_costo").val(total);
                $('#cantidad').val(1);

            } else {
                // alert('no 24');
                total = $("#total_costo").val();
                totalr = $("#precioDolarHabitacio").val();
                $("#precio_costo").val(totalr);
            }

                    total_costo = total_costo + subtotalPC[cont];
                    total_d     = total;
                    total_p     = total;
                    total_tp    = total;
                    total_m     = total;
                    total_e     = total;

            $("#PagoTtotal").html(numDecimal(total));

        }
</script> -->
<script>

        document.addEventListener('DOMContentLoaded', function() {

            $('#imprimirFactura').show();
            $('#imprimirBoleta').show();

            try {

                onScan.attachTo(document, {
                    //configuración del sufijo/ tecla esperada al finalizar la lectura del scan, esto indica a onScan la finalización del evento
                    suffixKeyCodes: [13],
                    minLength: 7,
                    onScan: function(barcode) { //función callback que se dispara después de una lectura
                        console.log(barcode)

                        if (procesoCambioSalida == 0) {

                            // alert('cerrar '+ procesoCambioSalida);
                            $('#imprimirFactura').hide();
                            $('#imprimirBoleta').hide();

                            let nombreVieja = $('#nombre_vieja').val();
                                // alert(nombreVieja);

                            let nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
                            // alert('leer '+nombreHabitacionBarcode);
                            let sentence1 = nombreHabitacionBarcode;

                            let word1 = '/';
                            if (sentence1.includes(word)) {
                                datosHabitacionNueva1 = document.getElementById('nombreHabitacionBarcode').value.split('/');
                                // alert(datosHabitacionNueva1[0]);

                                // nombreHabitacionBarcode = datosHabitacionNueva1[0];
                                nombreHabitacionBarcode = fijaLargoIzquierdaBarcode(datosHabitacionNueva1[0]);

                                // alert(nombreHabitacionBarcode);
                                // return false;
                            } else {
                                nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
                            }
                            // alert(nombreHabitacionBarcode);
                            var n = barcode;
                            // alert(n);

                            if (barcode == nombreHabitacionBarcode) {

                                // alert('Todo va bien');
                                // $("#form1").submit();
                                //En la funcion click de imprimir Boleta contiene el codigo de abajo y otros para procesar si tiene deuda o vueltos pendientes
                                $("#imprimirBoleta").click();
                                // let $valorDeuda = $('#total').val();

                                // if($valorDeuda > 0){
                                //     $("#pagoPendienteBtn").click();
                                //     // console.log('tienes deuda pendiente'+$valorDeuda);
                                //     return false;
                                // }else{
                                //     // console.log('todo bien');
                                //     if (VueltosvtosPendientes) {
                                //     alert('VueltosvtosPendientes '+VueltosvtosPendientes);

                                //     } else {
                                //         $("#form1").submit();
                                //         // return false;
                                //     }

                                // }

                            }else{
                                alert('¡Error al Ingresar el QR!... Por favor Ingrese el QR correcto. (llave incorrecta)');
                                return false;
                            }

                        }



                        // alert(procesoCambioSalida);
                        if (procesoCambioSalida == 1) {

                            $('#imprimirFactura').hide();
                            $('#imprimirBoleta').hide();

                            let nombreHabitacionBarcodeNueva = $("#nombreHabitacionBarcodeNueva").val();
                            // alert(nombreHabitacionBarcode);
                            var n = barcode;

                            if (barcode == nombreHabitacionBarcodeNueva) {
                                // alert('es igual barcode');
                                // return false;
                                modoPagoOn = $('#modo_pago').val();
                                tipoPago = $('#tipo_pago').val();
                                monto_dejadoR = $('#monto_dejado').val();
                                // alert(monto_dejadoR);

                                if (modoPagoOn == 'contado') {
                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    if(tipoPago == 0 || tipoPago == null){
                                        alert('No has seleccionado el tipo de pago...! (Ej: Dolar, Peso, Trans, Punto, Mixto...)');
                                        return false;
                                    }

                                    if(monto_dejadoR == 0 || monto_dejadoR == null){
                                        alert('No has ingresado el monto a pagar...!');
                                        return false;
                                    }
                                    // alert('contado');
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'cambio'){
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'cortesia'){
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    $("#form3").submit();
                                }else if (modoPagoOn == 'credito') {
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    let estado_credito = $("#estado_credito").val();
                                    if (estado_credito == 'Moroso') {
                                        alert('Cliente se encuentra suspendido por Incumplimiento de pago! Favor pasar por Oficina a realizar el respectivo pago...');
                                    } else {

                                        addHabitacion();
                                        $("#monto_dejado").val(0);
                                        $("#base_vuelto_monto_dejado").val(0);
                                        $('#modo_pago').val('credito');
                                        let costo = $("#total_costo").val();
                                        // alert('total costo '+costo);
                                        let deuda_credito_pendiente = $("#total_credito_pendiente").val();
                                        // alert(deuda_credito_pendiente);
                                        let limite_fecha_credito = $("#limite_fecha").val();
                                        let limite_monto_credito = $("#limite_monto").val();

                                        if (deuda_credito_pendiente) {
                                            let credito_disponible = limite_monto_credito - deuda_credito_pendiente;
                                            // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                                            if (credito_disponible > 0) {
                                                // alert('es mayor puede continuar costo '+ costo);
                                                let credito_disponible_total_operacion = credito_disponible - costo;

                                                if (credito_disponible_total_operacion >= 0) {
                                                    // alert('puede seguir');
                                                    $("#form3").submit();
                                                }else{
                                                    alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                                                }

                                            }else{
                                                alert('El cliente no tiene Credito...');
                                            }
                                        } else {
                                            // alert('no hay deuda pendiente');
                                            let credito_disponible = limite_monto_credito;
                                            // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

                                            if (credito_disponible > 0) {
                                                // alert('es mayor puede continuar costo '+ costo);
                                                let credito_disponible_total_operacion = credito_disponible - costo;

                                                if (credito_disponible_total_operacion >= 0) {
                                                    // alert('puede seguir');
                                                    $("#form3").submit();
                                                }else{
                                                    alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
                                                }

                                            }else{
                                                alert('El cliente no tiene Credito...');
                                            }
                                        }

                                    }
                                }else{
                                    let cliente_id = $("#cliente_id").val();

                                    if(cliente_id == 0 || cliente_id == null){
                                        alert('No has seleccionado un cliente...!');
                                        return false;
                                    }
                                    alert('No has seleccionado un modo de pago! ... (Contado, Crédito o Cortesía.)');
                                }
                            }else{
                                alert('¡Error al Ingresar el QR!... Por favor Ingrese el QR correcto. (llave incorrecta)');
                                return false;
                            }
                        }

                    },

                    onScanError: function(err) {
                        var sFormatedErrorString = "Error Details: {\n";
                        for (var i in err) {
                            sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
                        }
                        sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
                        console.log("[onScanError]: " + sFormatedErrorString);
                    }

                })

            } catch (e) { //captura de errores generales de inicialización de onscan.js
                alert('Error OnScan' + e);
                // toastr.error('', 'Error OnScan' + e)
            }

        });


    </script>
    <script>

        function numDecimal(valor){
            let result = Number(valor).toFixed(2);

            return result;
        }

        // focusMethod = function getFocus() {
            //document.getElementById(".selval").focus();
            //$(".selval").val('default');
            //$(".selval").selectpicker("refresh");
        //}

        $(function() {
            $('.refrescar').on('click', function() {
                $(".selval").val('default');
                    $(".selval").selectpicker("refresh");
                $('.detalle').hide('swing');
                $('#procesarServicio').hide("swing");
                $('#btnPago').hide("swing");
                $('#infoPago').hide("swing");
                $('#infoQR').hide("swing");

            });
        });

        $(function() {
            $('.precio').on('change', function() {
                // alert('limpiesa');
                let catID = $("#categoria_id_nueva").val();

                let valor = this.value;
                let catid = catID;
                let texto = this.options[this.selectedIndex].text;
                console.log(valor + ' '+catid + ' '+texto+ ' - _-' +catID);


                $('.horario').val(valor);


                if ($.trim(valor != '')) {
                    let tasaDolar = $('#tasaDolar').val();
                    let tasaPeso = $('#tasaPeso').val();
                    $.ajax({
                        type: 'get',
                        url: '{{ url ("precio") }}',
                        data: "cat_id=" + catid+"&horario_id=" + valor,
                        success: function (precioData) {
                            // alert('jj'+precioData[0].precio);
                            if (precioData.length) {
                                $('.precio').val(precioData[0].id);
                                console.log(precioData);
                                // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');
                                $("#horario_id").val(valor);
                                $("#precio_id").val(precioData[0].id);
                                $("#precio_id_nueva").val(precioData[0].precio);
                                $("#precio_id_nueva2").val(precioData[0].precio);
                                // $("#horario_tipo").val(precioData[0].precio);
                                // $("#horario").val(precioData[0].precio);

                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                let precio = precioData[0].precio;

                                let isCortesia = $("#isCortesia").val();
                                let isCredito = $("#isCredito").val();

                                precio = numDecimal(precio);
                                $("#precio_nueva").val(precio);


                                let precioVieja = $("#precio_vieja").val();

                                precioVieja = numDecimal(precioVieja);
                                let diferencia = precio - precioVieja;

                                $("#precioDolarHabitacio").val(diferencia);
                                $("#diferenciaPrecio").html('($'+diferencia+')');
                                $("#total_costo").val(diferencia);
                                $("#precio_costo").val(diferencia);

                                if( (is_numeric(diferencia)) && (diferencia>0) ){
                                    // return true;
                                }else{
                                    $('#modo_pago').val('cambio');
                                    $("#precioDolarHabitacio").val(diferencia);
                                    $("#diferenciaPrecio").html('($0.00)');
                                    $("#total_costo").val(0.00);
                                    $("#precio_costo").val(0.00);
                                }

                                // if(){
                                //     $('#modo_pago').val('cambio');
                                // }

                                // let str = datosHabitacionNueva[1].padStart(8,'0');
                                // alert(str);

                                // $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[7]);


                                // $('#modo_pago').val('cambio');


                                // procesoCambioSalida = 1;

                                // alert('precio = ' + precio + ' precio vieja = ' + precioVieja);

                                if(isCortesia){
                                    let precio = $("#precioDolarHabitacio").val();
                                    $("#precio_costo").val(precio);
                                    $('#cortesia').show();
                                    $('#precortesia').show();
                                    // console.log('Este cliente puede tener credito');
                                }else{
                                    $('#cortesia').hide();
                                    $('#precortesia').hide();
                                }
                                if(isCredito){
                                    let precio = $("#precioDolarHabitacio").val();
                                    $("#precio_costo").val(precio);
                                    // console.log('Este cliente puede tener credito');
                                    $('#credito').show();
                                    $('#precredito').show();

                                }else{

                                    $('#credito').hide();
                                    $('#precredito').hide();
                                }

                                // let valor = this.value;
                                // let catid = $(this).attr('data-id');
                                // let texto = this.options[this.selectedIndex].text;

                                // console.log('valor = '+valor+' catid= '+catid);

                                // $('.horario').val(valor);



                                let tasaDolar = $('#tasaDolar').val();
                                let tasaPeso = $('#tasaPeso').val();



                                if (precio) {
                                    $('.precio').val(precio);
                                    // console.log(precio);
                                    // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                                    // $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio*tasaDolar)+'</h5></div></div>');





                                    // TODO utilizamos la libreria decimal.js para realizar operaciones de comparacion en el precio de las habitaciones


                                    $('.detalle').show("swing");

                                    x1 = new Decimal(precio);

                                    y1 = new Decimal(precioVieja);


                                    if (x1.greaterThan(y1)) {
                                        // alert('mayor');
                                        $('#btnPago').show("swing");
                                        $('#infoQR').hide("swing");
                                        $('#infoPago').show("swing");
                                    }
                                    if (x1.lessThanOrEqualTo(y1)) {
                                        // alert('menor o igual');
                                        $('#btnPago').hide("swing");
                                        $('#infoQR').show("swing");
                                        $('#infoPago').hide("swing");

                                    }

                                    $('#procesarServicio').show();
                                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                                            // // alert(origens[0].nombre);
                                            // for(var i = 0; i < origens.length; i++){
                                            // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                                            // }
                                }else{
                                    // alert('no precio');
                                    $('.detalle').hide("swing");
                                    $('#procesarServicio').hide("swing");
                                    $('#btnPago').hide("swing");
                                    $('#infoQR').hide("swing");
                                    $('#infoPago').hide("swing");

                                }
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precioData[0].precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precioData[0].precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precioData[0].precio*tasaDolar)+'</h5></div></div>');

                                $('.detalle').show("swing");
                                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                                    // // alert(origens[0].nombre);
                                    // for(var i = 0; i < origens.length; i++){
                                    // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                                    // }

                                $('.ocular').show('swing');
                            }else{
                                $('.ocular').hide('swing');
                            }
                        }
                    });

                }

            });
        });

        $('.detalle').hide();
        $('#procesarServicio').hide();
        $('#btnPago').hide();
        $('#infoQR').hide();
        $('#infoPago').hide();

        $(function() {
            $('.selval').on('change', function() {
                $('.detalle').hide("swing");
                $('#procesarServicio').hide("swing");
                $('#btnPago').hide("swing");
                $('#infoQR').hide("swing");
                $('#infoPago').hide("swing");

                // procesoCamb = 0;

                datosHabitacionNueva = document.getElementById('buscarHabitacion').value.split('_');
                    // alert(datosHabitacionNueva[3]);
                    // return false;
                    // $("#jprecio_venta").val(datosArticulo[2]);
                    $("#habitacion_id_nueva").val(datosHabitacionNueva[0]);
                    $("#habitacion_id_nueva2").val(datosHabitacionNueva[0]);
                    $("#nombre_nueva").val(datosHabitacionNueva[1]);
                    $("#nombre_nueva2").val(datosHabitacionNueva[1]);
                    $("#categoria_id_nueva").val(datosHabitacionNueva[2]);
                    $("#categoria_id_nueva2").val(datosHabitacionNueva[2]);
                    $("#categoria_nueva").val(datosHabitacionNueva[3]);
                    $("#categoria_nueva2").val(datosHabitacionNueva[3]);
                    $("#categoria_dest_nueva").val(datosHabitacionNueva[4]);
                    $("#categoria_dest_nueva2").val(datosHabitacionNueva[4]);

                    $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[5]);
                    // $("#precio_id_nueva").val(datosHabitacionNueva[5]);
                    // $("#precio_id_nueva2").val(datosHabitacionNueva[5]);


                //     let precio = $("#precio_id_nueva").val();

                //     let isCortesia = $("#isCortesia").val();
                //     let isCredito = $("#isCredito").val();

                //     precio = numDecimal(precio);
                //     $("#precio_nueva").val(precio);


                //     let precioVieja = $("#precio_vieja").val();

                //     precioVieja = numDecimal(precioVieja);
                //     let diferencia = precio - precioVieja;

                //     $("#precioDolarHabitacio").val(diferencia);
                //     $("#diferenciaPrecio").html('($'+diferencia+')');
                //     $("#total_costo").val(diferencia);
                //     $("#precio_costo").val(diferencia);

                //     if( (is_numeric(diferencia)) && (diferencia>0) ){
                //         // return true;
                //     }else{
                //         $('#modo_pago').val('cambio');
                //         $("#precioDolarHabitacio").val(diferencia);
                //         $("#diferenciaPrecio").html('($0.00)');
                //         $("#total_costo").val(0.00);
                //         $("#precio_costo").val(0.00);
                //     }

                //     // if(){
                //     //     $('#modo_pago').val('cambio');
                //     // }

                //     // let str = datosHabitacionNueva[1].padStart(8,'0');
                //     // alert(str);

                //     $("#nombreHabitacionBarcodeNueva").val(datosHabitacionNueva[5]);


                //     // $('#modo_pago').val('cambio');


                //     // procesoCambioSalida = 1;

                //     // alert('precio = ' + precio + ' precio vieja = ' + precioVieja);

                //     if(isCortesia){
                //         let precio = $("#precioDolarHabitacio").val();
                //         $("#precio_costo").val(precio);
                //         $('#cortesia').show();
                //         $('#precortesia').show();
                //         // console.log('Este cliente puede tener credito');
                //     }else{
                //         $('#cortesia').hide();
                //         $('#precortesia').hide();
                //     }
                //     if(isCredito){
                //         let precio = $("#precioDolarHabitacio").val();
                //         $("#precio_costo").val(precio);
                //         // console.log('Este cliente puede tener credito');
                //         $('#credito').show();
                //         $('#precredito').show();

                //     }else{

                //         $('#credito').hide();
                //         $('#precredito').hide();
                //     }

                // // let valor = this.value;
                // // let catid = $(this).attr('data-id');
                // // let texto = this.options[this.selectedIndex].text;

                // // console.log('valor = '+valor+' catid= '+catid);

                // // $('.horario').val(valor);

                //     let tasaDolar = $('#tasaDolar').val();
                //     let tasaPeso = $('#tasaPeso').val();

                //     if (precio) {
                //         $('.precio').val(precio);
                //         // console.log(precio);
                //         // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                //         $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio*tasaDolar)+'</h5></div></div>');


                //     // TODO utilizamos la libreria decimal.js para realizar operaciones de comparacion en el precio de las habitaciones


                //         $('.detalle').show("swing");

                //         x1 = new Decimal(precio);

                //         y1 = new Decimal(precioVieja);


                //         if (x1.greaterThan(y1)) {
                //             // alert('mayor');
                //             $('#btnPago').show("swing");
                //             $('#infoQR').hide("swing");
                //             $('#infoPago').show("swing");
                //         }
                //         if (x1.lessThanOrEqualTo(y1)) {
                //             // alert('menor o igual');
                //             $('#btnPago').hide("swing");
                //             $('#infoQR').show("swing");
                //             $('#infoPago').hide("swing");

                //         }

                //         $('#procesarServicio').show();
                //         // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                //                 // // alert(origens[0].nombre);
                //                 // for(var i = 0; i < origens.length; i++){
                //                 // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                //                 // }
                //     }else{
                //         // alert('no precio');
                //         $('.detalle').hide("swing");
                //         $('#procesarServicio').hide("swing");
                //         $('#btnPago').hide("swing");
                //         $('#infoQR').hide("swing");
                //         $('#infoPago').hide("swing");

                //     }

            });

            //     $(function(){
            //   $(".ver").click(function(){
            //     var valor = $(this).attr('data-id')
            //     alert(valor);
            //     // $("#resultado").html(valor)
            //   })
            // })


        });

        function is_numeric( mixed_var ) {
            // Returns true if value is a number or a numeric string

            return !isNaN(mixed_var * 1);
        }

        function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g,
                    "$1" +
                    thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        };

        function addHabitacion(){
            totalcosto = $("#total_costo").val();
            var is24 = $('#is24').val();
            if (totalcosto == '') {
                total = $("#precioDolarHabitacio").val();
                totalcosto = total;
                $("#total_costo").val(total);
                $('#cantidad').val(1);

            } else {
                // alert('no 24');
                total = $("#total_costo").val();
                totalr = $("#precioDolarHabitacio").val();
                $("#precio_costo").val(totalr);
            }

                    total_costo = total_costo + subtotalPC[cont];
                    total_d     = total;
                    total_p     = total;
                    total_tp    = total;
                    total_m     = total;
                    total_e     = total;

            $("#PagoTtotal").html(numDecimal(total));

        }
</script>

<script>

        // document.addEventListener('DOMContentLoaded', function() {
        //     $('#imprimirFactura').hide();
        //     $('#imprimirBoleta').hide();
        //     try {


        //         onScan.attachTo(document, {
        //             //configuración del sufijo/ tecla esperada al finalizar la lectura del scan, esto indica a onScan la finalización del evento
        //             suffixKeyCodes: [13],
        //             minLength: 2,
        //             onScan: function(barcode) { //función callback que se dispara después de una lectura
        //                 console.log(barcode)

        //                     let nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
        //                     // alert(nombreHabitacionBarcode);
        //                     var n = barcode;
        //                     // alert(n);

        //                     if (barcode == nombreHabitacionBarcode) {

        //                         // alert('Todo va bien');
        //                         $("#form1").submit();

        //                     }else{
        //                         alert('¡Error al Ingresar el QR!... Por favor Ingrese el QR correcto. (llave incorrecta)');
        //                         return false;
        //                     }
        //             },

        //             // onScanError: function(e) { //función callback para captura de errores de lectura
        //             //     console.log('Error de lectura' + e[0]);
        //             //     // toastr.error('', 'Error de lectura' + e)
        //             // },
        //             onScanError: function(err) {
        //                 var sFormatedErrorString = "Error Details: {\n";
        //                 for (var i in err) {
        //                     sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
        //                 }
        //                 sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
        //                 console.log("[onScanError]: " + sFormatedErrorString);
        //             }

        //         })
        //         // alert('OnScan ready!');
        //         // toastr.success('', 'OnScan ready!')

        //     } catch (e) { //captura de errores generales de inicialización de onscan.js
        //         alert('Error OnScan' + e);
        //         // toastr.error('', 'Error OnScan' + e)
        //     }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // $("#enviar").on('click', function() {
            //     $("#form1").submit();
            // });


            // $("#procesarServicio").on('click', function() {
            //     addHabitacion();
            //     $("#monto_dejado").val(0);
            //     $('#modo_pago').val('cambio');
            //     $("#form3").submit();
            // });

            // $("#contado").on('click', function() {
            //     let cliente_id = $("#cliente_id").val();
            //     if(cliente_id == 0 || cliente_id == null){
            //         alert('No has seleccionado un cliente...!');
            //         return false;
            //     }

            //     $('#modo_pago').val('contado');
            // });
            // $("#precortesia").on('click', function() {
            //     let cliente_id = $("#cliente_id").val();
            //     if(cliente_id == 0 || cliente_id == null){
            //         alert('No has seleccionado un cliente...!');
            //         return false;
            //     }
            //     $('#modo_pago').val('cortesia');

            // });
            // $("#cortesia").on('click', function() {
            //     addHabitacion();
            //     $("#monto_dejado").val(0);
            //     $('#modo_pago').val('cortesia');
            //     $("#form1").submit();
            // });
            // $("#precredito").on('click', function() {

            //     let cliente_id = $("#cliente_id").val();
            //     if(cliente_id == 0 || cliente_id == null){
            //         alert('No has seleccionado un cliente...!');
            //         return false;
            //     }
            //     $('#modo_pago').val('credito');

            // });
            // $("#credito").on('click', function() {
            //     let estado_credito = $("#estado_credito").val();
            //     if (estado_credito == 'Moroso') {
            //         alert('Cliente se encuentra suspendido por Incumplimiento de pago! Favor pasar por Oficina a realizar el respectivo pago...');
            //     } else {


            //     addHabitacion();
            //     $("#monto_dejado").val(0);
            //     $('#modo_pago').val('credito');
            //     let costo = $("#total_costo").val();
            //     // alert('total costo '+costo);
            //     let deuda_credito_pendiente = $("#total_credito_pendiente").val();
            //     // alert(deuda_credito_pendiente);
            //     let limite_fecha_credito = $("#limite_fecha").val();
            //     let limite_monto_credito = $("#limite_monto").val();

            //     if (deuda_credito_pendiente) {
            //         let credito_disponible = limite_monto_credito - deuda_credito_pendiente;
            //         // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

            //         if (credito_disponible > 0) {
            //             // alert('es mayor puede continuar costo '+ costo);
            //             let credito_disponible_total_operacion = credito_disponible - costo;

            //             if (credito_disponible_total_operacion >= 0) {
            //                 // alert('puede seguir');
            //                 $("#form1").submit();
            //             }else{
            //                 alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
            //             }

            //         }else{
            //             alert('El cliente no tiene Credito...');
            //         }
            //     } else {
            //         // alert('no hay deuda pendiente');
            //         let credito_disponible = limite_monto_credito;
            //         // alert('si hay deuda pendiente y el limite es de '+limite_monto_credito+ ' y el credito disponible es de '+credito_disponible);

            //         if (credito_disponible > 0) {
            //             // alert('es mayor puede continuar costo '+ costo);
            //             let credito_disponible_total_operacion = credito_disponible - costo;

            //             if (credito_disponible_total_operacion >= 0) {
            //                 // alert('puede seguir');
            //                 $("#form1").submit();
            //             }else{
            //                 alert('El credito disponible no supera el monto a pagar... Credito disponible es de: $'+credito_disponible+ ' Costo del Servicio es de: $'+costo);
            //             }

            //         }else{
            //             alert('El cliente no tiene Credito...');
            //         }
            //     }


            //     }
            // });
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // })





</script>
@endpush
@endsection
