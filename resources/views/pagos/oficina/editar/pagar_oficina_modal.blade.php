@extends ('layouts.admin3')
@section('contenido')
<div class="modal fade bs-example-modal-xm refrescar" id="modalPagoPendienteOpciones" role="dialog"
    aria-labelledby="myModalLabel">
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
                                        <h3 class="box-title">Debe devolver al cliente (<b class="text-danger"
                                                id="countVueltosPendientes">${{ $pagarporoficina_deuda->deuda_total_acumulada ?? '' }}</b>). (De vueltos pendiente)...!</h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div id="btnPago2Opciones">
                                            {{--  <div id="contado2Opciones"  --}}
                                                class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 small">
                                                {{-- <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a> --}}
                                                {{--  <button type="botton" name="devolverVueltos" id="devolverVueltos"
                                                    class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">
                                                    Contado</button>  --}}
                                            {{--  </div>  --}}

                                            <div id="precortesia2Opciones"
                                                class="panel-group col-lg-12 col-sm-12 col-md-12 col-xs-12 small">
                                                {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                <button type="botton" name="pagarPorOficinaBtn" id="pagarPorOficinaBtn"
                                                    class="btn btn-sm btn-warning btn-block col-lg-pull-2 small"> Pagar
                                                    Por Oficina</button>
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

                                            {{-- <div id="precredito2Opciones" class="panel-group col-lg-4 col-sm-4 col-md-4 col-xs-12 small">

                                        <button type="botton"  name="crearCuentaBtn"  id="crearCuentaBtn" class="btn btn-sm btn-success btn-block col-lg-pull-2 small"> Crear Cuenta</button>
                                    </div> --}}

                                        </div>
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div>
                        <div id="contentPagarOficina">
                            <form id="guardar_form"autocomplete="off">

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
                                                                <h4 id="trans_" class="text-primary" style="margin-top: 0px !important;">
                                                                    Transferencia:
                                                                    &nbsp;&nbsp;&nbsp; <input class="transferencia" @if ($pagarporoficina->isTransferencia ==
                                                                    '1') checked @elseif (old('isTransferencia') ==
                                                                    '1') checked @endif
                                                                    name="isTransferencia" type="checkbox"></h4>
                                                            </td>


                                                            <td>
                                                                <h4 id="mobil_" class="text-primary" style="margin-top: 0px !important;">Pago Mobil:
                                                                    &nbsp;&nbsp;&nbsp;<input class="pagomobil" @if ($pagarporoficina->isPagoMobil == '1')
                                                                    checked @elseif (old('isPagoMobil') == '1')
                                                                    checked @endif
                                                                    name="isPagoMobil" type="checkbox"></h4>
                                                            </td>


                                                            <td>
                                                                <h4 class="text-primary" style="margin-top: 0px !important;">Efectivo:
                                                                    &nbsp;&nbsp;&nbsp;<input class="efectivo" @if ($pagarporoficina->isEfectivo == '1')
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
                                            <h3 class="box-title"><b class="text-warning" id="tituloPagarCrear">Pagar
                                                    Por Oficina</b></h3><br>
                                            Datos de cliente:
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <div id="formPagarOficina">


                                                <div class="row">

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nombre" style="display:none">
                                                        <div class="form-group">
                                                            <label class="text-black" for="nombre">Nombre del
                                                                cliente</label>
                                                            <input required readonly type="text" id="nombre" name="nombre" class="form-control titulo"
                                                                value="{{ $pagarporoficina->nombre_cliente ?? '' }}" placeholder="Nombre...">
                                                        </div>
                                                    </div>



                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 cedula" style="display:none">
                                                        <div class="form-group">
                                                            <label class="text-black" for="num_documento">Número de
                                                                Documento</label>
                                                            <input required readonly type="number" id="num_documento" name="num_documento" class="form-control enteros"
                                                                value="{{ $pagarporoficina->cedula_cliente ?? '' }}" placeholder="Número de Documento...">
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
                                                <div id="datosBanco" class="box box-default datosBanco"
                                                    style="display:none">

                                                    <!-- /.box-header -->
                                                    <div class="box-body">
                                                        <div class="row">

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 selecctBanco "
                                                                style="display:none">
                                                                <div class="box-header with-border">
                                                                    <!-- {{-- <h3 class="box-title">Conceder Privilegios</h3> --}} -->
                                                                    <br>
                                                                    Datos Bancarios:
                                                                </div>
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
                                                                                value="{{ $banco->id }}_{{ $banco->nombre_banco }}_{{ $banco->codigo }}">
                                                                                {{ $banco->nombre_banco }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  nombreBanco " style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre_banco">Nombre Banco</label>
                                                                    <input type="text" id="nombre_banco" name="nombre_banco" class="form-control titulo"
                                                                        value="{{ $pagarporoficina->nombre_banco_cliente ?? '' }}" placeholder="Nombre Banco...">
                                                                </div>
                                                            </div>



                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 codigo"
                                                                style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black"
                                                                        for="num_documento">Código</label>
                                                                    <input readonly type="number" id="codigo"
                                                                        name="codigo" class="form-control enteros"
                                                                        value="{{ old('codigo') }}"
                                                                        placeholder="Código...">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 numCuenta" style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="direccion">Número
                                                                        de cuenta</label>
                                                                    <input type="text" id="num_cuenta" name="num_cuenta" class="form-control mayuscula"
                                                                        value="{{ $pagarporoficina->num_cuenta_cliente ?? '' }}" placeholder="Número de cuenta...">
                                                                </div>
                                                            </div>


                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tipoCuenta" style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="tipo_cuenta">Tipo
                                                                        de cuenta</label>
                                                                    <select class="form-control" id="tipo_cuenta" name="tipo_cuenta">
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
                                                                        for="pago_mobil">Teléfono</label>
                                                                    <input type="text" id="pago_mobil" name="pago_mobil"
                                                                        class="form-control"
                                                                        data-inputmask='"mask": "(9999) 999-9999"'
                                                                        data-mask value="{{ $pagarporoficina->telefono_pago_movil_cliente ?? '' }}"
                                                                        placeholder="Pago mobil...">
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
                                                        <input class="text-black hidden" type="text"
                                                            id="dcliente_id" name="dcliente_id" value="{{ $pagarporoficina->persona_id }}">
                                                        <input class="text-black hidden" type="text"
                                                            id="banco_id" name="banco_id">
                                                        <input class="text-black hidden" type="text"
                                                            id="bandera" name="bandera">
                                                        <input class="text-black hidden" type="text"
                                                            id="excedente" name="excedente">
                                                        <input class="text-black hidden" type="text"
                                                            id="servicio_id" name="servicio_id"
                                                            value="{{ $servicio->id ?? '' }}">
                                                        <input class="text-black hidden" type="text"
                                                            id="num_servicio" name="num_servicio"
                                                            value="{{ $servicio->num_servicio ?? '' }}">
                                                        <input class="text-black hidden" type="text"
                                                            id="motivo" name="motivo" value="servicio">
                                                        <input class="text-black hidden" type="text"
                                                            id="caja_id" name="caja_id"
                                                            value="{{ session('session_caja')->id ?? '' }}">
                                                        <button class="btn btn-primary " id="guardarFormaPago"
                                                            type="submit">Guardar</button>
                                                            <input id="vtosPendientes" name="vtosPendientes" value="{{ $pagarporoficina_deuda->deuda_total_acumulada ?? '' }}" type="hidden">
                                                            <input id="VueltosvtosPendientes" name="VueltosvtosPendientes"
                                                                value="{{ $pagarporoficina_deuda->deuda_total_acumulada ?? '' }}" type="hidden">
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
    <a href="{{route('caja.show', session('session_caja')->id) ?? ''}}" class="btn btn-outline pull-left" >Cancelar</a>

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

<script>
    //ACTUALIZAR UN REGISTRO
$('#guardar_form').submit(function(e){
    e.preventDefault();
    var id2 = $('#dcliente_id').val();
    var nombre = $('#nombre').val();
    var num_documento = $('#num_documento').val();
    var nombre_banco = $('#nombre_banco').val();
    var codigo = $('#codigo').val();
    var num_cuenta = $('#num_cuenta').val();
    var tipo_cuenta = $('#tipo_cuenta').val();
    var pago_mobil = $('#pago_mobil').val();
    var caja_id = $('#caja_id').val();

    var isTransferencia = $("input[name='isTransferencia']:checked").val();
    var isPagoMobil = $("input[name='isPagoMobil']:checked").val();
    var isEfectivo = $("input[name='isEfectivo']:checked").val();
    var isEfectivo = $("input[name='isEfectivo']:checked").val();
    var _token2 = $("input[name=_token]").val();

    $.ajax({
        url: "{{ route('guardar') }}",
        type: "POST",
        data:{
            id:id2,
            nombre:nombre,
            num_documento:num_documento,
            nombre_banco:nombre_banco,
            codigo:codigo,
            num_cuenta:num_cuenta,
            tipo_cuenta:tipo_cuenta,
            pago_mobil:pago_mobil,
            caja_id:caja_id,
            isTransferencia:isTransferencia,
            isPagoMobil:isPagoMobil,
            isEfectivo:isEfectivo,
            _token:_token2
        },
        success:function(response){
            if(response){
                let urlback = "{{route('caja.show', session('session_caja')->id) ?? ''}}";
                $(location).attr('href',urlback);
                //console.log('response', caja_id);
                //$('#animal_edit_modal').modal('hide');
                //toastr.info('El registro fue actualizado correctamente.', 'Actualizar Registro', {timeOut:3000});
                //$('#tabla-animal').DataTable().ajax.reload();
            }
        }
    })
});
</script>
<script>
$("#modalPagoPendienteOpcionesBtn").click();

    var vtosPendientes = $('#vtosPendientes').val();

    // alert(vtosPendientes);
    $("#dispExcedente").val(vtosPendientes);
    $("#modalPagoPendienteOpcionesBtn").on('click', function() {
    fncSumar();

    procesoCambioSalida = 3;
    if (VueltosvtosPendientes > 0) {
    $("#banderaHorasExtras").val('pagarVueltosPendientes');
    // alert('total pendiente '+totalPendiente);

    const RestaTotalV = document.getElementById('RestaTtotalV');

    let totalPendt1 = new Decimal(VueltosvtosPendientes);

    RestaTotalV.innerHTML = numDecimal(totalPendt1); //se llena el campo resta
    PagoTtotalV.innerHTML = numDecimal(totalPendt1);
    // verify();

    // $("#modalPago").click();

    $("#total_costo").val('');


    $("#VueltospagoConExcedente").val('');

    $("#banderaHorasExtras").val('pagarVueltosPendientes');

    $("#countVueltosPendientes").html('$' + VueltosvtosPendientes);

    } else {
    // $("#banderaHorasExtras").val('');
    alert('No posee vueltos pendiente...');
    return false;
    }


    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $("#selec_banco").change(showValuesBanco);

    $("#selec_banco").on("change", function() {
    // document.getElementById("tipo_documento").focus();
    // $("#jidarticulo").val('0');
    // document.getElementById('jidarticulo').val('0');
    });
    $("#selec_banco").on("change", function() {
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
$("#contentPagarOficina").hide();
$("#contentCrearCuenta").hide();
const tituloPagarCrear = document.getElementById('tituloPagarCrear');
const box_PagarCrear = document.getElementById('box_PagarCrear');

$("#pagarPorOficinaBtn").on('click', function() {
$('.pagarPorOficina').css('display', 'none');
$("#selec_cliente").val('default').selectpicker("refresh");
$("#selec_banco").val('default').selectpicker("refresh");
$("#guardar_form")[0].reset();

// const RestaTotal = document.getElementById('RestaTtotal');

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
$("#guardar_form")[0].reset();
$("#datosBanco").hide('swing');
// const tituloPagarCrear = document.getElementById('tituloPagarCrear');

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

$("#selec_cliente").change(showValuesCliente);

$("#selec_cliente").on("change", function() {
// document.getElementById("tipo_documento").focus();
// $("#jidarticulo").val('0');
// document.getElementById('jidarticulo').val('0');
});
$("#tipo_documento").on("change", function() {
// document.getElementById("selec_banco").focus();
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

    $("#devolverVueltos").click(function() {

    $("#selec_cliente").val('default').selectpicker("refresh");
    $("#selec_banco").val('default').selectpicker("refresh");
    $("#guardar_form")[0].reset();

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
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $("#dualbtn").click(function() {
    let valorDeuda = $('#total').val();

    // return false;
    if (valorDeuda > 0) {
    // alert(valorDeuda);
    // return false;
    $("#pagoPendienteBtn").click();
    // console.log('tienes deuda pendiente'+$valorDeuda);
    return false;
    }

    if (VueltosvtosPendientes > 0) {
    // alert(VueltosvtosPendientes);
    $("#modalPagoPendienteOpcionesBtn").click();
    $("#countVueltosPendientes").html('$' + VueltosvtosPendientes);




    return false;
    }
    alert('No posee deuda ni hay vueltos pendientes por entregar...');
    return false;



    });

    $(document).ready(function() {
    $("#imprimirBoleta").click(function() {
    let valorDeuda = $('#total').val();

    // return false;
    if (valorDeuda > 0) {
    // alert(valorDeuda);
    // return false;
    $("#pagoPendienteBtn").click();
    // console.log('tienes deuda pendiente'+$valorDeuda);
    return false;
    }

    if (VueltosvtosPendientes > 0) {
    // alert(VueltosvtosPendientes);
    $("#modalPagoPendienteOpcionesBtn").click();
    $("#countVueltosPendientes").html('$' + VueltosvtosPendientes);




    return false;
    }
    $("#form1").submit();
    return false;



    });
    });




    $("#devolverVueltos").click(function() {

    $("#selec_cliente").val('default').selectpicker("refresh");
    $("#selec_banco").val('default').selectpicker("refresh");
    $("#guardar_form")[0].reset();

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
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


</script>

    <script>
        $(document).ready(function() {
            //$('#modalPagoPendienteOpciones').modal('toggle')
            $('#modalPagoPendienteOpciones').modal({backdrop: 'static', keyboard: false})

            // #####################################################################################################
            // Maneja el comportamiento de los checkbox, permite mostrar los campos en el form4
            // #####################################################################################################

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

            // #####################################################################################################
            // Se encarga de traer los clentes de la base de datos del archivo searchController y manejar las validaciones del apartado efectivo
            // #####################################################################################################

            $('#nombrea').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('search.personas') }}",
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data)
                            console.log('respuesta ', data.item);

                        }

                    });

                },
                response: function(event, ui) {
                    if (!ui.content.length) {
                        $("#dcliente_id").val('');
                        $("#nombre").val('');
                        $("#num_documento_oficina").val('');
                        $("#direccion").val('');
                        $("#telefono").val('');
                        $("#email").val('');
                        $("#nombreamesagge").removeClass("text-green");
                        $("#nombreamesagge").addClass("text-red");
                        $("#nombreamesagge").text('!Cliente no encontrado...');
                        $("#guardarFormaPago").addClass("hidden");

                        $("#nombre").removeAttr("readonly");
                        // $("#nombre").addClass("readonly");
                        $("#nombre").blur();
                        $("#nombre").keyup();


                    }
                },
                minLength: 3,
                select: function(event, ui) {
                    // alert(ui.item.label);
                    // $("#nombrea").val(ui.item.label);
                    $("#dcliente_id").val(ui.item.id);
                    $("#nombre").val(ui.item.nombre);
                    $("#num_documento_oficina").val(ui.item.num_documento);
                    $("#direccion").val(ui.item.direccion);
                    $("#telefono").val(ui.item.telefono);
                    $("#email").val(ui.item.email);
                    $("#nombrea").val('');
                    // $('#codigo').val(ui.item.codigo)
                    $("#nombreamesagge").removeClass("text-red");
                    $("#nombreamesagge").addClass("text-green");
                    $("#nombreamesagge").text('¡Se ve bien!');

                    // $("#nombre").removeClass("text-red");
                    $("#nombre").attr("readonly", "readonly");
                    // $("#nombre").addClass("readonly");
                    $("#nombre").blur();
                    $("#nombre").keyup();
                    return false;
                },
                // focus: function( event, ui ) {
                //     $("#num_documento_oficina").val(ui.item.num_documento);
                //     $("#nombre").val(ui.item.nombre);
                //     $("#nombre").attr("readonly","readonly");
                //     $("#nombre").keyup();
                //     // $("#nombre").blur();
                // },
            });

            $("#num_documento_oficina").click(function() {

                let leng = $(this).val().length;

                if (leng < 7) {
                    $("#num_documento_oficina_mesagge").removeClass("text-green");
                    $("#num_documento_oficina_mesagge").addClass("text-red");
                    $("#num_documento_oficina_mesagge").text('!Tiene que ingrasar mas de 7 numeros...');
                    $("#guardarFormaPago").addClass("hidden");
                    return false;
                }

                $("#num_documento_oficina_mesagge").removeClass("text-red");
                $("#num_documento_oficina_mesagge").addClass("text-green");
                $("#num_documento_oficina_mesagge").text('¡Se ve bien!');

            });

            $("#nombre").keyup(function() {
                $("#num_documento_oficina").click();

                let leng = $(this).val().length;

                if (leng < 4) {
                    $("#nombre_mesagge").removeClass("text-green");
                    $("#nombre_mesagge").addClass("text-red");
                    $("#nombre_mesagge").text('!Tiene que ingrasar mas de 4 caracteres...');
                    $("#guardarFormaPago").addClass("hidden");
                    return false;
                }

                $("#nombre_mesagge").removeClass("text-red");
                $("#nombre_mesagge").addClass("text-green");
                $("#nombre_mesagge").text('¡Se ve bien!');
                $("#guardarFormaPago").removeClass("hidden");


            });

            $("#nombre").blur(function() {
                let is_num_documento = $("#num_documento_oficina").val();
                let is_nombre = $("#nombre").val();
                let leng = $(this).val().length;

                if (is_num_documento == '' || is_nombre == '' || leng < 4) {
                    $("#guardarFormaPago").addClass("hidden");
                    $("#nombre").keyup();
                } else {
                    $("#nombre").keyup();
                    $("#guardarFormaPago").removeClass("hidden");
                }
            });

            $("#nombrea").blur(function() {
                let is_num_documento = $("#num_documento_oficina").val();
                let is_nombre = $("#nombre").val();
                let is_nombrea = $("#nombrea").val();
                let is_dcliente_id = $("#dcliente_id").val();
                if (is_dcliente_id === '' && !is_nombrea == '') {
                    document.getElementById("nombre").focus();
                    $("#num_documento_oficina").val(is_nombrea);
                    $("#nombre").removeAttr("readonly");
                    $("#nombre").blur();
                    $("#nombre").keyup();

                } else if (is_dcliente_id) {
                    $("#nombre").attr("readonly", "readonly");
                    document.getElementById("nombre").focus();
                    $("#nombre").blur();
                    $("#nombre").keyup();
                    $("#guardarFormaPago").removeClass("hidden");
                } else {
                    $("#nombre").blur();
                    $("#nombre").keyup();

                }

            });

            // #####################################################################################################
            // Controla los eventos del boton del formulario de pagar por oficina
            // #####################################################################################################

            //$("#guardarFormaPago").on('click', function() {
//
            //    if ($('#isTransferencia').prop('checked')) {
            //        alert('isTransferencia seleccionado');
            //    } else {
            //        alert('isTransferencia deseleccionado');
            //    }
//
            //    if ($('#isPagoMobil').prop('checked')) {
            //        alert('isPagoMobil seleccionado');
            //    } else {
            //        alert('isPagoMobil deseleccionado');
            //    }
//
            //    if ($('#isEfectivo').prop('checked')) {
            //        alert('isEfectivo seleccionado');
            //    } else {
            //        alert('isEfectivo deseleccionado');
            //    }
//
            //    $("#guardar_form").submit();
            //    // alert('form4 ' + isEfectivo);
            //    return false;
//
            //});
            // #####################################################################################################
            //
            // #####################################################################################################

            // #####################################################################################################
        });
    </script>
@endpush
