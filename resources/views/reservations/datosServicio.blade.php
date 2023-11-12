<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><b class="text-warning">DATOS DEl SERVICIO</b></h3>

    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="form-group col-md-6">
                <label class="text-black" for="txtHabitacion">Tipo de habitacion<span class="text-danger">(*)</span>:</label>
                <select required class="form-control" name="txtHabitacion" id="txtHabitacion" placeholder="txtHabitacion">
                    <option value="0">Seleccione servicio</option>
                    @foreach ($tipoServicios as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach

                </select>
                <span id="txtHabitacionMesagge" class="text-red"></span>
            </div>
            <div class="form-group col-md-6">
                <label class="text-black" for="txtNumHabitacion">N° de habitacion<span class="text-danger">(*)</span>:</label>
                <select required class="form-control" name="txtNumHabitacion" id="txtNumHabitacion" placeholder="txtNumHabitacion">
                    <option value="0">Seleccione servicio</option>


                </select>
                <span id="txtHabitacionMesagge" class="text-red"></span>
            </div>
            <div class="form-group col-md-6">
                <label class="text-black" for="txtServicio">Tipo de servicio<span class="text-danger">(*)</span>:</label>
                <select required class="form-control" name="txtServicio" id="txtServicio" placeholder="txtServicio">
                    <option value="0">Seleccione tipo</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->id }}">{{ $horario->tipo }}</option>
                    @endforeach

                </select>
                <span id="txtServicioMesagge" class="text-red"></span>
            </div>
            <div class="form-group col-md-3">
                <label class="text-black">Precio:<h3 class="box-title"><b
                            class="text-warning">$<b id=precioShowServicio>0.00</b></b></h3></label>


            </div>
        </div>
    </div>
</div>

@push('sciptsMain')
    <script>
        // Escuchar el evento de cambio en el primer select
$('#txtHabitacion').on('change', function() {
    // Obtener el valor seleccionado en el primer select
    obtenerNumHabitacion()

    obtenerPrecio()
});
    </script>

    <script>
        // Escuchar el evento de cambio en el primer select
$('#txtServicio').on('change', function() {
    // Obtener el valor seleccionado en el primer select
    obtenerPrecio()



});
    </script>

    <script>
        function obtenerPrecio() {
            // Obtener el valor seleccionado en el primer select
    var cat_id = $("#txtHabitacion").val();
    var horario_id = $("#txtServicio").val();

    // Hacer una petición AJAX para obtener las habitaciones correspondientes a la categoría seleccionada
    $.ajax({
        url: "{{ route('search.precio') }}",
        type: 'GET',
        data: { horario_id: horario_id, cat_id: cat_id },
        success: function(precio) {
            console.log(precio)
            // Limpiar el segundo select
            $('#precioShow').html('0.00');
            $('#precioShowServicio').html('0.00');

            $('#precioShow').html(precio.precio);
            $('#precioShowServicio').html(precio.precio);
        }
    });
}

function obtenerNumHabitacions(){
     var cat_id = $("#txtHabitacion").val();

    // Hacer una petición AJAX para obtener las habitaciones correspondientes a la categoría seleccionada
    $.ajax({
        url: "{{ route('search.habitaciones') }}",
        type: 'GET',
        data: { cat_id: cat_id },
        success: function(habitaciones) {
            // Limpiar el segundo select
            $('#txtNumHabitacion').empty();

            // Agregar una opción por defecto
            $('#txtNumHabitacion').append('<option value="0">Seleccione habitación</option>');

            // Agregar las opciones correspondientes a las habitaciones obtenidas
            $.each(habitaciones, function(key, habitacion) {
                $('#txtNumHabitacion').append('<option value="' + habitacion.id + '">' + habitacion.nombre + '</option>');
            });
        }
    });
}

function obtenerNumHabitacion(numHabitacion = 0) {
    var cat_id = $("#txtHabitacion").val();
    var servicio_id = $("#txtServicio").val();
    console.log(numHabitacion)
    console.log('cat_id',cat_id)
    console.log('servicio_id',servicio_id)
    // Hacer una petición AJAX para obtener las habitaciones correspondientes a la categoría seleccionada
    $.ajax({
        url: "{{ route('search.habitaciones') }}",
        type: 'GET',
        data: { cat_id: cat_id },
        success: function(habitaciones) {
            // Limpiar el segundo select
            $('#txtNumHabitacion').empty();
            // Agregar una opción por defecto
            $('#txtNumHabitacion').append('<option value="0">Seleccione habitación</option>');
            // Agregar las opciones correspondientes a las habitaciones obtenidas
            $.each(habitaciones, function(key, habitacion) {
                $('#txtNumHabitacion').append('<option value="' + habitacion.id + '">' + habitacion.nombre + '</option>');
            });

            if(numHabitacion > 0){
                // Obtener la habitación seleccionada de la base de datos
                var habitacionSeleccionada = numHabitacion;
                // Recorrer las opciones y seleccionar la habitación correspondiente
                $('#txtNumHabitacion option').each(function() {
                    console.log($(this).text())
                    if ($(this).text() === habitacionSeleccionada.toString()) {
                        $(this).attr('selected', true);
                        return false; // Termina el bucle cuando se encuentra la opción correcta
                    }
                });
            }


        }
    });
}
    </script>
@endpush
