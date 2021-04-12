@extends ('layouts.admin3')
@section('contenido')

<!-- Default box -->
<!-- Content Header (Page header) -->
    {{-- <section class="content-header">
      <h1>
        <!--Blank page-->
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section> --}}

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
            <h3>Nuevo Precio</h3>
            @include('custom.message')


            <form action="{{ route('precio.store')}}" method="POST" autocomplete="off">

            @csrf
            <div class="form-group">
                <label for="">Servicio</label>
                <select required name="horario_id" id="horario_id" class="form-control select2">
                    <option value="0">Seleccione Servicio</option>
                    @foreach($horarios as $horario)
                            <option value="{{$horario->id}}">{{$horario->nombre}}</option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label for="">Categoría</label>
                <select required name="cat_id" id="cat_id" class="form-control select2">
                    <option value="0">Seleccione Categoría</option>
                    @foreach($categorias as $cat)
                            <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                        @endforeach
                    </select>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input required type="text" name="precio" class="form-control form-control-sm mayusculas" placeholder="Precio del Servicio...">
            </div>



            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <a class="btn btn-danger" href="{{route('precio.index')}}">{{__('Back')}}</a>
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
// Funcion JavaScript para la conversion a mayusculas
$(function() {
            $('.mayusculas').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

});



</script>
@endpush
@endsection
