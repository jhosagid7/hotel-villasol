
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><b class="text-warning">DATOS DE CONTACTO</b></h3><br>
        Datos del cliente:
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="form-group col-md-5">
                <label class="text-black" for="txtPersonaContacto">Persona de contacto:</label>
                <input class="form-control" type="text" name="txtPersonaContacto" id="txtPersonaContacto"
                    placeholder="txtPersonaContacto">
            </div>
            <div class="form-group col-md-5">
                <label class="text-black" for="txtTelefonoContacto">Telefono contacto<span
                        class="text-danger">(*)</span>:</label>
                <input required class="form-control" type="text" name="txtTelefonoContacto" id="txtTelefonoContacto"
                    placeholder="txtTelefonoContacto">
            </div>
            <div class="form-group col-md-2">
                <label class="text-black" for="txtNumAcompanantes">N°/Pers:</label>
                <input class="form-control" type="number" name="txtNumAcompanantes" id="txtNumAcompanantes" value="1" min="1">
            </div>
            <input hidden class="text-black " type="text" id="persona_id" name="persona_id">

            <div id="buscarClienteInput" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label id="titlebuscarcliente" class="text-black" for="nombrea"><h5 class="box-title"><b class="text-primary">BUSCAR CLIENTE</b></h5></label>
                    <input autofocus type="text" name="nombrea" id="nombrea" class="form-control"
                        placeholder="Buscar cliente por nombre o C.I./RIF...">
                    <span id="nombreamesagge" class="text-red"></span>
                </div>

            </div>



            <div class="form-group col-md-6">
                <label class="text-black" for="txtNombreCliente">Nombre cliente<span
                        class="text-danger">(*)</span>:</label>
                <input required disabled class="form-control" type="text" name="txtNombreCliente" id="txtNombreCliente"
                    placeholder="txtNombreCliente">
                    <span id="txtNombreCliente_mesagge" class="text-red"></span>
            </div>

            <div class="form-group col-md-6">
                <label class="text-black" for="txtCedulaCliente">Cedula cliente<span
                        class="text-danger">(*)</span>:</label>
                <input disabled required class="form-control" type="number" name="txtCedulaCliente" id="txtCedulaCliente"
                    placeholder="txtCedulaCliente">
                    <span id="txtCedulaCliente_mesagge" class="text-red"></span>
            </div>









        </div>
    </div>
</div>

