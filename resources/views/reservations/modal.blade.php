<div class="modal fade bs-example-modal-xm refrescar" id="exampleModal" arial-hidden="true">
    <div class="modal-dialog modal-info modal-xl">
        <div class="modal-dialog">
            <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="fa fa-spinner"></span>
                            <b id="modalHeading"></b>
                        </h4>
                    </div>
                    <div class="modal-body" style="background-color:#fff !important;">

                       <input class="hidden" type="text" name="txtID" id="txtID" placeholder="txtID">
                       <input class="hidden" type="text" name="txtTitle" id="txtTitle" placeholder="txtTitle">

                       @include('reservations.datosServicio')
                        @include('reservations.horario')
                        @include('reservations.datosCliente')
                        @include('reservations.datosPago')
                        @include('reservations.formaPago')
                        @include('reservations.datosControl')


                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="btnAgregar">Agregar</button>
                        <button class="btn btn-warning" id="btnModificar">Modificar</button>
                        <button class="btn btn-danger" id="btnEliminar">Eliminar</button>
                        <button class="btn btn-primary" data-dismiss="modal" id="btnCancelar">Cancelar</button>
                    </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
@include('reservations.guardarNumServicio')
