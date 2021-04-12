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
            <h3>Editar Horario: {{$horario->nombre}}</h3>
            @include('custom.message')



            <form action="{{ route('horario.update', $horario->id)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="descripcion">Tipo</label>
                    <input required type="text" name="tipo" class="form-control form-control-sm mayuscula" value="{{ $horario->tipo }}" placeholder="Tipo...">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input required type="text" name="nombre" class="form-control form-control-sm mayuscula" value="{{ $horario->nombre }}" placeholder="Nombre...">
                    <div class="checkbox">
                        <label>
                          <input name="is24Horas" type="checkbox"
                          @if ($horario->is24Horas =="1")
                                checked
                            @elseif (old('is24Horas')=="1")
                                checked
                            @endif
                          >
                          24 Horas&nbsp;
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bootstrap-timepicker">
                    <div class="form-group">
                    <label>Desde:</label>

                    <div class="input-group">
                        <input type="text" id="desde" name="desde" class="form-control timepicker" value="{{ date('h:i:s A', strtotime( $horario->desde)) }}">

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
                        <input type="text" id="hasta" name="hasta" class="form-control timepicker" value="{{ date('h:i:s A', strtotime( $horario->hasta)) }}">

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
                            <input type="text" id="restringir" name="restringir" class="form-control timepicker" value="{{ date('h:i:s A', strtotime( $horario->restringir)) }}">

                            <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                </div>

            <div class="form-group col-lg-12">
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

@endsection