@push('sciptsMain')
<script src="{{ asset('jquery-ui-1.12.1/jquery-ui.js') }}"></script>
    <script>
        $(document).ready(function() {
            //#####################################################################################################
            // Se encarga de traer los clentes de la base de datos del archivo searchController y manejar las validaciones del apartado efectivo
            // #####################################################################################################

            $('#nombrea').autocomplete({

                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('search.personas') }}",
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                         beforeSend: function() {
                            $("#nombreamesagge").removeClass("text-green");
                            $("#nombreamesagge").removeClass("text-red");
                            $("#nombreamesagge").addClass("text-blue");
                            $("#nombreamesagge").text('¡Buscando cliente!');
                        },
                        success: function(data) {
                            response(data)
                            console.log('respuesta ', data.item);


                        }

                    });

                },
                response: function(event, ui) {
                    if (!ui.content.length) {
                        $("#persona_id").val('');
                        $("#txtNombreCliente").val('');
                        $("#txtCedulaCliente").val('');
                        $('#txtCedulaPago').val('')
                        $("#direccion").val('');
                        $("#tetxtNombreClientelefono").val('');
                        $("#email").val('');
                        $("#nombreamesagge").removeClass("text-blue");
                        $("#nombreamesagge").removeClass("text-green");
                        $("#nombreamesagge").addClass("text-red");
                        $("#nombreamesagge").text('!Cliente no encontrado...');
                        $("#guardarFormaPago").addClass("hidden");

                        $("#txtCedulaCliente").prop("disabled", true);
                        $("#txtNombreCliente").prop("disabled", false);
                        // $("#nombre").addClass("readonly");
                        $("#txtNombreCliente").blur();
                        $("#txtNombreCliente").keyup();


                    }else{
                        $("#nombreamesagge").removeClass("text-blue");
                        $("#nombreamesagge").removeClass("text-red");
                        $("#nombreamesagge").addClass("text-green");
                        $("#nombreamesagge").text('¡Cliente encontrado!');
                    }
                },

                minLength: 3,
                select: function(event, ui) {
                    // alert(ui.item.label);
                    // $("#nombrea").val(ui.item.label);
                    $("#persona_id").val(ui.item.id);
                    $("#txtNombreCliente").val(ui.item.nombre);
                    $("#txtCedulaCliente").val(ui.item.num_documento);
                    $('#txtCedulaPago').val(ui.item.num_documento)
                    $("#direccion").val(ui.item.direccion);
                    $("#telefono").val(ui.item.telefono);
                    $("#email").val(ui.item.email);
                    $("#nombrea").val('');
                    // $('#codigo').val(ui.item.codigo)
                    $("#nombreamesagge").removeClass("text-blue");
                    $("#nombreamesagge").removeClass("text-red");
                    $("#nombreamesagge").addClass("text-green");
                    $("#nombreamesagge").text('¡Cliente encontrado!');

                    // $("#nombre").removeClass("text-red");
                    $("#txtCedulaCliente").prop("disabled", true);
                    $("#txtNombreCliente").prop("disabled", true);
                    // $("#nombre").addClass("readonly");
                    $("#txtNombreCliente").blur();
                    $("#txtNombreCliente").keyup();
                    return false;
                },
                // focus: function( event, ui ) {
                //     $("#num_documento_oficina").val(ui.item.num_documento);
                //     $("#nombre").val(ui.item.nombre);
                //     $("#nombre").attr("readonly","readonly");
                //     $("#nombre").keyup();
                //     // $("#nombre").blur();
                // },
            });

            $("#txtCedulaCliente").click(function() {

                let leng = $(this).val().length;

                if (leng < 7) {
                    $("#txtCedulaCliente_mesagge").removeClass("text-green");
                    $("#txtCedulaCliente_mesagge").addClass("text-red");
                    $("#txtCedulaCliente_mesagge").text('!Tiene que ingrasar mas de 7 numeros...');
                    $("#guardarFormaPago").addClass("hidden");
                    return false;
                }

                $("#txtCedulaCliente_mesagge").removeClass("text-red");
                $("#txtCedulaCliente_mesagge").addClass("text-green");
                $("#txtCedulaCliente_mesagge").text('¡Se ve bien!');

            });

            $("#txtNombreCliente").keyup(function() {
                $("#txtCedulaCliente").click();

                let leng = $(this).val().length;

                if (leng < 4) {
                    $("#txtNombreCliente_mesagge").removeClass("text-green");
                    $("#txtNombreCliente_mesagge").addClass("text-red");
                    $("#txtNombreCliente_mesagge").text('!Tiene que ingrasar mas de 4 caracteres...');
                    $("#guardarFormaPago").addClass("hidden");
                    return false;
                }

                $("#txtNombreCliente_mesagge").removeClass("text-red");
                $("#txtNombreCliente_mesagge").addClass("text-green");
                $("#txtNombreCliente_mesagge").text('¡Se ve bien!');
                $("#guardarFormaPago").removeClass("hidden");


            });

            $("#txtNombreCliente").blur(function() {
                let is_num_documento = $("#txtCedulaCliente").val();
                let is_nombre = $("#txtNombreCliente").val();
                let leng = $(this).val().length;

                if (is_num_documento == '' || is_nombre == '' || leng < 4) {
                    $("#guardarFormaPago").addClass("hidden");
                    $("#txtNombreCliente").keyup();
                } else {
                    $("#txtNombreCliente").keyup();
                    $("#guardarFormaPago").removeClass("hidden");
                }
            });

            $("#nombrea").blur(function() {

                let is_num_documento = $("#txtCedulaCliente").val();
                let is_nombre = $("#txtNombreCliente").val();
                let is_nombrea = $("#nombrea").val();
                let is_persona_id = $("#persona_id").val();
                if (is_persona_id === '' && !is_nombrea == '') {
                    document.getElementById("txtNombreCliente").focus();
                    $("#txtCedulaCliente").val(is_nombrea);
                    $("#txtNombreCliente").removeAttr("readonly");
                    $("#txtNombreCliente").blur();
                    $("#notxtNombreClientembre").keyup();

                } else if (is_persona_id) {
                    $("#txtNombreCliente").attr("readonly", "readonly");
                    document.getElementById("txtNombreCliente").focus();
                    $("#txtNombreCliente").blur();
                    $("#txtNombreCliente").keyup();
                    $("#guardarFormaPago").removeClass("hidden");
                } else {
                    $("#txtNombreCliente").blur();
                    $("#txtNombreCliente").keyup();

                }

            });



        });
    </script>
@endpush
