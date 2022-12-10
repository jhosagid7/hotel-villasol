@extends ('layouts.admin3')
@section('contenido')
<!-- Default box -->
<!-- Content Header (Page header) -->
    {{-- <section class="content-header">
      <h1>
        <!--Blank page-->
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section> --}}

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border no-print">
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
        {{-- cabecera de box --}}
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
            <h3 class="box-title">@isset($title)
                {{$title}}
                @else
                {!!"Sistema"!!}
            @endisset</h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="operador">Nombre:</label>
                    <p>{{ $pagarporoficina->nombre_cliente ?? ''}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="proveedor">Cédula:</label>
                    <p>{{ $pagarporoficina->cedula_cliente ?? ''}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="proveedor">Fecha:</label>
                    <p>{{date('d-m-y')}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="tipo_comprobante">Teléfono:</label>
                    <p>{{ $pagarporoficina->telefono_cliente ?? ''}}</p>
                </div>
            </div> --}}

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="serie_comprobante">Tipo pago:</label>
                    <p>
                        @if ($pagarporoficina->isTransferencia)
                            Transferencia:
                        @endif
                        @if ($pagarporoficina->isPagoMobil)
                            Pago mobil:
                        @endif
                        @if ($pagarporoficina->isEfectivo)
                            Efectivo:
                        @endif
                        </p>
                    {{-- <p>{{ $pagarporoficina->direccion_cliente ?? ''}}</p> --}}
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="text-blue text-bold" for="num_comprobante">Operador:</label>
                    <p>{{ $UserName ?? ''}}</p>
                </div>
            </div>
        </div>

    </div>


    @if ($pagarporoficina->isTransferencia || $pagarporoficina->isPagoMobil)
        <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="detalles" class="table table-striped table-borderd table-condensed table-hover">
                            <thead style="background-color: #A9D0F5">
                                <th>ID</th>
                                <th>Banco</th>
                                <th>Tipo Cuenta</th>
                                <th>Numero de cuenta</th>
                                <th>Pago movil</th>


                            </thead>

                            <tbody>
                                @php
                                    $total_deuda = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($BancosClientes as $BancosCliente)
                                    <tr>
                                    <td>{{$i ?? ''}}</td>
                                    <td>{{$BancosCliente->nombre_banco_cliente ?? 'S/N'}}</td>
                                    <td>{{$BancosCliente->tipo_cuenta_cliente ?? 'S/N'}}</td>
                                    <td>{{$BancosCliente->num_cuenta_cliente ?? 'S/N'}}</td>
                                    <td>{{$BancosCliente->telefono_pago_movil_cliente ?? 'S/N'}}</td>



                                    </tr>
                                    @php
                                        $total_deuda += floatval($pagarporoficina->excedente);
                                        // $i++
                                    @endphp
                                @endforeach
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                <th><br></th>

                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>



        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="detalles" class="table table-striped table-borderd table-condensed table-hover">
                            <thead style="background-color: #A9D0F5">
                                <th>ID</th>
                                <th>Número de Servicio</th>
                                <th>Estatus</th>
                                <th>Deuda Anterior</th>
                                <th>Monto Operacion</th>
                                <th>Deuda Actual</th>
                                <th>Fecha Emision</th>
                                <th>Caja ID N°</th>

                            </thead>

                            <tbody>
                                @php
                                    $total_deuda = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($historialExcedentes as $historialExcedente)
                                    <tr>
                                    <td>{{$i ?? ''}}</td>
                                    <td>{{$historialExcedente->num_servicio ?? ''}}</td>
                                    <td>{{$historialExcedente->status ?? ''}}</td>
                                    <td>{{floatval($historialExcedente->saldo_anterior) ?? ''}}</td>
                                    <td>{{$historialExcedente->saldo_operacion ?? ''}}</td>
                                    <td>{{$historialExcedente->saldo_disponible ?? ''}}</td>
                                    <td>{{$historialExcedente->created_at ?? ''}}</td>
                                    <td>{{$historialExcedente->caja_id ?? ''}}</td>


                                    </tr>
                                    @php
                                        $total_deuda += floatval($historialExcedente->saldo_disponible);
                                        $i++
                                    @endphp
                                @endforeach
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th><br><h4><b>Total deuda: </b></h4></th>

                                <th><br><h4 id="total"><b>$. {{$total_deuda}}</b></h4></th>
                                <th><br><a id="modalPagoPendienteOpcionesBtn" href="#" data-toggle="modal" data-target="#modalPagoPendienteOpciones"  class="btn btn-sm btn-danger btn-block col-lg-pull-2 small no-print">Procesar pago pendiente</a><br>
                                        {{-- <a id="modalPago" href="#" onClick="selFactura({{floatval($total_deuda) ?? ''}},{{ $pagarporoficina->id ?? ''}},'todas');"  data-toggle="modal" data-target="#limpieza" class="btn btn-sm btn-success btn-block col-lg-pull-2 small no-print">Registrar pago</a></th> --}}
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>



        </div>
    </div>

{{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
    <a class="btn btn-danger no-print" href="{{ url()->previous() }}">{{__('Regresar')}}</a>
    <a onClick="imprimir('imprimir')" target="_blank" class="btn btn-primary  hidden-print">
        <i class="fa fa-print"></i>
        Imprimir
    </a>
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
</div>

<div class="modal fade bd-example-modal-lg refrescar" id="limpieza" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-primary">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-horizontal submit-prevent-form" id="form1" role="form" action="{{ route('creditos.store')}}" method="POST">
                    @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span class="fa fa-spinner"></span> GESTIONAR PAGOS DE CREDITOS </h4>
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
                                                        <h4 id="r" class="text-bold">RESTA</h4>
                                                        <h4 id="tap" class="text-bold">TOTAL A PAGAR</h4>
                                                        <input id="monto_dejado" name="monto_dejado" type="hidden" value="">
                                                        <input id="base_vuelto_monto_dejado" name="base_vuelto_monto_dejado" type="text" value="">
                                                        <input id="monto_dejadoResta" name="monto_dejadoResta" type="text" value="">
                                                        <input id="isVueltos" name="isVueltos" type="hidden" value="0">
                                                        <input id="cantidad" name="cantidad" type="hidden" value="">
                                                        {{-- <input id="num_servicio" name="num_servicio" type="hidden" value="{{$num_servicio}}"> --}}
                                                        <input id="operador" name="operador" type="hidden" value="{{$UserName}}">
                                                        <input id="facturas_pagadas" name="facturas_pagadas" type="hidden" value="">
                                                        <input id="facturas_pagadas_id" name="facturas_pagadas_id" type="hidden" value="">
                                                        <input id="total_costo" name="total_costo" type="hidden" value="{{floatval($pagarporoficina->excedente) ?? ''}}">
                                                        <input id="precio_costo" name="precio_costo" type="hidden" value="{{floatval($pagarporoficina->excedente) ?? ''}}">
                                                        <input id="tipo_pago" name="tipo_pago" type="hidden" value="">
                                                        <input id="modo_pago" name="modo_pago" type="hidden" value="">
                                                        <input id="caja_id" name="caja_id" type="hidden" value="{{$caja->id}}">
                                                        <input id="cliente_id" name="cliente_id" type="hidden" value="{{ $pagarporoficina->persona_id ?? ''}}">
                                                        {{-- <input id="caja_id" name="caja_id" type="hidden" value="{{$caja->id}}"> --}}
                                                        {{-- <input id="user_id" name="user_id" type="hidden" value="{{$UserId}}"> --}}
                                                    <th>
                                                        <h4 class="text-bold" id="spTotal">0.00</h4>
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
                                                                        {{-- <td><h5 class="description-header text-bold">$. {{ number_format($cajas->SumaTotalPeso + $caja->monto_peso,2,',','.') ?? ' 0,00' }}</h5></td> --}}
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
                                                                        {{-- <td><h5 class="description-header text-bold">$. {{ number_format($cajas->SumaTotalPeso + $caja->monto_peso,2,',','.') ?? ' 0,00' }}</h5></td> --}}
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
                                                                        {{-- <td><h5 class="description-header  text-bold">Bs. {{ number_format($cajas->SumaTotalPunto + $caja->monto_bolivar,2,',','.') ?? ' 0,00' }}</h5></td> --}}
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
            <button id="enviar" class="btn btn-outline submit-prevent-button" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio</button>
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
{{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                {{-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

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
                                    <h4 class="modal-title"><span class="fa fa-spinner"></span>PROCESAR VUELTOS PENDIENTEww </h4>
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
                                                <h3 class="box-title">Deuda a pagar (<b class="text-danger" id="countVueltosPendientes">$0.00</b>). (Pago programado para pagar por oficina)...!</h3>

                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="btnPago2Opciones">
                                                    {{-- <div id="contado2Opciones" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 small"> --}}
                                                        {{-- <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a> --}}
                                                        {{-- <button type="botton"  name="devolverVueltos"  id="devolverVueltos" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small"> Contado</button>
                                                    </div> --}}
                                                    <form id="form4" action="{{ route('registro.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">
                                                    @csrf
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nombre">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="num_operacion">Numero de Operacion </label>
                                                                    <input required type="text" id="num_operacion" name="num_operacion" class="form-control titulo" value="{{old('emanumOperacionil')}}" placeholder="num Operacion...">
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nombre">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre">Tipo de pago</label>
                                                                    <select required class="form-control" id="tipo_documento" name="tipo_documento">
                                                                        <option value="0">Seleccione tipo de pago</option>
                                                                        <option @if ($pagarporoficina->isTransferencia =="1") selected  @elseif (old('isTransferencia')=="1") selected @endif  value="Transferencia">Transferencia</option>
                                                                        <option @if ($pagarporoficina->isPagoMobil =="1") selected  @elseif (old('isPagoMobil')=="1") selected @endif value="Pago_movil">Pago mobil</option>
                                                                        <option @if ($pagarporoficina->isEfectivo =="1") selected  @elseif (old('isEfectivo')=="1") selected @endif value="Efectivo">Efectivo</option>

                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nombre">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre">Detalle</label>
                                                                    <textarea class="text-black" id="detallePago" name="detallePago" cols="60" rows="5"></textarea>
                                                                </div>

                                                            </div>

                                                    {{-- <div id="precortesia2Opciones" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 small"> --}}
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        {{-- <button type="botton"  name="pagarPorOficinaBtn"  id="pagarPorOficinaBtn" class="btn btn-sm btn-warning btn-block col-lg-pull-2 small"> Pagar Por Oficina</button> --}}
                                                        {{-- <a href="#" data-toggle="modal" data-target="#precortesiamodal"  class="btn btn-sm btn-warning btn-block col-lg-pull-2 small">Pagar Por Oficina</a> --}}
                                                    {{-- </div> --}}

                                                    {{-- <div id="cortesia"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"   class="btn btn-xs btn-warning btn-block col-lg-pull-2 small">Cortesía</a>

                                                    </div> --}}

                                                    {{-- <div id="creditoa"
                                                        class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">
                                                        <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Crédito</a>

                                                    </div> --}}

                                                    {{-- <div id="precredito2Opciones" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">

                                                        <button type="botton"  name="crearCuentaBtn"  id="crearCuentaBtn" class="btn btn-sm btn-success btn-block col-lg-pull-2 small"> Crear Cuenta</button>
                                                    </div> --}}

                                                </div>
                                            </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        </div>
                                    </div>
                                    <div id="contentPagarOficina">
                                        {{-- <form id="form4" action="{{ route('excedente.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off"> --}}

                                                        {{-- @csrf --}}
                                        <div class="col-md-12">
                                            <div class="box box-default">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Seleccione tipo de pago</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                    <div class="table-responsive">
                                                        <table class="table no-margin">

                                                            <tbody style="padding: 0px;">
                                                                <tr style="padding: 0px;">
                                                                    <td><h4 id="trans_" class="text-primary" style="margin-top: 0px !important;">Transferencia: &nbsp;&nbsp;&nbsp; <input class="transferencia" @if ($pagarporoficina->isTransferencia =="1") checked  @elseif (old('isTransferencia')=="1") checked @endif name="isTransferencia" type="checkbox"></h4></td>


                                                                    <td><h4 id="mobil_" class="text-primary" style="margin-top: 0px !important;">Pago Mobil: &nbsp;&nbsp;&nbsp;<input class="pagomobil" @if ($pagarporoficina->isPagoMobil =="1") checked  @elseif (old('isPagoMobil')=="1") checked @endif name="isPagoMobil" type="checkbox"></h4></td>


                                                                    <td><h4 class="text-primary" style="margin-top: 0px !important;">Efectivo: &nbsp;&nbsp;&nbsp;<input class="efectivo" @if ($pagarporoficina->isEfectivo =="1") checked  @elseif (old('isEfectivo')=="1") checked @endif name="isEfectivo" type="checkbox"></h4></td>


                                                                </tr>



                                                            </tbody>
                                                        </table>

                                                    </div>
                                                    <!-- /.table-responsive -->
                                                </div>


                                            </div>
                                            <div id="box_PagarCrear" class="box box-warning pagarPorOficina"  style="display:none">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"><b class="text-warning" id="tituloPagarCrear">Pagar Por Oficina</b></h3><br>
                                                Datos de cliente:
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="formPagarOficina">


                                                        <div class="row">

                                                            {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 seleccioneCliente"  style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="Banco">Seleccione Cliente</label>
                                                                    <select name="selec_cliente" id="selec_cliente" class="form-control selectpicker" data-live-search="true">
                                                                        <option value="default" selected="selected">Seleccione Cliente</option>
                                                                        @foreach ($clientes as $dcliente)
                                                                    <option value="{{$dcliente->id}}_{{$dcliente->nombre}}_{{$dcliente->num_documento}}_{{$dcliente->direccion}}_{{$dcliente->telefono}}_{{$dcliente->email}}">{{$dcliente->nombre}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nombre"  style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre">Nombre del cliente</label>
                                                                    <input required type="text" id="nombre" name="nombre" class="form-control titulo" value="{{$pagarporoficina->nombre_cliente ?? ''}}" placeholder="Nombre...">
                                                                </div>
                                                            </div>

                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                                            </div> --}}

                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 cedula"  style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="num_documento">Número de Documento</label>
                                                                    <input required type="number" id="num_documento" name="num_documento" class="form-control enteros" value="{{$pagarporoficina->cedula_cliente ?? ''}}" placeholder="Número de Documento...">
                                                                </div>
                                                            </div>

                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="direccion">Dirección</label>
                                                                    <input required type="text" id="direccion" name="direccion" class="form-control mayuscula" value="{{old('direccion')}}" placeholder="Dirección...">
                                                                </div>
                                                            </div> --}}

                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="telefono">Teléfono</label>
                                                                    <input required type="text" id="telefono" name="telefono" class="form-control"  data-inputmask='"mask": "(9999) 999-9999"' data-mask value="{{old('telefono')}}" placeholder="Teléfono...">
                                                                </div>
                                                            </div> --}}

                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="email">Email</label>
                                                                    <input required type="email" id="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Email...">
                                                                </div>
                                                            </div> --}}
                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="imagen">Imagen</label>
                                                                    <input required type="file" name="imagen" class="form-control" accept="image/*">
                                                                </div>
                                                            </div> --}}

                                                        </div>
                                                        <div id="datosBanco" class="box box-default datosBanco"  style="display:none">
                                                            <div class="box-header with-border">
                                                                {{-- <h3 class="box-title">Conceder Privilegios</h3> --}}
                                                                <br>
                                                                Datos Bancarios:
                                                            </div>
                                                            <!-- /.box-header -->
                                                            <div class="box-body">
                                                                <div class="row">

                                                                    {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 selecctBanco "  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="selec_banco">Seleccione Banco</label>
                                                                            <select name="selec_banco" id="selec_banco" class="form-control selectpicker" data-live-search="true">
                                                                                <option value="default" selected="selected">Seleccione Banco</option>
                                                                                @foreach ($bancos as $banco)
                                                                            <option value="{{$banco->id}}_{{$banco->nombre_banco}}_{{$banco->codigo}}">{{$banco->nombre_banco}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  nombreBanco "  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="nombre_banco">Nombre Banco</label>
                                                                            <input required type="text" id="nombre_banco" name="nombre_banco" class="form-control titulo" value="{{$pagarporoficina->nombre_banco_cliente ?? ''}}" placeholder="Nombre Banco...">
                                                                        </div>
                                                                    </div>



                                                                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 codigo" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="num_documento">Código</label>
                                                                            <input required type="number" id="codigo" name="codigo" class="form-control enteros" value="{{old('codigo')}}" placeholder="Código...">
                                                                        </div>
                                                                    </div> --}}

                                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 numCuenta" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="direccion">Número de cuenta</label>
                                                                            <input required type="text" id="num_cuenta" name="num_cuenta" class="form-control mayuscula" value="{{$pagarporoficina->num_cuenta_cliente ?? ''}}" placeholder="Número de cuenta...">
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tipoCuenta" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="tipo_cuenta">Tipo de cuenta</label>
                                                                            <select required class="form-control" id="tipo_cuenta" name="tipo_cuenta">
                                                                                @if($pagarporoficina->tipo_cuenta_cliente=='Corriente')
                                                                                    <option value="Corriente" selected>Corriente</option>
                                                                                    <option value="Ahorro">Ahorro</option>
                                                                                @else
                                                                                    <option value="Corriente">Corriente</option>
                                                                                    <option value="Ahorro" selected>Ahorro</option>
                                                                                @endif
                                                                                {{-- <option value="0">Seleccione tipo de cuenta</option>
                                                                                <option value="Corriente">Corriente</option>
                                                                                <option value="Ahorro">Ahorro</option> --}}
                                                                            </select>

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 telefonoMobil"  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="pago_movil">Teléfono</label>
                                                                            <input required type="text" name="pago_movil" class="form-control"  data-inputmask='"mask": "(9999) 999-9999"' data-mask value="{{$pagarporoficina->telefono_pago_movil_cliente ?? ''}}" placeholder="Pago mobil...">
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
                                                                <input class="text-black hidden" type="text" id="dcliente_id" name="dcliente_id" value="{{ $pagarporoficina->persona_id ?? '' }}">
                                                                <input class="text-black hidden" type="text" id="motivo" name="motivo" value="servicio">
                                                                <input class="text-black hidden" type="text" id="caja_id" name="caja_id" value="{{$caja->id ?? ''}}">
                                                                <input class="text-black hidden" id="deudaPendiente" name="deudaPendiente" value="{{ $pagarporoficina->excedente ?? '' }}">
                                                                <input class="text-black hidden" id="sucursal_id" name="sucursal_id" value="{{$caja->sucursal_id ?? ''}}">

                                                                {{-- <input class="text-black hidden" type="text" id="dcliente_id" name="dcliente_id"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="banco_id" name="banco_id"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="bandera" name="bandera"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="excedente" name="excedente"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="servicio_id" name="servicio_id" value="{{$servicio->id ?? ''}}"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="num_servicio" name="num_servicio" value="{{$servicio->num_servicio ?? ''}}"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="motivo" name="motivo" value="servicio"> --}}
                                                                {{-- <input class="text-black hidden" type="text" id="caja_id" name="caja_id" value="{{$servicio->caja_id ?? ''}}"> --}}
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
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelara</button>
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
</section>
@push('sciptsMain')

<script language="javascript">
  $(document).ready(function(){

      // Comprobacion usando funcion .is()

    // console.log("Checkbox transferencia");
    if($('.transferencia').is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.datosBanco').css('display', 'block');
                $('.selecctBanco').css('display', 'block');
                $('.nombreBanco').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.codigo').css('display', 'block');
                $('.numCuenta').css('display', 'block');
                $('.tipoCuenta').css('display', 'block');
                // $('.telefonoMobil').css('display', 'block');
                // $('.transM').css('display', 'block');
            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.datosBanco').css('display', 'none');
                $('.selecctBanco').css('display', 'none');
                $('.nombreBanco').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.codigo').css('display', 'none');
                $('.numCuenta').css('display', 'none');
                $('.tipoCuenta').css('display', 'none');
                // $('.transM').css('display', 'none');


                if($('.pagomobil').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.telefonoMobil').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.telefonoMobil').css('display', 'none');

                    if($('.efectivo').is(':checked')){
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    }else{
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }


    // console.log("Checkbox pagomobil");
    if($('.pagomobil').is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.datosBanco').css('display', 'block');
                $('.selecctBanco').css('display', 'block');
                $('.nombreBanco').css('display', 'block');
                $('.telefonoMobil').css('display', 'block');
            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.datosBanco').css('display', 'none');
                $('.selecctBanco').css('display', 'none');
                $('.nombreBanco').css('display', 'none');
                $('.telefonoMobil').css('display', 'none');

                if($('.transferencia').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.codigo').css('display', 'block');
                    $('.numCuenta').css('display', 'block');
                    $('.tipoCuenta').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.codigo').css('display', 'none');
                    $('.numCuenta').css('display', 'none');
                    $('.tipoCuenta').css('display', 'none');

                    if($('.efectivo').is(':checked')){
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    }else{
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }


    // console.log("Checkbox efectivo");
    if($('.efectivo').is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                // $('.datosBanco').css('display', 'block');
                // $('.selecctBanco').css('display', 'block');
                // $('.nombreBanco').css('display', 'block');
                // $('.telefonoMobil').css('display', 'block');
            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                // $('.datosBanco').css('display', 'none');
                // $('.selecctBanco').css('display', 'none');
                // $('.nombreBanco').css('display', 'none');
                // $('.telefonoMobil').css('display', 'none');

                if($('.transferencia').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.codigo').css('display', 'block');
                    $('.numCuenta').css('display', 'block');
                    $('.tipoCuenta').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.codigo').css('display', 'none');
                    $('.numCuenta').css('display', 'none');
                    $('.tipoCuenta').css('display', 'none');

                    if($('.pagomobil').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.telefonoMobil').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.telefonoMobil').css('display', 'none');
                    }
                }


            }


        $('.transferencia').click(function(){
            if($(this).is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.datosBanco').css('display', 'block');
                $('.selecctBanco').css('display', 'block');
                $('.nombreBanco').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.codigo').css('display', 'block');
                $('.numCuenta').css('display', 'block');
                $('.tipoCuenta').css('display', 'block');
                // $('.telefonoMobil').css('display', 'block');
                // $('.transM').css('display', 'block');
            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.datosBanco').css('display', 'none');
                $('.selecctBanco').css('display', 'none');
                $('.nombreBanco').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.codigo').css('display', 'none');
                $('.numCuenta').css('display', 'none');
                $('.tipoCuenta').css('display', 'none');
                // $('.transM').css('display', 'none');


                if($('.pagomobil').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.telefonoMobil').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.telefonoMobil').css('display', 'none');

                    if($('.efectivo').is(':checked')){
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    }else{
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }

        });

        $('.pagomobil').click(function(){
            if($(this).is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.datosBanco').css('display', 'block');
                $('.selecctBanco').css('display', 'block');
                $('.nombreBanco').css('display', 'block');
                $('.telefonoMobil').css('display', 'block');
            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.datosBanco').css('display', 'none');
                $('.selecctBanco').css('display', 'none');
                $('.nombreBanco').css('display', 'none');
                $('.telefonoMobil').css('display', 'none');

                if($('.transferencia').is(':checked')){
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.codigo').css('display', 'block');
                    $('.numCuenta').css('display', 'block');
                    $('.tipoCuenta').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.codigo').css('display', 'none');
                    $('.numCuenta').css('display', 'none');
                    $('.tipoCuenta').css('display', 'none');

                    if($('.efectivo').is(':checked')){
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    }else{
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }
        });


        $('.efectivo').click(function(){
            if($(this).is(':checked')){
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');


            }else{
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');

                if($('.transferencia').is(':checked')){
                    // alert('trans');
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.codigo').css('display', 'block');
                    $('.numCuenta').css('display', 'block');
                    $('.tipoCuenta').css('display', 'block');
                }else{
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.codigo').css('display', 'none');
                    $('.numCuenta').css('display', 'none');
                    $('.tipoCuenta').css('display', 'none');

                    if($('.pagomobil').is(':checked')){
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                        $('.datosBanco').css('display', 'block');
                        $('.selecctBanco').css('display', 'block');
                        $('.nombreBanco').css('display', 'block');
                        $('.telefonoMobil').css('display', 'block');
                    }else{
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                        $('.datosBanco').css('display', 'none');
                        $('.selecctBanco').css('display', 'none');
                        $('.nombreBanco').css('display', 'none');
                        $('.telefonoMobil').css('display', 'none');
                    }
                }




            }
        });
    });

selFactura = function(total_costo_selec,Ids_selec,facturas_pagadas){
    // alert(PreCosto+'  '+PreVenta+'  '+Ids);

    $("#total_costo").val(total_costo_selec);
    addHabitacion();
    $("#modo_pago").val('Contado');
    $("#facturas_pagadas").val(facturas_pagadas);
    $("#facturas_pagadas_id").val(Ids_selec);

    $("#bt_addD").click();
    $("#precio_costo").val(total_costo_selec);
};

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
<script>
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
    // $("#modalPagoPendienteOpcionesBtn").click();

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
    var vtosPendientes = $('#deudaPendiente').val();
    var VueltosvtosPendientes = $('#deudaPendiente').val();
    $("#countVueltosPendientes").html('$'+ VueltosvtosPendientes);

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

// $("#selec_cliente").change(showValuesCliente);

// $("#selec_cliente").on("change", function () {
// document.getElementById("tipo_documento").focus();
// // $("#jidarticulo").val('0');
// // document.getElementById('jidarticulo').val('0');
// });
// $("#tipo_documento").on("change", function () {
// document.getElementById("selec_banco").focus();
// // $("#jidarticulo").val('0');
// // document.getElementById('jidarticulo').val('0');
// });

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
$("#contentPagarOficina").show();
$("#contentCrearCuenta").show();
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

let num_transaccion = $('#num_operacion').val();
if(!num_transaccion){
    alert('!Error. Debe ingresar un número de operación...');
    return false;
}
$("#form4").submit();
return false;
});
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$("#selec_banco_empresa").change(showValuesBancoEmpresa);
$("#selec_banco_cliente").change(showValuesBancoCliente);

$("#selec_banco_empresa").on("change", function () {
// document.getElementById("tipo_documento").focus();
// $("#jidarticulo").val('0');
// document.getElementById('jidarticulo').val('0');
});
$("#selec_banco_empresa").on("change", function () {
$("#num_cuenta_empresa").val('');
document.getElementById("num_cuenta_cliente").focus();
// $("#jidarticulo").val('0');
// document.getElementById('jidarticulo').val('0');
});

focusMethod = function getFocus() {
document.getElementById("selec_banco_cliente").focus();


$("#selec_banco_cliente").val('default');
$("#selec_banco_cliente").selectpicker("refresh");
}

function showValuesBancoEmpresa() {
// alert('show');
datosArticulo = document.getElementById('selec_banco_empresa').value.split('_');
// $("#jprecio_venta").val(datosArticulo[2]);
$("#banco_id_banco_empresa").val(datosArticulo[0]);
$("#nombre_banco_empresa").val(datosArticulo[1]);
$("#codigo_banco_empresa").val(datosArticulo[2]);
$("#num_cuenta_banco_empresa").val(datosArticulo[3]);
$("#tipo_cuenta_banco_empresa").val(datosArticulo[4]);
$("#pago_movil_banco_empresa").val(datosArticulo[5]);
// $("#jstock").val(datosArticulo[2]);


// $("#jmarjen_venta_dolar").val(12);


}

// $("#selec_banco_cliente").change(showValuesBancoCliente);
// $("#selec_banco_cliente").ch(showValuesBancoEmpresa);

$("#selec_banco_cliente").on("change", function () {
// document.getElementById("tipo_documento").focus();
// $("#jidarticulo").val('0');
// document.getElementById('jidarticulo').val('0');
});
$("#selec_banco_cliente").on("change", function () {
// $("#num_cuenta_banco_cliente").val('');
// document.getElementById("num_cuenta_banco_cliente").focus();
// $("#jidarticulo").val('0');
// document.getElementById('jidarticulo').val('0');
});

focusMethod = function getFocus() {
document.getElementById("selec_banco_cliente").focus();


$("#num_cuenta_banco_cliente").val('default');
$("#num_cuenta_banco_cliente").selectpicker("refresh");
}


function showValuesBancoCliente() {
// alert('show');
datosArticulo = document.getElementById('selec_banco_cliente').value.split('_');
// $("#jprecio_venta").val(datosArticulo[2]);
$("#banco_id_banco_cliente").val(datosArticulo[0]);
$("#nombre_banco_cliente").val(datosArticulo[1]);
$("#codigo_banco_cliente").val(datosArticulo[2]);
$("#num_cuenta_banco_cliente").val(datosArticulo[3]);
$("#tipo_cuenta_banco_cliente").val(datosArticulo[4]);
$("#pago_movil_banco_cliente").val(datosArticulo[5]);
// $("#jstock").val(datosArticulo[2]);


// $("#jmarjen_venta_dolar").val(12);


}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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


                if(isCortesia){
                    let precio = $("#precioDolarHabitacio").val();
                    $("#precio_costo").val(precio);
                    $('#cortesia').show();
                    // console.log('Este cliente puede tener credito');
                }else{
                    $('#cortesia').hide();
                }
                if(isCredito){
                    let precio = $("#precioDolarHabitacio").val();
                    $("#precio_costo").val(precio);
                    // console.log('Este cliente puede tener credito');
                    $('#credito').show();

                }else{

                    $('#credito').hide();
                }

            }



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

    $("#cortesia").hide();
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
            $('#modo_pago').val('contado');
        });
        $("#cortesia").on('click', function() {
            addHabitacion();
            $("#monto_dejado").val(0);
            $("#base_vuelto_monto_dejado").val(0);
            $('#modo_pago').val('cortesia');
            $("#form1").submit();
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
                        alert('puede seguir');
                        // $("#form1").submit();
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
                        alert('puede seguir');
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
        // totalcosto = 10.50;
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
            // $("#precio_costo").val(totalr);
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



        var valor           = PagoTtotal.innerHTML;
        var valor_restar    = spTotal.innerHTML;
        var resta           = numDecimal(valor -valor_restar);
        // var valorV           = PagoTtotalV.innerHTML;
        // var valor_restarV    = spTotalV.innerHTML;
        // var restaV           = numDecimal(valorV -valor_restarV);

        RestaTotal.innerHTML = numDecimal(resta); //se llena el campo resta



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
            $("#guardar").show("linear");//oculta boton de prosesar servicio




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


        $("#DMontoDolar").keyup(function() {
            DMontoDolar();
        });

        $("#DMontoPeso").keyup(function() {
            DMontoPeso();
        });

        $("#DMontoBolivar").keyup(function() {
            DMontoBolivar();
        });

        $("#DMontoPunto").keyup(function() {
            DMontoPunto();
        });

        $("#DMontoTrans").keyup(function() {
            DMontoTrans();
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
                / console.log(r.toFixed(2));

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
                DMontoDolarV();
            }
        });

        $("#cargarPesoV").on('click', function() {

            if(vcargarpV == 0){
                vcargarpV = 1;
                vcargarV = 0;
                vcargarbV = 0;




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
                DMontoPesoV();
            }
        });

        $("#cargarBolivarV").on('click', function() {
            if(vcargarbV == 0){
                vcargarbV = 1;
                vcargarV = 0;
                vcargarpV = 0;




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
                DMontoBolivarV();
            }
        });
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
</script>

@endpush
@endsection
