<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="pagopendiente" tabindex="-1" role="dialog"
    aria-labelledby="pagopendiente" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLongTitle">Pagos pendientes</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reintegros.store') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" id="pnombre_cliente" name="pnombre_cliente">
                <input type="hidden" id="phistorial_id" name="phistorial_id">
                <input type="hidden" id="pcliente_id" name="pcliente_id">

                <div class="modal-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> --}}
                                <div class="form-group">
                                    <label for="vp">Cliente</label>
                                    <select name="vp" id="vp" class="form-control selectpicker" data-live-search="true">
                                        <option value=''>Seleccione</option>
                                        @php
                                        $vp_ids = [290,467];
                                        @endphp
                                        @foreach ($clientes_vueltos as $vp)
                                        $vp_ids[] = $vp->cliente_id;
                                        @php $cliente = "App\Persona"::where('id', $vp->cliente_id)->first(); @endphp
                                        <option
                                            value='{{ $vp->id }}_{{ $vp->deuda_total_acumulada }}_{{ $vp->nombre_cliente }}_{{ $vp->cliente_id ?? '' }}_{{ $vp->monto_excedente_actual ?? '' }}_{{ URL::action("ExcedenteController@getPagoCliente", $vp->cliente_id) }}'>
                                            {{ $vp->nombre_cliente ?? '' }}</option>

                                        @endforeach

                                    </select>
                                </div>
                                {{--
                            </div> --}}
                        </div>
                    </div>
                    {{-- <div class="clearfix"></div> --}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                        <div id="result" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4><strong class="text-blue">Cliente: </strong><span id="nom_cliente">Nombre cliente</span>
                            </h4>
                            <h4><strong class="text-blue">Total vueltos: </strong><span
                                    id="total_vuelto_cliente">0.00</span></h4>
                            <h4><strong class="text-blue">Vueltos disponibles: </strong><span class='text-bold'
                                    id="deud_cliente">0.00</span></h4>
                            <p id="edit_pago_cliente"></p>

                        </div>

                    </div>



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pagar">
                        <hr>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Modo</th>
                                            <th>Cantidad</th>
                                            <th>Moneda</th>
                                            <th>Dolar</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Dolar</td>
                                            <td><input type="text" id="pcantidad_dolar_rep"
                                                    name="pcantidad_dolar_rep"><input type="hidden" id="pTasaDolar"
                                                    name="pTasaDolar" value="{{ $tasaDolar->tasa }}"></td>
                                            <td><b id="pdif_moneda_dolar_to_tasa">0.00</b><input type="hidden"
                                                    id="pdif_moneda_dolar_to_tasa_input"
                                                    name="pdif_moneda_dolar_to_tasa_input"></td>
                                            <td><b id="pdif_moneda_dolar_to_dolar">0.00</b><input onchange="psumar();"
                                                    class="pmonto" type="hidden" id="pdif_moneda_dolar_to_dolar_input"
                                                    name="pdif_moneda_dolar_to_dolar_input"></td>
                                            <input type="hidden" id="pdolar_sistema" name="pdolar_sistema">

                                        </tr>
                                        <tr>
                                            <td>Peso</td>
                                            <td><input type="text" id="pcantidad_peso_rep"
                                                    name="pcantidad_peso_rep"><input type="hidden" id="pTasaPeso"
                                                    name="pTasaPeso" value="{{ $tasaPeso->tasa }}"></td>
                                            <td><b id="pdif_moneda_peso_to_tasa">0.00</b><input type="hidden"
                                                    id="pdif_moneda_peso_to_tasa_input"
                                                    name="pdif_moneda_peso_to_tasa_input"></td>
                                            <td><b id="pdif_moneda_peso_to_dolar">0.00</b><input onchange="psumar();"
                                                    class="pmonto" type="hidden" id="pdif_moneda_peso_to_dolar_input"
                                                    name="pdif_moneda_peso_to_dolar_input"></td>
                                            <input type="hidden" id="ppeso_sistema" name="ppeso_sistema">
                                        </tr>
                                        {{-- <tr>
                                            <td>Punto</td>
                                            <td><input type="text" id="pcantidad_punto_rep"
                                                    name="pcantidad_punto_rep"><input type="hidden" id="pTasaPunto"
                                                    name="pTasaPunto" value="{{$tasaTransferenciaPunto->tasa}}"></td>
                                            <td><b id="pdif_moneda_punto_to_tasa">0.00</b><input type="hidden"
                                                    id="pdif_moneda_punto_to_tasa_input"
                                                    name="pdif_moneda_punto_to_tasa_input"></td>
                                            <td><b id="pdif_moneda_punto_to_dolar">0.00</b><input onchange="psumar();"
                                                    class="pmonto" type="hidden" id="pdif_moneda_punto_to_dolar_input"
                                                    name="pdif_moneda_punto_to_dolar_input"></td>
                                            <input type="hidden" id="ppunto_sistema" name="ppunto_sistema">
                                        </tr>
                                        <tr>
                                            <td>Trans</td>
                                            <td><input type="text" id="pcantidad_trans_rep"
                                                    name="pcantidad_trans_rep"><input type="hidden" id="pTasaTrans"
                                                    name="pTasaTrans" value="{{$tasaTransferenciaPunto->tasa}}"></td>
                                            <td><b id="pdif_moneda_trans_to_tasa">0.00</b><input type="hidden"
                                                    id="pdif_moneda_trans_to_tasa_input"
                                                    name="pdif_moneda_trans_to_tasa_input"></td>
                                            <td><b id="pdif_moneda_trans_to_dolar">0.00</b><input onchange="psumar();"
                                                    class="pmonto" type="hidden" id="pdif_moneda_trans_to_dolar_input"
                                                    name="pdif_moneda_trans_to_dolar_input"></td>
                                            <input type="hidden" id="ptrans_sistema" name="ptrans_sistema">
                                        </tr> --}}
                                        <tr>
                                            <td>Efectivo</td>
                                            <td><input type="text" id="pcantidad_efectivo_rep"
                                                    name="pcantidad_efectivo_rep"><input type="hidden" id="pTasaBolivar"
                                                    name="pTasaBolivar" value="{{ $tasaEfectivo->tasa }}"></td>
                                            <td><b id="pdif_moneda_efectivo_to_tasa">0.00</b><input type="hidden"
                                                    id="pdif_moneda_efectivo_to_tasa_input"
                                                    name="pdif_moneda_efectivo_to_tasa_input"></td>
                                            <td><b id="pdif_moneda_efectivo_to_dolar">0.00</b><input
                                                    onchange="psumar();" class="pmonto" type="hidden"
                                                    id="pdif_moneda_efectivo_to_dolar_input"
                                                    name="pdif_moneda_efectivo_to_dolar_input"></td>
                                            <input type="hidden" id="pefectivo_sistema" name="pefectivo_sistema">
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <h4><strong class="text-blue">Total pagado por Operador</strong></h4>
                                            </td>
                                            <td>
                                                <h4><b id="ptotal_operador_reg">0.00</b></h4><input type="hidden"
                                                    name="ptotal_operador_reg_input" id="ptotal_operador_reg_input">
                                            </td>

                                        </tr>
                                        {{-- @php
                                        dd(($cajas->SumaTotalVentas + $cajas->SumaTotalServicios));
                                        @endphp --}}
                                        <tr>
                                            <td colspan="3">
                                                <h4><strong class="text-blue">Total a pagar </strong></h4>
                                            </td>
                                            <td>
                                                <h4><b id="ptotal_sistema_reg">0.00</b></h4><input type="hidden"
                                                    name="ptotal_sistema_reg_input" id="ptotal_sistema_reg_input"
                                                    value="">
                                            </td>


                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <h4><strong class="text-blue">Diferencia</strong></h4>
                                            </td>
                                            <td>
                                                <h4><b id="ptotal_dif">0.00</b></h4><input type="hidden"
                                                    name="ptotal_dif_input" id="ptotal_dif_input">
                                            </td>


                                        </tr>
                                        <tr>
                                            <td>
                                                <h4><strong class="text-blue">Observaciones</strong></h4>
                                            </td>
                                            <input name="pcredito_id" id="pcredito_id" type="hidden"
                                                value="{{ $credito_id ?? '' }}">
                                            <td colspan="4">
                                                <textarea name="pObservaciones" id="pObservaciones" cols="50"
                                                    rows="4"></textarea>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
        </div>
    </div>
    </form>
</div>
@push('styles')
@endpush

@push('sciptsMain')
<script>
    $(document).ready(function() {
            $("#vp").change(showValuesCliente);

            var ptsri = $("#ptotal_sistema_reg_input").val();
            $("#ptotal_sistema_reg").html(pnumDecimal(ptsri));
            // $("#pdolar_sistema").val(ptsri);
            /* Sumar dos números. */
            function psumar() {
                // alert('suma');
                var ptotal_suma = 0;
                $(".pmonto").each(function() {
                    if (isNaN(parseFloat($(this).val()))) {
                        ptotal_suma += 0;
                    } else {
                        ptotal_suma += parseFloat($(this).val());
                    }
                });
                // alert(total_suma);
                document.getElementById('ptotal_operador_reg').innerHTML = pnumDecimal(ptotal_suma);
                $("#ptotal_operador_reg_input").val(ptotal_suma);

            }

            function pnumDecimal(pvalor) {
                let presult = Number(pvalor).toFixed(2);

                return presult;
            }

            /* Restar dos números. */
            function presta() {

                let preg_sistama = $("#ptotal_sistema_reg_input").val();
                let preg_operador = $("#ptotal_operador_reg_input").val();
                let presult = preg_operador - preg_sistama;

                $("#ptotal_dif_input").val(pnumDecimal(presult));
                $("#ptotal_dif").html(pnumDecimal(presult));
            }

            function showValuesCliente() {

                datosArticulo = document.getElementById('vp').value.split('_');
                $("#ptotal_sistema_reg_input").val(datosArticulo[1]);
                $("#nom_cliente").html(datosArticulo[2]);
                $("#deud_cliente").html(datosArticulo[1]);
                $("#pnombre_cliente").val(datosArticulo[2]);
                $("#phistorial_id").val(datosArticulo[0]);
                $("#pcliente_id").val(datosArticulo[3]);
                $("#total_vuelto_cliente").html(datosArticulo[4]);
                $("#edit_pago_cliente").html("<a id='modalPagoPendienteOpciones' href='"+datosArticulo[5]+"' class='btn btn-sm btn-danger btn-block col-lg-pull-2 small no-print'>EDITAR TIPO DE PAGO</a>");
                //$("#edit_pago_cliente").html("<a class='btn btn-success' href='{{route('editar', "datosArticulo[3]")}}'>{{__('EDITAR TIPO DE PAGO')}}</a>");

                var ptsri = $("#ptotal_sistema_reg_input").val();
                $("#ptotal_sistema_reg").html(pnumDecimal(ptsri));
                // $("#pdolar_sistema").val(ptsri);
                $("#total_costo").val(datosArticulo[1]);
                $("#deudaPendiente").val(datosArticulo[1]);
                //alert(datosArticulo[1]);
                $("#countVueltosPendientes").html('$' + datosArticulo[1]);



                pDMontoDolarRep();

                // historia_id           = datosArticulo[0]
                // deuda     = datosArticulo[1];
                // nombre_cliente         = datosArticulo[2];
            }

            pDMontoDolarRep();
            pDMontoPesoRep();
            pDMontoBolivarRep();
            pDMontoPuntoRep();
            pDMontoTransRep();

            function pDMontoDolarRep() {

                pMdolar = $("#pcantidad_dolar_rep").val();
                pMdolarSistema = $("#pdolar_sistema").val();

                pTdolar = $("#pTasaDolar").val();
                pTpeso = $("#pTasaPeso").val();
                pTbolivar = $("#pTasaBolivar").val();
                pTpunto = $("#pTasaPunto").val();
                pTtrans = $("#pTasaTrans").val();

                pTdolarToDolarSis = pMdolarSistema / pTdolar;

                pDsupTotal = pMdolar * pTdolar;

                pDsupTotalDolar = pDsupTotal * pTdolar;
                $("#pdif_moneda_dolar_to_tasa_input").val(pnumDecimal(pDsupTotalDolar));
                $("#pdif_moneda_dolar_to_tasa").html(pnumDecimal(pDsupTotalDolar - pMdolarSistema));
                $("#ptotal_dolar_dif").val(pnumDecimal(pDsupTotalDolar - pMdolarSistema));
                $("#ptotal_dolar").val(pDsupTotalDolar);
                $("#pdif_moneda_dolar_to_dolar_input").val(pnumDecimal(pDsupTotal));
                $("#pdif_moneda_dolar_to_dolar").html(pnumDecimal(pDsupTotal - pTdolarToDolarSis));

                psumar();

                presta();

            }

            function pDMontoPesoRep() {

                pMpeso = $("#pcantidad_peso_rep").val();
                pMpesoSistema = $("#ppeso_sistema").val();
                pTdolar = $("#pTasaDolar").val();
                pTpeso = $("#pTasaPeso").val();
                pTbolivar = $("#pTasaBolivar").val();
                pTpunto = $("#pTasaPunto").val();
                pTtrans = $("#pTasaTrans").val();

                pTpesoToDolarSis = pMpesoSistema / pTpeso;

                pPsupTotal = pMpeso / pTpeso;
                pPsupTotalDolar = pPsupTotal * pTpeso;
                $("#pdif_moneda_peso_to_tasa_input").val(pnumDecimal(pPsupTotalDolar));
                $("#pdif_moneda_peso_to_tasa").html(pnumDecimal(pPsupTotalDolar - pMpesoSistema));
                $("#ptotal_peso_dif").val(pnumDecimal(pPsupTotalDolar - pMpesoSistema));
                $("#ptotal_peso").val(pnumDecimal(pPsupTotalDolar));
                $("#pdif_moneda_peso_to_dolar_input").val(pnumDecimal(pPsupTotal));
                $("#pdif_moneda_peso_to_dolar").html(pnumDecimal(pPsupTotal - pTpesoToDolarSis));
                psumar();
                presta();

            }

            function pDMontoBolivarRep() {
                pMbolivar = $("#pcantidad_efectivo_rep").val();
                pMbolivarSistema = $("#pefectivo_sistema").val();
                pTdolar = $("#pTasaDolar").val();
                pTpeso = $("#pTasaPeso").val();
                pTbolivar = $("#pTasaBolivar").val();
                pTpunto = $("#pTasaPunto").val();
                pTtrans = $("#pTasaTrans").val();

                pTbolivarToDolarSis = pMbolivarSistema / pTbolivar;


                pBsupTotal = pMbolivar / pTbolivar;
                pBsupTotalDolar = pBsupTotal * pTbolivar;
                $("#pdif_moneda_efectivo_to_tasa_input").val(pnumDecimal(pBsupTotalDolar));
                $("#pdif_moneda_efectivo_to_tasa").html(pnumDecimal(pBsupTotalDolar - pMbolivarSistema));
                $("#ptotal_bolivar_dif").val(pnumDecimal(pBsupTotalDolar - pMbolivarSistema));
                $("#ptotal_bolivar").val(pnumDecimal(pBsupTotalDolar));
                $("#pdif_moneda_efectivo_to_dolar_input").val(pnumDecimal(pBsupTotal));
                $("#pdif_moneda_efectivo_to_dolar").html(pnumDecimal(pBsupTotal - pTbolivarToDolarSis));
                psumar();
                presta();

            }

            function pDMontoPuntoRep() {
                pMpunto = $("#pcantidad_punto_rep").val();
                pMpuntoSistema = $("#ppunto_sistema").val();
                pTdolar = $("#pTasaDolar").val();
                pTpeso = $("#pTasaPeso").val();
                pTbolivar = $("#pTasaBolivar").val();
                pTpunto = $("#pTasaPunto").val();
                pTtrans = $("#pTasaTrans").val();

                pTpuntoToDolarSis = pMpuntoSistema / pTpunto;


                pPTsupTotal = pMpunto / pTbolivar;
                pPTsupTotalDolar = pPTsupTotal * pTpunto;
                $("#pdif_moneda_punto_to_tasa_input").val(pnumDecimal(pPTsupTotalDolar));
                $("#pdif_moneda_punto_to_tasa").html(pnumDecimal(pPTsupTotalDolar - pMpuntoSistema));
                $("#ptotal_punto_dif").val(pnumDecimal(pPTsupTotalDolar - pMpuntoSistema));
                $("#ptotal_punto").val(pnumDecimal(pPTsupTotalDolar));
                $("#pdif_moneda_punto_to_dolar_input").val(pnumDecimal(pPTsupTotal));
                $("#pdif_moneda_punto_to_dolar").html(pnumDecimal(pPTsupTotal - pTpuntoToDolarSis));
                psumar();
                presta();

            }

            function pDMontoTransRep() {
                pMtrans = $("#pcantidad_trans_rep").val();
                pMtransSistema = $("#ptrans_sistema").val();
                pTdolar = $("#pTasaDolar").val();
                pTpeso = $("#pTasaPeso").val();
                pTbolivar = $("#pTasaBolivar").val();
                pTpunto = $("#pTasaPunto").val();
                pTtrans = $("#pTasaTrans").val();

                pTtransToDolarSis = pMtransSistema / pTtrans;


                pTsupTotal = pMtrans / pTtrans;
                pTsupTotalDolar = pTsupTotal * pTtrans;
                $("#pdif_moneda_trans_to_tasa_input").val(pnumDecimal(pTsupTotalDolar));
                $("#pdif_moneda_trans_to_tasa").html(pnumDecimal(pTsupTotalDolar - pMtransSistema));
                $("#ptotal_trans_dif").val(pnumDecimal(pTsupTotalDolar - pMtransSistema));
                $("#ptotal_trans").val(pnumDecimal(pTsupTotalDolar));
                $("#pdif_moneda_trans_to_dolar_input").val(pnumDecimal(pTsupTotal));
                $("#pdif_moneda_trans_to_dolar").html(pnumDecimal(pTsupTotal - pTtransToDolarSis));
                psumar();
                presta();

            }


            $("#pcantidad_dolar_rep").keyup(function() {
                pDMontoDolarRep();
            });

            $("#pcantidad_peso_rep").keyup(function() {
                pDMontoPesoRep();
            });

            $("#pcantidad_efectivo_rep").keyup(function() {
                pDMontoBolivarRep();
            });

            $("#pcantidad_punto_rep").keyup(function() {
                pDMontoPuntoRep();
            });

            $("#pcantidad_trans_rep").keyup(function() {
                pDMontoTransRep();
            });
        });
</script>
@endpush
