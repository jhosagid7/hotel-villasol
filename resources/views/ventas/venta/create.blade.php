@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Nueva Venta</h3>
            @include('custom.message')
        </div>
    </div>

            {{-- {!! Form::open(array('url' => 'ventas/venta','method'=>'POST', 'autocomplete'=>'off' )) !!}
            {{ Form::token() }} --}}
            <form action="{{ route('venta.store')}}" method="POST" autocomplete="off">

                @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="cliente">Cliente</label>
                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">
                    @foreach ($personas as $persona)
                <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="tipo_comprobante">Tipo Comprobante</label>
                <select name="tipo_comprobante" class="form-control">
                    <option value="Oreden">Oreden</option>
                    <option value="Factura">Factura</option>
                    <option value="Ticket">Ticket</option>
                </select>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Control Comprobante</label>
                <input type="text" name="serie_comprobante" class="form-control" value="{{old('serie_comprobante')}}" placeholder="Control Comprobante...">
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante</label>
                <input type="text" name="num_comprobante" required class="form-control" value="{{old('num_comprobante')}}" placeholder="Número Comprobante...">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row margin-bottom">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="form-group">
                                <label for="articulo">Artículo</label>
                                <select name="jidarticulo" id="jidarticulo" class="form-control selectpicker" data-live-search="true">
                                    <option value="seleccione...">Seleccione Articulo</option>
                                    @foreach ($articulos as $articulo)
                                <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_compra}}_{{$articulo->nombre}}">{{$articulo->articulo}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                            <div class="form group">
                                <label for="cantidad">Cantidad</label>
                                <input type="text" name="jcantidad" id="jcantidad"  class="form-control enteros" placeholder="Cantidad...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                            <div class="form group">
                                <label for="stock">Stock</label>
                                <input type="text" readonly name="jstock" id="jstock"  class="form-control" placeholder="Stock...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                            <div class="form group">
                                <label for="descuento">Descuento</label>
                                <input type="text" name="jdescuento" id="jdescuento"  class="form-control decimal" placeholder="Descuento...">
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <label for="precio_venta_dolar">Precio Dolar</label>
                                    <h4 class="font-weight-bold" id="vprecio_venta_dolar">$. 0.00</h4>
                                    <input type="hidden" name="jprecio_venta_d_dolar" id="jprecio_venta_d_dolar"  class="form-control">
                                    <input type="hidden" name="jprecio_compra" id="jprecio_compra"  class="form-control" placeholder="Precio venta dolar...">

                                    <input type="hidden" name="jprecio_venta" id="jprecio_venta"  class="form-control" placeholder="Precio dolar...">
                                    <input type="hidden" name="jprecio_venta_dolar" id="jprecio_venta_dolar"  class="form-control" placeholder="Precio dolar...">
                                    <input type="hidden" name="jmarjen_ganancia_dolar" id="jmarjen_ganancia_dolar" value="{{$tasaDolar->porcentaje_ganancia}}">

                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <label for="jprecio_venta_peso">Precio Pesos</label>
                                    <h4 class="font-weight-bold" id="vprecio_venta_peso">$. 0.00</h4>
                                    <input type="hidden" name="jprecio_venta_p_dolar" id="jprecio_venta_p_dolar"  class="form-control">
                                    <input type="hidden" name="jprecio_venta_peso" id="jprecio_venta_peso"  class="form-control" placeholder="Precio pesos...">
                                    <input type="hidden" name="jmarjen_ganancia_peso" id="jmarjen_ganancia_peso" value="{{$tasaPeso->porcentaje_ganancia}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <label for="jprecio_venta_trans_punto">Precio Trans/Punto</label>
                                    <h4 class="font-weight-bold" id="vprecio_venta_trans_punto">Bs. 0.00</h4>
                                    <input type="hidden" name="jprecio_venta_tp_dolar" id="jprecio_venta_tp_dolar"  class="form-control">
                                    <input type="hidden" name="jprecio_venta_trans_punto" id="jprecio_venta_trans_punto"  class="form-control" placeholder="Precio trans/punto...">
                                    <input type="hidden" name="jmarjen_ganancia_trans_punto" id="jmarjen_ganancia_trans_punto" value="{{$tasaTransferenciaPunto->porcentaje_ganancia}}">
                                    <input type="hidden" name="tasaTransPunto" id="tasaTransPunto" value="{{$tasaTransferenciaPunto->tasa}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <label for="jprecio_venta_mixto">Precio Mixto</label>
                                    <h4 class="font-weight-bold" id="vprecio_venta_mixto">Bs. 0.00</h4>
                                    <input type="hidden" name="jprecio_venta_m_dolar" id="jprecio_venta_m_dolar"  class="form-control">
                                    <input type="hidden" name="jprecio_venta_mixto" id="jprecio_venta_mixto"  class="form-control" placeholder="Precio Mixto...">
                                    <input type="hidden" name="jmarjen_ganancia_mixto" id="jmarjen_ganancia_mixto" value="{{$tasaMixto->porcentaje_ganancia}}">
                                    <input type="hidden" name="tasaMixto" id="tasaMixto" value="{{$tasaMixto->tasa}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <label for="precio_venta_Efectivo">Precio Efectivo</label>
                                    <h4 class="font-weight-bold" id="vprecio_venta_Efectivo">Bs. 0.00</h4>
                                    <input type="hidden" name="jprecio_venta_e_dolar" id="jprecio_venta_e_dolar"  class="form-control">
                                    <input type="hidden" name="jprecio_venta_Efectivo" id="jprecio_venta_Efectivo"  class="form-control" placeholder="Precio venta efecti...">
                                    <input type="hidden" name="jmarjen_ganancia_Efectivo" id="jmarjen_ganancia_Efectivo" value="{{$tasaEfectivo->porcentaje_ganancia}}">
                                    <input type="hidden" name="tasaEfectivo" id="tasaEfectivo" value="{{$tasaEfectivo->tasa}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                <div class="form group">
                                    <button id="bt_add" type="button" class="btn btn-primary btn-md btn-block">Agregar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                <h3 class="panel-title">Cargar Artículos</h3>
                                </div>
                                <div class="panel-body">
                                <table id="detalles" class="table table-striped table-borderd table-condensed table-hover">
                                <thead>
                                    <th class="info">Op</th>
                                    <th class="info">Artículo</th>
                                    <th class="info">Cant</th>
                                    <th class="success">P/Dolar</th>
                                    <th class="success">S/Total</th>
                                    <th class="warning">P/Peso</th>
                                    <th class="warning">S/Total</th>
                                    <th class="success">P/T/P</th>
                                    <th class="success">S/Total</th>
                                    <th class="warning">P/Mixto</th>
                                    <th class="warning">S/Total</th>
                                    <th class="success">P/Efect</th>
                                    <th class="success">S/Total</th>
                                    <th class="info">Desto</th>
                                    {{-- <th class="info">Subtotal</th> --}}
                                </thead>
                                <tfoot>
                                    <th colspan="3">TOTAL</th>

                                    <th colspan="2"><h4 class="font-weight-bold" id="totald">$. 0.00</h4></th>

                                    <th colspan="2"><h4 class="font-weight-bold" id="totalp">COP. 0.00</h4></th>

                                    <th colspan="2"><h4 class="font-weight-bold" id="totaltp">Bs. 0.00</h4></th>

                                    <th colspan="2"><h4 class="font-weight-bold" id="totalm">Bs. 0.00</h4></th>

                                    <th colspan="2"><h4 class="font-weight-bold" id="totale">Bs. 0.00</h4></th><input type="hidden" name="total_venta" id="total_venta"><input type="" name="total_ventad" id="total_ventad"><input type="" name="total_ventap" id="total_ventap"><input type="" name="total_ventatp" id="total_ventatp"><input type="" name="total_ventam" id="total_ventam"><input type="" name="total_ventae" id="total_ventae">
                                    <th></th>
                                </tfoot>
                                <tbody>

                                </tbody>
                            </table>
                            <button id='bt_addD' type='button' class='btn btn-primary btn-md'>Dolar</button>
                            <button id='bt_addP' type='button' class='btn btn-primary btn-md'>Peso</button>
                            <button id='bt_addTP' type='button' class='btn btn-primary btn-md');>Punto/Trans</button>
                            <button id='bt_addM' type='button' class='btn btn-primary btn-md');>Mixto</button>
                            <button id='bt_addE' type='button' class='btn btn-primary btn-md'>Efectivo</button>
                        </div>
                    </div>
                </div>

                    <div id="gestionpago" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                              <h2 id="gestionPago" class="panel-title">Gestion de pagos efectivo</h2>
                            </div>
                            <div class="panel-body">
                        <table id="pagos" class="table table-striped table-borderd table-condensed table-hover">
                            <thead>
                                <th>Divisa</th>
                                <th>Monto</th>
                                <th>Tasa</th>
                                <th>Divisa a dolar</th>
                                <th>Resta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th ><h4 id="tp" class="text-bold">TOTAL PAGADO</h4><h4 id="r" class="text-bold">RESTA</h4><h4 id="tap" class="text-bold">TOTAL A PAGAR</h4>
                                <th><h4 class="text-bold" id="spTotal">0.00</h4><h4 class="text-bold" id="RestaTtotal">0.00</h4><h4 class="text-bold" id="PagoTtotal">0.00</h4></th>
                            </tfoot>
                            <tbody>
                                <tr id="trD">
                                <td>Dolar</td><input name="divisa[]" value="Dolar" type="hidden">
                                <td><input name="MontoDivisa[]" class="decimal" type="texto"  id="DMontoDolar"></td>
                                <td><input name="TasaTike[]" type="texto" readonly id="TasaDolar" value="{{ $tasaDolar->tasa }}"></td>
                                <td><input name="MontoDolar[]" type="text" readonly id="DolarToDolar" class="monto" onchange="sumar();"></td>
                                <td><input name="Veltos[]" type="text" readonly id="RestaDolar"></td>
                                <td id="DsubTotal"></td>
                                </tr>
                                <tr id="trP">
                                <td>Peso</td></th><input name="divisa[]" value="Peso" type="hidden">
                                <td><input name="MontoDivisa[]" class="decimal" type="texto" id="DMontoPeso"></td>
                                <td><input name="TasaTike[]" type="texto" readonly id="TasaPeso" value="{{ $tasaPeso->tasa }}"></td>
                                <td><input name="MontoDolar[]" type="text" readonly id="PesoToDolar" class="monto" onchange="sumar();"></td>
                                <td><input name="Veltos[]" type="text" readonly id="RestaPeso"></td>
                                <td id="PeSubTotal"></td>
                                </tr>
                                <tr id="trE">
                                <td>Efectivo</td></th><input name="divisa[]" value="Bolivar" type="hidden">
                                <td><input name="MontoDivisa[]" class="decimal" type="texto" id="DMontoBolivar"></td>
                                <td><input name="TasaTike[]" type="texto" readonly id="TasaBolivar" value="{{ $tasaTransferenciaPunto->tasa }}"></td>
                                <td><input name="MontoDolar[]" type="texto" readonly id="BolivarToDolar" class="monto" onchange="sumar();"></td>
                                <td><input name="Veltos[]" type="text" readonly id="RestaBolivar"></td>
                                <td id="BoSubTotal"></td>
                                </tr>
                                <tr id="trTP">
                                <td>Punto</td></th><input name="divisa[]" value="Punto" type="hidden">
                                <td><input name="MontoDivisa[]" class="decimal" type="texto" id="DMontoPunto"></td>
                                <td><input name="TasaTike[]" type="texto" class="enteros" id="NumTiker" value="" placeholder="N° de tiket..."><input type="hidden" id="TasaPunto" value="{{ $tasaTransferenciaPunto->tasa }}"></td>
                                <td><input name="MontoDolar[]" readonly type="texto" id="PuntoToDolar" class="monto" onchange="sumar();"></td>
                                <td><input name="Veltos[]" readonly type="text" id="RestaPunto"></td>
                                <td id="PuSubTotal"></td>
                                </tr>
                                <tr id="trT">
                                <td>Transferencia</td></th><input name="divisa[]" value="Transferencia" type="hidden">
                                <td><input name="MontoDivisa[]" class="decimal" class="" type="texto" id="DMontoTrans"></td>
                                <td><input name="TasaTike[]" type="texto"  class="enteros" id="NumtTrans" value="" placeholder="N° de Transferencia..."><input type="hidden" id="TasaTrans" value="{{ $tasaTransferenciaPunto->tasa }}"></td>
                                <td><input name="MontoDolar[]" type="texto" readonly id="TransToDolar" class="monto" onchange="sumar();"></td>
                                <td><input name="Veltos[]" type="text" readonly id="RestaTrans"></td>
                                <td id="TrSubTotal"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="guardar">
                            <div class="form-group">
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <button class="btn btn-danger" type="reset">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>

            </div>





        </div>
    </div>
            </form>
            {{-- {!! Form::close() !!} --}}

