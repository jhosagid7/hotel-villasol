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
            <h3>Nuevo Horario</h3>
            @include('custom.message')


            <form action="{{ route('horario.store')}}" method="POST" autocomplete="off">

                @csrf
                    <div class="form-group">
                        <label for="nombre">Tipo</label>
                        <input required type="text" name="tipo" class="form-control form-control-sm mayusculas" placeholder="Nombre...">

                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input required type="text" name="nombre" class="form-control form-control-sm mayusculas" placeholder="Nombre...">
                        <div class="checkbox">

                            <label>
                                <input name="is24Horas" type="checkbox">
                                <b>24 Horas</b>
                              </label>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                            <label>Desde:</label>

                            <div class="input-group">
                                <input type="text" name="desde" class="form-control timepicker">

                                <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                            <label>Hasta:</label>

                            <div class="input-group">
                                <input type="text" name="hasta" class="form-control timepicker">

                                <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                        </div>
                            <!-- /.form group -->
                        </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                <label>Restringir:</label>

                                <div class="input-group">
                                    <input type="text" name="restringir" class="form-control timepicker">

                                    <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-danger" href="{{route('horario.index')}}">{{__('Back')}}</a>
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
