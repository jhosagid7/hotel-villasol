<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><b class="text-warning">DATOS DE CONTROL</b></h3>

    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="form-group col-md-6">
                <label class="text-black" for="txtNombreOperador">Operador:</label>
                <input readonly class="form-control" type="text" name="txtNombreOperador" id="txtNombreOperador" placeholder="txtNombreOperador" value="{{ Auth::user()->name }}">
            </div>
            <div class="form-group col-md-3">
                <label class="text-black" for="txtNumServicio">N° servicio:</label>
                <input disabled class="form-control" type="text" name="txtNumServicio" id="txtNumServicio"
                    placeholder="txtNumServicio">
                    <span id="txtNumServicio_mesagge" class="text-red"></span>
            </div>

            <div class="form-group col-md-3">
                <label class="text-black" for="txtStatus">Status:</label>
                <select class="form-control" name="txtStatus" id="txtStatus" placeholder="txtStatus">
                    <option value="Pendiente" selected>Pendiente</option>
                    <option id="optionProcesado" value="Procesado">Procesado</option>
                    {{--  <option id="optionCancelado" value="Cancelado">Cancelado</option>  --}}
                </select>
                <span id="txtStatuMesagge" class="text-red"></span>
            </div>

            <div class="form-group col-md-12">
                <label class="text-black" for="txtOboservation">Observacion:</label>
                <textarea class="form-control" name="txtOboservation" id="txtOboservation" cols="30" rows="3"></textarea>
            </div>
            <div class="form-group col-md-12">
                <input class="form-control" type="color" name="txtColor" id="txtColor" placeholder="txtColor">
            </div>
        </div>
    </div>
</div>
