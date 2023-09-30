<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="box-title" id="exampleModalLabel"><b class="text-warning">REGISTRO DE RESERVACION</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="hidden" type="text" name="txtID" id="txtID" placeholder="txtID">

                @include('reservations.horario')
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnAgregar">Agregar</button>
                <button class="btn btn-warning" id="btnModificar">Modificar</button>
                <button class="btn btn-danger" id="btnEliminar">Eliminar</button>
                <button class="btn btn-secondary" id="btnCancelar">Cancelar</button>


            </div>
        </div>
    </div>
</div>
