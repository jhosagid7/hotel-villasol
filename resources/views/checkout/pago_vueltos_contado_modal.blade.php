
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
                                                <h3 class="box-title">Debe devolver al cliente (<b class="text-danger" id="countVueltosPendientes">$0.00</b>). (De vueltos pendiente)...!</h3>
                                            </div><!-- /.box-header -->
                                            <div class="box-body">
                                                <div id="btnPago2Opciones">
                                                    <div id="contado2Opciones" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#" data-toggle="modal" data-target="#dolar" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small">Contado</a> --}}
                                                        <button type="botton"  name="devolverVueltos"  id="devolverVueltos" class="btn btn-sm btn-primary btn-block col-lg-pull-2 small"> Contado</button>
                                                    </div>

                                                    <div id="precortesia2Opciones" class="panel-group col-lg-6 col-sm-6 col-md-6 col-xs-12 small">
                                                        {{-- <a id="modalPago" href="#"  class="btn btn-xs btn btn-success btn-block col-lg-pull-2 small">Activar Crédito</a> --}}
                                                        <button type="botton"  name="pagarPorOficinaBtn"  id="pagarPorOficinaBtn" class="btn btn-sm btn-warning btn-block col-lg-pull-2 small"> Pagar Por Oficina</button>
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
                                        <form id="form4" action="{{ route('excedente.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">

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
                                                                    <td><h4 class="text-primary" style="margin-top: 0px !important;">Transferencia: &nbsp;&nbsp;&nbsp; <input class="transferencia" name="isTransferencia" type="checkbox"></h4></td>

                                                                    <td><h4 class="text-primary" style="margin-top: 0px !important;">Pago Mobil: &nbsp;&nbsp;&nbsp;<input class="pagomobil" name="isPagoMobil" type="checkbox"></h4></td>

                                                                    <td><h4 class="text-primary" style="margin-top: 0px !important;">Efectivo: &nbsp;&nbsp;&nbsp;<input class="efectivo" name="isEfectivo" type="checkbox"></h4></td>


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

                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 seleccioneCliente"  style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="Banco">Seleccione Cliente</label>
                                                                    <select name="selec_cliente" id="selec_cliente" class="form-control selectpicker" data-live-search="true">
                                                                        <option value="default" selected="selected">Seleccione Cliente</option>
                                                                        @foreach ($clientes as $dcliente)
                                                                    <option value="{{$dcliente->id}}_{{$dcliente->nombre}}_{{$dcliente->num_documento}}_{{$dcliente->direccion}}_{{$dcliente->telefono}}_{{$dcliente->email}}">{{$dcliente->nombre}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nombre"  style="display:none">
                                                                <div class="form-group">
                                                                    <label class="text-black" for="nombre">Nombre del cliente</label>
                                                                    <input required type="text" id="nombre" name="nombre" class="form-control titulo" value="{{old('nombre')}}" placeholder="Nombre...">
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
                                                                    <input required type="number" id="num_documento" name="num_documento" class="form-control enteros" value="{{old('num_documento')}}" placeholder="Número de Documento...">
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

                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 selecctBanco "  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="selec_banco">Seleccione Banco</label>
                                                                            <select name="selec_banco" id="selec_banco" class="form-control selectpicker" data-live-search="true">
                                                                                <option value="default" selected="selected">Seleccione Banco</option>
                                                                                @foreach ($bancos as $banco)
                                                                            <option value="{{$banco->id}}_{{$banco->nombre_banco}}_{{$banco->codigo}}">{{$banco->nombre_banco}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  nombreBanco "  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="nombre_banco">Nombre Banco</label>
                                                                            <input required type="text" id="nombre_banco" name="nombre_banco" class="form-control titulo" value="{{old('nombre')}}" placeholder="Nombre Banco...">
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 codigo" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="num_documento">Código</label>
                                                                            <input required type="number" id="codigo" name="codigo" class="form-control enteros" value="{{old('codigo')}}" placeholder="Código...">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 numCuenta" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="direccion">Número de cuenta</label>
                                                                            <input required type="text" id="num_cuenta" name="num_cuenta" class="form-control mayuscula" value="{{old('num_cuenta')}}" placeholder="Número de cuenta...">
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 tipoCuenta" style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="tipo_cuenta">Tipo de cuenta</label>
                                                                            <select required class="form-control" id="tipo_cuenta" name="tipo_cuenta">
                                                                                <option value="0">Seleccione tipo de cuenta</option>
                                                                                <option value="Corriente">Corriente</option>
                                                                                <option value="Ahorro">Ahorro</option>
                                                                            </select>

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 telefonoMobil"  style="display:none">
                                                                        <div class="form-group">
                                                                            <label class="text-black" for="pago_mobil">Teléfono</label>
                                                                            <input required type="text" name="pago_mobil" class="form-control"  data-inputmask='"mask": "(9999) 999-9999"' data-mask value="{{old('pago_mobil')}}" placeholder="Pago mobil...">
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
                                                                <input class="text-black hidden" type="text" id="dcliente_id" name="dcliente_id">
                                                                <input class="text-black hidden" type="text" id="banco_id" name="banco_id">
                                                                <input class="text-black hidden" type="text" id="bandera" name="bandera">
                                                                <input class="text-black hidden" type="text" id="excedente" name="excedente">
                                                                <input class="text-black hidden" type="text" id="servicio_id" name="servicio_id" value="{{$servicio->id ?? ''}}">
                                                                <input class="text-black hidden" type="text" id="num_servicio" name="num_servicio" value="{{$servicio->num_servicio ?? ''}}">
                                                                <input class="text-black hidden" type="text" id="motivo" name="motivo" value="servicio">
                                                                <input class="text-black hidden" type="text" id="caja_id" name="caja_id" value="{{$servicio->caja_id ?? ''}}">
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
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
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
