

<div class="modal fade bs-example-modal-xm refrescar mg-0" tabindex="-1"
    id="modalPagoPendienteOpciones" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-danger">
        <div class="modal-dialog modal-xm">
            <div class="modal-content">
                {{-- <form id="form2" action="{{route('proceso')}}" method="post">
                    @csrf --}}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="fa fa-spinner"></span>EDITAR TIPO DE PAGO </h4>
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
                                            <h3 class="box-title">Deuda a pagar (<b class="text-danger"
                                                    id="countVueltosPendientes">${{
                                                    $pagarporoficina_deuda->deuda_total_acumulada }}</b>).
                                                (Pago programado para pagar
                                                por oficina)...!</h3>

                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <div id="btnPago2Opciones">
                                                {{-- <form id="edit_pago" action="{{ route('registro.store') }}"
                                                    enctype="multipart/form-data" method="POST" autocomplete="off">
                                                    @csrf --}}
                                            </div>
                                        </div><!-- /.box-body -->
                                    </div><!-- /.box -->
                                </div>
                            </div>
                            <div id="contentPagarOficina">
                                <form id="edit_pago" action="{{ route('excedente.store') }}" method="POST"
                                    autocomplete="off">
                                    @csrf
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
                                                                <td>
                                                                    <h4 id="trans_" class="text-primary"
                                                                        style="margin-top: 0px !important;">
                                                                        Transferencia:
                                                                        &nbsp;&nbsp;&nbsp; <input class="transferencia"
                                                                            @if ($pagarporoficina->isTransferencia ==
                                                                        '1') checked @elseif (old('isTransferencia') ==
                                                                        '1') checked @endif
                                                                        name="isTransferencia" type="checkbox"></h4>
                                                                </td>


                                                                <td>
                                                                    <h4 id="mobil_" class="text-primary"
                                                                        style="margin-top: 0px !important;">Pago Mobil:
                                                                        &nbsp;&nbsp;&nbsp;<input class="pagomobil" @if
                                                                            ($pagarporoficina->isPagoMobil == '1')
                                                                        checked @elseif (old('isPagoMobil') == '1')
                                                                        checked @endif
                                                                        name="isPagoMobil" type="checkbox"></h4>
                                                                </td>


                                                                <td>
                                                                    <h4 class="text-primary"
                                                                        style="margin-top: 0px !important;">Efectivo:
                                                                        &nbsp;&nbsp;&nbsp;<input class="efectivo" @if
                                                                            ($pagarporoficina->isEfectivo == '1')
                                                                        checked @elseif (old('isEfectivo') == '1')
                                                                        checked @endif
                                                                        name="isEfectivo" type="checkbox"></h4>
                                                                </td>


                                                            </tr>



                                                        </tbody>
                                                    </table>

                                                </div>
                                                <!-- /.table-responsive -->
                                            </div>


                                        </div>
                                        <div id="box_PagarCrear" class="box box-warning pagarPorOficina"
                                            style="display:none">
                                            <div class="box-header with-border">
                                                <h3 class="box-title"><b class="text-warning"
                                                        id="tituloPagarCrear">Pagar
                                                        Por Oficina</b></h3><br>
                                                Datos de cliente:
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="formPagarOficina">


                                                    <div class="row">


                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nombre"
                                                            style="display:none">
                                                            <div class="form-group">
                                                                <label class="text-black" for="nombre">Nombre del
                                                                    cliente</label>
                                                                <input required readonly type="text" id="nombre"
                                                                    name="nombre" class="form-control titulo"
                                                                    value="{{ $pagarporoficina->nombre_cliente ?? '' }}"
                                                                    placeholder="Nombre...">
                                                            </div>
                                                        </div>



                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 cedula"
                                                            style="display:none">
                                                            <div class="form-group">
                                                                <label class="text-black" for="num_documento">Número de
                                                                    Documento</label>
                                                                <input required readonly type="number"
                                                                    id="num_documento" name="num_documento"
                                                                    class="form-control enteros"
                                                                    value="{{ $pagarporoficina->cedula_cliente ?? '' }}"
                                                                    placeholder="Número de Documento...">
                                                            </div>
                                                        </div>



                                                    </div>
                                                    <div id="datosBanco" class="box box-default datosBanco"
                                                        style="display:none">
                                                        <div class="box-header with-border">
                                                            {{-- <h3 class="box-title">Conceder Privilegios</h3> --}}
                                                            <br>
                                                            Datos Bancarios:
                                                        </div>
                                                        <!-- /.box-header -->
                                                        <div class="box-body">
                                                            <div class="row">

                                                                {{-- <div
                                                                    class="col-lg-12 col-md-12 col-sm-12 col-xs-12 selecctBanco "
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black"
                                                                            for="selec_banco">Seleccione Banco</label>
                                                                        <select name="selec_banco" id="selec_banco"
                                                                            class="form-control selectpicker"
                                                                            data-live-search="true">
                                                                            <option value="default" selected="selected">
                                                                                Seleccione Banco</option>
                                                                            @foreach ($bancos as $banco)
                                                                            <option
                                                                                value="{{$banco->id}}_{{$banco->nombre_banco}}_{{$banco->codigo}}">
                                                                                {{$banco->nombre_banco}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  nombreBanco "
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black"
                                                                            for="nombre_banco">Nombre Banco</label>
                                                                        <input required type="text" id="nombre_banco"
                                                                            name="nombre_banco"
                                                                            class="form-control titulo"
                                                                            value="{{ $pagarporoficina->nombre_banco_cliente ?? '' }}"
                                                                            placeholder="Nombre Banco...">
                                                                    </div>
                                                                </div>



                                                                {{-- <div
                                                                    class="col-lg-3 col-md-3 col-sm-3 col-xs-12 codigo"
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black"
                                                                            for="num_documento">Código</label>
                                                                        <input required type="number" id="codigo"
                                                                            name="codigo" class="form-control enteros"
                                                                            value="{{old('codigo')}}"
                                                                            placeholder="Código...">
                                                                    </div>
                                                                </div> --}}

                                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 numCuenta"
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black" for="direccion">Número
                                                                            de cuenta</label>
                                                                        <input required type="text" id="num_cuenta"
                                                                            name="num_cuenta"
                                                                            class="form-control mayuscula"
                                                                            value="{{ $pagarporoficina->num_cuenta_cliente ?? '' }}"
                                                                            placeholder="Número de cuenta...">
                                                                    </div>
                                                                </div>


                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tipoCuenta"
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black" for="tipo_cuenta">Tipo
                                                                            de cuenta</label>
                                                                        <select required class="form-control"
                                                                            id="tipo_cuenta" name="tipo_cuenta">
                                                                            @if ($pagarporoficina->tipo_cuenta_cliente
                                                                            == 'Corriente')
                                                                            <option value="Corriente" selected>
                                                                                Corriente</option>
                                                                            <option value="Ahorro">Ahorro</option>
                                                                            @else
                                                                            <option value="Corriente">Corriente
                                                                            </option>
                                                                            <option value="Ahorro" selected>Ahorro
                                                                            </option>
                                                                            @endif
                                                                            {{-- <option value="0">Seleccione tipo de
                                                                                cuenta</option>
                                                                            <option value="Corriente">Corriente</option>
                                                                            <option value="Ahorro">Ahorro</option> --}}
                                                                        </select>

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 telefonoMobil"
                                                                    style="display:none">
                                                                    <div class="form-group">
                                                                        <label class="text-black"
                                                                            for="pago_movil">Teléfono</label>
                                                                        <input required type="text" name="pago_movil"
                                                                            class="form-control"
                                                                            data-inputmask='"mask": "(9999) 999-9999"'
                                                                            data-mask
                                                                            value="{{ $pagarporoficina->telefono_pago_movil_cliente ?? '' }}"
                                                                            placeholder="Pago mobil...">
                                                                    </div>
                                                                </div>



                                                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="imagen">Imagen</label>
                                                                        <input required type="file" name="imagen"
                                                                            class="form-control" accept="image/*">
                                                                    </div>
                                                                </div> --}}

                                                            </div>
                                                            <!-- /.table-responsive -->
                                                        </div>


                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <input class="text-black hidden" type="text"
                                                                id="dcliente_id" name="dcliente_id"
                                                                value="{{ $pagarporoficina->persona_id ?? '' }}">
                                                            <input class="text-black hidden" type="text" id="motivo"
                                                                name="motivo" value="servicio">
                                                            <input class="text-black hidden" type="text" id="caja_id"
                                                                name="caja_id" value="{{ $caja->id ?? '' }}">
                                                            <input class="text-black hidden" id="deudaPendiente"
                                                                name="deudaPendiente"
                                                                value="{{ $pagarporoficina_deuda->deuda_total_acumulada }}">
                                                            <input class="text-black hidden" id="sucursal_id"
                                                                name="sucursal_id"
                                                                value="{{ $caja->sucursal_id ?? '' }}">
                                                            <input id="total_costo" name="total_costo" type="hidden"
                                                                value="{{ $pagarporoficina_deuda->deuda_total_acumulada }}">

                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="dcliente_id" name="dcliente_id"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="banco_id" name="banco_id"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="bandera" name="bandera"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="excedente" name="excedente"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="servicio_id" name="servicio_id"
                                                                value="{{$servicio->id ?? ''}}"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="num_servicio" name="num_servicio"
                                                                value="{{$servicio->num_servicio ?? ''}}"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="motivo" name="motivo" value="servicio"> --}}
                                                            {{-- <input class="text-black hidden" type="text"
                                                                id="caja_id" name="caja_id"
                                                                value="{{$servicio->caja_id ?? ''}}"> --}}
                                                            <button class="btn btn-primary" id="guardarFormaPago"
                                                                type="button">Guardar</button>
                                                            {{-- <a class="btn btn-danger"
                                                                href="{{ url()->previous() }}">{{__('Regresar')}}</a>
                                                            --}}
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
    {{-- <button name="procesarServicioPendiente" id="procesarServiciopendiente" class="btn btn-outline ocular"
        type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio Pendientes</button> --}}
    {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn
        btn-outline">Procesar Servicio</a> --}}
</div>
{{-- </form> --}}
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>

@push('sciptsMain')
<script language="javascript">
    $(document).ready(function() {

            $('#modalPagoPendienteOpciones').modal('toggle')
            // Comprobacion usando funcion .is()

            // console.log("Checkbox transferencia");
            if ($('.transferencia').is(':checked')) {
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
            } else {
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


                if ($('.pagomobil').is(':checked')) {
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.telefonoMobil').css('display', 'block');
                } else {
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.telefonoMobil').css('display', 'none');

                    if ($('.efectivo').is(':checked')) {
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    } else {
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }


            // console.log("Checkbox pagomobil");
            if ($('.pagomobil').is(':checked')) {
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                $('.datosBanco').css('display', 'block');
                $('.selecctBanco').css('display', 'block');
                $('.nombreBanco').css('display', 'block');
                $('.telefonoMobil').css('display', 'block');
            } else {
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                $('.datosBanco').css('display', 'none');
                $('.selecctBanco').css('display', 'none');
                $('.nombreBanco').css('display', 'none');
                $('.telefonoMobil').css('display', 'none');

                if ($('.transferencia').is(':checked')) {
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
                } else {
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

                    if ($('.efectivo').is(':checked')) {
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                    } else {
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                    }
                }


            }


            // console.log("Checkbox efectivo");
            if ($('.efectivo').is(':checked')) {
                $('.pagarPorOficina').css('display', 'block');
                $('.seleccioneCliente').css('display', 'block');
                $('.nombre').css('display', 'block');
                $('.cedula').css('display', 'block');
                // $('.datosBanco').css('display', 'block');
                // $('.selecctBanco').css('display', 'block');
                // $('.nombreBanco').css('display', 'block');
                // $('.telefonoMobil').css('display', 'block');
            } else {
                $('.pagarPorOficina').css('display', 'none');
                $('.seleccioneCliente').css('display', 'none');
                $('.nombre').css('display', 'none');
                $('.cedula').css('display', 'none');
                // $('.datosBanco').css('display', 'none');
                // $('.selecctBanco').css('display', 'none');
                // $('.nombreBanco').css('display', 'none');
                // $('.telefonoMobil').css('display', 'none');

                if ($('.transferencia').is(':checked')) {
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
                } else {
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

                    if ($('.pagomobil').is(':checked')) {
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                        $('.datosBanco').css('display', 'block');
                        $('.selecctBanco').css('display', 'block');
                        $('.nombreBanco').css('display', 'block');
                        $('.telefonoMobil').css('display', 'block');
                    } else {
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


            $('.transferencia').click(function() {
                if ($(this).is(':checked')) {
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
                } else {
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


                    if ($('.pagomobil').is(':checked')) {
                        $('.pagarPorOficina').css('display', 'block');
                        $('.seleccioneCliente').css('display', 'block');
                        $('.nombre').css('display', 'block');
                        $('.cedula').css('display', 'block');
                        $('.datosBanco').css('display', 'block');
                        $('.selecctBanco').css('display', 'block');
                        $('.nombreBanco').css('display', 'block');
                        $('.telefonoMobil').css('display', 'block');
                    } else {
                        $('.pagarPorOficina').css('display', 'none');
                        $('.seleccioneCliente').css('display', 'none');
                        $('.nombre').css('display', 'none');
                        $('.cedula').css('display', 'none');
                        $('.datosBanco').css('display', 'none');
                        $('.selecctBanco').css('display', 'none');
                        $('.nombreBanco').css('display', 'none');
                        $('.telefonoMobil').css('display', 'none');

                        if ($('.efectivo').is(':checked')) {
                            $('.pagarPorOficina').css('display', 'block');
                            $('.seleccioneCliente').css('display', 'block');
                            $('.nombre').css('display', 'block');
                            $('.cedula').css('display', 'block');
                        } else {
                            $('.pagarPorOficina').css('display', 'none');
                            $('.seleccioneCliente').css('display', 'none');
                            $('.nombre').css('display', 'none');
                            $('.cedula').css('display', 'none');
                        }
                    }


                }

            });

            $('.pagomobil').click(function() {
                if ($(this).is(':checked')) {
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');
                    $('.datosBanco').css('display', 'block');
                    $('.selecctBanco').css('display', 'block');
                    $('.nombreBanco').css('display', 'block');
                    $('.telefonoMobil').css('display', 'block');
                } else {
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');
                    $('.datosBanco').css('display', 'none');
                    $('.selecctBanco').css('display', 'none');
                    $('.nombreBanco').css('display', 'none');
                    $('.telefonoMobil').css('display', 'none');

                    if ($('.transferencia').is(':checked')) {
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
                    } else {
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

                        if ($('.efectivo').is(':checked')) {
                            $('.pagarPorOficina').css('display', 'block');
                            $('.seleccioneCliente').css('display', 'block');
                            $('.nombre').css('display', 'block');
                            $('.cedula').css('display', 'block');
                        } else {
                            $('.pagarPorOficina').css('display', 'none');
                            $('.seleccioneCliente').css('display', 'none');
                            $('.nombre').css('display', 'none');
                            $('.cedula').css('display', 'none');
                        }
                    }


                }
            });


            $('.efectivo').click(function() {
                if ($(this).is(':checked')) {
                    $('.pagarPorOficina').css('display', 'block');
                    $('.seleccioneCliente').css('display', 'block');
                    $('.nombre').css('display', 'block');
                    $('.cedula').css('display', 'block');


                } else {
                    $('.pagarPorOficina').css('display', 'none');
                    $('.seleccioneCliente').css('display', 'none');
                    $('.nombre').css('display', 'none');
                    $('.cedula').css('display', 'none');

                    if ($('.transferencia').is(':checked')) {
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
                    } else {
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

                        if ($('.pagomobil').is(':checked')) {
                            $('.pagarPorOficina').css('display', 'block');
                            $('.seleccioneCliente').css('display', 'block');
                            $('.nombre').css('display', 'block');
                            $('.cedula').css('display', 'block');
                            $('.datosBanco').css('display', 'block');
                            $('.selecctBanco').css('display', 'block');
                            $('.nombreBanco').css('display', 'block');
                            $('.telefonoMobil').css('display', 'block');
                        } else {
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

        selFactura = function(total_costo_selec, Ids_selec, facturas_pagadas) {
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

        var cont = 0;
        var total = parseFloat(0.00);
        var porEspecial = null;
        var isDolar = null;
        var isPeso = null;
        var isTransPunto = null;
        var isMixto = null;
        var isEfectivo = null;
        var isKilo = null;
        var stock = 0;
        // $("#modalPagoPendienteOpcionesBtn").click();

        // var totalp=parseFloat(0.00);
        // var totaltp=parseFloat(0.00);
        // var totalm=parseFloat(0.00);
        // var totale=parseFloat(0.00);

        var total_d = parseFloat(0.00);
        var total_p = parseFloat(0.00);
        var total_tp = parseFloat(0.00);
        var total_m = parseFloat(0.00);
        var total_e = parseFloat(0.00);
        var total_costo = parseFloat(0.00);

        var precio_costo = parseFloat(0.00);
        var jporEspecial = parseFloat(0.00);
        var precio_venta = parseFloat(0.00);
        var precio_compra = parseFloat(0.00);

        subtotal = [];

        subtotalPC = [];
        subtotald = [];
        subtotalp = [];
        subtotaltp = [];
        subtotalm = [];
        subtotale = [];
        subtotalc = [];

        var tasaD = parseFloat($("#TasaDolar").val());
        var tasaP = parseFloat($("#TasaPeso").val());
        var tasaTP = parseFloat($("#tasaTransPunto").val());
        var tasaM = parseFloat($("#tasaMixto").val());
        var tasaE = parseFloat($("#tasaEfectivo").val());
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
        $("#countVueltosPendientes").html('$' + VueltosvtosPendientes);

        $("#devolverVueltos").click(function() {

            $("#selec_cliente").val('default').selectpicker("refresh");
            $("#selec_banco").val('default').selectpicker("refresh");
            $("#edit_pago")[0].reset();

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
        //const tituloPagarCrear = document.getElementById('tituloPagarCrear');
        //const box_PagarCrear = document.getElementById('box_PagarCrear');

        $("#pagarPorOficinaBtn").on('click', function() {

            $("#selec_cliente").val('default').selectpicker("refresh");
            $("#selec_banco").val('default').selectpicker("refresh");
            $("#edit_pago")[0].reset();

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
            $("#edit_pago")[0].reset();
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
            if (!num_transaccion) {
                alert('!Error. Debe ingresar un número de operación...');
                return false;
            }
            $("#edit_pago").submit();
            return false;
        });
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $("#selec_banco_empresa").change(showValuesBancoEmpresa);


        $("#selec_banco_empresa").on("change", function() {
            // document.getElementById("tipo_documento").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });
        $("#selec_banco_empresa").on("change", function() {
            $("#num_cuenta_empresa").val('');
            document.getElementById("num_cuenta_cliente").focus();
            // $("#jidarticulo").val('0');
            // document.getElementById('jidarticulo').val('0');
        });



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









        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






        $("#guardar").hide();
        $("#gestionpago").hide();
        $("#gestionpago_boton").show();

        $('#bt_addD').show();
        $('#bt_addP').show();
        $('#bt_addTP').show();
        $('#bt_addM').show();
        $('#bt_addE').show();
        $("#procesarkilos").hide();
        $("#vueltos").hide();

        $("#cortesia").hide();
        $("#credito").hide();

        function is_negative_number(number = 0) {

            if ((is_numeric(number)) && (number > 0)) {
                return true;
            } else {
                return false;
            }
        }


        $(document).ready(function() {
            $("#enviar").on('click', function() {
                $("#form1").submit();
            });








        });

        function numDecimal(valor) {
            let result = Number(valor).toFixed(3);

            return result;
        }



        $(document).ready(function() {
            // calculo();





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

        function numDecimalExp(valor) {
            let result = Number((valor)).toFixed(3);
            return result;
        }



        function verify() {

            if (total > 0) {
                $('#gestionpago').show("linear");
            } else {
                $('#gestionpago').hide("linear");
            }
        }



        function multiplicar(decimal) {
            return decimal * 1000;
        }

        function dividir(decimal) {
            return decimal / 1000;
        }


        $(document).ready(function() {
            $("#mostrar").click(function() {
                $('.target').show("swing");
            });
            $("#ocultar").click(function() {
                $('.target').hide("linear");
            });
        });
</script>
@endpush
