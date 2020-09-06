@extends ('layouts.admin3') @section('contenido')


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
        <h3>Nuevo Articulo</h3>
        @include('custom.message')
    </div>
</div>
<form action="{{ route('articulo.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">

@csrf

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-control">
                    @foreach($categorias as $cat)
                        <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                    @endforeach
                </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="number" name="codigo" required value="{{old('codigo')}}" class="form-control enteros" placeholder="Codigo de articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" required value="{{old('stock')}}" class="form-control enteros" placeholder="Stock de articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" required value="{{old('descripcion')}}" class="form-control" placeholder="Descripción del articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="precio_costo">Precio Compra</label>
            <input type="text" name="precio_costo" required value="{{old('precio_costo')}}" class="form-control decimal" placeholder="Precio de compra en dolares...">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

    </div>
</div>
</div>
<!-- /.box-body -->
<div class="box-footer">
    <div class="form-group margin">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <a class="btn btn-danger" href="{{route('articulo.index')}}">{{__('Back')}}</a>
    </div>
</div>
<!-- /.box-footer-->
</form>

{{-- fin de la cabecera de box --}}

</div>
    </section>
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
