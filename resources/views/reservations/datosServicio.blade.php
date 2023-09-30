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
                        <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                    @endforeach

                </select>
                <span id="txtHabitacionMesagge" class="text-red"></span>
            </div>
            <div class="form-group col-md-6">
                <label class="text-black" for="txtServicio">Tipo de servicio<span class="text-danger">(*)</span>:</label>
                <select required class="form-control" name="txtServicio" id="txtServicio" placeholder="txtServicio">
                    <option value="0">Seleccione tipo</option>
                    @foreach ($horarios as $horario)
                        <option value="{{ $horario->tipo }}">{{ $horario->tipo }}</option>
                    @endforeach

                </select>
                <span id="txtServicioMesagge" class="text-red"></span>
            </div>
        </div>
    </div>
</div>
