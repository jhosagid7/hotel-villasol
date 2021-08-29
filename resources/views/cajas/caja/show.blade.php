@extends ('layouts.admin3')
@section('contenido')


{{-- <script>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
</script> --}}


    <!-- Main content -->
    <section class="content text-sm">

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
            @if ($caja->estado === 'Abierta')
            <a href="" data-target="#modal-delete-{{$caja->id}}" data-toggle="modal"><button class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i> Cerrar caja</button></a>
            @can('haveaccess', 'ventas.create')
            <a class="btn btn-success" href="{{route('venta.create')}}">{{__('Ir a ventas')}}</a>
            @endcan
            @endif
            <a class="btn btn-warning" href="{{route('caja.index')}}">{{__('Ir a cajas')}}</a>
            <a onClick="imprimir('imprimir')" target="_blank" class="btn btn-primary  hidden-print">
                <i class="fa fa-print"></i>
                Imprimir
            </a>
            <a onclick="printDiv('areaImprimir')" target="_blank" class="btn btn-primary  hidden-print">
                <i class="fa fa-print"></i>
                imprimir Resumen
            </a>
            {{-- <input class="btn btn-primary  hidden-print" type="button" onclick="printDiv('areaImprimir')" value="imprimir Resumen" /> --}}

            {{-- <button onclick="imprimir()">Imprimir pantalla</button> --}}

        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->
    <!-- Main content -->
    <div id="areaImprimir" class="invoice">
