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

            <a class="btn btn-success" href="{{route('reporte-creditos')}}">{{__('Nueva Busqueda')}}</a>
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
                            $totalDeuda = 0;

                            $credPagadoCant = 0;
                            $credPagadoCantServicio = 0;
                            $credPagadoCantConsumo = 0;
                            $montoServicio = 0;
                            $montoConsumo = 0;


                            $totalD = 0;
                            $totalC = 0;
                            $mayorD = 0;
                            $detalD = 0;
                            $mayorC = 0;
                            $detalC = 0;
                            $operacones = 0;
                            $operaconesS = 0;
                            $operaconesC = 0;
                        @endphp
                        @foreach ($creditos as $cred)
                        @php
                        $operacones++;

                        if ($cred->total_Servicio > 0) {
                            $totalServicio += $cred->total_Servicio;

                            $totalDeuda += $cred->total_Servicio;
                            $operaconesS++;
                        }
                        if ($cred->total_Consumo > 0) {
                            $totalConsumo += $cred->total_Consumo;
                            $totalDeuda += $cred->total_Consumo;
                            $operaconesC++;
                        }


                        @endphp

                        @foreach ($cred->creditos_pagados as $cred_pag)
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

                        @endforeach
                        @endforeach
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        <h4><strong>Datos de Busqueda:</strong></h4>

        <address>
        <strong>Desde: </strong> {{$fecha_inicio}}<br>
        <strong>Hasta: </strong> {{$fecha_fin}}<br>
        <strong>Operador: </strong>{{ Auth::user()->name}} <br>
        <strong>Total Operaciones: </strong>{{$operacones}} <br>


        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <h4><strong>Datos de Creditos:</strong></h4>
        <address>
            <strong>Facturas Pagadas: </strong> {{$credPagadoCant}}<br>
            <strong>Cant/Servicio: </strong> {{number_format($credPagadoCantServicio,0,',','.') ?? '0'}} <br>
            <strong>Cant/Consumo: </strong> {{number_format($credPagadoCantConsumo,0,',','.') ?? '0'}}



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
                    <th style="width:50%">Total Servicios:</th>
                    <td><b>$ {{ number_format(floatval($montoServicio),2,',','.') ?? '0'}}</b></td>
                  </tr>
                  <tr>
                    <th>Total Consumos:</th>
                    <td>$ {{number_format(floatval($montoConsumo),2,',','.') ?? '0'}}</td>
                  </tr>
                  <tr>
                    <th style="font-size: 20px">Total:</th>
                    <td style="font-size: 20px" class="text-bold">$ {{number_format($totalDeuda,2,',','.') ?? '0'}}</td>
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

<div class="row ">
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
                            <td colspan="3"><b>Fecha:</b> {{$actual}}&nbsp;&nbsp; <b>N°:</b> {{$cred->id}}&nbsp;&nbsp; <b>Operador:</b> {{$cred->user->name}}&nbsp;&nbsp; <b>Cliente:</b> {{$cred->persona->nombre}}</td>

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
    </div>


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
