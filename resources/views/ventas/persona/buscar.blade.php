{!! Form::open(array('url'=>'ventas/cliente','method'=>'GET', 'autocomplete'=>'off', 'role'=>'buscar')) !!}
<div class="form-group">
    <div class="input-group">
        <input type="text" class='form-control' name="buscarTexto" placeholder="Buscar..." value="{{$buscarTexto}}">
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit"><i class='glyphicon glyphicon-search'></i> Buscar</button>
        </span>
    </div>
</div>
{{Form::close() }}