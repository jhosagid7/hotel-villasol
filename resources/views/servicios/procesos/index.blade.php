@extends ('layouts.admin3')
@section('contenido')

<?php
date_default_timezone_set('America/Caracas');
     $hoy = date("Y-m-d");
   $hora = date("H:i:s");



   $dia24 = strtotime('+1 day', strtotime($hoy));
   $dia24 = date('Y-m-d', $dia24);

   $horaCalculoComercial = date("H");
   $valorOtro = $horaCalculoComercial;

   //este proceso controla las horas extras comencial
   if($valorOtro >= 17 && $valorOtro <= 23){
        // $valorOtrodata = 'si sumar dìas '.$valorOtro;
        $diaComercial = strtotime('+1 day', strtotime($hoy));
        $diaComercial = date('Y-m-d', $diaComercial);
    }else{
        // $valorOtrodata = 'no sumar dìas '.$valorOtro;
        $diaComercial = strtotime($hoy);
        $diaComercial = date('Y-m-d', $diaComercial);
   }


//    $hora24 = $dia24->format('H:i:s A');

   $hora24 = strtotime('+24 hour', strtotime($hora));
   $hora24 = date('H:i:s', $hora24);
?>
<style type="text/css">
.table > tbody > tr > td{
    padding: 0px !important;
}
.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
    width: 100%;
}

/* @media (min-width: 100%) {
     .modal-dialog {
       max-width: 100%;
     }
} */


</style>
@section('styles')
{{-- <link rel="stylesheet" href="{{asset('dist/css/jquery-ui.css')}}"> --}}



