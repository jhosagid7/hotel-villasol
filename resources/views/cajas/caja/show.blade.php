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
                $tr = '';
            @endphp
            @foreach ($cajas->creditos_pagados as $creditosPagados)

                @if ($creditosPagados->user_id == $caja->user->id)
                    @if ($creditosPagados->tipo_operacion == 'Consumo')
                        @php
                            $consumoCreditosPagadosPorCaja = $consumoCreditosPagadosPorCaja + $creditosPagados->monto;
                            $tr = '<tr';
                        @endphp
                    @endif
                    @if ($creditosPagados->tipo_operacion == 'Servicio')
                        @php
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
                        <th ><h4><strong class="text-blue">N° Caja:</strong> <strong>{{ $caja->codigo}}</strong></h4></th>
                        <th></th>
                        <th ><h4><strong class="text-blue">Operador:</strong> <strong>{{ $caja->user->name}}</strong></h4></th>
                        <th></th>
                        <th ><h4><strong class="text-blue">Fecha:</strong> <strong>{{ $caja->fecha->format('d-m-Y')}}</strong></h4></th>
                        <th></th>
                        <th ><h4><strong class="text-blue"> {{$cajas->SumaTotalCreditosPagadosTotalesPorCaja}}</strong></h4></th>
                        <th></th>
                        <th ><h4><strong class="text-blue">{{$cajas->SumaTotalCreditosPagadosTotalesPorOficina}}</strong></h4></th>
                        <th></th>

                    </tr>
                    @endcan

                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-blue">Consumo:</strong></h4></th>
                    <td></td>
                    <th class="text-blue"></th>
                    <td class="text-blue"></td>
                    <th><h4><strong class="text-blue">Servicios:</strong></h4></th>
                    <td></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-blue">Creditos:</h4></strong></th>
                    <td></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Contado:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadVentasContado ?? '0' }}</strong></td>
                    <th>Total/Dolar:</th>
                    <td><strong>${{ $cajas->SumaTotalDolar + $cajas->SumaTotalDolarCredConsumo + $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar ?? '0.000' }}</strong></td>
                    <th>Serv/Contado:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadServicios ?? '0' }}</strong></td>
                    <th>Total/Dolar:</th>
                    <td><strong>${{ $cajas->SumaTotalDolarServ + $cajas->SumaTotalDolarCredServicio + $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar ?? '0.000' }}</strong></td>
                    <th>Creditos/vigentes:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadCreditosVigentes ?? '0' }}</strong></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th>Cons/Credíto:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasCredito ?? '0' }}</b></td>
                    <th>Total/Peso:</th>
                    <td><b>${{ number_format($cajas->SumaTotalPeso + $cajas->SumaTotalPesoCredConsumo,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Serv/Credíto:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosPorPagar ?? '0' }}</b></td>
                    <th>Total/Peso:</th>
                    <td><b>${{ number_format($cajas->SumaTotalPesoServ + $cajas->SumaTotalPesoCredServicio + $cajas->SumaTotalServiciosExcedenteNuevoPeso,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Creditos/vencidos:</th>
                    <td><strong>{{ $cajas->SumaTotalCantidadCreditosVencidos ?? '0' }}</strong></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Cortesía:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasCortesia ?? '0' }}</b></td>
                    <th>Total/Punto:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalPunto + $cajas->SumaTotalPuntoCredConsumo,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Serv/Cortesía:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosCortesia ?? '0' }}</b></td>
                    <th>Total/Punto:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPuntoCredServicio + $cajas->SumaTotalServiciosExcedenteNuevoPunto,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Creditos/pagados:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosTotales ?? ''}}</td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Total/Consumo:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadVentasContado + $cajas->SumaTotalCantidadVentasCredito + $cajas->SumaTotalCantidadVentasCortesia ?? '0' }}</b></td>
                    <th>Total/Transf:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalTransferencia + $cajas->SumaTotalTransferenciaCredConsumo,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Total/Serv:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServicios + $cajas->SumaTotalCantidadServiciosPorPagar + $cajas->SumaTotalCantidadServiciosCortesia ?? ' 0,00' }}</b></td>
                    <th>Total/Transf:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferenciaCredServicio + $cajas->SumaTotalServiciosExcedenteNuevoTransferencia,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Creditos nuevos:</th>
                    <td><b>{{ $cajas->SumaTotalCantidadServiciosPorPagar + $cajas->SumaTotalCantidadVentasCredito ?? '0' }}</b></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th>Cons/Créd/Pag:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosConsumo ?? ''}}</td>
                    <th>Total/Bolivar:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalBolivar + $cajas->SumaTotalBolivarCredConsumo,2,'.',',') ?? '0.00' }}</b></td>
                    <th>Serv/Créd/Pag:</th>
                    <td>{{$cajas->SumaTotalCantidadCreditosPagadosServicio ?? ''}}</td>
                    <th>Total/Bolivar:</th>
                    <td><b>Bs.{{ number_format($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivarCredServicio + $cajas->SumaTotalServiciosExcedenteNuevoBolivar,2,'.',',') ?? '0.00' }}</b></td>
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
                    <td><h4><strong>${{ number_format($cajas->SumaTotalVentasPagadosConExcedente + $cajas->SumaTotalVentasCredito + $cajas->SumaTotalCreditosPagadosConsumoPorOficina + $cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar + $cajas->SumaTotalVentas,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td class="text-blue"></td>
                    <th><h4><strong class="text-blue">Servicios Brutoa:</strong></h4></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalServiciosPagadosConExcedente + $cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalCreditosPagadosServicioPorOficina + $cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar + $cajas->SumaTotalServicios,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-blue">Total Bruto:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalServiciosPagadosConExcedente + $cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalCreditosPagadosServicioPorOficina + $cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar + $cajas->SumaTotalServicios) + ($cajas->SumaTotalVentasPagadosConExcedente + $cajas->SumaTotalVentasCredito + $cajas->SumaTotalCreditosPagadosConsumoPorOficina + $cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar + $cajas->SumaTotalVentas),2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
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
                  @endcan

                  @can('haveaccess', 'cajatotalventa.show')
                  <tr>
                    <th><h4><strong class="text-danger">Cons/Créd/Nuevos:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalVentasCredito,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td class="text-blue"></td>
                    <th><h4><strong class="text-danger">Serv/Créd/Nuevos:</strong></h4></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPorPagar,2,'.',',') ?? '0.000' }}</h4></strong></td>
                    <th class="text-blue"></th>
                    <td></td>
                    <th><h4><strong class="text-danger">Total/Créd/Nuevos:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalServiciosPorPagar + $cajas->SumaTotalVentasCredito,2,'.',',') ?? '0.000' }}</h4></strong></td>
                  </tr>
                  @endcan

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-danger">Cons/Créd/Pagados/Oficina:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalCreditosPagadosConsumoPorOficina,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-danger">Serv/Créd/Pagados/Oficina:</h4></strong></th>
                    <td><h4><strong class="text-danger">${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorOficina,2,',','.') ?? '0.000' }}</h4></strong></td>
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
                    <td></td>
                    <th><h4><strong class="text-aqua">Serv/Créd/Pagados/Caja:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorCaja,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Créd/Pagados/Caja:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosTotalesPorCaja,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan
                  @can('haveaccess', 'cajatotalventa.show')
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
                  @endcan

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-aqua">Cons/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalVentas,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    {{-- - $cajas->SumaTotalServiciosExcedenteNuevo --}}
                    <th><h4><strong class="text-aqua">Serv/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalServicios,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-aqua">Total/Contado:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalVentas + $cajas->SumaTotalServicios),2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                  </tr>
                  @endcan

                  @can('haveaccess', 'cajautilidad.show')

                  <tr>
                    <th><h4><strong class="text-blue">Cons/Total/Recibido:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->SumaTotalConsumoExcedenteNuevo + $cajas->SumaTotalVentas,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-blue">Serv/Total/Recibido:</h4></strong></th>
                    <td><h4><strong>${{ number_format($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar + $cajas->SumaTotalServicios,2,',','.') ?? '0.000' }}</h4></strong></td>
                    <td></td>
                    <td></td>
                    <th><h4><strong class="text-blue">Total/Recibidos:</h4></strong></th>
                    <td><h4><strong>${{ number_format(($cajas->SumaTotalCreditosPagadosConsumoPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar + $cajas->SumaTotalVentas) + ($cajas->SumaTotalCreditosPagadosServicioPorCaja + $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar + $cajas->SumaTotalServicios),2,',','.') ?? '0.000' }}</h4></strong></td>
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
    </div>
    <!-- /.row -->
    <div class="row">

            <div class="panel panel-primary">
                <div class="panel-body">
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

            @include('cajas.caja.caja')
    </div>
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- Montos de apertura de caja --}}

    <div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Montos de Apertura de Caja </h3>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-text">Tasa del Día</span>
                    <h5 class="description-header">Caja Chica:</h5>
                    <h5 class="description-header">Vtos/Pendientes/Caja/Anterior:</h5>
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
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <span class="description-text">OBSERVACIONES:</span>
                <div>
                    {{$cajas->Observaciones}}
                </div>

            </div>
        </div>
    </div>

    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- Proceso de excedentes nuevos --}}
    {{-- // TODO Crear proceso para manejo de excedentes nuevos --}}
    <div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Procesos de Vueltos Pendientes </h3>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">

                    <h5 class="description-header">Vtos/Pendientes/Caja/Anterior:</h5>
                    <h5 class="description-header">Vtos/Pendientes Recibidos:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">&nbsp;&nbsp;&nbsp;</h5>
                    <h5 class="description-header text-red">- Vtos/Devueltos:</h5>
                    <h5 class="description-header text-red">- Vtos/Pagar/Oficina:</h5>
                    <h5 class="description-header text-red">- Excts/Nuevos:</h5>
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
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
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
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
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
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoPuntoDivisa,2,',','.') ?? ' 0,00' }}</h5>
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
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisass,2,',','.') ?? ' 0,00' }}</h5>
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
                    <h5 class="description-header text-red">Bs. {{ number_format($cajas->SumaVueltosExcedenteNuevoBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Bs. {{ number_format($cajas->SumaVueltosPendientesBolivarDivisa,2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header">______________</h5>


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
    {{-- Montos recibidos en caja --}}
    <div class="box-header with-border">
        <h3 class="box-title text-bold text-blue">Montos Recibidos en Caja </h3>

        <div class="row">
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-text">Servicios y Consumos</span>

                    <h5 class="description-header">Créd/Pagados/Caja:</h5>
                    <h5 class="description-header">Exct/Nuevos:</h5>
                    <h5 class="description-header">Contado:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Total Contable:</h5>
                    <h5 class="description-header">Caja chica Inicial:</h5>
                    <h5 class="description-header">Vtos/Pendientes:</h5>
                    <h5 class="description-header">+ Devueltos Flotantes:</h5>
                    <h5 class="description-header">+ Vueltos Flotantes:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="description-header">&nbsp;&nbsp;&nbsp;</h5>
                    <h5 class="description-header text-red">- Vueltos Pagados:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">&nbsp;&nbsp;&nbsp;</h5>
                    <h5 class="description-header">Vtos/Pagar/Oficina:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">&nbsp;&nbsp;&nbsp;</h5>
                    <h5 class="description-header text-red">- Total Contable:</h5>
                    <h5 class="description-header text-red">- Vtos/Pagar/Oficina:</h5>
                    <h5 class="description-header text-red">- Vtos/Pendientes:</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">Caja chica Final:</h5>
                </div>
            </div>
            <div class="col-sm-2 col-xs-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-green"><i
                            class="fa fa-caret-up"></i>
                        {{ $tasaDolar->porcentaje_ganancia ?? ''}}%</span>

                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_dolar),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalDolarServDflotante),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesDolarDivisa + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalDolarVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesDolarDivisa + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaVueltosPendientesDolarDivisa + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos) + ($cajas->SumaVueltosPagarOficinaDolarDivisa)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header text-red">$. {{ number_format(floatval(($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPendientesDolarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaVueltosPendientesDolarDivisa + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos) + ($cajas->SumaVueltosPagarOficinaDolarDivisa)) - ((($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar)) + ($cajas->SumaVueltosPagarOficinaDolarDivisa) + ($cajas->SumaVueltosPendientesDolarDivisa))),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->dolar_dolar_operador * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5> --}}
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
                    <span class="description-percentage text-yellow"><i
                            class="fa fa-caret-left"></i>
                        {{ $tasaPeso->porcentaje_ganancia  ?? ''}}%</span>

                        <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_peso),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPesoServDflotante),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPesoDivisa + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPesoVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPesoDivisa + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaVueltosPendientesPesoDivisa + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos) + ($cajas->SumaVueltosPagarOficinaPesoDivisa)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header text-red">$. {{ number_format(floatval(($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPesoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaVueltosPendientesPesoDivisa + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos) + ($cajas->SumaVueltosPagarOficinaPesoDivisa)) - ((($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso)) + ($cajas->SumaVueltosPagarOficinaPesoDivisa) + ($cajas->SumaVueltosPendientesPesoDivisa))),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->dolar_dolar_operador * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5> --}}
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
                            class="fa fa-caret-up"></i>
                        {{ $tasaTransferenciaPunto->porcentaje_ganancia  ?? ''}}%</span>

                        <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_punto),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalPuntoServDflotante),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPuntoDivisa + $cajas->monto_punto + ($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto) + ($cajas->SumaVueltosDevueltosPuntoDivisa + $cajas->SumaTotalPuntoServDflotante)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalPuntoVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPuntoDivisa + $cajas->monto_punto + ($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto) + ($cajas->SumaVueltosDevueltosPuntoDivisa + $cajas->SumaTotalPuntoServDflotante) - $cajas->SumaTotalPuntoVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaVueltosPendientesPuntoDivisa + $cajas->monto_punto + ($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto) + ($cajas->SumaVueltosDevueltosPuntoDivisa + $cajas->SumaTotalPuntoServDflotante) - $cajas->SumaTotalPuntoVueltos) + ($cajas->SumaVueltosPagarOficinaPuntoDivisa)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header text-red">$. {{ number_format(floatval(($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPendientesPuntoDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaVueltosPendientesPuntoDivisa + $cajas->monto_punto + ($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto) + ($cajas->SumaVueltosDevueltosPuntoDivisa + $cajas->SumaTotalPuntoServDflotante) - $cajas->SumaTotalPuntoVueltos) + ($cajas->SumaVueltosPagarOficinaPuntoDivisa)) - ((($cajas->SumaTotalPuntoCredConsumo + $cajas->SumaTotalPuntoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPuntoDivisa) + ($cajas->SumaTotalPuntoServ + $cajas->SumaTotalPunto)) + ($cajas->SumaVueltosPagarOficinaPuntoDivisa) + ($cajas->SumaVueltosPendientesPuntoDivisa))),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->dolar_dolar_operador * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_punto_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
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
                        {{ $tasaTransferenciaPunto->porcentaje_ganancia }}%</span>

                        <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_trans),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPendientesTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaServDflotante),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesTransferenciaDivisa + $cajas->monto_trans + ($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia) + ($cajas->SumaVueltosDevueltosTransferenciaDivisa + $cajas->SumaTotalTransferenciaServDflotante)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalTransferenciaVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesTransferenciaDivisa + $cajas->monto_trans + ($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia) + ($cajas->SumaVueltosDevueltosTransferenciaDivisa + $cajas->SumaTotalTransferenciaServDflotante) - $cajas->SumaTotalTransferenciaVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaVueltosPendientesTransferenciaDivisa + $cajas->monto_trans + ($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia) + ($cajas->SumaVueltosDevueltosTransferenciaDivisa + $cajas->SumaTotalTransferenciaServDflotante) - $cajas->SumaTotalTransferenciaVueltos) + ($cajas->SumaVueltosPagarOficinaTransferenciaDivisa)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header text-red">$. {{ number_format(floatval(($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPendientesTransferenciaDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaVueltosPendientesTransferenciaDivisa + $cajas->monto_trans + ($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia) + ($cajas->SumaVueltosDevueltosTransferenciaDivisa + $cajas->SumaTotalTransferenciaServDflotante) - $cajas->SumaTotalTransferenciaVueltos) + ($cajas->SumaVueltosPagarOficinaTransferenciaDivisa)) - ((($cajas->SumaTotalTransferenciaCredConsumo + $cajas->SumaTotalTransferenciaCredServicio) + ($cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa) + ($cajas->SumaTotalTransferenciaServ + $cajas->SumaTotalTransferencia)) + ($cajas->SumaVueltosPagarOficinaTransferenciaDivisa) + ($cajas->SumaVueltosPendientesTransferenciaDivisa))),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->dolar_dolar_operador * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_trans_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
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
                        {{ $tasaEfectivo->porcentaje_ganancia }}%</span>

                        <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosExcedenteNuevoBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->monto_bolivar),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPendientesBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosDevueltosBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaTotalBolivarServDflotante),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesBolivarDivisa + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaTotalBolivarVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval($cajas->SumaVueltosPendientesBolivarDivisa + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval(($cajas->SumaVueltosPendientesBolivarDivisa + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos) + ($cajas->SumaVueltosPagarOficinaBolivarDivisa)),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                    <h5 class="description-header text-red">$. {{ number_format(floatval(($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar)),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPagarOficinaBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header text-red">$. {{ number_format(floatval($cajas->SumaVueltosPendientesBolivarDivisa),2,',','.') ?? ' 0,00' }}</h5>
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold">$. {{ number_format(floatval((($cajas->SumaVueltosPendientesBolivarDivisa + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos) + ($cajas->SumaVueltosPagarOficinaBolivarDivisa)) - ((($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar)) + ($cajas->SumaVueltosPagarOficinaBolivarDivisa) + ($cajas->SumaVueltosPendientesBolivarDivisa))),2,',','.') ?? ' 0,00' }}</h5>
                    {{-- <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->dolar_dolar_operador * $tasaDolar->tasa),2,',','.') ?? ' 0,00' }}</h5> --}}
                    <h5 class="description-header">______________</h5>
                    <h5 class="box-title text-bold text-red">$. {{ number_format(floatval($cajas->monto_bolivar_cierre_dif),2,',','.') ?? ' 0,00' }}</h5>
                    <br>
                        <span class="description-text">EFECTIVO</span>
                </div>
                <!-- /.description-block -->
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <span class="description-text">OBSERVACIONES:</span>
                <div>
                    {{$cajas->Observaciones}}
                </div>

            </div>
        </div>
    </div>
    </div></div>
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
    {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

    @can('haveaccess', 'cajadatosventas.show')
    @if (count($cajas->servicios) > 0)
    <!-- Table row -->
    <div class="row">
        <div class="panel panel-primary">
      <div class="col-xs-12 table-responsive">


            <h4><strong>Datos de Servicios</strong></h4>


        <table class="table table-striped table-bordered table-condensed table-hover">

                @php
                    //creamos esta variable para ir contando los valores donde el tipo de pago sea cambio
                    //para luego restarselo a las cortesias ya que el valor que cuenta en las cortesias
                    //es cuando el tipo de pago es exonerado.

                    $restarAcortesia = 0;
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
                    >{{ $serv->id ?? '' }}</td>
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
                            {{ ' '.$pagovuts->Divisa.': '.floatval($pagovuts->MontoDivisa) ?? '0.00' }}
                            @endforeach
                        @endif
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosDevueltos)
                            @if ($vtosDevueltos->servicio_id == $serv->id && $vtosDevueltos->Estado == 'Devueltos')
                            <b class="text-red">{{ ' '.$vtosDevueltos->Divisa.': '.floatval($vtosDevueltos->MontoDivisa) ?? '' }}</b>
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
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $excdteNuevo)
                            @if ($excdteNuevo->servicio_id == $serv->id && $excdteNuevo->Estado == 'ExcedenteNuevo')
                            {{ ' '.$excdteNuevo->Divisa.': '.floatval($excdteNuevo->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPendtes)
                            @if ($vtosPendtes->servicio_id == $serv->id && $vtosPendtes->Estado == 'Pendiente')
                            {{ ' '.$vtosPendtes->Divisa.': '.floatval($vtosPendtes->MontoDivisa) ?? '' }}
                            @endif

                            @endforeach
                        @endif

                        @if ($cajas->excedente_actual)
                            @foreach ($cajas->excedente_actual as $vtosPagarOfic)
                            @if ($vtosPagarOfic->servicio_id == $serv->id && $vtosPagarOfic->Estado == 'PagarOficina')
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
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color: rgb(35, 192, 245);" class="tituloAzul">
                <th>ID</th>
                <th>Fecha</th>
                <th>Comprobante</th>
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
                    <td>{{ $venta->serie_comprobante ?? '' }}</td>
                    <td>{{ $venta->tasaTransPunto ?? '' }}</td>
                    <td>{{ $venta->modo_pago ?? '' }}</td>
                    <td>{{ $venta->tipo_pago ?? '' }}</td>
                    <td>&nbsp;{{ $venta->num_Punto ?? '' }} &nbsp;{{ $venta->num_Trans ?? '' }}</td>
                    @can('haveaccess', 'cajacosto.show')
                    <td>{{ floatval($venta->precio_costo) ?? '' }}</td>
                    <td>{{ $venta->margen_ganancia ?? '' }}</td>
                    @endcan

                    <td>{{ floatval($venta->total_venta) ?? '' }}</td>

                    @can('haveaccess', 'cajacosto.show')
                    <td>{{ floatval($venta->ganancia_neta) ?? '' }}</td>
                    @endcan
                    <td>{{ $venta->estado ?? '' }}</td>

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
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color: #A9D0F5;" class="tituloAzul">
                <th>ID</th>
                <th>Cliente</th>
                <th>Operador</th>
                <th>Pagado en</th>
                <th>N° de Factura</th>
                <th>Tipo de Operacion</th>
                <th>Monto</th>
                <th>Tipo Pago</th>
                <th>Fecha Pago</th>
                <th>Estado al pagar</th>
                <th>D/Pago</th>


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
                    <td>
                        <?php $operadorRecive = "App\User"::where('id',$creditosPagados->user_id)->first(); ?>
                        {{ $operadorRecive->name ?? '' }}
                    </td>
                    <td>{{ $pagoEn ?? '' }}</td>
                    <td>{{ $creditosPagados->numero_factura ?? '' }}</td>
                    <td>{{ $creditosPagados->tipo_operacion ?? '' }}</td>
                    <td>{{ $creditosPagados->monto ?? '' }}</td>
                    <td>{{ $creditosPagados->tipo_pago ?? '' }}</td>
                    <td>{{ $creditosPagados->fecha_pago ?? '' }}</td>
                    <td>{{ $creditosPagados->estado_credito_al_pagar ?? '' }}</td>
                    <td>
                        @if ($creditosPagados->tipo_pago)
                            @php
                                $pago_creditos = "App\Pago_Credito"::where('detalle_credito_id', $creditosPagados->detalle_credito_id)->get();
                            @endphp
                            @if (count($pago_creditos))
                            @foreach ($pago_creditos as $pagoCrt)
                            {{ ' '.$pagoCrt->Divisa.': '.floatval($pagoCrt->MontoDivisa) ?? '' }}
                            @endforeach
                            @endif

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
