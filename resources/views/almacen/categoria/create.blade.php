@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Nueva Categoria</h3>
            @include('custom.message')


            <form action="{{ route('categoria.store')}}" method="POST" autocomplete="off">

            @csrf
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombre...">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" class="form-control form-control-sm" placeholder="Descripcion...">
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
                <button class="btn btn-danger btn-sm" type="reset">Cancelar</button>
            </div>

            </form>
        </div>
    </div>

@endsection
