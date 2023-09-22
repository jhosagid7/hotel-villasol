<div class="modal fade bs-example-modal-xm refrescar" id="crear_cliente" arial-hidden="true">
    <div class="modal-dialog modal-info">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="clienteform" id="clienteform" class="submit-prevent-form" method="POST">
                    @method('POST')
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="fa fa-spinner"></span>
                            <b id="modalHeading"></b></h4>
                    </div>
                    <div class="modal-body" style="background-color:#fff !important;">

                        <div class="row">
                            <div class="col-md-offset-1 col-md-10">

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"> N° DOCUMENTO
                                        </span>
                                        <input type="number" class="form-control col-md-8" id="documento_cliente" name="documento_cliente"
                                            value="" placeholder="Ej: 13021811">

                                    </div>

                                        <span id="error_documento" class="text-danger er"></span>

                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"> NOMBRES </span>
                                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente"
                                            value="" placeholder="Ej: Jhonny Pirela">

                                    </div>
                                    <span id="error_nombre" class="text-danger er"></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"> DIRECCION
                                        </span>
                                        <input type="text" class="form-control col-md-8" id="direccion_cliente" name="nombre_cliente"
                                            value="" placeholder="Ingrese direccion (Opcional)">
                                    </div>
                                </div>


                                <div class="text-black detalle" id="detalle">

                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left"
                            data-dismiss="modal">Cancelar</button>
                        <button id="guardarClienteBtn"
                            class="btn btn-outline ocular submit-prevent-button" type="submit"><i
                                class='glyphicon glyphicon-plus'></i>
                            Guardar</button>

                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
