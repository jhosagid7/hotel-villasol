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
            <h3>Nuevo Ingreso</h3>
            @include('custom.message')
        </div>
    </div>


<form action="{{route('ingreso.store')}}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true">
                    @foreach ($personas as $persona)
                <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="tipo_comprobante">Tipo Comprobante</label>
                <select name="tipo_comprobante" class="form-control">
                    <option value="Oreden">Oreden</option>
                    <option value="Factura">Factura</option>
                    <option value="Ticket">Ticket</option>
                </select>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Control Comprobante</label>
                <input type="text" name="serie_comprobante" class="form-control" value="{{old('serie_comprobante')}}" placeholder="Control Comprobante...">
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante</label>
                <input type="text" name="num_comprobante" required class="form-control" value="{{old('num_comprobante')}}" placeholder="Número Comprobante...">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="articulo">Artículo</label>
                            <select name="jidarticulo" id="jidarticulo" class="form-control selectpicker" data-live-search="true">
                                @foreach ($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}">{{$articulo->articulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" name="jcantidad" id="jcantidad"  class="form-control" placeholder="Cantidad...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form group">
                            <label for="precio_compra">Precio Compra</label>
                            <input type="number" name="jprecio_compra" id="jprecio_compra"  class="form-control" placeholder="Precio compra...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form group">
                            <label for="precio_venta">Precio Venta</label>
                            <input type="number" name="jprecio_venta" id="jprecio_venta"  class="form-control" placeholder="Precio venta...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form group">
                            <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <table id="detalles" class="table table-striped table-borderd table-condensed table-hover">
                            <thead style="background-color: #A9D0F5">
                                <th>Opciones</th>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">$. 0.00</h4></th>
                            </tfoot>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="guardar">
            <div class="form-group">

                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
        </div>
    </div>
</form>

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

    $(document).ready(function(){
        $("#bt_add").click(function(){
            add_article();
        });
    });

    var cont=0;
    var total=parseFloat(0.00);
    var precio_compra=parseFloat(0.00);
    var precio_venta=parseFloat(0.00);
    subtotal=[];
    $("#guardar").hide();

    function add_article(){
        idarticulo=$("#jidarticulo").val();
        articulo=$("#jidarticulo option:selected").text();
        cantidad=$("#jcantidad").val();
        precio_compra=$("#jprecio_compra").val();
        precio_venta=$("#jprecio_venta").val();

        if(idarticulo!="" && cantidad!="" && cantidad>0 && precio_compra!="" && precio_venta!=""){
            subtotal[cont]=(cantidad*precio_compra);
            total=total+subtotal[cont];

            var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="cantidad[]" value="'+cantidad+'"></td><td><input type="number" name="precio_compra[]" value="'+precio_compra+'"></td><td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td><td>'+parseFloat(subtotal[cont])+'</td></tr>';
            cont++

            clear();
            $("#total").html("$. " + total);
            verify();
            $("#detalles").append(fila);
        }else{
            alert("Error al ingresar el producto, revise los datos del articulo");
        }
    };

    function clear(){
        $("#jcantidad").val("");
        $("#jprecio_compra").val("");
        $("#jprecio_venta").val("");
    }

    function verify(){
        if(total>0){
            $("#guardar").show();
        }else{
            $("#guardar").hide();
        }
    }
    function eliminar(index){
        total=total-subtotal[index];
        $("#total").html("$/. " + total);
        $("#fila" + index).remove();
        verify();
    };
</script>
@endpush

@endsection
