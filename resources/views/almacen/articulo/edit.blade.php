@extends ('layouts.admin3')
@section('contenido')


    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
          <h3 class="box-title">@isset($title)
              {{$title}}
              @else
              {!!"Sistema"!!}
          @endisset</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                      title="Collapse">
                <i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
        {{-- cabecera de box --}}
    <div class="row">
        <div class="col-lg-6">
            <h3>Editar el Articulo: {{$articulo->nombre}}</h3>
            @include('custom.message')
        </div>
    </div>

            {{-- {!! Form::model($articulo,['route'=>['articulo.update', $articulo->idarticulo], 'method'=>'PATCH', 'files'=>'true']) !!}
            {{ Form::token() }} --}}
    <form action="{{ route('articulo.update', $articulo->id)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-control">
                    @foreach($categorias as $cat)
                    @if($cat->id==$articulo->id)
                    <option value="{{$cat->id}}" selected>{{$cat->nombre}}</option>
                    @else
                    <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" required value="{{$articulo->codigo}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="text" name="stock" required value="{{$articulo->stock}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" required value="{{$articulo->descripcion}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" name="imagen" class="form-control">
            @if(($articulo->imagen) !="")
                <img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" alt="{{$articulo->nombre}}" height="100px" width="100px">
            @endif
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="precio_costo">Precio de costo</label>
                <input type="number" name="precio_costo" required value="{{$articulo->precio_costo}}" class="form-control decimal">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <a class="btn btn-danger" href="{{route('articulo.index')}}">{{__('Back')}}</a>
            </div>
        </div>
    </div>

            </form>
        </div>
    </div>

    {{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
  {{-- Footer --}}
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
@push('sciptsMain')
  <script>
$(document).ready(function() {

    $(function() {
            $('.enteros').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    $('.decimal').on('keypress', function(e) {
        // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
        var field = $(this);
        key = e.keyCode ? e.keyCode : e.which;

        if (key == 8) return true;
            if (key > 47 && key < 58) {
                if (field.val() === "") return true;
                    var existePto = (/[.]/).test(field.val());
            if (existePto === false) {
                regexp = /.[0-9]{10}$/;
            } else {
                regexp = /.[0-9]{2}$/;
            }

            return !(regexp.test(field.val()));
        }
        if (key == 46) {
            if (field.val() === "") return false;
                regexp = /^[0-9]+$/;
                return regexp.test(field.val());
        }
            return false;
    });

});



</script>
@endpush
@endsection
