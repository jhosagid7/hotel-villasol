@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Editar Categoria: {{$categoria->nombre}}</h3>
            @include('custom.message')


            {{-- {!! Form::model($categoria,['route'=>['categoria.update', $categoria->idcategoria], 'method'=>'PATCH']) !!}

            {{ Form::token() }} --}}
            <form action="{{ route('categoria.update', $categoria->idcategoria)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
                @csrf
                @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control form-control-sm" value="{{ $categoria->nombre }}" placeholder="Nombre...">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" class="form-control form-control-sm" value="{{ $categoria->descripcion }}" placeholder="Descripcion...">
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
                <button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
            </div>
            </form>

        </div>
    </div>

@endsection
