

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <form action="{{ route('reporte-general-creditos')}}" method="GET" autocomplete="off" class="form-inline pull-right" role="buscar">
                    @csrf
                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="checkbox">
                            <label>
                              <input checked name="detallado" type="checkbox">
                              Reporte Detallado&nbsp;&nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="input-group">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                <input name="fecha" type="text" class="form-control pull-right" id="daterange-btn">
                            </div>
                        </div>
                    {{-- </div> --}}
                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="cliente" id="cliente" class="form-control selectpicker  overflow-hidden" data-live-search="true">
                                        <option value="0">Nombre del Cliente.&nbsp;&nbsp;&nbsp;</option>
                                        @foreach ($clientes as $cliente)
                                    <option value="{{$cliente->persona_id}}">{{$cliente->nombre_cliente}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    {{-- </div> --}}

                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        {{-- <div class="form-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="cliente" id="cliente" class="form-control selectpicker  overflow-hidden" data-live-search="true">
                                        <option value="0">Seleccione Nombre del Cliente&nbsp;&nbsp;&nbsp;</option>
                                        @foreach ($clientes as $cliente)
                                    <option value="{{$cliente->persona_id}}">{{$cliente->nombre_cliente}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                    {{-- </div> --}}
                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="form-group">
                            <div class="input-group">
                                <select name="estadoPago" id="estadoPago" class="form-control selectpicker"  data-live-search="true">
                                    <option value="0">Estado Pago. &nbsp;&nbsp;&nbsp;</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Pagado">Pagado</option>
                                </select>
                            </div>
                        </div>
                    {{-- </div> --}}
                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="form-group">
                            <div class="input-group">
                                <select name="estadoCredito" id="estadoCredito" class="form-control selectpicker"  data-live-search="true">
                                    <option value="0">Estado Credito. &nbsp;&nbsp;&nbsp;</option>
                                    <option value="Vigente">Vigente</option>
                                    <option value="Vencido">Vencido</option>
                                </select>
                            </div>
                        </div>
                    {{-- </div> --}}

                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="form-group">
                            <div class="input-group">
                                <select name="operador" id="operador" class="form-control selectpicker" data-live-search="true">
                                    <option value="0">Operador.&nbsp;&nbsp;&nbsp;</option>
                                    @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    {{-- </div> --}}
                     {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> --}}
                        <div class="form-group">
                            <div class="input-group">
                                <select name="caja_id" id="caja_id" class="form-control selectpicker"  data-live-search="true">
                                    <option value="0">Número Caja. &nbsp;&nbsp;&nbsp;</option>
                                    @foreach ($cajas as $caja)
                                    <option value="{{$caja->id}}">{{$caja->codigo}}</option>
                                        @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit"><i class='glyphicon glyphicon-search'></i> Buscar</button>
                                </span>
                            </div>
                        </div>
                    {{-- </div> --}}
                </form>

        </div>
    </div>



