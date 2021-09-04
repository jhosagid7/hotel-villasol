@extends ('layouts.admin3')
@section('contenido')





    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border  no-print">
          <h3 class="box-title">@isset($title)
              {{$title}}
              @else
              {!!"Sistema"!!}
          @endisset</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                      title="Collapse">
                <i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">



    <div class="row">

        <!-- /.box-body -->
        <div class="box-footer no-print">
            @if (1 == 1)

            <a class="btn btn-success" href="{{route('reporte-general-creditos-buscar')}}">{{__('Nueva Busqueda')}}</a>
            @endif
            <a class="btn btn-warning" href="{{route('creditos.index')}}">{{__('Ir a creditos')}}</a>
            <a onClick="imprimir('imprimir')" target="_blank" class="btn btn-primary  hidden-print">
                <i class="fa fa-print"></i>
                Imprimir
            </a>
            {{-- <button onclick="imprimir()">Imprimir pantalla</button> --}}

        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->
    <!-- Main content -->
<section id="imprimir" class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-globe"></i> VillaSoft Punto
        <small class="pull-right">Fecha: {{date('d-m-y')}}<br>Hora: {{date('H:m A')}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <h3 class="box-title text-bold">@isset($title)
        {{$title}}
        @else
        {!!"Sistema"!!}
    @endisset</h3>
    <!-- info row -->
    @php

                            $totalServicio = 0;
                            $totalConsumo = 0;
                            $totalHorasExtras = 0;

                            $totalDeuda = 0;

                            $credPagadoCant = 0;
                            $credPagadoCantServicio = 0;
                            $credPagadoCantConsumo = 0;
                            $montoServicio = 0;
                            $montoConsumo = 0;

                            $totalServicioPendiente = 0;
                            $totalConsumoPendiente = 0;
                            $totalHorasExtrasPendiente = 0;

                            $totalServicioPagado = 0;
                            $totalConsumoPagado = 0;
                            $totalHorasExtrasPagado = 0;

                            $totalCantPendiente = 0;

                            $totalDeudaPendiente = 0;
                            $totalDeudaPagado = 0;

                            $cantConsumoPendiente = 0;
                            $cantServicioPendiente = 0;
                            $cantServicioPendiente = 0;
                            $cantHorasExtrasPendiente = 0;


                            $totalCantPagado = 0;
                            $cantServicioPagado = 0;
                            $cantConsumoPagado = 0;
                            $cantHorasExtrasPagado = 0;
                            
                            

                            $totalD = 0;
                            $totalC = 0;
                            $mayorD = 0;
                            $detalD = 0;
                            $mayorC = 0;
                            $detalC = 0;
                            $operacones = 0;
                            $operaconesS = 0;
                            $operaconesC = 0;
                            $operaconesH = 0;

                            $totalVigente = 0;
                            $totalVencido = 0;
                        @endphp
                        @foreach ($creditos as $cred)
                        @php
                        $operacones++;

                        if ($cred) {
                            if($cred->tipo_operacion == 'Servicio'){
                                if($cred->estado_pago == 'Pendiente'){
                                    $totalServicioPendiente += $cred->monto;
                                    $totalDeudaPendiente += $cred->monto;
                                    $cantServicioPendiente++;
                                    $totalCantPendiente++;

                                    if($cred->estado_credito == 'Vigente'){
                                    $totalVigente ++;   
                                    }

                                    if($cred->estado_credito == 'Vencido'){
                                        $totalVencido ++;                                  
                                    }
                                }

                                if($cred->estado_pago == 'Pagado'){
                                    $totalServicioPagado += $cred->monto;
                                    $totalDeudaPagado += $cred->monto;
                                    $cantServicioPagado++;
                                    $totalCantPagado++;
                                }

                                

                                    $totalServicio += $cred->monto;
                                    $totalDeuda += $cred->monto;
                                    $operaconesS++;

                            }

                            if($cred->tipo_operacion == 'Consumo'){
                                if($cred->estado_pago == 'Pendiente'){
                                    $totalConsumoPendiente += $cred->monto;
                                    $totalDeudaPendiente += $cred->monto;
                                    $cantConsumoPendiente++;
                                    $totalCantPendiente++;

                                    if($cred->estado_credito == 'Vigente'){
                                    $totalVigente ++;   
                                    }

                                    if($cred->estado_credito == 'Vencido'){
                                        $totalVencido ++;                                  
                                    }
                                }

                                if($cred->estado_pago == 'Pagado'){
                                    $totalConsumoPagado += $cred->monto;
                                    $totalDeudaPagado += $cred->monto;
                                    $cantConsumoPagado++;
                                    $totalCantPagado++;
                                }
                                    $totalConsumo += $cred->monto;
                                    $totalDeuda += $cred->monto;
                                    $operaconesC++;

                            }

                            if($cred->tipo_operacion == 'Horas_Extras'){
                                if($cred->estado_pago == 'Pendiente'){
                                    $totalHorasExtrasPendiente += $cred->monto;
                                    $totalDeudaPendiente += $cred->monto;
                                    $cantHorasExtrasPendiente++;
                                    $totalCantPendiente++;

                                    if($cred->estado_credito == 'Vigente'){
                                    $totalVigente ++;   
                                    }

                                    if($cred->estado_credito == 'Vencido'){
                                        $totalVencido ++;                                  
                                    }
                                }

                                if($cred->estado_pago == 'Pagado'){
                                    $totalHorasExtrasPagado += $cred->monto;
                                    $totalDeudaPagado += $cred->monto;
                                    $cantHorasExtrasPagado++;
                                    $totalCantPagado++;
                                }
                                    $totalHorasExtras += $cred->monto;
                                    $totalDeuda += $cred->monto;
                                    $operaconesH++;

                            }



                        }



                        @endphp

                        {{-- @foreach ($cred->creditos_pagados as $cred_pag)
                        @php
                        if ($cred_pag) {

                            $totalD += $cred_pag->monto;
                            $credPagadoCant++;
                            if ($cred_pag->tipo_operacion == 'Servicio') {
                                $credPagadoCantServicio++;
                                $montoServicio += $cred_pag->monto;
                            } else {
                                $credPagadoCantConsumo++;
                                $montoConsumo += $cred_pag->monto;
                            }
                        }

                        @endphp

                        @endforeach --}}
                        @endforeach
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <h4><strong>Datos de Busqueda:</strong></h4>

        <address>
        <strong>Desde: </strong> {{$fecha_inicio ?? ''}}<br>
        <strong>Hasta: </strong> {{$fecha_fin ?? ''}}<br>
        <strong>Operador: </strong>{{ Auth::user()->name ?? '0'}} <br>
        <strong>Total Operaciones: </strong>{{$operacones ?? '0'}} <br>
        <strong>Total Servicio: </strong>{{$operaconesS ?? '0'}} <br>
        <strong>Total Consumo: </strong>{{$operaconesC ?? '0'}} <br>
        <strong>Total Extras: </strong>{{$operaconesH ?? '0'}} <br>
        <hr>
        <h4><strong>Estado de Facturas Pendientes:</strong></h4>
        <strong>Total Vigente: </strong>{{$totalVigente ?? '0'}} <br>
        <strong>Total Vencida: </strong>{{$totalVencido ?? '0'}} <br>
        <hr>

        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <h4><strong>Datos de Creditos:</strong></h4>
        <address>
            <strong>Facturas Pendientes: </strong> {{$totalCantPendiente ?? ''}}<br>
            <strong>Cant/Servicio: </strong> {{number_format($cantServicioPendiente,0,',','.') ?? '0'}} <br>
            <strong>Cant/Consumo: </strong> {{number_format($cantConsumoPendiente,0,',','.') ?? '0'}}<br>
            <strong>Cant/Hextras: </strong> {{number_format($cantHorasExtrasPendiente,0,',','.') ?? '0'}}<br>
            <br>
            <strong>Facturas Pagadas: </strong> {{$totalCantPagado ?? ''}}<br>
            <strong>Cant/Servicio: </strong> {{number_format($cantServicioPagado,0,',','.') ?? '0'}} <br>
            <strong>Cant/Consumo: </strong> {{number_format($cantConsumoPagado,0,',','.') ?? '0'}}<br>
            <strong>Cant/Extras: </strong> {{number_format($cantHorasExtrasPagado,0,',','.') ?? '0'}}



        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <h4><strong>Totales:</strong></h4>
        <address>

            <div class="table-responsive">
            {{-- @can('haveaccess', 'role.index') --}}
                <table class="table">
                    <tr>
                    <th style="width:40%"></th>
                    <th style="width:30%">Pendienes:</th>
                    <th style="width:30%">Pagados:</th>

                  </tr>
                  <tr>
                    <th style="width:20%">Total Servicios:</th>
                    <td><b>$ {{ number_format(floatval($totalServicioPendiente),2,',','.') ?? '0'}}</b></td>
                    <td><b>$ {{ number_format(floatval($totalServicioPagado),2,',','.') ?? '0'}}</b></td>
                  </tr>
                  <tr>
                    <th>Total Consumos:</th>
                    <td>$ {{number_format(floatval($totalConsumoPendiente),2,',','.') ?? '0'}}</td>
                    <td>$ {{number_format(floatval($totalConsumoPagado),2,',','.') ?? '0'}}</td>
                  </tr>
                  <tr>
                    <th>Total Extras:</th>
                    <td>$ {{number_format(floatval($totalHorasExtrasPendiente),2,',','.') ?? '0'}}</td>
                    <td>$ {{number_format(floatval($totalHorasExtrasPagado),2,',','.') ?? '0'}}</td>
                  </tr>
                  <tr>
                    <th style="font-size: 20px">Total:</th>
                    <td style="font-size: 20px" class="text-bold">$ {{number_format($totalServicioPendiente + $totalConsumoPendiente + $totalHorasExtrasPendiente,2,',','.') ?? '0'}}</td>
                    <td style="font-size: 20px" class="text-bold">$ {{number_format($totalServicioPagado + $totalConsumoPagado + $totalHorasExtrasPagado,2,',','.') ?? '0'}}</td>
                  </tr>
                  <tr>
                    <th>
                        &nbsp;

                    </th>
                    <td>&nbsp;</td>
                  </tr>

                </table>
                {{-- @endcan --}}
              </div>

        </address>
        
      </div>
      <!-- /.col -->
      
    </div>
    <!-- /.row -->
    <div class="row">

    </div>

{{-- <div class="row ">
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="panel panel-primary">
            <div class="col-xs-12 table-responsive">

                <h4><strong>Detalle de Cada Transaccion</strong></h4>
                <table class="table table-striped table-bordered table-condensed table-hover">

                    <tbody>
                        @php
                            $count = 1;
                            $actual = 0;
                            // $totalDevolucion = 0;
                            // $totalCompras = 0;
                        @endphp
                        @foreach ($creditos as $cred)

                        @if ($cred->id !== $actual)
                            @php

                            $actual = $cred->created_at;





                            @endphp

                        @if ($cred->estado == 'Cancelado')
                            <tr style="background-color: red;" class="text-black ">
                        @else
                            <tr style="background-color: lightblue;" class="text-black ">
                        @endif

                            <td>{{$count}}</td>
                            <td colspan="3"><b>Fecha:</b> {{$actual ?? ''}}&nbsp;&nbsp; <b>N°:</b> {{$cred->id ?? ''}}&nbsp;&nbsp; <b>Operador:</b> {{$cred->user->name ?? ''}}&nbsp;&nbsp; <b>Cliente:</b> {{$cred->persona->nombre ?? ''}}</td>

                                <td colspan="3"><b>Total Pagado:</b> </td>
                                <td><b> {{ number_format($cred->total_deuda, 3,',','.') ?? '' }}</b></td>




                        </tr>
                        <tr class="{{$detallado ?? ''}}">
                            <th>ID</th>
                            <th>Num Factura</th>
                            <th>Tipo Operacion</th>

                            <th>Fecha Pago.</th>

                            <th>Monto</th>




                        </tr>

                        @php
                            $count++;
                        @endphp
                        @endif

                        @foreach ($cred->creditos_pagados as $credPag)
                        <tr class="{{ $detallado ?? '' }}">
                            <th>{{ $credPag->id ?? '' }}</th>
                            <th>{{ $credPag->numero_factura ?? '' }}</th>
                            <th>{{ $credPag->tipo_operacion ?? '' }}</th>

                            <th>{{ $credPag->fecha_pago ?? '' }}</th>

                            <th>{{ $credPag->monto ?? '' }}</th>




                        </tr>

                        @endforeach
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>
      <!-- /.col -->
    </div> --}}
<hr>

</section></section>
  <!-- /.content -->
  <div class="clearfix"></div>
@push('sciptsMain')
<script>

    $(document).ready(function() {
       var dataTable = $('#ven').dataTable({
        "language": {
                    "info": "_TOTAL_ registros",
                    "search": "Buscar",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior",
                    },
                    "lengthMenu": 'Mostrar <select >'+
                                '<option value="5">5</option>'+
                                '<option value="10">10</option>'+
                                '<option value="-1">Todos</option>'+
                                '</select> registros',
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos",
                    "zeroRecords": "No hay coincidencias",
                    "infoEmpty": "",
                    "infoFiltered": ""
                },
                "iDisplayLength" : 5,
       });
       $("#buscarTexto").keyup(function() {
           dataTable.fnFilter(this.value);
       });
   });
</script>

<script language="javascript">

    function imprimirContenido(el){
        // $('#guion').show();
        var restaurarPagina = document.body.innerHTML;
        // var urlPagina = window.location.href;

//        alert(urlPagina);
        // $('#headerPagina').show();
        // $('#firmaPagina').show();
        var imprimircontenido = document.getElementById(el).innerHTML;
        document.body.innerHTML = imprimircontenido;
        window.print();
        // $('#headerPagina').hide();
        // $('#firmaPagina').hide();
        document.body.innerHTML = restaurarPagina;
        // $('#guion').show();
        // window.location= urlPagina;

    }
//     $( document ).ready( function() {
// $("#print_button1").click(function(){
//     alert('entro');
//             var mode = 'iframe'; // popup
//             var close = mode == "popup";
//             var options = { mode : mode, popClose : close};
//             $("div.contePrint").printArea( options );
//         });
// });
</script>
@endpush
@endsection
