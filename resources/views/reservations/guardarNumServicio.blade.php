<div id="modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Guardar número de servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="numServicio" placeholder="Número de servicio">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('sciptsMain')
<script>
    $(document).ready(function() {
    $('#txtStatus').change(function() {
        // Obtener el valor seleccionado del select
        alert('aja')
        var selectedValue = $(this).val();

        // Verificar si el valor seleccionado es "Procesado"
        if (selectedValue === "Procesado") {
            // Abrir el modal
            abrirModal();
        }
    });
});

function abrirModal() {
    // Mostrar el modal utilizando Bootstrap
    $('#modal').modal('show');

    // Agregar un evento al botón de guardar del modal
    $('#btnGuardar').click(function() {
        // Obtener el valor del campo de número de servicio
        var numServicio = $('#numServicio').val();
        var reservationId = $('#txtID').val();
        alert('sun ', numServicio)

        // Realizar la consulta por AJAX y guardar el dato
        $.ajax({
            url: "{{ url('search/service') }}",
            type: 'GET',
            data: { reservationId: reservationId, numServicio: numServicio },
            success: function(response) {
                // Cerrar el modal
                $('#modal').modal('hide');

                // Cambiar el valor del select a "Procesado"
                $('#txtStatus').val('Procesado');
            },
            error: function() {
                // Manejar el error si la consulta AJAX falla
            }
        });
    });
}
</script>
@endpush