@endsection





        <div class="box">

            <div style="background-color: #e7eaeb" class="box-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs pull-right">




                            <li class="pull-left header"><i class="fa fa-hotel"></i> @isset($title)
                                {{$title}}
                                @else
                                {!!"PROCESAR HABITACIÓN"!!}
                            @endisset</li>
                          </ul>
                          <div style="background-color: #e7eaeb" class="tab-content">
                            <div class="row">
                            {{-- <section class="content-header">
                                <h1 >
                                  <span class="fa fa-hotel"></span> PROCESAR HABITACIÓN
                                  <small>Avance</small>
                                </h1>
                                <ol class="breadcrumb">
                                  <li><a href="index.php?view=reserva"><i class="fa fa-home"></i> Inicio</a></li>
                                  <li><a href="#">Recepción</a></li>
                                  <li class="active">Procesar</li>
                                </ol>
                          </section> --}}
                          </div>

                          <div class="row">
                          <section class="content">

                            @if (isset($habitacion->id))

                                          @if ($habitacion)
                                            {{-- si hay habitacion --}}
                                            <form class="form-horizontal" id="form1" role="form" action="{{ route('servicio.store')}}" method="POST">
                                            @csrf
                                            {{-- <form class="form-horizontal" method="post" id="form1" action="index.php?view=addproceso" role="form"> --}}
                                  <div class="box box-default">
                                      <div class="box-header with-border">
                                        <h3 class="box-title">Datos de la habitación</h3>
                                      </div>
                                      <!-- /.box-header -->
                                      <div class="box-body">
                                        <div class="table-responsive">
                                          <table class="table no-margin">

                                            <tbody style="padding: 0px;">
                                            <tr style="padding: 0px;">
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Nombre:</h4></td>
                                              <td>{{$habitacion->nombre}} <input type="hidden" name="nombreHabitacion" value="{{$habitacion->nombre ?? ''}}">
                                                <input type="hidden" name="nombreHabitacionBarcode" id="nombreHabitacionBarcode" value="{{str_pad($habitacion->nombre,7,'0',STR_PAD_LEFT) ?? ''}}"></td>
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Tipo:</h4></td>
                                              <td>
                                                <div class="sparkbar" data-color="#00a65a" data-height="20">{{$habitacion->cat->nombre}}</div>
                                              </td>
                                            </tr>
                                            <tr style="padding: 0px;">
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Detalles:</h4></td>
                                              <td>{{$habitacion->cat->descripcion}} <input type="hidden" name="detalle_habitacion" value="{{$habitacion->cat->descripcion ?? ''}}"></td>
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Estado:</h4></td>
                                              <td>
                                                <div class="sparkbar" data-color="#f39c12" data-height="20"><span class="label label-success">DISPONIBLE</span></div>
                                              </td>
                                            </tr>


                                            </tbody>
                                          </table>

                                        </div>
                                        <!-- /.table-responsive -->
                                      </div>

                                    </div>
                                    <!-- /.box -->



                                  <div class="box box-default">

                                      <div class="box-body">
                                        <div class="table-responsive">

                                          <div class="col-md-6">
                                          <table class="table no-margin">
                                            <tr>
                                              <th colspan="4" style="text-align: center;">DATOS DEL CLIENTE</th>
                                            </tr>
                                            <tbody style="padding: 0px;">

                                            <tr style="padding: 0px;">


                                    <td colspan="2">

                                        <!-- Date dd/mm/yyyy -->
                                        <div class="form-group">
                                          <label>Seleccione Cliente:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                            <select name="buscarCliente" id="buscarCliente" class="form-control selectpicker"
                                            data-live-search="true">
                                            <option value="0">Ingrese cliente para buscar</option>
                                            @php
                                                $i = 0;
                                            @endphp
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}_{{ $cliente->nombre }}_{{ $cliente->num_documento }}_{{ $cliente->direccion }}_{{ $cliente->isCortesia }}_{{ $cliente->isCredito }}_{{ $cliente->telefono }}_{{ $cliente->limite_fecha }}_{{ $cliente->limite_monto }}_<?php $deuda_cliente = "App\Credito"::where('persona_id',$cliente->id)->select('total_deuda')->first(); ?>{{$deuda_cliente['total_deuda']}}_<?php $deuda_cliente = "App\Credito"::where('persona_id',$cliente->id)->select('estado_credito')->first(); ?>{{$deuda_cliente['estado_credito']}}_<?php $excedente_cliente = "App\Excedente"::where('persona_id',$cliente->id)->select('excedente')->first(); ?>{{$excedente_cliente['excedente']}}">{{ $cliente->nombre }}</option>
                                                @php
                                                $i++;
                                            @endphp
                                            @endforeach
                                        </select>
                                        <div class="input-group-addon">
                                            <i class="fa fa-search-plus"></i>
                                          </div>
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        {{-- <div class="form-group">
                                          <label>Tipo de Documento:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                            <select class="form-control" name="tipo_documento">
                                              <option value="1">C.I.</option>
                                              <option value="2">PASAPORTE</option>
                                              <option value="3">R.U.T</option>
                                            </select>
                                          </div>
                                          <!-- /.input group -->
                                        </div> --}}

                                        <div class="form-group">
                                          <label>Documento:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa  fa-arrow-circle-o-right"></i>
                                            </div>
                                            <input type="text" class="form-control" name="num_documento" id="num_documento" readonly required="required" placeholder="Ingrese número de documento">
                                            <input type="hidden" name="cliente_id" value="" id="cliente_id">
                                            <input type="hidden" name="limite_fecha" value="" id="limite_fecha">
                                            <input type="hidden" name="limite_monto" value="" id="limite_monto">
                                            <input type="hidden" name="total_credito_pendiente" value="" id="total_credito_pendiente">
                                            <input type="hidden" name="estado_credito" value="" id="estado_credito">
                                            {{-- <div class="input-group-addon">
                                                <i class="fa fa-search-plus"></i>
                                              </div> --}}
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        <div class="form-group">
                                          <label>Nombres:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-user-secret"></i>
                                            </div>
                                            <input type="text" class="form-control" name="nombre" id="nombre"  readonly required placeholder="Ingrese nombres" >
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        <div class="form-group">
                                          <label>Dirección:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-map-marker"></i>
                                            </div>
                                            <input type="text" class="form-control" name="direccion" id="direccion" readonly  placeholder="Ingrese direccion (No es obligatorio)"  data-mask>
                                            <input id="telefono" type="hidden" name="telefono" value="">
                                          </div>
                                          <!-- /.input group -->
                                        </div>



                          </td>

                                            </tr>




                                            </tbody>
                                          </table>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">
                                           <table class="table no-margin">
                                           <thead>
                                            <tr>
                                              <th colspan="4" style="text-align: center;">DATOS DEL ALOJAMIENTO</th>
                                            </tr>
                                            </thead>
                                            <tbody style="padding: 0px;">

                                                              <tr style="padding: 0px;">


                                <td colspan="3">

                                        <!-- Date dd/mm/yyyy -->
                                        <div class="form-group">
                                          <label>Servicio:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                        <input type="text" readonly class="form-control" name="horario" id="horario" placeholder="Ingrese Servicio" value="{{$horario->nombre}}">
                                        <input type="hidden" class="form-control" name="horario_tipo" id="horario" placeholder="Ingrese Servicio" value="{{$horario->tipo}}">
                                          </div>
                                          <!-- /.input group -->
                                        </div>




                                          <div class="form-group">
                                              <label>Fecha y hora de entrada:</label>

                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                            </div>
                                            <input readonly type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" value="<?php echo $hoy; ?>"  data-mask>
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input readonly type="time" class="form-control" name="hora_entrada" value="<?php echo $hora; ?>"  data-mask>
                                        </div>
                                        <!-- /.input group -->
                                    </div>

                                    <div class="form-group">
                                    <label>Fecha y hora de salida:</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <?php
                                              if($horario->is24Horas){
                                                ?>
                                                  <input id="is24" type="hidden" value="1">
                                                  <?php
                                                $dia = $dia24;
                                                $hora = $hora24;
                                              }else{
                                                ?>
                                                  <input id="is24" type="hidden" value="0">
                                                  <?php

                                              }

                                              if ($horario->tipo == 'COMERCIAL') {
                                                $dia = $diaComercial;
                                                // echo $dia;
                                                $hora = $horario->hasta;
                                              }

                                              if ($horario->tipo == 'DIURNO') {
                                                $dia = $hoy;
                                                $hora = $horario->hasta;
                                              }

                                               ?>
                                              <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" value="{{$dia}}"  data-mask>
                                              <div class="input-group-addon">
                                                  <i class="fa fa-clock-o"></i>
                                                </div>
                                            <input type="time" class="form-control" name="hora_salida" id="hora_salida" value="{{$hora}}"  data-mask>
                                            </div>
                                            <!-- /.input group -->
                                        </div>


                                        <div class="form-group">

                                            <label>Precio:</label>
                                            <div class="input-group">
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Dolar</span>
                                                    <h5 id="mostrarPrecioDolar" class="description-header">${{floatval($precio->precio)}}</h5>

                                                    <input type="hidden" id="precioDolarHabitacio" name="precioDolarHabitacion" value="{{floatval($precio->precio)}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Pesos</span>
                                                    <h5 id="mostrarPrecioPeso" class="description-header">${{number_format($precio->precio*$tasaPesoHabitacion->tasa,2,',','.')}}</h5>
                                                    <input type="hidden" id="precioPesoHabitacio" name="precioPesoHabitacion" value="{{$precio->precio*$tasaPesoHabitacion->tasa}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Bolivares</span>
                                                    <h5 id="mostrarPrecioBolivar" class="description-header">Bs.{{number_format($precio->precio*$tasaDolarHabitacion->tasa,2,',','.')}}</h5>
                                                    <input type="hidden" id="precioBolivarHabitacion" name="precioBolivarHabitacion" value="{{$precio->precio*$tasaDolarHabitacion->tasa}}">
                                                </div>
                                            </div>
                                            </div>
                                            <div id="contado"
                                                class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a>

                                            </div>
                                            <div id="precortesia"
                                                class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
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
                                            <div id="precredito"
                                                class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                <a href="#" data-toggle="modal" data-target="#precreditomodal"  class="btn btn-sm btn-success btn-block col-lg-pull-2 small">Crédito</a>

                                            </div>
                                            <!-- /.input group -->
                                        </div>

                                           <div class="box-footer">
                                          <a href="index.php?view=recepcion" class="btn btn-danger">Cancelar</a>
                                          <input type="hidden" name="id_habitacion" value="<?php echo $habitacion->id; ?>">
                                          <button type="submit" class="btn btn-success pull-right hidden">Registrar ingreso</button>
                                          <button type="button" id="procesoH" class="btn btn-success pull-right hidden">Registrar ingreso</button>
                                        </div>

                          </td>

                                            </tr>



                                            </tbody>
                                          </table>
                                        </div>

                                        </div>
                                        <!-- /.table-responsive -->
                                        {{-- /////////////////////////////////////////////////////////////////////////////////// --}}




                                        {{-- /////////////////////////////////////////////////////////////////////////////////// --}}
                                    </div>

                                    </div>

                            {{-- </form> --}}
                                    <!-- /.box -->

                                    @else
                                     <h4 class='alert alert-success'>NO EXISTE ESTA HABITACIÓN</h4>

                                      @endif


                               @else
                               <h4 class='alert alert-success'>NO SE SELECCIONÓ HABITACIÓN</h4>
                                @endif

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
</section>

</div>

                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->

                </div>
        </div>
        </div>
        <div id="countdown2"></div>
        <div class="countdown"></div>

        <div id="resultado">

        </div>
        <div id="minuto">

        </div>
        <div id="segundo">

        </div>
    </section>
    <div class="modal fade bd-example-modal-lg refrescar" id="dolar" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg modal-primary">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    {{-- <form action="{{route('proceso')}}" method="post">
                        @csrf --}}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="fa fa-spinner"></span> SELECCIONE SERVICIO </h4>
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

                                                            <td><input name="TasaTike[]"  size="10px" type="texto"
                                                                    id="TasaDolar"
                                                                    value="{{ $tasaDolar->tasa }}">
                                                            </td>
                                                            <td><input name="MontoDolar[]"  size="10px" type="text"
                                                                    id="DolarToDolar" class="monto"
                                                                    onchange="sumar();"></td>
                                                            <td><input name="Veltos[]"  size="10px" type="text"
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
                                                            <h4 id="ex" class="text-bold">TOTAL EXCEDENTE</h4>
                                                            <h4 id="r" class="text-bold">RESTA</h4>
                                                            <h4 id="tap" class="text-bold">TOTAL A PAGAR</h4>
                                                            <input id="monto_dejado" name="monto_dejado" type="text" value="">
                                                            <input id="base_vuelto_monto_dejado" name="base_vuelto_monto_dejado" type="text" value="">
                                                            <input id="monto_dejadoResta" name="monto_dejadoResta" type="text" value="">
                                                            <input id="isVueltos" name="isVueltos" type="hidden" value="0">
                                                            <input id="cantidad" name="cantidad" type="hidden" value="">
                                                            <input id="num_servicio" name="num_servicio" type="hidden" value="{{$num_servicio}}">
                                                            <input id="operador" name="operador" type="hidden" value="{{$UserName}}">
                                                            <input id="total_costo" name="total_costo" type="hidden" value="">
                                                            <input id="precio_costo" name="precio_costo" type="hidden" value="">
                                                            <input id="tipo_pago" name="tipo_pago" type="hidden" value="">
                                                            <input id="modo_pago" name="modo_pago" type="hidden" value="">
                                                            <input id="caja_id" name="caja_id" type="hidden" value="{{$caja->id}}">
                                                            <input id="user_id" name="user_id" type="hidden" value="{{$UserId}}">
                                                        <th>
                                                            <h4 class="text-bold" id="spTotal">0.00</h4>
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
                                                                            <td><h5 class="description-header text-bold">$. {{ number_format($cajas->SumaTotalPeso + $caja->monto_peso,2,',','.') ?? ' 0,00' }}</h5></td>
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
                                                                            <td><h5 class="description-header text-bold">$. {{ number_format($cajas->SumaTotalPeso + $caja->monto_peso,2,',','.') ?? ' 0,00' }}</h5></td>
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
                                                                            <td><h5 class="description-header  text-bold">Bs. {{ number_format($cajas->SumaTotalPunto + $caja->monto_bolivar,2,',','.') ?? ' 0,00' }}</h5></td>
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

                                                    {{-- <table class="table table-condensed">
                                                        <tbody><tr>
                                                            <th>Divisa</th>
                                                            <th>Disponible</th>
                                                            <th>Vuelto</th>
                                                            <th >Monto a devitar</th>
                                                            <th>Quedan</th>
                                                        </tr>
                                                        <tr>
                                                        <td>Dolar</td>
                                                        <td>
                                                            <h5 class="description-header">$. {{ number_format($cajas->SumaTotalDolar + $caja->monto_dolar,3,',','.') ?? ' 0,00' }}</h5>
                                                            <input type="hidden" value=""  id="deuda">

                                                        </td>
                                                        <td>
                                                            <input type="text" value=""  id="deuda2">
                                                        </td>
                                                        <td><input type="text" class="Can_Dolar"><br/></td>
                                                        </tr>
                                                        <tr>
                                                        <td>Peso</td>
                                                        <td><h5 class="description-header">$. {{ number_format($cajas->SumaTotalPeso + $caja->monto_peso,2,',','.') ?? ' 0,00' }}</h5></td>
                                                        <td>
                                                            <input type="text" value=""  id="deuda2Peso">
                                                        </td>
                                                        <td><input type="text" class="Can_Peso"></td>
                                                        </tr>
                                                        <tr>
                                                        <td>Bolivar</td>
                                                        <td><h5 class="description-header">Bs. {{ number_format($cajas->SumaTotalPunto + $caja->monto_bolivar,2,',','.') ?? ' 0,00' }}</h5></td>
                                                        <td>
                                                            <input type="text" value=""  id="deuda2Bolivar">
                                                        </td>
                                                        <td><input type="text" class="Can_Bolivar"><br/></td>
                                                        </tr>

                                                    </tbody></table> --}}
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
                                                {{-- <button id="enviar" class="btn btn-primary btn-block"
                                                    type="button">Guardar</button> --}}
                                            </div>
                                            {{-- <div class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12"
                                                id="guardar1">
                                                <button class="btn btn-danger btn-block"
                                                    type="reset">Cancelar</button>
                                            </div> --}}
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
                id="guardar">
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
  <!-- /.content -->
  <div class="clearfix"></div>
@push('sciptsMain')
<script src="{{asset('dist/js/moment.min.js')}}"></script>
<script src="{{asset('dist/js/onscan.js')}}"></script>
<script>



moment.locale('es');
// console.log(moment.locale()); // en
    const hoy = moment();
console.log(moment().format('MMMM Do YYYY, h:mm:ss a'));







$("#buscarCliente").change(showValues);


$("#excedente").hide();
$("#ex").hide();
$("#excdt").hide();
function showValues() {
                // alert('show');
                datosArticulo = document.getElementById('buscarCliente').value.split('_');
                // $("#jprecio_venta").val(datosArticulo[2]);
                $("#cliente_id").val(datosArticulo[0]);
                $("#nombre").val(datosArticulo[1]);
                $("#num_documento").val(datosArticulo[2]);
                $("#direccion").val(datosArticulo[3]);
                let isCortesia = datosArticulo[4];
                let isCredito = datosArticulo[5];
                $("#telefono").val(datosArticulo[6]);
                $("#limite_fecha").val(datosArticulo[7]);
                $("#limite_monto").val(datosArticulo[8]);
                $("#total_credito_pendiente").val(datosArticulo[9]);
                $("#estado_credito").val(datosArticulo[10]);

                // alert(datosArticulo[9]);

                let deuda_credito_pendiente = $("#total_credito_pendiente").val();
                // alert(deuda_credito_pendiente);
                let limite_fecha_credito = $("#limite_fecha").val();
                let limite_monto_credito = $("#limite_monto").val();
                let credito_disponible = 0;

                if (deuda_credito_pendiente) {
                    credito_disponible = limite_monto_credito - deuda_credito_pendiente;
                }else{
                    credito_disponible = limite_monto_credito;
                }

                $("#dispCredito").val(credito_disponible);

                $("#dispExcedente").val(datosArticulo[11]);
                var verCajaExcedente = datosArticulo[11];
                if(verCajaExcedente > 0){
                $("#excedente").show();
                $("#ex").show();
                $("#excdt").show();

                }else{
                    $("#excedente").hide();
                    $("#ex").hide();
                    $("#excdt").hide();
                }


                let dispCredito = credito_disponible;
                let dispExcedente =datosArticulo[11];

                $("#dispCreditoShow").html('$'+dispCredito);
                $("#dispExcedenteShow").html('$'+dispExcedente);


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

            }



    //         var duration = moment.duration({
    //         'minutes': 1,
    //         'seconds': 5

    //         });

    // var timestamp = new Date(0, 0, 0, 2, 10, 30);
    // var interval = 1;
    // var timer = setInterval(function() {
    //   timestamp = new Date(timestamp.getTime() + interval * 1000);

    //   duration = moment.duration(duration.asSeconds() - interval, 'seconds');
    //   var min = duration.minutes();
    //   var sec = duration.seconds();

    //   sec -= 1;
    //   if (min < 0) return clearInterval(timer);
    //   if (min < 10 && min.length != 2) min = '0' + min;
    //   if (sec < 0 && min != 0) {
    //     min -= 1;
    //     sec = 59;
    //   } else if (sec < 10 && sec.length != 2) sec = '0' + sec;

    //   if(min == 1 && sec == 0){
    //     alert('hola');
    //   }

    //   $('.countdown').text(min + ':' + sec);
    //   if (min == 0 && sec == 0)

    //     clearInterval(timer);


    // }, 1000);

    // if (min < 4) alert('Faltan '+ min);






// focusMethod = function getFocus() {
//                 document.getElementById(".selval").focus();
//                 $(".selval").val('default');
//                 $(".selval").selectpicker("refresh");
//             }

    $(function() {
        $('.refrescar').on('click', function() {
            $(".selval").val('default');
                $(".selval").selectpicker("refresh");
            $('.detalle').hide('swing');

        });
    });

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////



    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
$('.detalle').hide();
    $(function() {
        $('.selval').on('change', function() {
            $('.detalle').hide("swing");
            let valor = this.value;
            let catid = $(this).attr('data-id');
            let texto = this.options[this.selectedIndex].text;

            // console.log('valor = '+valor+' catid= '+catid);

            $('.horario').val(valor);


            if ($.trim(valor != '')) {
                let tasaDolar = $('#tasaDolar').val();
                let tasaPeso = $('#tasaPeso').val();
                $.ajax({
                    type: 'get',
                    url: '{{ url ("precio") }}',
                    data: "cat_id=" + catid+"&horario_id=" + valor,
                    success: function (precio) {

                if (precio.length) {
                    $('.precio').val(precio[0].id);
                    // console.log(precio);
                    // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                    $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio[0].precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio[0].precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio[0].precio*tasaDolar)+'</h5></div></div>');







                    $('.detalle').show("swing");
                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                            // // alert(origens[0].nombre);
                            // for(var i = 0; i < origens.length; i++){
                            // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                            // }
                        }
                    }
                });

            }

    });

//     $(function(){
//   $(".ver").click(function(){
//     var valor = $(this).attr('data-id')
//     alert(valor);
//     // $("#resultado").html(valor)
//   })
// })


});

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
</script>

{{-- <script type="text/javascript">
    var final = moment("2020-11-19 09:00:00 am");
    setInterval(function() {
        var inicio = moment();
        var duracion = final.diff(inicio);
        var intervalo = moment(duracion);
        var mes = intervalo.month()+1;
        var diaDelMes = intervalo.date();
        var hora = intervalo.hour();
        var minuto = intervalo.minute();
        var segundo = intervalo.second();
        var resultado = (intervalo.format("MM/DD HH:mm:ss"));
        $("#resultado").html(mes + " Meses " + diaDelMes + " Dias " + hora + " Horas " + minuto + " Minutos " + segundo + " Segundos");
        $("#minuto").html(minuto);
        $("#segundo").html(segundo);
    }, 1000);

</script> --}}

<script>
    var end = new Date('1/02/2021 10:41 AM');

        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;



        function showRemaining() {
            var now = new Date();
            var distance = end - now;

            if (distance < 0) {

                clearInterval(timer);
                document.getElementById('countdown2').innerHTML = 'EXPIRED!';

                return;
            }


            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            var seconds = Math.floor((distance % _minute) / _second);


            document.getElementById('countdown2').innerHTML = days + ' dias, ';
            document.getElementById('countdown2').innerHTML += hours + ' horas, ';
            document.getElementById('countdown2').innerHTML += minutes + ' minutos y ';
            document.getElementById('countdown2').innerHTML += seconds + ' segundos';

            if (minutes == 1 && seconds == 0) {


console.log('Falta '+minutes);

// return;
}

        }



        timer = setInterval(showRemaining, 1000);
    </script>

<script>
    var pagoExc = 0;
    var pagoCred = 0;

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






    $("#guardar").hide();
    $("#gestionpago").hide();
    $("#gestionpago_boton").show();
    $("#jidarticulo").change(showValues);
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
            $("#form1").submit();
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
            $("#form1").submit();
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
                        $("#form1").submit();
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
                        $("#form1").submit();
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

    // function showValues() {
    //     // alert('show');
    //     datosArticulo = document.getElementById('jidarticulo').value.split('_');
    //     // $("#jprecio_venta").val(datosArticulo[2]);
    //     $("#jprecio_compra").val(datosArticulo[2]);
    //     $("#jstock").val(datosArticulo[1]);


    //     stock           = datosArticulo[1]
    //     porEspecial     = datosArticulo[4];
    //     isDolar         = datosArticulo[5];
    //     isPeso          = datosArticulo[6];
    //     isTransPunto    = datosArticulo[7];
    //     isMixto         = datosArticulo[8];
    //     isEfectivo      = datosArticulo[9];
    //     isKilo          = datosArticulo[10];
    //     // $("#jmarjen_venta_dolar").val(12);
    //     // alert(isKilo);

    //    var exkilo = 0;
    //    var exgramos = 0;

    //    var res = divisorEnteroDecimala(stock);

    //    exkilo = res[0];
    //    exgramos = res[1];

    //     $("#exkilo").html(exkilo);
    //     $("#exgramos").html(fijaLargoDerecha(exgramos));
    //     $("#stockRealKilos").val(stock);

    //     $("#restaKilos").val(stock);



    // }

    $(document).ready(function() {
        // var kilo = 0;
        // var gramos = 0;
        $("#kilo").keyup(function() {
            restarKilos();
        });

        $("#gramos").keyup(function() {
            restarKilos();
        });

        $("#procesaKilo").on('click',function() {
            // alert('listo');
            $("#kilo").val('');
            $("#gramos").val('');
            $("#ckilo").html('0');
            $("#cgramos").html('0');
            $('#exampleModalCenter').modal('toggle');
            add_article();
        });

        function restarKilos(){
            $("#restaKilos").val('');

            $("#cantidadKilos").val('');
            kilo =   $("#kilo").val();
            gramos =   $("#gramos").val();
            resultado = unionEnteroDecimalaEnteros(kilo,gramos);
            // alert('gramos '+resultado);

           $("#cantidadKilos").val(resultado);
           cantidadKilos =  $("#cantidadKilos").val();
           cantidad        = $("#jcantidad").val(cantidadKilos);

            showCantidad=divisorEnteroDecimala(cantidadKilos);
            showEntero = showCantidad[0];
            showDecimal = showCantidad[1];kilosCompletos

            cantidadMostrar = showEntero +"."+fijaLargoDerecha(showDecimal)+".Kg";

            $("#ckilo").html(showEntero);
            $("#cgramos").html(fijaLargoDerecha(showDecimal));

           resta1 = stock - cantidadKilos;

           $("#restaKilos").val(resta1);
            // alert(cantidadKilos);

            var res = divisorEnteroDecimala(resta1);

       exkilo = res[0];
       exgramos = res[1];

        $("#exkilo").html(exkilo);
        $("#exgramos").html(fijaLargoDerecha(exgramos));

            cantidad = $("#jcantidad").val();
            precio = $("#precio").val();

            // precio_compra = precio/1000;

            // alert(precio_compra);


        }



    });

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

    $("#jidarticulo").on("change", function () {
        document.getElementById("jcantidad").focus();
        // $("#jidarticulo").val('0');
        // document.getElementById('jidarticulo').val('0');

    });

    focusMethod = function getFocus() {
        document.getElementById("jidarticulo").focus();
        $("#jidarticulo").val('default');
        $("#jidarticulo").selectpicker("refresh");
    }

    $("#fecha_salida").on('change', function() {
    var is24 = $('#is24').val();
    if(is24 == 1){
    // addHabitacion();
            // alert('hola');
            var entrada = $("#fecha_entrada").val();
            var salida = $("#fecha_salida").val();

            var fecha1 = moment(entrada);
            var fecha2 = moment(salida);
            let resultado = fecha2.diff(fecha1, 'days');

            $('#cantidad').val(resultado);

           let tasaDolar =  $("#tasaDolarHabitacion").val();
           let tasaPeso =  $("#tasaPesoHabitacion").val();


            total =$("#precioDolarHabitacio").val();
            $("#precio_costo").val(total);
            console.log('totoal '+total);

            var k = total*resultado;
            $("#total_costo").val(k);

            $("#mostrarPrecioDolar").html(k);
            $("#mostrarPrecioPeso").html(formatMoney(k * tasaPeso,2,',','.'));
            $("#mostrarPrecioBolivar").html(formatMoney(k * tasaDolar,2,',','.'));
            // $("#precioDolarHabitacio").val();


            console.log(k);
            // $("#PagoTtotal").html(numDecimal(k));

            console.log(fecha2.diff(fecha1, 'days'), ' dias de diferencia');
        }
        });


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

    // function add_article() {
    //     datosArticulo = document.getElementById('jidarticulo').value.split('_');


    //     idarticulo = datosArticulo[0];
    //     articulo = datosArticulo[3];



    //     // if(porEspecial){
    //     //     alert('Si tiene precio especial '+porEspecial);
    //     // }else{
    //     //     alert('No tiene precio especial '+porEspecial);
    //     // }

    //     // alert(articulo);
    //     cantidad        = $("#jcantidad").val();
    //     descuento       = $("#jdescuento").val();
    //     precio_compra   = $("#jprecio_compra").val();
    //     precio_venta    = $("#jprecio_venta").val();

    //     jporEspecial    = porEspecial;

    //     // alert('por especial'+jporEspecial);

    //     precio_venta_d  = $("#jprecio_venta_d_dolar").val();
    //     precio_venta_p  = $("#jprecio_venta_p_dolar").val();
    //     precio_venta_tp = $("#jprecio_venta_tp_dolar").val();
    //     precio_venta_m  = $("#jprecio_venta_m_dolar").val();
    //     precio_venta_e  = $("#jprecio_venta_e_dolar").val();
    //     precio_venta_c  = $("#precio_costo").val();



    //     $("#cantidadj").show();
    //     $("#procesarkilos").hide();



    //     stock = $("#jstock").val();

    //     if (idarticulo != "" && cantidad != "" && cantidad > 0 && precio_venta != "") {
    //         var stock = parseInt(stock)
    //         var cantidad = parseInt(cantidad)

    //         // var tasaD = parseFloat($("#TasaDolar").val());
    //         // var tasaP = parseFloat($("#TasaPeso").val());
    //         // var tasaTP = parseFloat($("#tasaTransPunto").val());
    //         // var tasaM = parseFloat($("#tasaMixto").val());
    //         // var tasaE = parseFloat($("#tasaEfectivo").val());

    //         // var descuento=parseFloat(0.00);

    //         if (stock >= cantidad) {
    //             subtotal[cont]      = (cantidad * precio_venta - descuento);
    //             subtotalPC[cont]    = (cantidad * precio_venta_c);
    //             subtotald[cont]     = (cantidad * precio_venta_d - descuento);
    //             subtotalp[cont]     = (cantidad * precio_venta_p - descuento);
    //             subtotaltp[cont]    = (cantidad * precio_venta_tp - descuento);
    //             subtotalm[cont]     = (cantidad * precio_venta_m - descuento);
    //             subtotale[cont]     = (cantidad * precio_venta_e);
    //             subtotalc[cont]     = (cantidad * precio_venta_c);




    //             total = total + subtotal[cont];

    //             precio_costo = precio_costo + subtotalc[cont];


    //             $("#precio_costo").val(precio_costo);


    //             total_costo = total_costo + subtotalPC[cont];
    //             total_d     = total_d + subtotald[cont];
    //             total_p     = total_p + subtotalp[cont];
    //             total_tp    = total_tp + subtotaltp[cont];
    //             total_m     = total_m + subtotalm[cont];
    //             total_e     = total_e + subtotale[cont];



    //             verPreciod      = $("#jprecio_venta_dolar").val();
    //             verPreciop      = $("#jprecio_venta_peso").val();
    //             verPreciotp     = $("#jprecio_venta_trans_punto").val();
    //             verPreciom      = $("#jprecio_venta_mixto").val();
    //             verPrecioe      = $("#jprecio_venta_Efectivo").val();

    //             // alert('nada');
    //             if (descuento == "") {
    //                 descuento = 0;
    //             }

    //             if (isKilo) {
    //                 cantidadVer = cantidadMostrar;
    //             } else {
    //                 cantidadVer  = cantidad;
    //             }
    //             // $("#total_venta").val(total.toFixed(2));
    //             $("#precio_costo").val(numDecimal(total_costo));
    //             $("#total_ventad").val(numDecimal(total_d));
    //             $("#total_ventap").val(numDecimal(total_p));
    //             $("#total_ventatp").val(numDecimal(total_tp));
    //             $("#total_ventam").val(numDecimal(total_m));
    //             $("#total_ventae").val(numDecimal(total_e));

    //             var fila = '<tr class="selected" id="fila' + cont +
    //                 '"><td><button type="button" class="btn btn-warning btn-xs" onclick="eliminar(' + cont +
    //                 ');">X</button></td><td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' +
    //                 articulo + '</td><td><input type="hidden" name="cantidad[]" value="' + cantidad + '">' + cantidadVer
    //                  +
    //                 '</td><td class="success"><input type="hidden" name="precio_venta[]" value="' + precio_venta +
    //                 '">' + verPreciod + '</td><td class="success">' + numDecimal(subtotal[cont]) +
    //                 '</td><td class="warning"><input type="hidden" name="precio_venta_p[]" value="' +
    //                 dividir(multiplicar(precio_venta_p)) +
    //                 '">' + formatMoney(verPreciop, 2, ',', '.') + '</td><td class="warning">' + formatMoney(

    //                         numDecimal(subtotalp[cont]) * tasaP, 2, ',', '.') +
    //                 '</td><td  class="success"><input type="hidden" name="precio_venta_tp[]" value="' +
    //                     dividir(multiplicar(precio_venta_tp)) + '">' + formatMoney(verPreciotp, 2, ',', '.') + '</td><td class="success">' +
    //                 formatMoney(numDecimal(subtotaltp[cont]) * tasaTP, 2, ',', '.') +
    //                 '</td><td class="warning"><input type="hidden" name="precio_venta_m[]" value="' +
    //                     dividir(multiplicar(precio_venta_m)) +
    //                 '">' + formatMoney(verPreciom, 2, ',', '.') + '</td><td class="warning">' + formatMoney(
    //                     numDecimal(subtotalm[cont]) * tasaM, 2, ',', '.') +
    //                 '</td><td class="success"><input name="precio_costo_unidad[]" type="hidden" value="'+dividir(multiplicar(precio_venta_c))+'"><input type="hidden" name="precio_venta_e[]" value="' +
    //                     dividir(multiplicar(precio_venta_e)) +
    //                 '">' + formatMoney(verPrecioe, 2, ',', '.') + '</td><td class="success">' + formatMoney(
    //                     numDecimal(subtotale[cont]) * tasaE, 2, ',', '.') + '</td><td><input type="hidden" name="descuento[]" value="' +
    //                 parseFloat(descuento) + '">' + parseFloat(descuento) + '<input type="hidden" name="porEspecial[]" value="'+jporEspecial+'"></td></tr>';
    //             cont++



    //             clear();
    //             // $("#total").html("<h4>$. " + total.toFixed(2) + "</h4>");

    //             $("#totald").html("<h4 class='text-bold text-primary'>$. " + numDecimal(total_d * tasaD)+
    //                 "</h4>");
    //             $("#totalp").html("<h4 class='text-bold text-primary'>$. " + formatMoney(numDecimal(total_p) * tasaP, 2, ',',
    //                     '.') +
    //                 "</h4>");
    //             $("#totaltp").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_tp) * tasaTP, 2,
    //                 ',',
    //                 '.') + "</h4>");
    //             $("#totalm").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_m) * tasaM, 2, ',',
    //                 '.') + "</h4>");
    //             $("#totale").html("<h4 class='text-bold text-primary'>Bs. " + formatMoney(numDecimal(total_e) * tasaE, 2, ',',
    //                 '.') + "</h4>");

    //             $("#PagoTtotal").html(numDecimal(total)); //aqui


    //             // verify(); deplega el div gestion de pagos
    //             mostrarBonotesPago();
    //             $("#detalles").append(fila);
    //             // alert('resta');
    //             $("#DMontoDolar").keyup();
    //             $("#DMontoPeso").keyup();
    //             $("#DMontoBolivar").keyup();
    //             $("#DMontoPunto").keyup();
    //             $("#DMontoTrans").keyup();
    //             $('#gestionpago').hide("linear");
    //         } else {
    //             alert('La cantidad a vender supera el stock...!');
    //             $("#jcantidad").val('');
    //         }

    //     } else {
    //         alert("Error al ingresar el detalle de la venta, revise los datos del articulo");
    //     }
    // };

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

// $('.Can_Produc').keyup(function() {

// var limite = parseInt($("#deuda").val());
// var nuevo_valor =  $(this).val();
// var importe_total = 0;

// $(".Can_Produc").each(
//     function(index, value) {
//         if ( $.isNumeric($(this).val()) ){
//           importe_total += parseInt($(this).val());
//        }
//     }
//   );

//   let tasaDolarHabitacion = $('#tasaDolarHabitacion').val();
//   let tasaPesoHabitacion = $('#tasaPesoHabitacion').val();

//     let precioDolarReal = limite + importe_total;


//   $("#deuda2").val(precioDolarReal);
//   $("#deuda2Peso").val(precioDolarReal * tasaPesoHabitacion);
//   $("#deuda2Bolivar").val(precioDolarReal * tasaDolarHabitacion);
// });

    /* Restar dos números. */
    // $("#PagoTtotalV").html(numDecimal(total));

    function resta() {
        // alert('resta');
        const RestaTotal    = document.getElementById('RestaTtotal');
        const Excdt    = document.getElementById('excdt');
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

        valor = valor - pagoExc - pagoCred;
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
        // var valorV           = PagoTtotalV.innerHTML;
        // var valor_restarV    = spTotalV.innerHTML;
        // var restaV           = numDecimal(valorV -valor_restarV);
        valor = parseFloat(valor);
        valor_restar = parseFloat(valor_restar);
        resta = parseFloat(resta);

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
            $("#guardar").show("linear");
            // $("#guardar").hide("linear");





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
    // color
    $(document).ready(function() {
        $("#mostrar").click(function() {
            $('.target').show("swing");
        });
        $("#ocultar").click(function() {
            $('.target').hide("linear");
        });
    });

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
            $("#DolarToDolar").val(DsupTotal.toFixed(2));
            $("#DsubTotal").html(DsupTotal.toFixed(2));

            sumar();
            resta();
            const Resta     = document.getElementById('RestaTtotal');
            var valor       = Resta.innerHTML;
            var RmultD      = valor * Tdolar;
            $("#RestaDolar").val(RmultD.toFixed(2));
            var RmultP      = valor * Tpeso;
            $("#RestaPeso").val(RmultP.toFixed(2));
            var RmultB      = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB.toFixed(2));
            var RmultPu     = valor * Tpunto;
            $("#RestaPunto").val(RmultPu.toFixed(2));
            var RmultT      = valor * Ttrans;
            $("#RestaTrans").val(RmultT.toFixed(2));

        }

        function DMontoPeso(){
            Mpeso       = $("#DMontoPeso").val();
            Tdolar      = $("#TasaDolar").val();
            Tpeso       = $("#TasaPeso").val();
            Tbolivar    = $("#TasaBolivar").val();
            Tpunto      = $("#TasaPunto").val();
            Ttrans      = $("#TasaTrans").val();

            PsupTotal = Mpeso / Tpeso;
            $("#PesoToDolar").val(PsupTotal.toFixed(2));
            $("#PeSubTotal").html(PsupTotal.toFixed(2));
            $("#RestaPeso").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor   = Resta.innerHTML;
            var RmultD  = valor * Tdolar;
            $("#RestaDolar").val(RmultD.toFixed(2));
            var RmultP  = valor * Tpeso;
            $("#RestaPeso").val(RmultP.toFixed(2));
            var RmultB  = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB.toFixed(2));
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu.toFixed(2));
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT.toFixed(2));
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
            $("#BolivarToDolar").val(BsupTotal.toFixed(2));
            $("#BoSubTotal").html(BsupTotal.toFixed(2));
            $("#RestaBolivar").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD.toFixed(2));
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP.toFixed(2));
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB.toFixed(2));
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu.toFixed(2));
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT.toFixed(2));
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
            $("#PuntoToDolar").val(PusupTotal.toFixed(2));
            $("#PuSubTotal").html(PusupTotal.toFixed(2));
            $("#RestaPunto").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD.toFixed(2));
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP.toFixed(2));
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB.toFixed(2));
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu.toFixed(2));
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT.toFixed(2));
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
            $("#TransToDolar").val(TsupTotal.toFixed(2));
            $("#TrSubTotal").html(TsupTotal.toFixed(2));
            $("#RestaTrans").val();
            sumar();
            resta();
            const Resta = document.getElementById('RestaTtotal');
            var valor = Resta.innerHTML;
            var RmultD = valor * Tdolar;
            $("#RestaDolar").val(RmultD.toFixed(2));
            var RmultP = valor * Tpeso;
            $("#RestaPeso").val(RmultP.toFixed(2));
            var RmultB = valor * Tbolivar;
            $("#RestaBolivar").val(RmultB.toFixed(2));
            var RmultPu = valor * Tpunto;
            $("#RestaPunto").val(RmultPu.toFixed(2));
            var RmultT = valor * Ttrans;
            $("#RestaTrans").val(RmultT.toFixed(2));

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
                console.log(r.toFixed(2));

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
                    $("#DolarToDolarV").val(DsupTotalV.toFixed(2));
                    $("#DsubTotalV").html(DsupTotalV.toFixed(2));

                    sumarV();
                    restaV();
                    const RestaV     = document.getElementById('RestaTtotalV');
                    var valorV       = RestaV.innerHTML;
                    var RmultDV      = valorV * TdolarV;
                    $("#RestaDolarV").val(RmultDV.toFixed(2));
                    var RmultPV      = valorV * TpesoV;
                    $("#RestaPesoV").val(RmultPV.toFixed(2));
                    var RmultBV      = valorV * TbolivarV;
                    $("#RestaBolivarV").val(RmultBV.toFixed(2));


                }

                function DMontoPesoV(){
                    MpesoV       = $("#DMontoPesoV").val();
                    TdolarV      = $("#TasaDolarV").val();
                    TpesoV      = $("#TasaPesoV").val();
                    TbolivarV   = $("#TasaBolivarV").val();


                    PsupTotalV = MpesoV / TpesoV;
                    $("#PesoToDolarV").val(PsupTotalV.toFixed(2));
                    $("#PeSubTotalV").html(PsupTotalV.toFixed(2));
                    $("#RestaPesoV").val();
                    sumarV();
                    restaV();
                    const RestaV = document.getElementById('RestaTtotalV');
                    var valorV   = RestaV.innerHTML;
                    var RmultDV  = valorV * TdolarV;
                    $("#RestaDolarV").val(RmultDV.toFixed(2));
                    var RmultPV  = valorV * TpesoV;
                    $("#RestaPesoV").val(RmultPV.toFixed(2));
                    var RmultBV  = valorV * TbolivarV;
                    $("#RestaBolivarV").val(RmultBV.toFixed(2));


                }

                function DMontoBolivarV(){
                    MbolivarV = $("#DMontoBolivarV").val();
                    TdolarV   = $("#TasaDolarV").val();
                    TpesoV    = $("#TasaPesoV").val();
                    TbolivarV = $("#TasaBolivarV").val();


                    pesoV = $("#RestaPesoV").val();
                    // 10767280  alert(Mpeso);
                    BsupTotalV = MbolivarV / TbolivarV;
                    $("#BolivarToDolarV").val(BsupTotalV.toFixed(2));
                    $("#BoSubTotalV").html(BsupTotalV.toFixed(2));
                    $("#RestaBolivarV").val();
                    sumarV();
                    restaV();
                    const RestaV = document.getElementById('RestaTtotalV');
                    var valorV = RestaV.innerHTML;
                    var RmultDV = valorV * TdolarV;
                    $("#RestaDolarV").val(RmultDV.toFixed(2));
                    var RmultPV = valorV * TpesoV;
                    $("#RestaPesoV").val(RmultPV.toFixed(2));
                    var RmultBV = valorV * TbolivarV;
                    $("#RestaBolivarV").val(RmultBV.toFixed(2));


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
                $("#DMontoDolarV").val(RdV.toFixed(2));
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
                $("#DMontoPesoV").val(RpV.toFixed(2));
                DMontoPesoV();
            }else{
                vcargarpV = 0;
                $("#isVueltos").val('');
                $("#DMontoPesoV").val('');
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
                $("#DMontoBolivarV").val(RbV.toFixed(2));
                DMontoBolivarV();
            }else{
                vcargarbV = 0;
                $("#isVueltos").val('');
                $("#DMontoBolivarV").val('');
                DMontoBolivarV();
            }
        });
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        try {


            onScan.attachTo(document, {
                //configuración del sufijo/ tecla esperada al finalizar la lectura del scan, esto indica a onScan la finalización del evento
                suffixKeyCodes: [13],
                minLength: 2,
                onScan: function(barcode) { //función callback que se dispara después de una lectura
                    console.log(barcode)
                        // alert(barcode);
                        // window.livewire.emit('doCheckOut', barcode, 2) //emitimos el evento para consultar la info y cobrar el ticket
                        let nombreHabitacionBarcode = $("#nombreHabitacionBarcode").val();
                        // alert(nombreHabitacionBarcode);
                        var n = barcode;
                        // alert(n);
                        // return false;
                        // $('#btnImprimir').focusout();
                        // let cliente_id = $("#cliente_id").val();

                        // if(cliente_id == 0 || cliente_id == null){
                        //     alert('No has seleccionado un cliente...!');
                        //     return false;
                        // }

                        if (barcode == nombreHabitacionBarcode) {
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
                                $("#form1").submit();
                            }else if (modoPagoOn == 'cortesia'){
                                let cliente_id = $("#cliente_id").val();

                                if(cliente_id == 0 || cliente_id == null){
                                    alert('No has seleccionado un cliente...!');
                                    return false;
                                }
                                $("#form1").submit();
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
                                                $("#form1").submit();
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
                                                $("#form1").submit();
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
                },

                // onScanError: function(e) { //función callback para captura de errores de lectura
                //     console.log('Error de lectura' + e[0]);
                //     // toastr.error('', 'Error de lectura' + e)
                // },
                onScanError: function(err) {
                    var sFormatedErrorString = "Error Details: {\n";
                    for (var i in err) {
                        sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
                    }
                    sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
                    console.log("[onScanError]: " + sFormatedErrorString);
                }

            })
            // alert('OnScan ready!');
            // toastr.success('', 'OnScan ready!')

        } catch (e) { //captura de errores generales de inicialización de onscan.js
            alert('Error OnScan' + e);
            // toastr.error('', 'Error OnScan' + e)
        }

    })


</script>
@endpush
@endsection