@push('sciptsMain')
<script>
var cont=0;
    var total=parseFloat(0.00);

    // var totalp=parseFloat(0.00);
    // var totaltp=parseFloat(0.00);
    // var totalm=parseFloat(0.00);
    // var totale=parseFloat(0.00);

    var total_d=parseFloat(0.00);
    var total_p=parseFloat(0.00);
    var total_tp=parseFloat(0.00);
    var total_m=parseFloat(0.00);
    var total_e=parseFloat(0.00);

    var precio_venta=parseFloat(0.00);
    var precio_compra=parseFloat(0.00);
    subtotal=[];

    subtotald=[];
    subtotalp=[];
    subtotaltp=[];
    subtotalm=[];
    subtotale=[];

    $("#guardar").hide();
    $("#gestionpago").hide();
    $("#jidarticulo").change(showValues);
    $("#jidarticulo").change(por);
    $('#bt_addD').hide();
    $('#bt_addP').hide();
    $('#bt_addTP').hide();
    $('#bt_addM').hide();
    $('#bt_addE').hide();


    $(document).ready(function(){
        $("#bt_add").click(function(){
            add_article();

        });

        $(function () {
            $('.enteros').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        $('.decimal').on('keypress', function (e) {
        // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
        var field = $(this);
        key = e.keyCode ? e.keyCode : e.which;

        if (key == 8) return true;
        if (key > 47 && key < 58) {
          if (field.val() === "") return true;
          var existePto = (/[.]/).test(field.val());
          if (existePto === false){
              regexp = /.[0-9]{10}$/;
          }
          else {
            regexp = /.[0-9]{2}$/;
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

        function prepara(){
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
        }

        $("#bt_addD").click(function(){
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Dolar</h4>");
            $("#PagoTtotal").html(total_d.toFixed(2));//aqui
            $("#RestaTtotal").html(total_d.toFixed(2));//aqui
            $("#total_venta").val(total_d.toFixed(2));
            $("#spTotal").html('0.00');//aqui


            prepara();


            $('#trD').show("linear");
            $('#trP').hide("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");

            verify();
            resta();
        });

        $("#bt_addP").click(function(){
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Peso</h4>");
            $("#PagoTtotal").html(total_p.toFixed(2));//aqui
            $("#RestaTtotal").html(total_p.toFixed(2));//aqui
            $("#total_venta").val(total_p.toFixed(2));

            $("#spTotal").html('0.00');//aqui

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

            $('#trD').hide("linear");
            $('#trP').show("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");

            verify();
            resta();
        });

        $("#bt_addTP").click(function(){
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Punto o Transferencia</h4>");
            $("#PagoTtotal").html(total_tp.toFixed(2));//aqui
            $("#RestaTtotal").html(total_tp.toFixed(2));//aqui
            $("#total_venta").val(total_tp.toFixed(2));
            $("#spTotal").html('0.00');//aqui

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


            $('#trD').hide("linear");
            $('#trP').hide("linear");
            $('#trTP').show("linear");
            $('#trT').show("linear");
            $('#trM').hide("linear");
            $('#trE').hide("linear");

            verify();
            resta();
        });


        $("#bt_addM").click(function(){
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago Mixto</h4>");
            $("#PagoTtotal").html(total_m.toFixed(2));//aqui
            $("#RestaTtotal").html(total_m.toFixed(2));//aqui
            $("#total_venta").val(total_m.toFixed(2));
            $("#spTotal").html('0.00');//aqui

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

            $('#trD').show("linear");
            $('#trP').show("linear");
            $('#trTP').show("linear");
            $('#trT').show("linear");
            $('#trM').show("linear");
            // $('#trE').hide("linear");


            verify();
            resta();
        });

        $("#bt_addE").click(function(){
            $("#gestionPago").html("<h4 class'bold'> Gestion de Pago en Efectivo</h4>");
            $("#PagoTtotal").html(total_e.toFixed(2));//aqui
            $("#RestaTtotal").html(total_e.toFixed(2));//aqui
            $("#total_venta").val(total_e.toFixed(2));
            $("#spTotal").html('0.00');//aqui

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


            $('#trD').hide("linear");
            $('#trP').hide("linear");
            $('#trTP').hide("linear");
            $('#trT').hide("linear");
            $('#trM').hide("linear");
            $('#trE').show("linear");

            verify();
            resta()
        });
    });


    function showValues(){
        // alert('show');
        datosArticulo=document.getElementById('jidarticulo').value.split('_');
        // $("#jprecio_venta").val(datosArticulo[2]);
        $("#jprecio_compra").val(datosArticulo[2]);
        $("#jstock").val(datosArticulo[1]);
        // $("#jmarjen_venta_dolar").val(12);


    }

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };

    function por(){
        var precio_compra = parseFloat($("#jprecio_compra").val());
        var porDolar = parseFloat($("#jmarjen_ganancia_dolar").val());
        var porPeso = parseFloat($("#jmarjen_ganancia_peso").val());
        var porTP = parseFloat($("#jmarjen_ganancia_trans_punto").val());
        var porM = parseFloat($("#jmarjen_ganancia_mixto").val());
        var porE = parseFloat($("#jmarjen_ganancia_Efectivo").val());


        var tasaD = parseFloat($("#TasaDolar").val());
        var tasaP = parseFloat($("#TasaPeso").val());
        var tasaTP = parseFloat($("#tasaTransPunto").val());
        var tasaM = parseFloat($("#tasaMixto").val());
        var tasaE = parseFloat($("#tasaEfectivo").val());
        // alert(tasaM);
        // alert(tasaE);


        var margenD = precio_compra*porDolar/100;
        var margenP = precio_compra*porPeso/100;
        var margenTP = precio_compra*porTP/100;
        var margenM = precio_compra*porM/100;
        var margenE = precio_compra*porE/100;



        precio_compraD = precio_compra+margenD;
        precio_compraD = precio_compraD.toFixed(2);
        $("#jprecio_venta_dolar").val(precio_compraD);
        $("#jprecio_venta_d_dolar").val(precio_compraD);
        $("#jprecio_venta").val(precio_compraD*tasaD);
        $("#vprecio_venta_dolar").html("<h4>$. " + formatMoney(precio_compraD*tasaD,2,',','.') + "</h4>");

        precio_compraP = precio_compra+margenP;
        $("#jprecio_venta_p_dolar").val(precio_compraP);
        precio_compraP = precio_compraP.toFixed(2);
        $("#jprecio_venta_peso").val(precio_compraP*tasaP);
        $("#vprecio_venta_peso").html("<h4>$. " + formatMoney(precio_compraP*tasaP,2,',','.') + "</h4>");

        precio_compraTP = precio_compra+margenTP;
        $("#jprecio_venta_tp_dolar").val(precio_compraTP);
        precio_compraTP = precio_compraTP.toFixed(2);
        $("#jprecio_venta_trans_punto").val(precio_compraTP*tasaTP);
        $("#vprecio_venta_trans_punto").html("<h4>Bs. " + formatMoney(precio_compraTP*tasaTP,2,',','.') + "</h4>");

        precio_compraM = precio_compra+margenM;
        $("#jprecio_venta_m_dolar").val(precio_compraM);
        precio_compraM = precio_compraM.toFixed(2);
        $("#jprecio_venta_mixto").val(precio_compraM*tasaM);
        $("#vprecio_venta_mixto").html("<h4>Bs. " + formatMoney(precio_compraM*tasaM,2,',','.') + "</h4>");

        precio_compraE = precio_compra+margenE;
        $("#jprecio_venta_e_dolar").val(precio_compraE);
        precio_compraE = precio_compraE.toFixed(2);
        $("#jprecio_venta_Efectivo").val(precio_compraE*tasaE);
        $("#vprecio_venta_Efectivo").html("<h4>Bs. " + formatMoney(precio_compraE*tasaE,2,',','.') + "</h4>");



    }

    function add_article(){
        datosArticulo=document.getElementById('jidarticulo').value.split('_');


        idarticulo=datosArticulo[0];
        articulo=datosArticulo[3];

        // alert(articulo);
        cantidad=$("#jcantidad").val();
        descuento=$("#jdescuento").val();
        precio_compra=$("#jprecio_compra").val();
        precio_venta=$("#jprecio_venta").val();

        precio_venta_d=$("#jprecio_venta_d_dolar").val();
        precio_venta_p=$("#jprecio_venta_p_dolar").val();
        precio_venta_tp=$("#jprecio_venta_tp_dolar").val();
        precio_venta_m=$("#jprecio_venta_m_dolar").val();
        precio_venta_e=$("#jprecio_venta_e_dolar").val();

        verPreciod = $("#jprecio_venta_dolar").val();
        verPreciop = $("#jprecio_venta_peso").val();
        verPreciotp = $("#jprecio_venta_trans_punto").val();
        verPreciom = $("#jprecio_venta_mixto").val();
        verPrecioe = $("#jprecio_venta_Efectivo").val();





        stock=$("#jstock").val();

        if(idarticulo!="" && cantidad!="" && cantidad>0 && precio_venta!=""){
            var stock=parseInt(stock)
            var cantidad=parseInt(cantidad)

            var tasaD = parseFloat($("#TasaDolar").val());
            var tasaP = parseFloat($("#TasaPeso").val());
            var tasaTP = parseFloat($("#tasaTransPunto").val());
            var tasaM = parseFloat($("#tasaMixto").val());
            var tasaE = parseFloat($("#tasaEfectivo").val());

            // var descuento=parseFloat(0.00);

            if(stock >=cantidad){
                subtotal[cont]=(cantidad*precio_venta-descuento);

                subtotald[cont]=(cantidad*precio_venta_d-descuento);
                subtotalp[cont]=(cantidad*precio_venta_p-descuento);
                subtotaltp[cont]=(cantidad*precio_venta_tp-descuento);
                subtotalm[cont]=(cantidad*precio_venta_m-descuento);
                subtotale[cont]=(cantidad*precio_venta_e);

                total=total+subtotal[cont];

                // totalp=total+subtotalp[cont];
                // totaltp=total+subtotaltp[cont];
                // totalm=total+subtotalm[cont];
                // totale=total+subtotale[cont];

                total_d=total_d+subtotald[cont];
                total_p=total_p+subtotalp[cont];
                total_tp=total_tp+subtotaltp[cont];
                total_m=total_m+subtotalm[cont];
                total_e=total_e+subtotale[cont];
// alert('nada');
                if(descuento==""){
                    descuento = 0;
                }
                var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning btn-xs" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="hidden" name="cantidad[]" value="'+cantidad+'">'+cantidad+'</td><td class="success"><input type="hidden" name="precio_venta[]" value="'+precio_venta+'">'+precio_venta+'</td><td class="success">'+ formatMoney(parseFloat(subtotal[cont]))+'</td><td class="warning"><input type="hidden" name="precio_venta_p[]" value="'+precio_venta_p+'">'+formatMoney(verPreciop,2,',','.')+'</td><td class="warning">'+ formatMoney(parseFloat(subtotalp[cont]*tasaP),2,',','.')+'</td><td  class="success"><input type="hidden" name="precio_venta_tp[]" value="'+precio_venta_tp+'">'+formatMoney(verPreciotp,2,',','.')+'</td><td class="success">'+ formatMoney(parseFloat(subtotaltp[cont]*tasaTP),2,',','.')+'</td><td class="warning"><input type="hidden" name="precio_venta_m[]" value="'+precio_venta_m+'">'+formatMoney(verPreciom,2,',','.')+'</td><td class="warning">'+ formatMoney(parseFloat(subtotalm[cont]*tasaM),2,',','.')+'</td><td class="success"><input type="hidden" name="precio_venta_e[]" value="'+precio_venta_e+'">'+formatMoney(verPrecioe,2,',','.')+'</td><td class="success">'+ formatMoney(parseFloat(subtotale[cont]*tasaE))+'</td><td><input type="hidden" name="descuento[]" value="'+parseFloat(descuento)+'">'+parseFloat(descuento)+'</td></tr>';
                cont++

                clear();
                // $("#total").html("<h4>$. " + total.toFixed(2) + "</h4>");

                $("#totald").html("<h4>$. " + formatMoney(total_d*tasaD,2,',','.') + "</h4>");
                $("#totalp").html("<h4>$. " + formatMoney(total_p*tasaP,2,',','.') + "</h4>");
                $("#totaltp").html("<h4>Bs. " + formatMoney(total_tp*tasaTP,2,',','.') + "</h4>");
                $("#totalm").html("<h4>Bs. " + formatMoney(total_m*tasaM,2,',','.') + "</h4>");
                $("#totale").html("<h4>Bs. " + formatMoney(total_e*tasaE,2,',','.') + "</h4>");

                $("#PagoTtotal").html(total.toFixed(2));//aqui

                // $("#total_venta").val(total.toFixed(2));

                $("#total_ventad").val(total_d.toFixed(2));
                $("#total_ventap").val(total_p.toFixed(2));
                $("#total_ventatp").val(total_tp.toFixed(2));
                $("#total_ventam").val(total_m.toFixed(2));
                $("#total_ventae").val(total_e.toFixed(2));
                // verify(); deplega el div gestion de pagos
                mostrarBonotesPago();
                $("#detalles").append(fila);
                // alert('resta');
                $("#DMontoDolar").keyup();
                $("#DMontoPeso").keyup();
                $("#DMontoBolivar").keyup();
                $("#DMontoPunto").keyup();
                $("#DMontoTrans").keyup();
                }else{
                    alert('La cantidad a vender supera el stock...!');
                }

        }else{
            alert("Error al ingresar el detalle de la venta, revise los datos del articulo");
        }
    };

    function clear(){
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

        $("#totald").html("<h4>$. 0.00</h4>");
        $("#totalp").html("<h4>$. 0.00</h4>");
        $("#totaltp").html("<h4>Bs. 0.00</h4>");
        $("#totalm").html("<h4>Bs. 0.00</h4>");
        $("#totale").html("<h4>Bs. 0.00</h4>");
    }

    function verify(){

        if(total>0){
            $('#gestionpago').show("linear");
        }else{
            $('#gestionpago').hide("linear");
        }
    }

    function mostrarBonotesPago(){
        if(total>0){
            $('#bt_addD').show("linear");
            $('#bt_addP').show("linear");
            $('#bt_addTP').show("linear");
            $('#bt_addM').show("linear");
            $('#bt_addE').show("linear");
        }else{
            $('#bt_addD').hide("linear");
            $('#bt_addP').hide("linear");
            $('#bt_addTP').hide("linear");
            $('#bt_addM').hide("linear");
            $('#bt_addE').hide("linear");
        }
    }

    function eliminar(index){
        total=total-subtotal[index];

        total_d=total_d-subtotald[index];
        total_p=total_p-subtotalp[index];
        total_tp=total_tp-subtotaltp[index];
        total_m=total_m-subtotalm[index];
        total_e=total_e-subtotale[index];

        $("#total").html("$/. " + total.toFixed(2));
        $("#PagoTtotal").html(total.toFixed(2));
        // $("#total_venta").val(total.toFixed(2));
// ddç


        $("#total_ventad").val(total_d.toFixed(2));
        $("#total_ventap").val(total_p.toFixed(2));
        $("#total_ventatp").val(total_tp.toFixed(2));
        $("#total_ventam").val(total_m.toFixed(2));
        $("#total_ventae").val(total_e.toFixed(2));

        $("#fila" + index).remove();
        mostrarBonotesPago();
        verify();
        resta()

    };

    // Gestion de pagos

    /* Restar dos números. */
    function resta(){
        // alert('resta');
        const RestaTotal = document.getElementById('RestaTtotal');
        const PagoTtotal = document.getElementById('PagoTtotal');
        const spTotal = document.getElementById('spTotal');
        const tp = document.getElementById('tp');
        const r = document.getElementById('r');
        const tap = document.getElementById('tap');



        var valor = PagoTtotal.innerHTML;
        var valor_restar = spTotal.innerHTML;
        var resta = valor-valor_restar;

        RestaTotal.innerHTML = resta.toFixed(2);//se llena el campo resta



        if(RestaTotal.innerHTML<=0){
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
            $("#guardar").show("linear");;




        }
        if(RestaTotal.innerHTML>0){
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
    $(document).ready(function(){
		$("#mostrar").click(function(){
			$('.target').show("swing");
		 });
		$("#ocultar").click(function(){
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
    // alert(total);
    document.getElementById('spTotal').innerHTML = total_suma.toFixed(2);

}



    $(document).ready(function(){
  $("#DMontoDolar").keyup(function(){
    // alert('clic');
    Mdolar= $("#DMontoDolar").val();
    Tdolar= $("#TasaDolar").val();
    Tpeso= $("#TasaPeso").val();
    Tbolivar= $("#TasaBolivar").val();
    Tpunto= $("#TasaPunto").val();
    Ttrans= $("#TasaTrans").val();

    DsupTotal = Mdolar*Tdolar;
    $("#DolarToDolar").val(DsupTotal.toFixed(2));
    $("#DsubTotal").html(DsupTotal.toFixed(2));

    sumar();
    resta();
    const Resta = document.getElementById('RestaTtotal');
    var valor = Resta.innerHTML;
    var RmultD = valor*Tdolar;
    $("#RestaDolar").val(RmultD.toFixed(2));
    var RmultP = valor*Tpeso;
    $("#RestaPeso").val(RmultP.toFixed(2));
    var RmultB = valor*Tbolivar;
    $("#RestaBolivar").val(RmultB.toFixed(2));
    var RmultPu = valor*Tpunto;
    $("#RestaPunto").val(RmultPu.toFixed(2));
    var RmultT = valor*Ttrans;
    $("#RestaTrans").val(RmultT.toFixed(2));


});

  $("#DMontoPeso").keyup(function(){
    Mpeso= $("#DMontoPeso").val();
    Tdolar= $("#TasaDolar").val();
    Tpeso= $("#TasaPeso").val();
    Tbolivar= $("#TasaBolivar").val();
    Tpunto= $("#TasaPunto").val();
    Ttrans= $("#TasaTrans").val();

     PsupTotal = Mpeso/Tpeso;
    $("#PesoToDolar").val(PsupTotal.toFixed(2));
    $("#PeSubTotal").html(PsupTotal.toFixed(2));
    $("#RestaPeso").val();
    sumar();
    resta();
    const Resta = document.getElementById('RestaTtotal');
    var valor = Resta.innerHTML;
    var RmultD = valor*Tdolar;
    $("#RestaDolar").val(RmultD.toFixed(2));
    var RmultP = valor*Tpeso;
    $("#RestaPeso").val(RmultP.toFixed(2));
    var RmultB = valor*Tbolivar;
    $("#RestaBolivar").val(RmultB.toFixed(2));
    var RmultPu = valor*Tpunto;
    $("#RestaPunto").val(RmultPu.toFixed(2));
    var RmultT = valor*Ttrans;
    $("#RestaTrans").val(RmultT.toFixed(2));

   });

   $("#DMontoBolivar").keyup(function(){

    Mbolivar= $("#DMontoBolivar").val();
    Tdolar= $("#TasaDolar").val();
    Tpeso= $("#TasaPeso").val();
    Tbolivar= $("#TasaBolivar").val();
    Tpunto= $("#TasaPunto").val();
    Ttrans= $("#TasaTrans").val();

    peso= $("#RestaPeso").val();
    // 10767280  alert(Mpeso);
    BsupTotal = Mbolivar/Tbolivar;
    $("#BolivarToDolar").val(BsupTotal.toFixed(2));
    $("#BoSubTotal").html(BsupTotal.toFixed(2));
    $("#RestaBolivar").val();
    sumar();
    resta();
    const Resta = document.getElementById('RestaTtotal');
    var valor = Resta.innerHTML;
    var RmultD = valor*Tdolar;
    $("#RestaDolar").val(RmultD.toFixed(2));
    var RmultP = valor*Tpeso;
    $("#RestaPeso").val(RmultP.toFixed(2));
    var RmultB = valor*Tbolivar;
    $("#RestaBolivar").val(RmultB.toFixed(2));
    var RmultPu = valor*Tpunto;
    $("#RestaPunto").val(RmultPu.toFixed(2));
    var RmultT = valor*Ttrans;
    $("#RestaTrans").val(RmultT.toFixed(2));

   });

   $("#DMontoPunto").keyup(function(){

     MPunto= $("#DMontoPunto").val();
     Tdolar= $("#TasaDolar").val();
     Tpeso= $("#TasaPeso").val();
     Tbolivar= $("#TasaBolivar").val();
     Tpunto= $("#TasaPunto").val();
     Ttrans= $("#TasaTrans").val();

     peso= $("#RestaPeso").val();
     // 10767280  alert(Mpeso);
     PusupTotal = MPunto/Tpunto;
     $("#PuntoToDolar").val(PusupTotal.toFixed(2));
     $("#PuSubTotal").html(PusupTotal.toFixed(2));
     $("#RestaPunto").val();
     sumar();
     resta();
     const Resta = document.getElementById('RestaTtotal');
     var valor = Resta.innerHTML;
     var RmultD = valor*Tdolar;
     $("#RestaDolar").val(RmultD.toFixed(2));
     var RmultP = valor*Tpeso;
     $("#RestaPeso").val(RmultP.toFixed(2));
     var RmultB = valor*Tbolivar;
     $("#RestaBolivar").val(RmultB.toFixed(2));
     var RmultPu = valor*Tpunto;
     $("#RestaPunto").val(RmultPu.toFixed(2));
     var RmultT = valor*Ttrans;
     $("#RestaTrans").val(RmultT.toFixed(2));

    });

    $("#DMontoTrans").keyup(function(){

     Mtrans= $("#DMontoTrans").val();
     Tdolar= $("#TasaDolar").val();
     Tpeso= $("#TasaPeso").val();
     Tbolivar= $("#TasaBolivar").val();
     Tpunto= $("#TasaPunto").val();
     Ttrans= $("#TasaTrans").val();

     peso= $("#RestaPeso").val();
     // 10767280  alert(Mpeso);
     TsupTotal = Mtrans/Ttrans;
     $("#TransToDolar").val(TsupTotal.toFixed(2));
     $("#TrSubTotal").html(TsupTotal.toFixed(2));
     $("#RestaTrans").val();
     sumar();
     resta();
     const Resta = document.getElementById('RestaTtotal');
     var valor = Resta.innerHTML;
     var RmultD = valor*Tdolar;
     $("#RestaDolar").val(RmultD.toFixed(2));
     var RmultP = valor*Tpeso;
     $("#RestaPeso").val(RmultP.toFixed(2));
     var RmultB = valor*Tbolivar;
     $("#RestaBolivar").val(RmultB.toFixed(2));
     var RmultPu = valor*Tpunto;
     $("#RestaPunto").val(RmultPu.toFixed(2));
     var RmultT = valor*Ttrans;
     $("#RestaTrans").val(RmultT.toFixed(2));

    });



});




</script>
@endpush

@endsection