<section id="imprimir" class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-globe"></i> {{ $appDate[0]->nombre }}
        <small class="pull-right">Fecha: {{date('d-m-y')}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>

    <!-- info row -->
    <div class="row invoice-info">



             <!-- @php
                if($cajas->margenActualVenta){
                    $utilidadEfectivo = $cajas->SumaTotalBolivar*$cajas->margenActualVenta/100;
                    if($utilidadEfectivo == 0){
                        $margenGananciaVentaEfectivo = 0;
                    }else{
                        $totalVentaEfectivo = $utilidadEfectivo + $cajas->SumaTotalBolivar;
                        $margenGananciaVentaEfectivo = $utilidadEfectivo/ $totalVentaEfectivo;
                    }
                }else{
                    $utilidadEfectivo = 0;
                }



                if($cajas->tasaActualVenta){
                    $efectivoToDolar = $utilidadEfectivo/$cajas->tasaActualVenta;

                }

                else{
                    $efectivoToDolar = 0;
                }
            @endphp -->

      <!-- /.col -->

             <!-- @php
                if($cajas->margenActualVenta){
                    $utilidadEfectivo = $cajas->SumaTotalBolivar*$cajas->margenActualVenta/100;
                    if($utilidadEfectivo == 0){
                        $margenGananciaVentaEfectivo = 0;
                    }else{
                        $totalVentaEfectivo = $utilidadEfectivo + $cajas->SumaTotalBolivar;
                        $margenGananciaVentaEfectivo = $utilidadEfectivo/ $totalVentaEfectivo;
                    }
                }else{
                    $utilidadEfectivo = 0;
                }



                if($cajas->tasaActualVenta){
                    $efectivoToDolar = $utilidadEfectivo/$cajas->tasaActualVenta;

                }

                else{
                    $efectivoToDolar = 0;
                }
            @endphp -->


      <div class="col-sm-12  ">
        <h2><strong>@isset($title)
            {{$title}}
            @else
            {!!"Sistema"!!}
        @endisset</strong></h2>
        <address>
            @php
                $totalCreditosPagadosPorCaja       = 0;
                $consumoCreditosPagadosPorCaja     = 0;
                $totalCreditosPagadosPorOficina    = 0;
                $servicioCreditosPagadosPorCaja    = 0;
                $consumoCreditosPagadosPorOficina  = 0;
                $servicioCreditosPagadosPorOficina = 0;
                $consumoCreditosPagadosPorCajaDolar = 0;
                $servicioCreditosPagadosPorCajaDolar = 0;
                $consumoCreditosPagadosPorCajaPeso = 0;
                $servicioCreditosPagadosPorCajaPeso = 0;
                $servicioCreditosPagadosPorCajaPunto = 0;
                $servicioCreditosPagadosPorCajaTransferencia = 0;
                $consumoCreditosPagadosPorCajaPunto = 0;
                $consumoCreditosPagadosPorCajaTransferencia = 0;
                $consumoCreditosPagadosPorCajaBolivar = 0;
                $consumoCreditosPagadosPorCajaTransferenciaPunto  = 0;
                $servicioCreditosPagadosPorCajaTransferenciaPunto = 0;
                $pago_creditos = 0;
                $tr = '';
            @endphp
            @foreach ($cajas->creditos_pagados as $creditosPagados)

                @if ($creditosPagados->user_id == $caja->user->id)
                    @if ($creditosPagados->tipo_operacion == 'Consumo')
                        @php
                        if($creditosPagados->tipo_pago == 'Dolar'){
                            $consumoCreditosPagadosPorCajaDolar = $consumoCreditosPagadosPorCajaDolar + $creditosPagados->monto * $tasaDolar->tasa;
                        }
                        if($creditosPagados->tipo_pago == 'Peso'){
                            $consumoCreditosPagadosPorCajaPeso = ($consumoCreditosPagadosPorCajaPeso + $creditosPagados->monto * $tasaPeso->tasa) ;
                        }
                        if($creditosPagados->tipo_pago == 'Trans/Punto'){
                            // return 'estoy ';
                            $consumoCreditosPagadosPorCajaTransferenciaPunto = $consumoCreditosPagadosPorCajaTransferenciaPunto + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            // $pago_creditos_Consumo = "App\Pago_Credito"::where('detalle__creditos__pagado_id', $creditosPagados->detalle__creditos__pagado_id)->get();
                            // foreach ($pago_creditos_Consumo as $divisaUsadaConsumo) {
                            //     if ($divisaUsadaConsumo->Divisa == 'Punto') {
                            //         $consumoCreditosPagadosPorCajaPunto = $consumoCreditosPagadosPorCajaPunto + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            //     }

                            //     if ($divisaUsadaConsumo->Divisa == 'Transferencia') {
                            //         $consumoCreditosPagadosPorCajaTransferencia = $consumoCreditosPagadosPorCajaTransferencia + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            //     }
                            // }
                            // $servicioCreditosPagadosPorCajaPeso = $consumoCreditosPagadosPorCajaPeso + $creditosPagados->monto * $tasaPeso->tasa;
                        }
                        if($creditosPagados->tipo_pago == 'Bolivar'){
                            $consumoCreditosPagadosPorCajaBolivar = ($consumoCreditosPagadosPorCajaBolivar + $creditosPagados->monto * $tasaBolivar->tasa) ;
                        }
                            $consumoCreditosPagadosPorCaja = $consumoCreditosPagadosPorCaja + $creditosPagados->monto;
                            $tr = '<tr';
                        @endphp
                    @endif
                    @if ($creditosPagados->tipo_operacion == 'Servicio')
                        @php
                        if($creditosPagados->tipo_pago == 'Dolar'){
                            $servicioCreditosPagadosPorCajaDolar = $consumoCreditosPagadosPorCajaDolar + $creditosPagados->monto * $tasaDolar->tasa;
                        }
                        if($creditosPagados->tipo_pago == 'Peso'){
                            $servicioCreditosPagadosPorCajaPeso = $consumoCreditosPagadosPorCajaPeso + $creditosPagados->monto * $tasaPeso->tasa;
                        }
                        if($creditosPagados->tipo_pago == 'Trans/Punto'){
                            // return 'estoy ';
                            $servicioCreditosPagadosPorCajaTransferenciaPunto = $servicioCreditosPagadosPorCajaTransferenciaPunto + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            // $pago_creditos_Servicios = "App\Pago_Credito"::where('detalle__creditos__pagado_id', $creditosPagados->detalle__creditos__pagado_id)->get();
                            // foreach ($pago_creditos_Servicios as $divisaUsada) {
                            //     if ($divisaUsada->Divisa == 'Punto') {
                            //         $servicioCreditosPagadosPorCajaPunto = $servicioCreditosPagadosPorCajaPunto + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            //     }

                            //     if ($divisaUsada->Divisa == 'Transferencia') {
                            //         $servicioCreditosPagadosPorCajaTransferencia = $servicioCreditosPagadosPorCajaTransferencia + $creditosPagados->monto * $tasaTransferenciaPunto->tasa;
                            //     }
                            // }
                            // $servicioCreditosPagadosPorCajaPeso = $consumoCreditosPagadosPorCajaPeso + $creditosPagados->monto * $tasaPeso->tasa;
                        }
                        if($creditosPagados->tipo_pago == 'Bolivar'){
                            $servicioCreditosPagadosPorCajaBolivar = ($servicioCreditosPagadosPorCajaBolivar + $creditosPagados->monto * $tasaBolivar->tasa) ;
                        }
                            $servicioCreditosPagadosPorCaja = $servicioCreditosPagadosPorCaja + $creditosPagados->monto;
                            $tr = '<tr>';
                        @endphp
                    @endif
                    @php
                        $totalCreditosPagadosPorCaja = $totalCreditosPagadosPorCaja + $creditosPagados->monto;
                        $tr = '<tr';
                    @endphp

                @else
                    @if ($creditosPagados->tipo_operacion == 'Consumo')
                        @php
                            $consumoCreditosPagadosPorOficina = $consumoCreditosPagadosPorOficina + $creditosPagados->monto;
                            $tr = '<tr style="background-color: red;" class="text-black tituloRojo">';
                        @endphp
                    @endif
                    @if ($creditosPagados->tipo_operacion == 'Servicio')
                        @php
                            $servicioCreditosPagadosPorOficina = $servicioCreditosPagadosPorOficina + $creditosPagados->monto;
                            $tr = '<tr style="background-color: red;" class="text-black tituloRojo">';
                        @endphp
                    @endif
                        @php
                            $totalCreditosPagadosPorOficina = $totalCreditosPagadosPorOficina + $creditosPagados->monto;
                            $tr = '<tr style="background-color: red;" class="text-black tituloRojo">';
                        @endphp
                @endif
            @endforeach
            <div class="table-responsive">
            @can('haveaccess', 'cajatotales.show')
                <table class="table">
                    @can('haveaccess', 'cajatotalventa.show')
                    <tr>
                        <th >
                            <h4><strong class="text-blue">N° Caja:</strong> <strong>{{ $caja->codigo ?? ''}}</strong>
                                <br>
                                <strong class="text-blue">Estado:</strong> <strong>{{ $caja->estado ?? ''}}</strong>
                            </h4>
                        </th>
                        {{-- <th></th> --}}
                        <th colspan="2">
                            <h4>
                                <strong class="text-blue">Operador:</strong> <strong>{{ $caja->user->name ?? ''}}</strong>
                                <br>
                                <strong class="text-blue">Hora Inicio:</strong> <strong>{{ $caja->hora ?? ''}}</strong>
                            </h4>
                        </th>
                        <th></th>
                        <th >
                            <h4>
                                <strong class="text-blue">Fecha:</strong> <strong>{{ $caja->fecha->format('d-m-Y') ?? ''}}</strong>
                                <br>
                                <strong class="text-blue">Hora Cierre:</strong> <strong>{{ $caja->hora_cierre ?? ''}}</strong>
                            </h4>
                        </th>
                        <th></th>
                        <th ><h4><strong class="text-blue"> </strong></h4></th>
                        <th></th>
                        <th ><h4><strong class="text-blue"></strong></h4></th>
                        <th></th>

                    </tr>
                    @endcan

                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-blue">Consumo:</strong></h4></th>
                    <td></td>
                    <th class="text-blue"></th>

                    <th><h4><strong class="text-blue">Servicios:</strong></h4></th>
                    <td></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th class="text-blue"></th>
                    <th><h4><strong class="text-blue">Creditos:</h4></strong></th>
                    <td></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Contado:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadVentasContado ?? '0' }}</strong></td>
                    <th></th>

                    <th>Serv/Contado:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadServicios ?? '0' }}</strong></td>
                    <th></th>
                    <td><strong></strong></td>
                    <td><strong></strong></td>
                    <th>Creditos/vigentes:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadCreditosVigentes ?? '0' }}</strong></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th>Cons/Credíto:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasCredito ?? '0' }}</b></td>
                    <th></th>
                    <th>Serv/Credíto:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosPorPagar ?? '0' }}</b></td>
                    <th></th>
                    <td><b></b></td>
                    <td><b></b></td>
                    <th>Creditos/vencidos:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadCreditosVencidos ?? '0' }}</strong></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Cortesía:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasCortesia ?? '0' }}</b></td>
                    <th></th>
                    <th>Serv/Cortesía:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosCortesia ?? '0' }}</b></td>
                    <td><b></b></td>
                    <th></th>
                    <td><b></b></td>
                    <th>Creditos/pagados:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosTotales ?? ''}}</td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Total/Consumo:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasContado + $cajas->SumaTotalCantidadVentasCredito + $cajas->SumaTotalCantidadVentasCortesia ?? '0' }}</b></td>
                    <th></th>
                    <th>Total/Serv:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServicios + $cajas->SumaTotalCantidadServiciosPorPagar + $cajas->SumaTotalCantidadServiciosCortesia ?? ' 0,00' }}</b></td>
                    <td><b></b></td>
                    <th></th>
                    <td><b></b></td>
                    <th>Creditos nuevos:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosPorPagar + $cajas->SumaTotalCantidadVentasCredito ?? '0' }}</b></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Créd/Pag:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosConsumo ?? ''}}</td>
                    <th></th>
                    <th>Serv/Créd/Pag:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosServicio ?? ''}}</td>
                    <td><b></b></td>
                    <th></th>
                    <td><b></b></td>
                    <th>Total/Creditos:</th>
                    <td><b>{{$cajas->SumaTotalCantidadCreditosVigentes + $cajas->SumaTotalCantidadCreditosVencidos + $cajas->SumaTotalCantidadCreditosPagadosTotales ?? ''}}</b></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-blue">Consumo Bruto:</strong></h4></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalVentasCredito + $cajas->SumaTotalVentas,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <th><h4><strong class="text-blue">Servicios Bruto:</strong></h4></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalServicios  + $cajas->SumaTotalExtra,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <td class="text-blue"></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-blue">Total Bruto:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalHorasExtrasPorPagar + $cajas->SumaTotalServicios) + ($cajas->SumaTotalVentasCredito + $cajas->SumaTotalVentas  + $cajas->SumaTotalExtra),2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan
                  {{-- @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-danger">Cons/Pago/Excts:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalVentasPagadosConExcedente,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td class="text-blue"></td>
                    <th><h4><strong class="text-danger">Serv/Pago/Excts:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPagadosConExcedente,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-danger">Total/Pago/Excts:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPagadosConExcedente + $cajas->SumaTotalVentasPagadosConExcedente,2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan --}}

                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-danger">Cons/Cortesía:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalVentasCortesia,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <th><h4><strong class="text-danger">Serv/Cortesía:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosCortesia,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <td class="text-blue"></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-danger">Total/Cortesía:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalVentasCortesia + $cajas->SumaTotalServiciosCortesia,2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan

                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-danger">Cons/Créd/Nuevos:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalVentasCredito,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <th><h4><strong class="text-danger">Serv/Créd/Nuevos:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalHorasExtrasPorPagar,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <td class="text-blue"></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-danger">Total/Créd/Nuevos:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalHorasExtrasPorPagar + $cajas->SumaTotalVentasCredito,2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-danger">Cons/Créd/Pagados/Oficina:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalCreditosPagadosConsumoPorOficina,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <th><h4><strong class="text-danger">Serv/Créd/Pagados/Oficina:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorOficina,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-danger">Total/Créd/Pagados/Oficina:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalCreditosPagadosTotalesPorOficina,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-aqua">Cons/Créd/Pagados/Caja:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosConsumoPorCaja,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Serv/Créd/Pagados/Caja:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorCaja,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Créd/Pagados/Caja:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosTotalesPorCaja,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan
                  {{-- @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-aqua">Cons/Exct/Nuevos:</strong></h4></th>
                    <td><h4><strong>${{ number_format($cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td class="text-blue"></td>
                    <th><h4><strong class="text-aqua">Serv/Exct/Nuevos:</strong></h4></th>
                    <td><h4><strong>${{ number_format($cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Exct/Nuevos:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar,2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan --}}

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-aqua">Cons/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalVentas,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    {{-- - $cajas->SumaTotalServiciosExcedenteNuevo --}}
                    <th><h4><strong class="text-aqua">Serv/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalServicios,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalVentas + $cajas->SumaTotalServicios),2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan

                  {{-- @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>

                    <th><h4><strong class="text-aqua">Pagar/Por/Oficina:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->TotalSumaVueltosPagarOficinaDolarToDolar,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Pagar/Por/Oficina:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->TotalSumaVueltosPagarOficinaDolarToDolar),2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan --}}

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    {{-- - $cajas->SumaTotalServiciosExcedenteNuevo --}}
                    <th><h4><strong class="text-aqua">Pagos/Extras:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalExtra,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Pagos/Extras:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalExtra),2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-blue">Cons/Total/Contable:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->SumaTotalConsumoExcedenteNuevo + $cajas->SumaTotalVentas,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <th><h4><strong class="text-blue">Serv/Total/Contable:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->SumaTotalServicios  + $cajas->SumaTotalExtra,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-blue">Total/Contable:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->SumaTotalVentas) + ($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->SumaTotalServicios  + $cajas->SumaTotalExtra),2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan

                </table>
                @endcan
              </div>

        </address>
      </div>
      <!-- /.col -->
    {{-- </div> --}}
    <!-- /.row -->
    <div class="row">

            <div class="panel panel-primary">
                {{-- <div class="panel-body">
                    <h4 class="text-blue"><strong>Datos de Caja</strong></h4>
                        <table id="detalles" class="table table-striped table-borderd table-condensed table-hover">
                            <thead class="tituloAzul" style="background-color: #A9D0F5">
                                <tr>
                                    <th>Codigo</th>
                                    <th>Fecha</th>
                                    <th>Hora inicio</th>
                                    <th>Hora cierre</th>
                                    <th>Inicio Dolar</th>
                                    <th>Inicio Peso</th>
                                    <th>Inicio Bolivar</th>
                                    <th>Cierre Dolar</th>
                                    <th>Cierre Peso</th>
                                    <th>Cierre Bolivar</th>
                                    <th>Estado</th>
                                    <th>Caja</th>
                                </tr>
                            </thead>
                            <tbody id="listarcaja">

                                <tr>
                                    <td>{{ $caja->codigo ?? '' }}</td>
                                    <td>{{ $caja->created_at->diffForHumans() }}</td>
                                    <td>{{ $caja->hora ?? '' }}</td>
                                    <td>{{ $caja->hora_cierre ?? '' }}</td>
                                    <td>{{ $caja->monto_dolar ?? '' }}</td>
                                    <td>{{ $caja->monto_peso ?? '' }}</td>
                                    <td>{{ $caja->monto_bolivar ?? '' }}</td>
                                    <td>{{ $caja->monto_dolar_cierre ?? '' }}</td>
                                    <td>{{ $caja->monto_peso_cierre ?? '' }}</td>
                                    <td>{{ $caja->monto_bolivar_cierre ?? '' }}</td>
                                    <td>{{ $caja->estado ?? '' }}</td>
                                    <td>{{ $caja->caja ?? '' }}</td>
                                </tr>



                            </tbody>
                        </table>


                    </div> --}}

    @include('cajas.caja.caja')
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    <div class="content"><div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Resumen de Caja </h3>


        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-text">Operaciones</span>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">Total Contable:</h5>
                    <h5 class="box-title text-bold">Vtos/Pagar/Oficina:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Registrado por Sistema:</h5>
                    <h5 class="box-title text-bold text-red">- Reportados por Operador:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">Diferencia:</h5>
                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-caret-up"></i>
                        Dolar $</span>
                        <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->SumaTotalVentas) + ($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->SumaTotalServicios  + $cajas->SumaTotalExtra),2,',','.') ?? '0.000' }}</h5>
                    <br>
                    <h5 class="box-title text-bold">$. {{ number_format(($cajas->TotalSumaVueltosPagarOficinaDolarToDolar),2,',','.') ?? '0.000' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->SumaTotalVentas) + ($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->SumaTotalServicios + $cajas->TotalSumaVueltosPagarOficinaDolarToDolar  + $cajas->SumaTotalExtra),2,',','.') ?? '0.000' }}</h5>
                    <br>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->total_operador_reg),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->total_diferencia),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <span class="description-text">DOLAR</span>
                </div>
                <!-- /.description-block -->
            </div>

            <div></div>
            <div></div>
            <!-- /.col -->
            <div class="col-sm-8 col-xs-6">
                <div class="text-left">
                <br>
                <br>

                        <span class="box-title text-bold">OBSERVACIONES:</span>

                        <div  class="text-left">
                            {{$cajas->Observaciones}}
                        </div>



                </div>

            </div>
            <!-- /.col -->

        </div>
        @php
            $totalDolarShow =0;
            $totalPagadosShow =0;
            $totalVueltosShow =0;
            $totalCajasShow =0;
            $totalExcedentesShow =0;
            $totalPagarPorOficinasShow =0;
            $totalPagarPorOficinasShow =0;
            $totalContablesShow =0;
        @endphp
        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"> </i> {{ number_format(floatval((($cajas->TotalSumaTotalServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarFinal - $cajas->TotalSumaTotalVueltosFinal - $cajas->TotalSumaTotalExcedenteFinal - $cajas->TotalSumaTotalPagarOficinaFinal)) + (($cajas->TotalSumaTotalConsFinal - $cajas->TotalSumaTotalVueltosFinalConsumo - $cajas->TotalSumaTotalExcedenteFinalConsumo - $cajas->TotalSumaTotalPagarOficinaFinalConsumo)) + (($cajas->TotalSumaTotalCreditoFinal - $cajas->TotalSumaTotalVueltosFinalCredito - $cajas->TotalSumaTotalExcedenteFinalCredito - $cajas->TotalSumaTotalPagarOficinaFinalCredito)) + (($cajas->TotalSumaTotalHorasExtrasFinal - $cajas->TotalSumaTotalVueltosFinalHorasExtras - $cajas->TotalSumaTotalExcedenteFinalHorasExtras - $cajas->TotalSumaTotalPagarOficinaFinalHorasExtras))),2,',','.') ?? ''}}</span>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">Total Contable:</h5>
                    <h5 class="box-title text-bold">Vtos/Pagar/Oficina:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Registrado por Sistema:</h5>
                    <h5 class="box-title text-bold text-red">- Reportados por Operador:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">Diferencia:</h5>
                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval((($cajas->SumaTotalDolarServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolar - $cajas->SumaTotalDolarVueltosFinalDolar - $cajas->SumaTotalDolarExcedenteFinalDolar - $cajas->SumaTotalDolarPagarOficinaFinalDolar)) + (($cajas->SumaTotalDolarConsFinalDolar - $cajas->SumaTotalDolarVueltosFinalDolarConsumo - $cajas->SumaTotalDolarExcedenteFinalDolarConsumo - $cajas->SumaTotalDolarPagarOficinaFinalDolarConsumo)) + (($cajas->SumaTotalDolarCreditoFinalDolar - $cajas->SumaTotalDolarVueltosFinalDolarCredito - $cajas->SumaTotalDolarExcedenteFinalDolarCredito - $cajas->SumaTotalDolarPagarOficinaFinalDolarCredito)) + (($cajas->SumaTotalDolarHorasExtrasFinalDolar - $cajas->SumaTotalDolarVueltosFinalDolarHorasExtras - $cajas->SumaTotalDolarExcedenteFinalDolarHorasExtras - $cajas->SumaTotalDolarPagarOficinaFinalDolarHorasExtras))),2,',','.') ?? ''}}</span>
                        <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval(($cajas->SumaTotalDolarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa - $cajas->SumaTotalDolarVueltosFinal - $cajas->SumaTotalDolarExcedenteFinal - $cajas->SumaTotalDolarPagarOficinaFinal) + ($cajas->SumaTotalDolarConsFinal - $cajas->SumaTotalDolarVueltosFinalConsumo - $cajas->SumaTotalDolarExcedenteFinalConsumo - $cajas->SumaTotalDolarPagarOficinaFinalConsumo) + ($cajas->SumaTotalDolarCreditoFinal - $cajas->SumaTotalDolarVueltosFinalCredito - $cajas->SumaTotalDolarExcedenteFinalCredito - $cajas->SumaTotalDolarPagarOficinaFinalCredito) + ($cajas->SumaTotalDolarHorasExtrasFinal - $cajas->SumaTotalDolarVueltosFinalHorasExtras - $cajas->SumaTotalDolarExcedenteFinalHorasExtras - $cajas->SumaTotalDolarPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalDolarPagarOficinaFinal) + ($cajas->SumaTotalDolarPagarOficinaFinalConsumo) + ($cajas->SumaTotalDolarPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaTotalDolarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa - $cajas->SumaTotalDolarVueltosFinal - $cajas->SumaTotalDolarExcedenteFinal - $cajas->SumaTotalDolarPagarOficinaFinal) + ($cajas->SumaTotalDolarConsFinal - $cajas->SumaTotalDolarVueltosFinalConsumo - $cajas->SumaTotalDolarExcedenteFinalConsumo - $cajas->SumaTotalDolarPagarOficinaFinalConsumo) + ($cajas->SumaTotalDolarCreditoFinal - $cajas->SumaTotalDolarVueltosFinalCredito - $cajas->SumaTotalDolarExcedenteFinalCredito - $cajas->SumaTotalDolarPagarOficinaFinalCredito) + ($cajas->SumaTotalDolarHorasExtrasFinal - $cajas->SumaTotalDolarVueltosFinalHorasExtras - $cajas->SumaTotalDolarExcedenteFinalHorasExtras - $cajas->SumaTotalDolarPagarOficinaFinalHorasExtras)) + (($cajas->SumaTotalDolarPagarOficinaFinal) + ($cajas->SumaTotalDolarPagarOficinaFinalConsumo) + ($cajas->SumaTotalDolarPagarOficinaFinalHorasExtras))),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_dolar_cierre * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_dolar_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <span class="description-text">DOLAR</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval((($cajas->SumaTotalPesoServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolar - $cajas->SumaTotalPesoVueltosFinalDolar - $cajas->SumaTotalPesoExcedenteFinalDolar - $cajas->SumaTotalPesoPagarOficinaFinalDolar)) + (($cajas->SumaTotalPesoConsFinalDolar - $cajas->SumaTotalPesoVueltosFinalDolarConsumo - $cajas->SumaTotalPesoExcedenteFinalDolarConsumo - $cajas->SumaTotalPesoPagarOficinaFinalDolarConsumo)) + (($cajas->SumaTotalPesoCreditoFinalDolar - $cajas->SumaTotalPesoVueltosFinalDolarCredito - $cajas->SumaTotalPesoExcedenteFinalDolarCredito - $cajas->SumaTotalPesoPagarOficinaFinalDolarCredito)) + (($cajas->SumaTotalPesoHorasExtrasFinalDolar - $cajas->SumaTotalPesoVueltosFinalDolarHorasExtras - $cajas->SumaTotalPesoExcedenteFinalDolarHorasExtras - $cajas->SumaTotalPesoPagarOficinaFinalDolarHorasExtras))),2,',','.') ?? ''}}</span>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval(($cajas->SumaTotalPesoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa - $cajas->SumaTotalPesoVueltosFinal - $cajas->SumaTotalPesoExcedenteFinal - $cajas->SumaTotalPesoPagarOficinaFinal) + ($cajas->SumaTotalPesoConsFinal - $cajas->SumaTotalPesoVueltosFinalConsumo - $cajas->SumaTotalPesoExcedenteFinalConsumo - $cajas->SumaTotalPesoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPesoCreditoFinal - $cajas->SumaTotalPesoVueltosFinalCredito - $cajas->SumaTotalPesoExcedenteFinalCredito - $cajas->SumaTotalPesoPagarOficinaFinalCredito) + ($cajas->SumaTotalPesoHorasExtrasFinal - $cajas->SumaTotalPesoVueltosFinalHorasExtras - $cajas->SumaTotalPesoExcedenteFinalHorasExtras - $cajas->SumaTotalPesoPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalPesoPagarOficinaFinal) + ($cajas->SumaTotalPesoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPesoPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaTotalPesoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa - $cajas->SumaTotalPesoVueltosFinal - $cajas->SumaTotalPesoExcedenteFinal - $cajas->SumaTotalPesoPagarOficinaFinal) + ($cajas->SumaTotalPesoConsFinal - $cajas->SumaTotalPesoVueltosFinalConsumo - $cajas->SumaTotalPesoExcedenteFinalConsumo - $cajas->SumaTotalPesoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPesoCreditoFinal - $cajas->SumaTotalPesoVueltosFinalCredito - $cajas->SumaTotalPesoExcedenteFinalCredito - $cajas->SumaTotalPesoPagarOficinaFinalCredito) + ($cajas->SumaTotalPesoHorasExtrasFinal - $cajas->SumaTotalPesoVueltosFinalHorasExtras - $cajas->SumaTotalPesoExcedenteFinalHorasExtras - $cajas->SumaTotalPesoPagarOficinaFinalHorasExtras)) + (($cajas->SumaTotalPesoPagarOficinaFinal) + ($cajas->SumaTotalPesoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPesoPagarOficinaFinalHorasExtras))),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_peso_cierre * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_peso_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <span class="description-text">PESO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval((($cajas->SumaTotalPuntoServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolar - $cajas->SumaTotalPuntoVueltosFinalDolar - $cajas->SumaTotalPuntoExcedenteFinalDolar - $cajas->SumaTotalPuntoPagarOficinaFinal)) + (($cajas->SumaTotalPuntoConsFinalDolar - $cajas->SumaTotalPuntoVueltosFinalDolarConsumo - $cajas->SumaTotalPuntoExcedenteFinalDolarConsumo - $cajas->SumaTotalPuntoPagarOficinaFinalDolarConsumo)) + (($cajas->SumaTotalPuntoCreditoFinalDolar - $cajas->SumaTotalPuntoVueltosFinalDolarCredito - $cajas->SumaTotalPuntoExcedenteFinalDolarCredito - $cajas->SumaTotalPuntoPagarOficinaFinalDolarCredito)) + (($cajas->SumaTotalPuntoHorasExtrasFinalDolar - $cajas->SumaTotalPuntoVueltosFinalDolarHorasExtras - $cajas->SumaTotalPuntoExcedenteFinalDolarHorasExtras - $cajas->SumaTotalPuntoPagarOficinaFinalDolarHorasExtras))),2,',','.') ?? ''}}</span>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval(($cajas->SumaTotalPuntoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa - $cajas->SumaTotalPuntoVueltosFinal - $cajas->SumaTotalPuntoExcedenteFinal - $cajas->SumaTotalPuntoPagarOficinaFinal) + ($cajas->SumaTotalPuntoConsFinal - $cajas->SumaTotalPuntoVueltosFinalConsumo - $cajas->SumaTotalPuntoExcedenteFinalConsumo - $cajas->SumaTotalPuntoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPuntoCreditoFinal - $cajas->SumaTotalPuntoVueltosFinalCredito - $cajas->SumaTotalPuntoExcedenteFinalCredito - $cajas->SumaTotalPuntoPagarOficinaFinalCredito) + ($cajas->SumaTotalPuntoHorasExtrasFinal - $cajas->SumaTotalPuntoVueltosFinalHorasExtras - $cajas->SumaTotalPuntoExcedenteFinalHorasExtras - $cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>

                        <br><h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalPuntoPagarOficinaFinal) + ($cajas->SumaTotalPuntoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold">Bs. {{ number_format(floatval((($cajas->SumaTotalPuntoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa - $cajas->SumaTotalPuntoVueltosFinal - $cajas->SumaTotalPuntoExcedenteFinal - $cajas->SumaTotalPuntoPagarOficinaFinal) + ($cajas->SumaTotalPuntoConsFinal - $cajas->SumaTotalPuntoVueltosFinalConsumo - $cajas->SumaTotalPuntoExcedenteFinalConsumo - $cajas->SumaTotalPuntoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPuntoCreditoFinal - $cajas->SumaTotalPuntoVueltosFinalCredito - $cajas->SumaTotalPuntoExcedenteFinalCredito - $cajas->SumaTotalPuntoPagarOficinaFinalCredito) + ($cajas->SumaTotalPuntoHorasExtrasFinal - $cajas->SumaTotalPuntoVueltosFinalHorasExtras - $cajas->SumaTotalPuntoExcedenteFinalHorasExtras - $cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras)) + (($cajas->SumaTotalPuntoPagarOficinaFinal) + ($cajas->SumaTotalPuntoPagarOficinaFinalConsumo) + ($cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras))),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_punto_cierre * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_punto_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <span class="description-text">PUNTO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval((($cajas->SumaTotalTransferenciaServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolar - $cajas->SumaTotalTransferenciaVueltosFinalDolar - $cajas->SumaTotalTransferenciaExcedenteFinalDolar - $cajas->SumaTotalTransferenciaPagarOficinaFinal)) + (($cajas->SumaTotalTransferenciaConsFinalDolar - $cajas->SumaTotalTransferenciaVueltosFinalDolarConsumo - $cajas->SumaTotalTransferenciaExcedenteFinalDolarConsumo - $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo)) + (($cajas->SumaTotalTransferenciaCreditoFinalDolar - $cajas->SumaTotalTransferenciaVueltosFinalDolarCredito - $cajas->SumaTotalTransferenciaExcedenteFinalDolarCredito - $cajas->SumaTotalTransferenciaPagarOficinaFinalCredito)) + (($cajas->SumaTotalTransferenciaHorasExtrasFinalDolar - $cajas->SumaTotalTransferenciaVueltosFinalDolarHorasExtras - $cajas->SumaTotalTransferenciaExcedenteFinalDolarHorasExtras - $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras))),2,',','.') ?? ''}}</span>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval(($cajas->SumaTotalTransferenciaServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa - $cajas->SumaTotalTransferenciaVueltosFinal - $cajas->SumaTotalTransferenciaExcedenteFinal - $cajas->SumaTotalTransferenciaPagarOficinaFinal) + ($cajas->SumaTotalTransferenciaConsFinal - $cajas->SumaTotalTransferenciaVueltosFinalConsumo - $cajas->SumaTotalTransferenciaExcedenteFinalConsumo - $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo) + ($cajas->SumaTotalTransferenciaCreditoFinal - $cajas->SumaTotalTransferenciaVueltosFinalCredito - $cajas->SumaTotalTransferenciaExcedenteFinalCredito - $cajas->SumaTotalTransferenciaPagarOficinaFinalCredito) + ($cajas->SumaTotalTransferenciaHorasExtrasFinal - $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras - $cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras - $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalTransferenciaPagarOficinaFinal) + ($cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo) + ($cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold">Bs. {{ number_format(floatval((($cajas->SumaTotalTransferenciaServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa - $cajas->SumaTotalTransferenciaVueltosFinal - $cajas->SumaTotalTransferenciaExcedenteFinal - $cajas->SumaTotalTransferenciaPagarOficinaFinal) + ($cajas->SumaTotalTransferenciaConsFinal - $cajas->SumaTotalTransferenciaVueltosFinalConsumo - $cajas->SumaTotalTransferenciaExcedenteFinalConsumo - $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo) + ($cajas->SumaTotalTransferenciaCreditoFinal - $cajas->SumaTotalTransferenciaVueltosFinalCredito - $cajas->SumaTotalTransferenciaExcedenteFinalCredito - $cajas->SumaTotalTransferenciaPagarOficinaFinalCredito) + ($cajas->SumaTotalTransferenciaHorasExtrasFinal - $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras - $cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras - $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras)) + (($cajas->SumaTotalTransferenciaPagarOficinaFinal) + ($cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo) + ($cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras))),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_trans_cierre * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_trans_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <span class="description-text">TRANS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block">
                    <span class="description-percentage text-green"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval((($cajas->SumaTotalBolivarServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolar - $cajas->SumaTotalBolivarVueltosFinalDolar - $cajas->SumaTotalBolivarExcedenteFinalDolar - $cajas->SumaTotalBolivarPagarOficinaFinal)) + (($cajas->SumaTotalBolivarConsFinalDolar - $cajas->SumaTotalBolivarVueltosFinalDolarConsumo - $cajas->SumaTotalBolivarExcedenteFinalDolarConsumo - $cajas->SumaTotalBolivarPagarOficinaFinalDolarConsumo)) + (($cajas->SumaTotalBolivarCreditoFinalDolar - $cajas->SumaTotalBolivarVueltosFinalDolarCredito - $cajas->SumaTotalBolivarExcedenteFinalDolarCredito - $cajas->SumaTotalBolivarPagarOficinaFinalDolarCredito)) + (($cajas->SumaTotalBolivarHorasExtrasFinalDolar - $cajas->SumaTotalBolivarVueltosFinalDolarHorasExtras - $cajas->SumaTotalBolivarExcedenteFinalDolarHorasExtras - $cajas->SumaTotalBolivarPagarOficinaFinalDolarHorasExtras))),2,',','.') ?? ''}}</span>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval(($cajas->SumaTotalBolivarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa - $cajas->SumaTotalBolivarVueltosFinal - $cajas->SumaTotalBolivarExcedenteFinal - $cajas->SumaTotalBolivarPagarOficinaFinal) + ($cajas->SumaTotalBolivarConsFinal - $cajas->SumaTotalBolivarVueltosFinalConsumo - $cajas->SumaTotalBolivarExcedenteFinalConsumo - $cajas->SumaTotalBolivarPagarOficinaFinalConsumo) + ($cajas->SumaTotalBolivarCreditoFinal - $cajas->SumaTotalBolivarVueltosFinalCredito - $cajas->SumaTotalBolivarExcedenteFinalCredito - $cajas->SumaTotalBolivarPagarOficinaFinalCredito) + ($cajas->SumaTotalBolivarHorasExtrasFinal - $cajas->SumaTotalBolivarVueltosFinalHorasExtras - $cajas->SumaTotalBolivarExcedenteFinalHorasExtras - $cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalBolivarPagarOficinaFinal) + ($cajas->SumaTotalBolivarPagarOficinaFinalConsumo) + ($cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras)),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold">Bs. {{ number_format(floatval((($cajas->SumaTotalBolivarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa - $cajas->SumaTotalBolivarVueltosFinal - $cajas->SumaTotalBolivarExcedenteFinal - $cajas->SumaTotalBolivarPagarOficinaFinal) + ($cajas->SumaTotalBolivarConsFinal - $cajas->SumaTotalBolivarVueltosFinalConsumo - $cajas->SumaTotalBolivarExcedenteFinalConsumo - $cajas->SumaTotalBolivarPagarOficinaFinalConsumo) + ($cajas->SumaTotalBolivarCreditoFinal - $cajas->SumaTotalBolivarVueltosFinalCredito - $cajas->SumaTotalBolivarExcedenteFinalCredito - $cajas->SumaTotalBolivarPagarOficinaFinalCredito) + ($cajas->SumaTotalBolivarHorasExtrasFinal - $cajas->SumaTotalBolivarVueltosFinalHorasExtras - $cajas->SumaTotalBolivarExcedenteFinalHorasExtras - $cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras)) + (($cajas->SumaTotalBolivarPagarOficinaFinal) + ($cajas->SumaTotalBolivarPagarOficinaFinalConsumo) + ($cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras))),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_bolivar_cierre * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold text-red">Bs. {{ number_format(floatval($cajas->monto_bolivar_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                        <span class="description-text">EFECTIVO</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        </div>
</div> {{-- imprimir --}}
    </div></div>
    </div></div>

    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- Montos de apertura de caja --}}
    <div class="col-12">
    <div >
<div class="content">
    <div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Montos de Apertura </h3>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-text">Tasa del Día</span>
                    <h5 class="description-header">Caja Chica:</h5>
                    <h5 class="description-header">Vtos/Pendtes/Caja/Anterior:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Total Base Inicial:</h5>

                    <h5 class="description-header text-red">&nbsp;&nbsp;&nbsp;</h5>
                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-caret-up"></i>
                        {{ $tasaDolar->tasa ?? ''}} $.</span>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_dolar),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->monto_dolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <span class="description-text">DOLAR</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-yellow"><i
                            class="fa fa-caret-left"></i>
                        {{ $tasaPeso->tasa  ?? ''}} $.</span>
                        <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_peso),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                        <h5 class="description-header">______________</h5>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->monto_peso + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                    <span class="description-text">PESO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-caret-up"></i>
                        {{ $tasaTransferenciaPunto->tasa  ?? ''}} Bs.</span>
                    <h5 class="description-header">Bs. {{ number_format($cajas->monto_punto,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format(floatval($cajas->monto_punto + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <span class="description-text">PUNTO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-caret-up"></i>
                        {{ $tasaTransferenciaPunto->tasa }} Bs.</span>
                    <h5 class="description-header">Bs. {{ number_format($cajas->monto_trans,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format(floatval($cajas->monto_trans + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <span class="description-text">TRANS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block">
                    <span class="description-percentage text-red"><i
                            class="fa fa-caret-down"></i>
                        {{ $tasaEfectivo->tasa }} Bs.</span>
                    <h5 class="description-header">Bs. {{ number_format($cajas->monto_bolivar,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format(floatval($cajas->monto_bolivar + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                        <br>
                    <span class="description-text">EFECTIVO</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-sm-6 col-xs-12">
                <span class="description-text">OBSERVACIONES:</span>
                <div>
                    {{$cajas->Observaciones}}
                </div>

            </div>
        </div> --}}
    </div>

    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- Proceso de excedentes nuevos --}}
    {{-- // TODO Crear proceso para manejo de excedentes nuevos --}}
    <div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Vueltos Pendientes </h3>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">

                    <h5 class="description-header">Vtos/Pendtes/Caja/Anterior:</h5>
                    <h5 class="description-header">Vtos/Pendtes Recibidos:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">&nbsp;&nbsp;&nbsp;</h5>
                    <h5 class="description-header text-red">- Vtos/Devueltos:</h5>
                    <h5 class="description-header text-red">- Vtos/Pagar/Oficina:</h5>
                    {{-- <h5 class="description-header text-red">- Excts/Nuevos:</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Vtos/Pendientes:</h5>
                    <h5 class="description-header">______________</h5>




                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">

                    <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosPendientesDolarDivisa - $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">$. {{ number_format(floatval(($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa + $cajas->TotalSumaVueltosPendientesDolarDivisa) - $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoDolarDivisa),2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


                    <span class="description-text">DOLAR</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->TotalSumaVueltosPendientesPesoDivisa - $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">$. {{ number_format(floatval(($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa + $cajas->TotalSumaVueltosPendientesPesoDivisa) - $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoPesoDivisa),2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


                    <span class="description-text">PESO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosPendientesPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa + $cajas->TotalSumaVueltosPendientesPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosDevueltosPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosPagarOficinaPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format($cajas->SumaVueltosPendientesPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


                    <span class="description-text">PUNTO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosPendientesTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa + $cajas->TotalSumaVueltosPendientesTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosDevueltosTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosPagarOficinaTransferenciaDivisass,2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisass,2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format($cajas->SumaVueltosPendientesTransferenciaDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


                    <span class="description-text">TRANS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block">
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">Bs. {{ number_format($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa + $cajas->TotalSumaVueltosPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosDevueltosBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosPagarOficinaBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format($cajas->SumaVueltosPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


                    <span class="description-text">EFECTIVO</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        <!-- {{-- <div class="row">
            <div class="col-sm-6 col-xs-12">
                <span class="description-text">OBSERVACIONES:</span>
                <div>
                    {{$cajas->Observaciones}}
                </div>

            </div>
        </div> --}} -->
    </div>


    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- Montos recibidos en caja --}}

</div>

    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
<div class="col-12">
    <div >
<div class="content">
    @can('haveaccess', 'cajadatosventas.show')
    @if (count($cajas->servicios) > 0)
    <!-- Table row -->
    <div class="row">
        <div class="panel panel-primary">
      <div class="col-xs-12 table-responsive">


            <h4><strong>Datos de Servicios</strong></h4>
    <div class="box-header with-border bg-danger">
        {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->TotalSumaTotalServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicioFinal - $cajas->TotalSumaTotalVueltosFinal - $cajas->TotalSumaTotalExcedenteFinal - $cajas->TotalSumaTotalPagarOficinaFinal)),2,',','.') ?? ''}}</span>
                    <h5 class="description-header">______________</h5>

                    <h5 class="box-title text-bold">Caja/Anterior:</h5><br>
                    <h5 class="box-title text-bold">Pagados:</h5>
                    <h5 class="description-header text-red">-Vueltos:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">Total/caja:</h5>
                    <h5 class="description-header text-red">-Excedente:</h5>
                    <h5 class="description-header text-bold">-Pagar Por Oficina:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">Total/Servicios:</h5>

                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage  box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->SumaTotalDolarServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicio - $cajas->SumaTotalDolarVueltosFinalDolar - $cajas->SumaTotalDolarExcedenteFinalDolar - $cajas->SumaTotalDolarPagarOficinaFinalDolar)),2,',','.') ?? ''}}</span>

                        <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaServicio),2,',','.') ?? ' 0,00' }}</h5><br>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarServFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaServicio - $cajas->SumaTotalDolarVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarExcedenteFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaServicio - $cajas->SumaTotalDolarVueltosFinal - $cajas->SumaTotalDolarExcedenteFinal - $cajas->SumaTotalDolarPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>

                    <br>
                    <span class="description-text">DOLAR</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->SumaTotalPesoServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarServicio - $cajas->SumaTotalPesoVueltosFinalDolar - $cajas->SumaTotalPesoExcedenteFinalDolar - $cajas->SumaTotalPesoPagarOficinaFinalDolar)),2,',','.')  ?? ''}}</span>

                        <h5 class="description-header">______________</h5>
                       <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaServicio),2,',','.') ?? ' 0,00' }}</h5><br>
                       <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoServFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaServiciov - $cajas->SumaTotalPesoVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoExcedenteFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaServicio - $cajas->SumaTotalPesoVueltosFinal - $cajas->SumaTotalPesoExcedenteFinal - $cajas->SumaTotalPesoPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                        <span class="description-text">PESO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->SumaTotalPuntoServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarServicio - $cajas->SumaTotalPuntoVueltosFinalDolar - $cajas->SumaTotalPuntoExcedenteFinalDolar - $cajas->SumaTotalPuntoPagarOficinaFinalDolar)),2,',','.')  ?? ''}}</span>
                        <h5 class="description-header">______________</h5>

                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaServicio),2,',','.') ?? ' 0,00' }}</h5><br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoServFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaServicio - $cajas->SumaTotalPuntoVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoExcedenteFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaServicioss - $cajas->SumaTotalPuntoVueltosFinal - $cajas->SumaTotalPuntoExcedenteFinal - $cajas->SumaTotalPuntoPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                        <span class="description-text">PUNTO</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->SumaTotalTransferenciaServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarServicios - $cajas->SumaTotalTransferenciaVueltosFinalDolar - $cajas->SumaTotalTransferenciaExcedenteFinalDolar - $cajas->SumaTotalTransferenciaPagarOficinaFinalDolar)),2,',','.') ?? '' }}</span>
                        <h5 class="description-header">______________</h5>

                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaServicio),2,',','.') ?? ' 0,00' }}</h5><br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaServFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaServicio - $cajas->SumaTotalTransferenciaVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaExcedenteFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaServicio - $cajas->SumaTotalTransferenciaVueltosFinal - $cajas->SumaTotalTransferenciaExcedenteFinal - $cajas->SumaTotalTransferenciaPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                        <span class="description-text">TRANS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->

            <div class="col-sm-2 col-xs-6">
                <div class="description-block">
                    <span class="description-percentage box-title text-bold text-blue"><i
                            class="fa fa-dollar"></i>
                        {{ number_format(floatval(($cajas->SumaTotalBolivarServFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarServicio - $cajas->SumaTotalBolivarVueltosFinalDolar - $cajas->SumaTotalBolivarExcedenteFinalDolar - $cajas->SumaTotalBolivarPagarOficinaFinalDolar)),2,',','.') ?? '' }} </span>
                        <h5 class="description-header">______________</h5>

                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaServicio),2,',','.') ?? ' 0,00' }}</h5><br>
                        <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarServFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaServicio - $cajas->SumaTotalBolivarVueltosFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarExcedenteFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarServFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaServicio - $cajas->SumaTotalBolivarVueltosFinal - $cajas->SumaTotalBolivarExcedenteFinal - $cajas->SumaTotalBolivarPagarOficinaFinal),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                        <span class="description-text">EFECTIVO</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-sm-6 col-xs-12">
                <span class="description-text">OBSERVACIONES:</span>
                <div>
                    {{$cajas->Observaciones}}
                </div>

            </div>
        </div> -->


    </div>

        <table class="table table-striped table-bordered table-condensed table-hover">

                @php
                    //creamos esta variable para ir contando los valores donde el tipo de pago sea cambio
                    //para luego restarselo a las cortesias ya que el valor que cuenta en las cortesias
                    //es cuando el tipo de pago es exonerado.

                    $restarAcortesia = 0;
                    $count = 1;
                @endphp

                @foreach ($cajas->servicios as $serv)

                @if ($serv->tipo_pago == 'cambio')
                @php
                    $restarAcortesia ++;

                @endphp
                @endif
                @if ($serv->modo_pago == 'Cortesía')
                <tr   style="background-color: #ff0000;" class="text-black tituloRojo">
                @elseif($serv->modo_pago == 'Crédito')
                    <tr style="background-color: rgb(36, 238, 10);" class="text-black tituloVerde">
                @else
                    <tr style="background-color: rgb(35, 192, 245);" class="text-black tituloAzul">
                @endif


                    <td>ID</td>
                    <td>Fecha</td>
                    <td>Comprobante</td>
                    <td>Tasa</td>
                    <td>Modo Pago</td>
                    <td>Tipo Pago</td>
                    <td>N° punto/trans</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>Precio</td>
                    <td>M/Dejado</td>
                    <td>D/Pago</td>
                    <td>D/Vueltos - <b class="text-red">Vtos/Devueltos</b></td>
                    @endcan


                    </tr>
                <tbody>
                    @if ($serv->modo_pago == 'Cortesía')
                        <tr style="background-color: rgb(247, 191, 191);" class="text-black detalleRojo">
                    @elseif($serv->modo_pago == 'Crédito')
                        <tr style="background-color: rgba(198, 250, 191, 0.692);" class="text-black detalleVerde">
                    @else
                        <tr style="background-color: rgba(174, 221, 236, 0.555);" class="text-black detalleAzul">
                    @endif
                    <td
                    @if ($serv->status_servicio == 'Iniciado')
                    style="background-color: red;"
                    @endif
                    >{{ $count ?? '' }}</td>
                    <td>{{ $serv->fecha_entrada.' '.$serv->hora_entrada ?? '' }}</td>
                    <td>{{ $serv->num_servicio ?? '' }}</td>
                    <td>{{ $serv->tasaTransPunto ?? '' }}</td>
                    <td>{{ $serv->modo_pago ?? '' }}</td>
                    <td>{{ $serv->tipo_pago ?? '' }}</td>
                    <td>&nbsp;{{ $serv->num_Punto ?? '' }} &nbsp;{{ $serv->num_Trans ?? '' }}</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>{{ '$. '.floatval($serv->precio_costo * $serv->cantidad) ?? '' }}</td>
                    <td>{{ '$. '.floatval($serv->dinero_dejado) ?? '' }}</td>
                    <td>
                        @if ($serv->modo_pago)
                            @foreach ($serv->pago_servicios as $pagoSrv)
                            {{ ' '.$pagoSrv->Divisa.': '.floatval($pagoSrv->MontoDivisa) ?? '' }}
                            @endforeach
                        @endif
                        @if ($serv->pago_con_excedente)
                            <b class="text-red">{{' Excedente: '. floatval($serv->pago_con_excedente) ?? '' }}</b>
                        @endif
                        {{-- {{ $serv->pago_servicios ?? '' }} --}}
                    </td>
                    <td>
                        {{-- @if ($serv->modo_pago)
                            @php
                                $vueltos_pagados = "App\Pago_Vuelto"::where('servicio_id', $serv->id)->get();
                            @endphp
                            @if (count($pago_creditos))
                            @foreach ($pago_creditos as $pagoCrt)
                            {{ ' '.$pagoCrt->Divisa.': '.floatval($pagoCrt->MontoDivisa) ?? '' }}
                            @endforeach
                            @endif

                        @endif --}}

                        @if ($serv->modo_pago)
                            @foreach ($serv->pago_vueltos as $pagovuts)
                            @if ($pagovuts->servicio_id == $serv->id && $pagovuts->Tipo == 'Servicio')
                                {{ ' '.$pagovuts->Divisa.': '.floatval($pagovuts->MontoDivisa) ?? '0.00' }}
                            @endif
                            @endforeach
                        @endif
                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosDevueltos)
                            @if ($vtosDevueltos->servicio_id == $serv->id && $vtosDevueltos->Estado == 'Devueltos' && $vtosDevueltos->Tipo == 'Servicio')
                                @if ($vtosDevueltos->Divisa <> 'Dolar')
                                    <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.number_format($vtosDevueltos->MontoDivisa,2) ?? '' }}</b>
                                @else
                                    <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.floatval($vtosDevueltos->MontoDivisa) ?? '' }}</b>
                                @endif

                            @endif

                            @endforeach
                        @endif

                        {{-- {{ $serv->pago_servicios ?? '' }} --}}
                    </td>
                    @endcan



                </tr>


                @if ($serv->modo_pago == 'Cortesía')
                        <tr style="background-color: rgb(247, 139, 139);" class="text-black subTituloRojo">
                    @elseif($serv->modo_pago == 'Crédito')
                        <tr style="background-color: rgba(154, 247, 141, 0.692);" class="text-black subTituloVerde">
                    @else
                        <tr style="background-color: rgba(126, 211, 240, 0.555);" class="text-black subTituloAzul">
                    @endif
                    <td>N° Hab</td>
                    <td>Detalle Habitación</td>
                    <td>Tipo Habitación</td>
                    <td colspan="2">Nombre</td>
                    <td>Cédula</td>
                    <td colspan="3">Dirección</td>
                    <td>Nvo/Excedete</td>
                    <td>D/Vtos/Pendtes - <b class="text-red">Pagar/Oficina</b></td>
                </tr>
                @if ($serv->modo_pago == 'Cortesía')
                        <tr style="background-color: rgb(247, 191, 191);" class="text-black detalleRojo">
                    @elseif($serv->modo_pago == 'Crédito')
                        <tr style="background-color: rgba(198, 250, 191, 0.692);" class="text-black detalleVerde">
                    @else
                        <tr style="background-color: rgba(174, 221, 236, 0.555);" class="text-black detalleAzul">
                    @endif
                    <td>{{ $serv->nombre_habitacion ?? '' }}</td>
                    <td>{{ $serv->detalle_habitacion ?? '' }}</td>
                    <td>{{ $serv->tipo_habitacion ?? '' }}
                        @php
                            if ($serv->cantidad > 1){
                        @endphp
                               / {{ $serv->cantidad ?? '' }} días
                        @php
                            };
                        @endphp

                    </td>
                    <td colspan="2">{{ $serv->nombre_cliente ?? '' }}</td>
                    <td>{{ $serv->cedula_cliente ?? '' }}</td>
                    <td colspan="3">{{ $serv->direccion_cliente ?? '' }}</td>
                    <td>
                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $excdteNuevo)
                            @if ($excdteNuevo->servicio_id == $serv->id && $excdteNuevo->Estado == 'ExcedenteNuevo')
                            {{ ' '.$excdteNuevo->Divisa.': '.floatval($excdteNuevo->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $serv->id && $vtosPendtes->Estado == 'Pendiente')
                            @if ($vtosPendtes->Divisa <> 'Dolar')
                                    <b class="text-bold">{{ ' '.$vtosPendtes->Divisa.': '.number_format(floatval($vtosPendtes->MontoDivisa),2,',','.') ?? '' }}</b>
                                @else
                                    <b class="text-bold">{{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }}</b>
                                @endif
                                {{-- {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }} --}}
                            @endif

                            @endforeach
                        @endif

                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $serv->id && $vtosPagarOfic->Estado == 'PagarOficina')
                            <b class="text-red">{{ ' '.$vtosPagarOfic->Divisa.': '.floatval($vtosPagarOfic->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif
                    </td>
                </tr>
                    @php
                        $count ++;
                    @endphp
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
      <!-- /.col -->
    </div>
    @endif
    <!-- /.row -->
    @endcan

    @can('haveaccess', 'cajadatosventas.show')
    @if (count($cajas->ventas) > 0)
    <!-- Table row -->
    <div class="row">
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="panel panel-primary">
      <div class="col-xs-12 table-responsive">

        <h4><strong>Datos de Ventas</strong></h4>
        <div class="box-header with-border bg-danger">
            {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

            <div class="box-header with-border bg-danger">
                {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

                <div class="row">
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->TotalSumaTotalConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumoFinal - $cajas->TotalSumaTotalVueltosFinalConsumo - $cajas->TotalSumaTotalExcedenteFinalConsumo - $cajas->TotalSumaTotalPagarOficinaFinalConsumo)),2,',','.') ?? ''}}</span>
                            <h5 class="description-header">______________</h5>

                            <h5 class="box-title text-bold">Caja/Anterior:</h5><br>
                            <h5 class="box-title text-bold">Pagados:</h5>
                            <h5 class="description-header text-red">-Vueltos:</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/caja:</h5>
                            <h5 class="description-header text-red">-Excedente:</h5>
                            <h5 class="description-header text-bold">-Pagar Por Oficina:</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/Ventas:</h5>

                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage  box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalDolarConsFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumo - $cajas->SumaTotalDolarVueltosFinalDolarConsumo - $cajas->SumaTotalDolarExcedenteFinalDolarConsumo - $cajas->SumaTotalDolarPagarOficinaFinalDolarConsumo)),2,',','.') ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaConsumo),2,',','.') ?? ' 0,00' }}</h5><br>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarConsFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaConsumo - $cajas->SumaTotalDolarVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarExcedenteFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaConsumo - $cajas->SumaTotalDolarVueltosFinalConsumo - $cajas->SumaTotalDolarExcedenteFinalConsumo - $cajas->SumaTotalDolarPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>

                            <br>
                            <span class="description-text">DOLAR</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPesoConsFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarConsumo - $cajas->SumaTotalPesoVueltosFinalDolarConsumo - $cajas->SumaTotalPesoExcedenteFinalDolarConsumo - $cajas->SumaTotalPesoPagarOficinaFinalDolarConsumo)),2,',','.')  ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaConsumol),2,',','.') ?? ' 0,00' }}</h5><br>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoConsFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaConsumo - $cajas->SumaTotalPesoVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoExcedenteFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaConsumo - $cajas->SumaTotalPesoVueltosFinalConsumo - $cajas->SumaTotalPesoExcedenteFinalConsumo - $cajas->SumaTotalPesoPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PESO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPuntoConsFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarConsumo - $cajas->SumaTotalPuntoVueltosFinalDolarConsumo - $cajas->SumaTotalPuntoExcedenteFinalDolarConsumo - $cajas->SumaTotalPuntoPagarOficinaFinalDolarConsumo)),2,',','.')  ?? ''}}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaConsumo),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoConsFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaConsumo - $cajas->SumaTotalPuntoVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoExcedenteFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaConsumo - $cajas->SumaTotalPuntoVueltosFinalConsumo - $cajas->SumaTotalPuntoExcedenteFinalConsumo - $cajas->SumaTotalPuntoPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PUNTO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalTransferenciaConsFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarConsumo - $cajas->SumaTotalTransferenciaVueltosFinalDolarConsumo - $cajas->SumaTotalTransferenciaExcedenteFinalDolarConsumo - $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo)),2,',','.') ?? '' }}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaConsumo),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaConsFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaConsumo - $cajas->SumaTotalTransferenciaVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaExcedenteFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaConsumo - $cajas->SumaTotalTransferenciaVueltosFinalConsumo - $cajas->SumaTotalTransferenciaExcedenteFinalConsumo - $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">TRANS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalBolivarConsFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarConsumo - $cajas->SumaTotalBolivarVueltosFinalDolarConsumo - $cajas->SumaTotalBolivarExcedenteFinalDolarConsumo - $cajas->SumaTotalBolivarPagarOficinaFinalDolarConsumo)),2,',','.') ?? '' }} </span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaConsumo),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarConsFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaConsumo - $cajas->SumaTotalBolivarVueltosFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarExcedenteFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarConsFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaConsumo - $cajas->SumaTotalBolivarVueltosFinalConsumo - $cajas->SumaTotalBolivarExcedenteFinalConsumo - $cajas->SumaTotalBolivarPagarOficinaFinalConsumo),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">EFECTIVO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <span class="description-text">OBSERVACIONES:</span>
                        <div>
                            {{$cajas->Observaciones}}
                        </div>

                    </div>
                </div> -->


            </div>
            <!-- <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <span class="description-text">OBSERVACIONES:</span>
                    <div>
                        {{$cajas->Observaciones}}
                    </div>

                </div>
            </div> -->


        </div>
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color: rgb(35, 192, 245);" class="tituloAzul">
                <th>ID</th>
                <th>Fecha</th>
                <th>Num Servicio</th>
                <th>Tasa</th>
                <th>Modo Pago</th>
                <th>Tipo Pago</th>
                <th>N° punto/trans</th>
                @can('haveaccess', 'cajacosto.show')
                <th>Precio Costo</th>
                <th>% Ganancia</th>
                @endcan

                <th>Precio Venta</th>

                @can('haveaccess', 'cajacosto.show')
                <th>Utilidad</th>
                @endcan
                <th>Estado</th>

            </thead>
            <tbody>
                @foreach ($cajas->ventas as $venta)
                    {{-- @if ($venta->estado == 'Cancelada' || $venta->tipo_pago == 'No pagado')
                        <tr style="background-color: red;" class="text-black tituloRojo">
                    @else
                        <tr style="background-color: lightblue;" class="text-black subTituloAzul">
                    @endif --}}

                    @if ($venta->estado == 'Cancelada' || $venta->modo_pago == 'Cortesía')
                        <tr   style="background-color: #ff0000;" class="text-black tituloRojo">
                    @elseif($venta->modo_pago == 'Crédito')
                        <tr style="background-color: rgb(36, 238, 10);" class="text-black tituloVerde">
                    @else
                    <tr style="background-color: #A9D0F5;" class="text-black tituloAzulCaja">
                    @endif
                    <td>{{ $venta->id ?? '' }}</td>
                    <td>{{ $venta->fecha_hora ?? '' }}</td>
                    <td>{{ $venta->servicio_id ?? '' }}</td>
                    <td>{{ $venta->tasaTransPunto ?? '' }}</td>
                    <td>{{ $venta->modo_pago ?? '' }}</td>
                    <td>{{ $venta->tipo_pago ?? '' }}</td>
                    <td>&nbsp;{{ $venta->num_Punto ?? '' }} &nbsp;{{ $venta->num_Trans ?? '' }}</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>
                        @if ($venta->modo_pago)
                        @foreach ($venta->pago_ventas as $pagoV)
                        {{ ' '.$pagoV->Divisa.': '.floatval($pagoV->MontoDivisa) ?? '' }}
                        @endforeach
                        @endif
                        {{-- @if ($venta->pago_con_excedente)
                            <b class="text-red">{{' Excedente: '. floatval($venta->pago_con_excedente) ?? '' }}</b>
                            @endif --}}
                        </td>
                        @endcan

                    <td>
                        @if ($venta->modo_pago)
                            @foreach ($cajas->pago_vueltos as $pagovuts)
                            @if ($pagovuts->servicio_id == $venta->servicio_id && $pagovuts->Tipo == 'Consumo' && $pagovuts->venta_id == $venta->id)
                                {{ ' '.$pagovuts->Divisa.': '.floatval($pagovuts->MontoDivisa) ?? '0.00' }}
                            @endif
                            @endforeach
                        @endif
                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosDevueltos)
                            @if ($vtosDevueltos->servicio_id == $venta->servicio_id && $vtosDevueltos->Estado == 'Devueltos' && $vtosDevueltos->Tipo == 'Consumo' && $vtosDevueltos->venta_id == $venta->id)
                                @if ($vtosDevueltos->Divisa <> 'Dolar')
                                    <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.number_format($vtosDevueltos->MontoDivisa,2) ?? '' }}</b>
                                @else
                                    <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.floatval($vtosDevueltos->MontoDivisa) ?? '' }}</b>
                                @endif

                            @endif

                            @endforeach
                        @endif
                    </td>
                    <td>{{ floatval($venta->total_venta) ?? '' }}</td>

                    @can('haveaccess', 'cajacosto.show')
                    <td>

                        @if ($venta->modo_pago)
                            @foreach ($cajas->pago_ventas as $pagoventas)
                            @if ($pagoventas->servicio_id == $venta->servicio_id && $pagoventas->venta_id == $venta->id)
                                @if (floatval($pagoventas->MontoDolarConsumo * $pagoventas->TasaTiket) > 0)
                                    {{ ' '.$pagoventas->Divisa.': '.floatval($pagoventas->MontoDolarConsumo * $pagoventas->TasaTiket) ?? '0.00' }}
                                @endif

                            @endif
                            @endforeach
                        @endif
                    </td>
                    @endcan
                    <td>
                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $venta->servicio_id && $vtosPendtes->Estado == 'Pendiente' && $vtosPendtes->Tipo == 'Consumo' && $vtosPendtes->venta_id == $venta->id)
                            @if ($vtosPendtes->Divisa <> 'Dolar')
                                    <b class="text-bold">{{ ' '.$vtosPendtes->Divisa.': '.number_format(floatval($vtosPendtes->MontoDivisa),2,',','.') ?? '' }}</b>
                                @else
                                    <b class="text-bold">{{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }}</b>
                                @endif
                                {{-- {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }} --}}
                            @endif

                            @endforeach
                        @endif

                        @if ($cajas->excedente_actual_valor)
                            @foreach ($cajas->excedente_actual_valor as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $venta->id && $vtosPagarOfic->Estado == 'PagarOficina')
                            <b class="text-red">{{ ' '.$vtosPagarOfic->Divisa.': '.floatval($vtosPagarOfic->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif
                    </td>

                </tr>

                @endforeach
            </tbody>
        </table>
      </div>
    </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    @endif
    @endcan

    @can('haveaccess', 'cajadatosarticulos.show')
    @if (count($cajas->articulo_ventas) > 0)
    <div class="row">
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="panel panel-primary">
            <div class="col-xs-12 table-responsive">

                <h4><strong>Datos de Articulos</strong></h4>
                <table class="table table-striped table-bordered table-condensed table-hover">

                    <tbody>
                        @php
                            $count = 0;
                            $actual = 0;

                        @endphp

                        @foreach ($cajas->articulo_ventas  as $art)
                        @if ($art->venta_id !== $actual)
                            @php
                            $actual = $art->venta_id;



                            @endphp
                            @if ($art->venta->estado == 'Cancelada' || $art->venta->tipo_pago == 'No pagado')
                                <tr style="background-color: red;" class="text-black tituloRojo">
                            @else
                                <tr style="background-color: lightblue;" class="text-black tituloAzul">
                            @endif

                            @if ($art->venta->estado == 'Cancelada' || $art->venta->modo_pago == 'Cortesía')
                                <tr   style="background-color: #ff0000;" class="text-black tituloRojo">
                            @elseif($art->venta->modo_pago == 'Crédito')
                                <tr style="background-color: rgb(36, 238, 10);" class="text-black tituloVerde">
                            @else
                            <tr style="background-color: #A9D0F5;" class="text-black tituloAzulCaja">
                            @endif
                                <td colspan="2"><b>ID Factura: </b> {{$actual}}</td><td colspan="2"> <b>Tipo pago: </b> {{$art->venta->tipo_pago}}
                                     @if ($art->venta->tipo_pago == 'Trans/Punto')
                                        @if ($art->venta->num_Punto !== null)<b> &nbsp; Nº Punto:</b>{{$art->venta->num_Punto}}@endif
                                        @if ($art->venta->num_Trans !== null)<b> &nbsp; Nº Trans:</b>{{$art->venta->num_Trans}}@endif
                                    @endif
                                    @if ($art->venta->tipo_pago == 'Mixto')
                                        @if ($art->venta->num_Punto !== null)<b>&nbsp; Nº Punto:</b>{{$art->venta->num_Punto}}@endif
                                        @if ($art->venta->num_Trans !== null)<b>&nbsp; Nº Trans:</b>{{$art->venta->num_Trans}}@endif
                                    @endif
                                </td><td colspan="2"> <b>Tasa: </b> {{$art->venta->tasaTransPunto}}
                                </td><td colspan="3"><b>Porcentaje: </b>
                                     @if ($art->venta->tipo_pago == 'Dolar')

                                        {{$art->venta->porDolar}} %
                                     @endif
                                     @if ($art->venta->tipo_pago == 'Peso')
                                             @php
                                             if($art->porEspecial && $art->venta->tipo_pago == 'Peso' && $art->isPeso == '1'){
                                                 $v = 1;
                                             }
                                             @endphp
                                        {{$art->venta->porPeso}} %
                                     @endif
                                     @if ($art->venta->tipo_pago == 'Trans/Punto')
                                             @php
                                             if($art->porEspecial && $art->venta->tipo_pago == 'Trans/Punto' && $art->isTransPunto == '1'){
                                                 $v = 1;
                                             }
                                             @endphp
                                        {{$art->venta->porTransPunto}} %
                                     @endif
                                     @if ($art->venta->tipo_pago == 'Mixto')
                                             @php
                                             if($art->porEspecial && $art->venta->tipo_pago == 'Mixto' && $art->isMixto == '1'){
                                                 $v = 1;
                                             }
                                             @endphp
                                        {{$art->venta->porMixto}} %
                                     @endif
                                     @if ($art->venta->tipo_pago == 'Efectivo')
                                             @php
                                             if($art->porEspecial && $art->venta->tipo_pago == 'Efectivo' && $art->isEfectivo == '1'){
                                                $v = 1;
                                             }
                                             @endphp
                                        {{$art->venta->porEfectivo}} %
                                     @endif

                                    </td>
                                    <td colspan="2"><b>Operacion: </b>{{$art->venta->estado}}</td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                @can('haveaccess', 'cajacosto.show')
                                <th>P/Costo</th>
                                <th>%/Espal</th>
                                @endcan
                                <th>Cant.</th>
                                @can('haveaccess', 'cajacosto.show')
                                <th>T/Costo.</th>
                                @endcan
                                <th>P/Venta</th>
                                <th>T/Venta.</th>
                                <th>Utilidad.</th>
                                <th>% Margen.</th>


                            </tr>

                        @endif

                        <tr>
                            <td>{{ $art->id ?? '' }}</td>
                            <th>{{ $cajas->nombreArticulos[$count]->codigo ?? '' }}</th>
                            <td>{{ $cajas->nombreArticulos[$count]->nombre ?? '' }}</td>
                            @can('haveaccess', 'cajacosto.show')
                            <td>{{ floatval($art->precio_costo_unidad) ?? '' }}</td>
                            <td>
                                @if ($art->porEspecial != null  && $art->isDolar == '1' && $art->venta->tipo_pago == 'Dolar')
                                    {{ $art->porEspecial ?? '-'}}
                                @elseif($art->porEspecial != null  && $art->isPeso == '1' && $art->venta->tipo_pago == 'Peso')
                                    {{ $art->porEspecial ?? '-'}}
                                @elseif($art->porEspecial != null  && $art->isTransPunto == '1' && $art->venta->tipo_pago == 'Trans/Punto')
                                    {{ $art->porEspecial ?? '-'}}
                                @elseif($art->porEspecial != null  && $art->isMixto == '1' && $art->venta->tipo_pago == 'Mixto')
                                    {{ $art->porEspecial ?? '-'}}
                                @elseif($art->porEspecial != null && $art->isEfectivo == '1' && $art->venta->tipo_pago == 'Efectivo')
                                    {{ $art->porEspecial ?? '-'}}
                                @endif
                            </td>
                            @endcan
                            <td>{{ $art->cantidad ?? '' }}</td>
                            @can('haveaccess', 'cajacosto.show')
                            <td>{{ floatval($art->cantidad * $art->precio_costo_unidad) ?? '' }}</td>
                            @endcan
                            <td>{{ floatval($art->precio_venta_unidad) ?? '' }}</td>
                            <td>{{ floatval(($art->cantidad * $art->precio_venta_unidad)) ?? '' }}</td>
                            <td>{{ number_format(floatval(($art->cantidad * $art->precio_venta_unidad) - ($art->cantidad * $art->precio_costo_unidad)),3,'.',',') ?? '' }}</td>
                            <td>{{ number_format(floatval(($art->cantidad * $art->precio_venta_unidad - $art->cantidad * $art->precio_costo_unidad)) / ($art->cantidad * $art->precio_venta_unidad),2,'.',',') ?? '' }}</td>


                        </tr>
                        @php
                            $count++;
                        @endphp
                        @endforeach
                    </tbody>
                    {{-- <tfoot>
                        <th></th>
                        <th></th>
                        <th>Totales:</th>
                        @can('haveaccess', 'cajacosto.show')
                        <th>P/Costo</th>
                        @endcan
                        <th>Cant.</th>
                        @can('haveaccess', 'cajacosto.show')
                        <th>T/Costo.</th>
                        @endcan
                        <th>P/Venta</th>
                        <th>T/Venta.</th>


                    </tfoot> --}}
                </table>
            </div>

        </div>
      <!-- /.col -->
    </div>
    @endif
    @endcan

    @can('haveaccess', 'cajadatosventas.show')
    @if (count($cajas->creditos_pagados) > 0)
    <!-- Table row -->
    <div class="row">
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="margin"></div>
        <div class="panel panel-primary">
      <div class="col-xs-12 table-responsive">

        <h4><strong>Creditos Pagados</strong></h4>
        <div class="box-header with-border bg-danger">
            {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

            <div class="box-header with-border bg-danger">
                {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

                <div class="row">
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->TotalSumaTotalCreditoFinal - $cajas->TotalSumaTotalVueltosFinalCredito - $cajas->TotalSumaTotalExcedenteFinalCredito - $cajas->TotalSumaTotalPagarOficinaFinalCredito)),2,',','.') ?? ''}}</span>
                            <h5 class="description-header">______________</h5>

                            <h5 class="box-title text-bold">Pagados:</h5>
                            <h5 class="description-header text-red">-Vueltos:</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/caja:</h5>
                            {{-- <h5 class="description-header text-red">-Excedente:</h5>
                            <h5 class="description-header text-bold">-Pagar Por Oficina:</h5> --}}
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/Creditos Pagados:</h5>

                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage  box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalDolarCreditoFinalDolar - $cajas->SumaTotalDolarVueltosFinalDolarCredito - $cajas->SumaTotalDolarExcedenteFinalDolarCredito - $cajas->SumaTotalDolarPagarOficinaFinalDolarCredito)),2,',','.') ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarCreditoFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarCreditoFinal - $cajas->SumaTotalDolarVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarExcedenteFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5> --}}
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarCreditoFinal - $cajas->SumaTotalDolarVueltosFinalCredito - $cajas->SumaTotalDolarExcedenteFinalCredito - $cajas->SumaTotalDolarPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>

                            <br>
                            <span class="description-text">DOLAR</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPesoCreditoFinalDolar - $cajas->SumaTotalPesoVueltosFinalDolarCredito - $cajas->SumaTotalPesoExcedenteFinalDolarCredito - $cajas->SumaTotalPesoPagarOficinaFinalDolarCredito)),2,',','.')  ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoCreditoFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoCreditoFinal - $cajas->SumaTotalPesoVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoExcedenteFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5> --}}
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoCreditoFinal - $cajas->SumaTotalPesoVueltosFinalCredito - $cajas->SumaTotalPesoExcedenteFinalCredito - $cajas->SumaTotalPesoPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PESO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPuntoCreditoFinalDolar - $cajas->SumaTotalPuntoVueltosFinalDolarCredito - $cajas->SumaTotalPuntoExcedenteFinalDolarCredito - $cajas->SumaTotalPuntoPagarOficinaFinalDolarCredito)),2,',','.')  ?? ''}}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoCreditoFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                {{-- <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoCreditoFinal - $cajas->SumaTotalPuntoVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoExcedenteFinalCredito),2,',','.') ?? ' 0,00' }}</h5> --}}
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoCreditoFinal - $cajas->SumaTotalPuntoVueltosFinalCredito - $cajas->SumaTotalPuntoExcedenteFinalCredito - $cajas->SumaTotalPuntoPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PUNTO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalTransferenciaCreditoFinalDolar - $cajas->SumaTotalTransferenciaVueltosFinalDolarCredito - $cajas->SumaTotalTransferenciaExcedenteFinalDolarCredito - $cajas->SumaTotalTransferenciaPagarOficinaFinalCredito)),2,',','.') ?? '' }}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaCreditoFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaCreditoFinal - $cajas->SumaTotalTransferenciaVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaExcedenteFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5> --}}
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaCreditoFinal - $cajas->SumaTotalTransferenciaVueltosFinalCredito - $cajas->SumaTotalTransferenciaExcedenteFinalCredito - $cajas->SumaTotalTransferenciaPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">TRANS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalBolivarCreditoFinalDolar - $cajas->SumaTotalBolivarVueltosFinalDolarCredito - $cajas->SumaTotalBolivarExcedenteFinalDolarCredito - $cajas->SumaTotalBolivarPagarOficinaFinalDolarCredito)),2,',','.') ?? '' }} </span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarCreditoFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarCreditoFinal - $cajas->SumaTotalBolivarVueltosFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                {{-- <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarExcedenteFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5> --}}
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarCreditoFinal - $cajas->SumaTotalBolivarVueltosFinalCredito - $cajas->SumaTotalBolivarExcedenteFinalCredito - $cajas->SumaTotalBolivarPagarOficinaFinalCredito),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">EFECTIVO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <span class="description-text">OBSERVACIONES:</span>
                        <div>
                            {{$cajas->Observaciones}}
                        </div>

                    </div>
                </div> -->


            </div>
            <!-- <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <span class="description-text">OBSERVACIONES:</span>
                    <div>
                        {{$cajas->Observaciones}}
                    </div>

                </div>
            </div> -->


        </div>
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color: #A9D0F5;" class="tituloAzul">
                <th>ID</th>
                <th>Cliente</th>
                {{-- <th>Operador</th> --}}
                <th>Pagado en</th>
                <th>N° de Factura</th>
                <th>Tipo de Operacion</th>
                <th>Monto</th>
                {{-- <th>Fecha Pago</th> --}}
                {{-- <th>Estado al pagar</th> --}}
                <th>D/Pago</th>
                <th>D/Vueltos</th>


            </thead>
            <tbody>
                @php
                    $pagoEn = '';
                @endphp

                @foreach ($cajas->creditos_pagados as $creditosPagados)
                    {{-- @if ($venta->estado == 'Cancelada' || $venta->tipo_pago == 'No pagado')
                        <tr style="background-color: red;" class="text-black tituloRojo">
                    @else
                        <tr style="background-color: lightblue;" class="text-black subTituloAzul">
                    @endif --}}


                    @if ($creditosPagados->user_id == $caja->user->id)
                        @php
                            $pagoEn = 'Caja';
                        @endphp
                        <tr>
                    @else
                        @php
                            $pagoEn = 'Oficina';
                        @endphp
                        <tr style="background-color: rgb(247, 188, 188);" class="text-black tituloRojo">
                    @endif

                    <td>{{ $creditosPagados->id ?? '' }}</td>
                    <td>
                        <?php $clientePago = "App\Persona"::where('id',$creditosPagados->persona_id)->first(); ?>
                        {{ $clientePago->nombre ?? '' }}
                    </td>
                    {{-- <td>
                        <?php $operadorRecive = "App\User"::where('id',$creditosPagados->user_id)->first(); ?>
                        {{ $operadorRecive->name ?? '' }}
                    </td> --}}
                    <td>{{ $pagoEn ?? '' }}</td>
                    <td>{{ $creditosPagados->numero_factura ?? '' }}</td>
                    <td>{{ $creditosPagados->tipo_operacion ?? '' }}</td>
                    <td>{{ $creditosPagados->monto ?? '' }}</td>
                    {{-- <td>{{ $creditosPagados->fecha_pago ?? '' }}</td> --}}
                    {{-- <td>{{ $creditosPagados->estado_credito_al_pagar ?? '' }}</td> --}}
                    <td>
                        @if ($creditosPagados->tipo_pago)
                        @php
                                $pago_creditos = "App\Pago_Credito"::where('detalle__creditos__pagado_id', $creditosPagados->detalle__creditos__pagado_id)->get();
                                @endphp
                            @if (count($pago_creditos))
                            @foreach ($pago_creditos as $pagoCrt)
                            {{ ' '.$pagoCrt->Divisa.': '.floatval($pagoCrt->MontoDivisa) ?? '' }} / {{ ' Tasa: '.floatval($pagoCrt->TasaTiket) ?? '' }}
                            @endforeach
                            @endif

                            @endif

                        </td>
                        <td>
                            @if ($creditosPagados->tipo_pago)
                            {{-- {{$creditosPagados->detalle__creditos__pagado_id}} --}}
                            @foreach ($cajas->pago_vueltos_credito as $pagoCreditos)
                            @if ($pagoCreditos->servicio_id == 1 && $pagoCreditos->detalle__creditos__pagado_id == $creditosPagados->detalle__creditos__pagado_id)
                                @if (floatval($pagoCreditos->MontoDivisa) > 0)
                                    {{ ' '.$pagoCreditos->Divisa.': '.floatval($pagoCreditos->MontoDivisa) ?? '0.00' }}
                                @endif

                            @endif
                            @endforeach
                        @endif
                        </td>


                </tr>

                @endforeach
            </tbody>
        </table>
      </div>
    </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    @endif
    @endcan
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
<br>
    @can('haveaccess', 'cajadatosventas.show')
    @if (count($cajas->pago_extras) > 0)
    <!-- Table row -->
    <div class="row">
        <div class="panel panel-primary">
      <div class="col-xs-12 table-responsive">


            <h4><strong>Datos de Pagos Extras</strong></h4>
        <div class="box-header with-border bg-danger">
            {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

            <div class="box-header with-border bg-danger">
                {{-- <h3 class="box-title text-bold text-blue">Montos Recibidos </h3> --}}

                <div class="row">
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->TotalSumaTotalHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtrasFinal - $cajas->TotalSumaTotalVueltosFinalHorasExtras - $cajas->TotalSumaTotalExcedenteFinalHorasExtras - $cajas->TotalSumaTotalPagarOficinaFinalHorasExtras)),2,',','.') ?? ''}}</span>
                            <h5 class="description-header">______________</h5>

                            <h5 class="box-title text-bold">Caja/Anterior:</h5><br>
                            <h5 class="box-title text-bold">Pagados:</h5>
                            <h5 class="description-header text-red">-Vueltos:</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/caja:</h5>
                            <h5 class="description-header text-red">-Excedente:</h5>
                            <h5 class="description-header text-bold">-Pagar Por Oficina:</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">Total/Pagos Extras:</h5>

                        </div>
                    </div>
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage  box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalDolarHorasExtrasFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtras - $cajas->SumaTotalDolarVueltosFinalDolarHorasExtras - $cajas->SumaTotalDolarExcedenteFinalDolarHorasExtras - $cajas->SumaTotalDolarPagarOficinaFinalDolarHorasExtras)),2,',','.') ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaHorasExtras),2,',','.') ?? ' 0,00' }}</h5><br>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarHorasExtrasFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaHorasExtras - $cajas->SumaTotalDolarVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarExcedenteFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalDolarPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalDolarHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaHorasExtras - $cajas->SumaTotalDolarVueltosFinalHorasExtras - $cajas->SumaTotalDolarExcedenteFinalHorasExtras - $cajas->SumaTotalDolarPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>

                            <br>
                            <span class="description-text">DOLAR</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPesoHorasExtrasFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarHorasExtras - $cajas->SumaTotalPesoVueltosFinalDolarHorasExtras - $cajas->SumaTotalPesoExcedenteFinalDolarHorasExtras - $cajas->SumaTotalPesoPagarOficinaFinalDolarHorasExtras)),2,',','.')  ?? ''}}</span>

                                <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaHorasExtras),2,',','.') ?? ' 0,00' }}</h5><br>
                            <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoHorasExtrasFinal),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaHorasExtras - $cajas->SumaTotalPesoVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoExcedenteFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPesoPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPesoHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaHorasExtras - $cajas->SumaTotalPesoVueltosFinalHorasExtras - $cajas->SumaTotalPesoExcedenteFinalHorasExtras - $cajas->SumaTotalPesoPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PESO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalPuntoHorasExtrasFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarHorasExtras - $cajas->SumaTotalPuntoVueltosFinalDolarHorasExtras - $cajas->SumaTotalPuntoExcedenteFinalDolarHorasExtras - $cajas->SumaTotalPuntoPagarOficinaFinalDolarHorasExtras)),2,',','.')  ?? ''}}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaHorasExtras),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoHorasExtrasFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaHorasExtras - $cajas->SumaTotalPuntoVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoExcedenteFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalPuntoHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaHorasExtras - $cajas->SumaTotalPuntoVueltosFinalHorasExtras - $cajas->SumaTotalPuntoExcedenteFinalHorasExtras - $cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">PUNTO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block border-right">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalTransferenciaHorasExtrasFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarHorasExtras - $cajas->SumaTotalTransferenciaVueltosFinalDolarHorasExtras - $cajas->SumaTotalTransferenciaExcedenteFinalDolarHorasExtras - $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras)),2,',','.') ?? '' }}</span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaHorasExtras),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaHorasExtrasFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaHorasExtras - $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaHorasExtras - $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras - $cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras - $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">TRANS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->

                    <div class="col-sm-2 col-xs-6">
                        <div class="description-block">
                            <span class="description-percentage box-title text-bold text-blue"><i
                                    class="fa fa-dollar"></i>
                                {{ number_format(floatval(($cajas->SumaTotalBolivarHorasExtrasFinalDolar + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarHorasExtras - $cajas->SumaTotalBolivarVueltosFinalDolarHorasExtras - $cajas->SumaTotalBolivarExcedenteFinalDolarHorasExtras - $cajas->SumaTotalBolivarPagarOficinaFinalDolarHorasExtras)),2,',','.') ?? '' }} </span>
                                <h5 class="description-header">______________</h5>

                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaHorasExtras),2,',','.') ?? ' 0,00' }}</h5><br>
                                <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarHorasExtrasFinal),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header">______________</h5>
                                <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaHorasExtras - $cajas->SumaTotalBolivarVueltosFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarExcedenteFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                                <h5 class="description-header text-bold">$. {{ number_format(floatval($cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <h5 class="description-header">______________</h5>
                            <h5 class="box-title text-bold text-blue">$. {{ number_format(floatval($cajas->SumaTotalBolivarHorasExtrasFinal + $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaHorasExtras - $cajas->SumaTotalBolivarVueltosFinalHorasExtras - $cajas->SumaTotalBolivarExcedenteFinalHorasExtras - $cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras),2,',','.') ?? ' 0,00' }}</h5>
                            <br>
                                <span class="description-text">EFECTIVO</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <span class="description-text">OBSERVACIONES:</span>
                        <div>
                            {{$cajas->Observaciones}}
                        </div>

                    </div>
                </div> -->


            </div>
            <!-- <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <span class="description-text">OBSERVACIONES:</span>
                    <div>
                        {{$cajas->Observaciones}}
                    </div>

                </div>
            </div> -->


        </div>

        <table class="table table-striped table-bordered table-condensed table-hover">

                @php
                    //creamos esta variable para ir contando los valores donde el tipo de pago sea cambio
                    //para luego restarselo a las cortesias ya que el valor que cuenta en las cortesias
                    //es cuando el tipo de pago es exonerado.

                    $restarAcortesia = 0;
                    $count = 1;
                @endphp

                @foreach ($cajas->horas_extras as $horasEx)

                @if ($horasEx->tipo_pago == 'cambio')
                @php
                    $restarAcortesia ++;

                @endphp
                @endif
                @if ($horasEx->modo_pago == 'Cortesia')
                <tr   style="background-color: #ff0000;" class="text-black tituloRojo">
                @elseif($horasEx->modo_pago == 'Credito')
                    <tr style="background-color: rgb(36, 238, 10);" class="text-black tituloVerde">
                @else
                    <tr style="background-color: rgb(35, 192, 245);" class="text-black tituloAzul">
                @endif


                    <td>ID</td>
                    <td>Fecha Registro</td>
                    <td>Comprobante</td>
                    <td>Tasa</td>
                    <td>Modo Pago</td>
                    <td>Tipo Pago</td>
                    <td>N° punto/trans</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>Precio</td>
                    <td>M/Dejado</td>
                    <td>D/Pago</td>
                    <td>D/Vueltos - <b class="text-red">Vtos/Devueltos</b></td>
                    @endcan


                    </tr>
                <tbody>
                    @if ($horasEx->modo_pago == 'Cortesia')
                        <tr style="background-color: rgb(247, 191, 191);" class="text-black detalleRojo">
                    @elseif($horasEx->modo_pago == 'Credito')
                        <tr style="background-color: rgba(198, 250, 191, 0.692);" class="text-black detalleVerde">
                    @else
                        <tr style="background-color: rgba(174, 221, 236, 0.555);" class="text-black detalleAzul">
                    @endif
                    <td
                    @if ($horasEx->status_servicio == 'Iniciado')
                    style="background-color: red;"
                    @endif
                    >{{ $count ?? '' }}</td>
                    <td>{{ $horasEx->created_at ?? '' }}</td>
                    <td>{{ $horasEx->num_servicio ?? '' }}</td>
                    <td>{{ $horasEx->tasaTransPunto ?? '' }}</td>
                    <td>{{ $horasEx->modo_pago ?? '' }}</td>
                    <td>{{ $horasEx->tipo_pago ?? '' }}</td>
                    <td>&nbsp;{{ $horasEx->num_Punto ?? '' }} &nbsp;{{ $horasEx->num_Trans ?? '' }}</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>{{ '$. '.floatval($horasEx->total_horas_extras_otros_montos) ?? '' }}</td>
                    <td>{{ '$. '.floatval($horasEx->dinero_dejado) ?? '' }}</td>
                    <td>
                        @if ($horasEx->modo_pago)
                            @foreach ($horasEx->pagos_extras as $pagoExtr)
                            {{ ' '.$pagoExtr->Divisa.': '.floatval($pagoExtr->MontoDivisa) ?? '' }}
                            @endforeach
                        @endif
                        @if ($horasEx->pago_con_excedente)
                            <b class="text-red">{{' Excedente: '. floatval($horasEx->pago_con_excedente) ?? '' }}</b>
                        @endif
                        {{-- {{ $serv->pago_servicios ?? '' }} --}}
                    </td>
                    <td>
                        {{-- @if ($serv->modo_pago)
                            @php
                                $vueltos_pagados = "App\Pago_Vuelto"::where('servicio_id', $serv->id)->get();
                            @endphp
                            @if (count($pago_creditos))
                            @foreach ($pago_creditos as $pagoCrt)
                            {{ ' '.$pagoCrt->Divisa.': '.floatval($pagoCrt->MontoDivisa) ?? '' }}
                            @endforeach
                            @endif

                        @endif --}}

                        {{-- @if ($horasEx->modo_pago)
                            @foreach ($cajas->pago_vueltos as $pagovuts)
                            @if ($pagovuts->servicio_id == $horasEx->servicio_id && $pagovuts->Tipo == 'Horas_Extras')
                            {{ ' '.$pagovuts->Divisa.': '.floatval($pagovuts->MontoDivisa) ?? '0.00' }}
                            @endif
                            @endforeach
                        @endif --}}
                        @if ($horasEx->modo_pago)
                            @foreach ($horasEx->pagos_extras as $pagoExtr)
                            @if ($pagoExtr->Vueltos > 0)
                            {{ ' '.$pagoExtr->Divisa.': '.floatval($pagoExtr->Vueltos) ?? '' }}
                            @endif

                            @endforeach
                        @endif
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosDevueltos)
                            @if ($vtosDevueltos->servicio_id == $horasEx->servicio_id && $vtosDevueltos->Estado == 'Devueltos' && $vtosDevueltos->Tipo == 'Horas_Extras'  && $horasEx->id == $vtosDevueltos->horas_extra_id)
                            <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.floatval($vtosDevueltos->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif

                        {{-- {{ $serv->pago_servicios ?? '' }} --}}
                    </td>
                    @endcan



                </tr>


                @if ($horasEx->modo_pago == 'Cortesia')
                        <tr style="background-color: rgb(247, 139, 139);" class="text-black subTituloRojo">
                    @elseif($horasEx->modo_pago == 'Credito')
                        <tr style="background-color: rgba(154, 247, 141, 0.692);" class="text-black subTituloVerde">
                    @else
                        <tr style="background-color: rgba(126, 211, 240, 0.555);" class="text-black subTituloAzul">
                    @endif


                    <td>N° Hab</td>

                    <td>Tipo Operacion</td>
                    <td>Fecha Inicio</td>
                    <td>Fecha Cierre Sugerido</td>
                    <td>Fecha Cierre Real</td>
                    <td>Detalle</td>
                    <td>Cant.</td>
                    <td>Precio</td>
                    <td>Total</td>



                    <td>Nvo/Excedete</td>
                    <td>D/Vtos/Pendtes - <b class="text-red">Pagar/Oficina</b></td>
                </tr>

                @if ($horasEx->monto_total_hora_extra > 0)
                @if ($horasEx->modo_pago == 'Cortesia')
                        <tr style="background-color: rgb(247, 191, 191);" class="text-black detalleRojo">
                    @elseif($horasEx->modo_pago == 'Credito')
                        <tr style="background-color: rgba(198, 250, 191, 0.692);" class="text-black detalleVerde">
                    @else
                        <tr style="background-color: rgba(174, 221, 236, 0.555);" class="text-black detalleAzul">
                    @endif
                    <td>{{ $horasEx->nombre_habitacion ?? '' }}</td>

                    <td>Horas Extras</td>
                    <td>{{ $horasEx->fecha_hora_entrada ?? '' }}</td>
                    <td>{{ $horasEx->fecha_hora_salida_sugerida ?? '' }}</td>
                    <td>{{ $horasEx->fecha_hora_salida_real ?? '' }}
                    <td>Excedido por: {{ $horasEx->cantidad_hora_extra ?? '' }} horas extras.</td>
                    <td >{{ $horasEx->cantidad_hora_extra ?? '0' }}</td>
                    <td>{{ $horasEx->precio_hora_extra ?? '0.00' }}</td>
                    <td>{{ floatval($horasEx->monto_total_hora_extra) ?? '0.00' }}</td>
                    <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $excdteNuevo)
                            @if ($excdteNuevo->servicio_id == $horasEx->servicio_id && $excdteNuevo->Estado == 'ExcedenteNuevo')
                            {{ ' '.$excdteNuevo->Divisa.': '.floatval($excdteNuevo->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $horasEx->servicio_id && $vtosPendtes->Estado == 'Pendiente' && $vtosPendtes->Tipo == 'Horas_Extras')
                            {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif

                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $horasEx->servicio_id && $vtosPagarOfic->Estado == 'PagarOficina')
                            <b class="text-red">{{ ' '.$vtosPagarOfic->Divisa.': '.floatval($vtosPagarOfic->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif
                    </td>
                </tr>
                @endif
                @if ($horasEx->otros_montos > 0)
                @if ($horasEx->modo_pago == 'Cortesia')
                        <tr style="background-color: rgb(247, 191, 191);" class="text-black detalleRojo">
                    @elseif($horasEx->modo_pago == 'Credito')
                        <tr style="background-color: rgba(198, 250, 191, 0.692);" class="text-black detalleVerde">
                    @else
                        <tr style="background-color: rgba(174, 221, 236, 0.555);" class="text-black detalleAzul">
                    @endif
                    <td>{{ $horasEx->nombre_habitacion ?? '' }}</td>

                    <td>Otros Montos</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $horasEx->detalle_otros_montos ?? '' }}</td>
                    <td ></td>
                    <td>{{ $horasEx->otros_montos ?? '' }}</td>
                    <td>{{ $horasEx->otros_montos ?? '' }}</td>
                        @if ($horasEx->monto_total_hora_extra > 0)
                    <td>

                    </td>
                    <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $horasEx->servicio_id && $vtosPendtes->Estado == 'Pendiente' && $vtosPendtes->Tipo == 'Horas_Extras')
                            {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif

                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $horasEx->servicio_id && $vtosPagarOfic->Estado == 'PagarOficina')
                            <b class="text-red">{{ ' '.$vtosPagarOfic->Divisa.': '.floatval($vtosPagarOfic->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif
                    </td>
                     @else
                     <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $excdteNuevo)
                            @if ($excdteNuevo->servicio_id == $horasEx->servicio_id && $excdteNuevo->Estado == 'ExcedenteNuevo')
                            {{ ' '.$excdteNuevo->Divisa.': '.floatval($excdteNuevo->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $horasEx->servicio_id && $vtosPendtes->Estado == 'Pendiente' && $vtosPendtes->Tipo == 'Horas_Extras')

                            @if ($vtosPendtes->MontoDivisa > 0)
                                @if ($vtosPendtes->MontoDivisa - ($pagoExtr->MontoDivisa - $horasEx->dinero_dejado ) > 0)
                                {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa - ($pagoExtr->MontoDivisa - $horasEx->dinero_dejado )) ?? '' }}
                                @endif

                            @endif
                            {{-- {{ floatval($pagoExtr->MontoDivisa - $horasEx->dinero_dejado ) ?? '' }} --}}

                            @endif

                            @endforeach
                        @endif


                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $horasEx->servicio_id && $vtosPagarOfic->Estado == 'PagarOficina')
                            <b class="text-red">{{ ' '.$vtosPagarOfic->Divisa.': '.floatval($vtosPagarOfic->MontoDivisa) ?? '' }}</b>
                            @endif

                            @endforeach
                        @endif
                    </td>
                    @endif
                </tr>
                @endif
                    @php
                        $count ++;
                    @endphp
                @endforeach
            </tbody>
        </table>
      </div>
    </div>
      <!-- /.col -->
    </div>
    @endif
    <!-- /.row -->
    @endcan

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

    function printDiv(nombreDiv) {
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;

     document.body.innerHTML = contenido;

     window.print();

     document.body.innerHTML = contenidoOriginal;
}

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
<script>


var tsri      = $("#total_sistema_reg_input").val();
$("#total_sistema_reg").html(numDecimal(tsri));
    /* Sumar dos números. */
    function sumar() {
            // alert('suma');
                var total_suma = 0;
                $(".monto").each(function() {
                    if (isNaN(parseFloat($(this).val()))) {
                        total_suma += 0;
                    } else {
                        total_suma += parseFloat($(this).val());
                    }
                });
                // alert(total_suma);
                document.getElementById('total_operador_reg').innerHTML = numDecimal(total_suma);
                $("#total_operador_reg_input").val(total_suma);

            }

            function numDecimal(valor){
                let result = Number(valor).toFixed(2);

                return result;
            }

     /* Restar dos números. */
     function resta() {

                let reg_sistama = $("#total_sistema_reg_input").val();
                let reg_operador = $("#total_operador_reg_input").val();
                let result = reg_operador - reg_sistama;

                $("#total_dif_input").val(numDecimal(result));
                $("#total_dif").html(numDecimal(result));

                // if (result <= 0) {
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
                // if (result > 0) {
                //     // alert('soy mayor');
                //     RestaTotal.classList.remove('text-danger');
                //     RestaTotal.classList.add('text-primary');
                //     r.classList.remove('text-danger');
                //     r.classList.add('text-primary');

                //     PagoTtotal.classList.remove('text-success');
                //     tap.classList.remove('text-success');

                //     $("#r").html("RESTA");
                //     $("#tap").html("TOTAL A PAGAR");
                //     $("#guardar").hide("linear");
                // }


            }
    $(document).ready(function() {

        DMontoDolarRep();
        DMontoPesoRep();
        DMontoBolivarRep();
        DMontoPuntoRep();
        DMontoTransRep();

            function DMontoDolarRep(){

                    Mdolar      = $("#cantidad_dolar_rep").val();
                    MdolarSistema       = $("#dolar_sistema").val();

                    Tdolar      = $("#TasaDolar").val();

                    Tpeso       = $("#TasaPeso").val();
                    Tbolivar    = $("#TasaBolivar").val();
                    Tpunto      = $("#TasaPunto").val();
                    Ttrans      = $("#TasaTrans").val();

                    TdolarToDolarSis = MdolarSistema / Tdolar;

                    DsupTotal = Mdolar * Tdolar;

                    DsupTotalDolar = DsupTotal * Tdolar;
                    $("#dif_moneda_dolar_to_tasa_input").val(numDecimal(DsupTotalDolar));
                    $("#dif_moneda_dolar_to_tasa").html(numDecimal(DsupTotalDolar - MdolarSistema));
                    $("#total_dolar_dif").val(numDecimal(DsupTotalDolar - MdolarSistema));
                    $("#total_dolar").val(DsupTotalDolar);
                    $("#dif_moneda_dolar_to_dolar_input").val(numDecimal(DsupTotal));
                    $("#dif_moneda_dolar_to_dolar").html(numDecimal(DsupTotal - TdolarToDolarSis));

                    sumar();

                    resta();

                }

                function DMontoPesoRep(){

                    Mpeso       = $("#cantidad_peso_rep").val();
                    MpesoSistema       = $("#peso_sistema").val();
                    Tdolar      = $("#TasaDolar").val();
                    Tpeso       = $("#TasaPeso").val();
                    Tbolivar    = $("#TasaBolivar").val();
                    Tpunto      = $("#TasaPunto").val();
                    Ttrans      = $("#TasaTrans").val();

                    TpesoToDolarSis = MpesoSistema / Tpeso;

                    PsupTotal = Mpeso / Tpeso;
                    PsupTotalDolar = PsupTotal * Tpeso;
                    $("#dif_moneda_peso_to_tasa_input").val(numDecimal(PsupTotalDolar));
                    $("#dif_moneda_peso_to_tasa").html(numDecimal(PsupTotalDolar - MpesoSistema));
                    $("#total_peso_dif").val(numDecimal(PsupTotalDolar - MpesoSistema));
                    $("#total_peso").val(numDecimal(PsupTotalDolar));
                    $("#dif_moneda_peso_to_dolar_input").val(numDecimal(PsupTotal));
                    $("#dif_moneda_peso_to_dolar").html(numDecimal(PsupTotal - TpesoToDolarSis));
                    sumar();
                    resta();

                }

                function DMontoBolivarRep(){
                    Mbolivar = $("#cantidad_efectivo_rep").val();
                    MbolivarSistema       = $("#efectivo_sistema").val();
                    Tdolar   = $("#TasaDolar").val();
                    Tpeso    = $("#TasaPeso").val();
                    Tbolivar = $("#TasaBolivar").val();
                    Tpunto   = $("#TasaPunto").val();
                    Ttrans   = $("#TasaTrans").val();

                    TbolivarToDolarSis = MbolivarSistema / Tbolivar;


                    BsupTotal = Mbolivar / Tbolivar;
                    BsupTotalDolar = BsupTotal * Tbolivar;
                    $("#dif_moneda_efectivo_to_tasa_input").val(numDecimal(BsupTotalDolar));
                    $("#dif_moneda_efectivo_to_tasa").html(numDecimal(BsupTotalDolar - MbolivarSistema));
                    $("#total_bolivar_dif").val(numDecimal(BsupTotalDolar - MbolivarSistema));
                    $("#total_bolivar").val(numDecimal(BsupTotalDolar));
                    $("#dif_moneda_efectivo_to_dolar_input").val(numDecimal(BsupTotal));
                    $("#dif_moneda_efectivo_to_dolar").html(numDecimal(BsupTotal - TbolivarToDolarSis));
                    sumar();
                    resta();

                }

                function DMontoPuntoRep(){
                    Mpunto = $("#cantidad_punto_rep").val();
                    MpuntoSistema       = $("#punto_sistema").val();
                    Tdolar   = $("#TasaDolar").val();
                    Tpeso    = $("#TasaPeso").val();
                    Tbolivar = $("#TasaBolivar").val();
                    Tpunto   = $("#TasaPunto").val();
                    Ttrans   = $("#TasaTrans").val();

                    TpuntoToDolarSis = MpuntoSistema / Tpunto;


                    PTsupTotal = Mpunto / Tbolivar;
                    PTsupTotalDolar = PTsupTotal * Tpunto;
                    $("#dif_moneda_punto_to_tasa_input").val(numDecimal(PTsupTotalDolar));
                    $("#dif_moneda_punto_to_tasa").html(numDecimal(PTsupTotalDolar - MpuntoSistema));
                    $("#total_punto_dif").val(numDecimal(PTsupTotalDolar - MpuntoSistema));
                    $("#total_punto").val(numDecimal(PTsupTotalDolar));
                    $("#dif_moneda_punto_to_dolar_input").val(numDecimal(PTsupTotal));
                    $("#dif_moneda_punto_to_dolar").html(numDecimal(PTsupTotal - TpuntoToDolarSis));
                    sumar();
                    resta();

                }

                function DMontoTransRep(){
                    Mtrans = $("#cantidad_trans_rep").val();
                    MtransSistema       = $("#trans_sistema").val();
                    Tdolar   = $("#TasaDolar").val();
                    Tpeso    = $("#TasaPeso").val();
                    Tbolivar = $("#TasaBolivar").val();
                    Tpunto   = $("#TasaPunto").val();
                    Ttrans   = $("#TasaTrans").val();

                    TtransToDolarSis = MtransSistema / Ttrans;


                    TsupTotal = Mtrans / Ttrans;
                    TsupTotalDolar = TsupTotal * Ttrans;
                    $("#dif_moneda_trans_to_tasa_input").val(numDecimal(TsupTotalDolar));
                    $("#dif_moneda_trans_to_tasa").html(numDecimal(TsupTotalDolar - MtransSistema));
                    $("#total_trans_dif").val(numDecimal(TsupTotalDolar - MtransSistema));
                    $("#total_trans").val(numDecimal(TsupTotalDolar));
                    $("#dif_moneda_trans_to_dolar_input").val(numDecimal(TsupTotal));
                    $("#dif_moneda_trans_to_dolar").html(numDecimal(TsupTotal - TtransToDolarSis));
                    sumar();
                    resta();

                }


            $("#cantidad_dolar_rep").keyup(function() {
                DMontoDolarRep();
            });

            $("#cantidad_peso_rep").keyup(function() {
                DMontoPesoRep();
            });

            $("#cantidad_efectivo_rep").keyup(function() {
                DMontoBolivarRep();
            });

            $("#cantidad_punto_rep").keyup(function() {
                DMontoPuntoRep();
            });

            $("#cantidad_trans_rep").keyup(function() {
                DMontoTransRep();
            });
    });
</script>
@endpush
@endsection
