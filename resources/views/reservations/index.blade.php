@extends ('layouts.admin3')
@section('contenido')

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    @isset($title)
                        {{ $title }}
                    @else
                        {!! 'Sistema' !!}
                    @endisset
                </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        @can('haveaccess', 'venta.create')
                            <h3>Reservasiones</h3>
                        @endcan
                    </div>
                </div>
                <div class="container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center">
                        @can('haveaccess', 'venta.create')
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10" id="calendar"></div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                            @include('reservations.modal')
                        @endcan
                    </div>
                </div>



            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{-- Footer --}}
            </div>
            <!-- /.box-footer-->
        </div>

        @push('sciptsMain')
            <script src="{{ asset('fullcalendar/dist/index.global.min.js') }}"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        //initialDate:'2023-07-01',
                        initialView: 'dayGridMonth',

                        navLinks: true, // can click day/week names to navigate views
                        editable: true,
                        dayMaxEvents: true,

                        customButtons: {
                            myCustomButton: {
                                text: 'crear reservacion!',
                                click: function() {
                                    limpiarFormulario()
                                    $('#exampleModal').modal()
                                }
                            }
                        },

                        headerToolbar: {
                            left: 'prev,next today myCustomButton',
                            center: 'title',
                            right: 'dayGridMonth,listWeek'
                        },

                        dateClick: function(info) {
console.log('dateClick')
                            limpiarFormulario()
                            activeBtnProcessService()

                            $('#txtFechaEntrada').val(info.dateStr)
                            $('#txtFechaSalida').val(info.dateStr)

                            $('#txtServicio').prop("disabled", false)
                            $('#btnModificar').prop("disabled", true)
                            $('#btnEliminar').prop("disabled", true)
                            $('#btnProcesar').prop("disabled", true)

                            $('#txtColor').val('#C67110')

                            document.getElementById("txtStatus").value = "Pendiente";

                            actualizarHorasFechas()

                            $('#exampleModal').modal()
                            console.log(info);


                            //calendar.addEvent({
                            //title:"Reservacion nueva",
                            //date:info.dateStr
                            //})
                        },

                        eventClick: function(info) {
                            limpiarFormulario()


console.log('eventClick')

                            var optionProcesado = document.getElementById("optionProcesado");

                            $('#btnAgregar').prop("disabled", true)
                            $('#btnModificar').prop("disabled", false)
                            $('#btnEliminar').prop("disabled", false)
                            $('#btnProcesar').prop("disabled", true)

                            whenIsProceded(info.event.extendedProps.status, info.event.extendedProps.numServicio)

                            if(info.event.extendedProps.numServicio){
                                optionProcesado.style.display = "none";
                            }else{
                                optionProcesado.style.display = "block";
                            }


                            mesEnd = (info.event.end.getMonth() + 1)
                            diaEnd = (info.event.end.getDate())
                            anioEnd = (info.event.end.getFullYear())

                            minutosEnd = info.event.end.getMinutes()
                            horaEnd = info.event.end.getHours()

                            minutosEnd = (minutosEnd < 10) ? "0" + minutosEnd : minutosEnd
                            horaEnd = (horaEnd < 10) ? "0" + horaEnd : horaEnd

                            horarioEnd = (horaEnd + ":" + minutosEnd)

                            mesEnd = (mesEnd < 10) ? "0" + mesEnd : mesEnd
                            diaEnd = (diaEnd < 10) ? "0" + diaEnd : diaEnd

                            mes = (info.event.start.getMonth() + 1)
                            dia = (info.event.start.getDate())
                            anio = (info.event.start.getFullYear())

                            minutos = info.event.start.getMinutes()
                            hora = info.event.start.getHours()

                            minutos = (minutos < 10) ? "0" + minutos : minutos
                            hora = (hora < 10) ? "0" + hora : hora

                            horario = (hora + ":" + minutos)

                            mes = (mes < 10) ? "0" + mes : mes
                            dia = (dia < 10) ? "0" + dia : dia




                            $('#txtID').val(info.event.id)
                            $('#txtTitle').val(info.event.title)
                            $('#txtFechaEntrada').val(anio + "-" + mes + "-" + dia)
                            $('#txtHoraEntrada').val(horario)
                            $('#txtFechaSalida').val(anioEnd + "-" + mesEnd + "-" + diaEnd)
                            $('#txtHoraSalida').val(horarioEnd)

                            $('#txtPersonaContacto').val(info.event.extendedProps.personaContacto)
                            $('#txtNombreCliente').val(info.event.extendedProps.nombreCliente)
                            $('#txtCedulaCliente').val(info.event.extendedProps.cedulaCliente)
                            $('#txtTelefonoContacto').val(info.event.extendedProps.telefonoContacto)
                            $('#txtNumAcompanantes').val(info.event.extendedProps.numAcompanantes)
                            $('#txtHabitacion').val(info.event.extendedProps.cat_id)
                            $('#txtServicio').val(info.event.extendedProps.horario_id)
                            $('#txtNumDias').val(info.event.extendedProps.cantidad)
                            $('#txtNumHabitacion').val(info.event.extendedProps.numHabitacion)
                            $('#txtPrecio').val(info.event.extendedProps.precio)
                            $('#txtMontoPago').val(info.event.extendedProps.montoPago)
                            $('#txtVueltoPago').val(info.event.extendedProps.vueltoPago)
                            $('#txtTelefonoPago').val(info.event.extendedProps.telefonoPago)
                            $('#txtCedulaPago').val(info.event.extendedProps.cedulaPago)
                            $('#txtNombreOperador').val('{{ Auth::user()->name }}')
                            $('#txtStatus').val(info.event.extendedProps.status)
                            $('#txtOboservation').val(info.event.extendedProps.observation)
                            $('#txtNumServicio').val(info.event.extendedProps.numServicio)
                            $('#txtColor').val(info.event.backgroundColor)
                            $('#servicio_id').val(info.event.extendedProps.servicio_id)
                            $('#persona_id').val(info.event.extendedProps.persona_id)
                            $('#user_id').val({{ Auth::user()->id }})


                            $('#txtServicio option[value="' + info.event.extendedProps.tipoServicio + '"]').attr(
                                'selected', true);
                            $('#txtHabitacion option[value="' + info.event.extendedProps.tipoHabitacion + '"]').attr(
                                'selected', true);
                            $('#txtStatus option[value="' + info.event.extendedProps.status + '"]').attr(
                                'selected', true);
console.log(info.event.extendedProps.montoPago)
                            var detallePagoReservaciones = info.event.extendedProps.detalle_pago_reservaciones;
                            console.log(detallePagoReservaciones)
                            if (detallePagoReservaciones) {
                                // Llenar la tabla con los datos obtenidos
                                detallePagoReservaciones.forEach(function(pago) {
                                    console.log(pago.caja_id, " - ", {{ $caja_id }})
                                    if({{ $caja_id }} != pago.caja_id){
                                        // Agregar una fila a la tabla con los datos de cada pago
                                        agregarPagoFromDataBase(pago.id, pago.tipoPago, pago.nombreBanco, pago.referencia, pago.fechaPago, pago.montoPagado, pago.vueltos);

                                    }else{
                                        agregarPagoEnTabla(pago.id, pago.tipoPago, pago.nombreBanco, pago.referencia, pago.fechaPago, pago.montoPagado, pago.vueltos, pago.caja_id);
                                    }


                                    // Actualizar el último tipo de pago y monto pagado en la misma moneda
                                    ultimoTipoPago = pago.tipoPago;
                                    ultimoMontoPagado = pago.montoPagado;

                                });
                                // Mostrar tabla de pagos si tiene datos
                                    var tablaPagosContainer = document.getElementById("tablaPagosContainer");
                                    tablaPagosContainer.style.display = "block";

                                    // Reiniciar campos de pago
                                    document.getElementById("camposPago").innerHTML = "";
                                    document.getElementById("tipoPago").value = "";

                            }
                            console.log('entre ', info.event.extendedProps.numHabitacion)
                            obtenerNumHabitacion(info.event.extendedProps.numHabitacion)
                            obtenerPrecio()

                            // Actualizar total de abonos en dólares
                                    actualizarTotalAbonos();



                            $('#exampleModal').modal()
                        },

                        events: "{{ url('/reservations/show') }}"


                    });
                    calendar.setOption('locale', 'Es')
                    calendar.render();

                    $('#btnAgregar').click(function() {

                        objReservacion = recolectarDatosGUI("POST")
                        console.log(objReservacion)
                        EnviarInformacion('', objReservacion)

                    })

                    $('#btnModificar').click(function() {

                        objReservacion = recolectarDatosGUI("PATCH")
                        console.log(objReservacion)

                        EnviarInformacion('/' + $('#txtID').val(), objReservacion)

                    })
                    $('#btnEliminar').click(function() {
                        objReservacion = recolectarDatosGUI("DELETE")

                        EnviarInformacion('/' + $('#txtID').val(), objReservacion)

                    })

                    $('#btnProcesar').click(function() {

                        objReservacion = recolectarDatosGUI("PATCH", true)

                        console.log(objReservacion)



                        let activeBtn = activeBtnProcessService()
                        if(activeBtn){

                            console.log('Enviando...', $('#txtID').val())
                            EnviarInformacion('/' + $('#txtID').val(), objReservacion)
                        }else{
                            console.log('Monto insuficiente...')
                        }


                        //objReservacion = recolectarDatosGUI("DELETE")

                        //EnviarInformacion('/' + $('#txtID').val(), objReservacion)

                    })



                    function recolectarDatosGUI(method, processService = false) {

                        nuevaReserva = {
                            id: $('#txtID').val(),
                            start: $('#txtFechaEntrada').val() + " " + $('#txtHoraEntrada').val(),
                            end: $('#txtFechaSalida').val() + " " + $('#txtHoraSalida').val(),
                            title: $('#txtHabitacion').find("option:selected").text() + " " + $('#txtNombreCliente').val(),
                            personaContacto: $('#txtPersonaContacto').val(),
                            nombreCliente: $('#txtNombreCliente').val(),
                            cedulaCliente: $('#txtCedulaCliente').val(),
                            telefonoContacto: $('#txtTelefonoContacto').val(),
                            numAcompanantes: $('#txtNumAcompanantes').val(),
                            cat_id: $('#txtHabitacion').find("option:selected").val(),
                            horario_id: $('#txtServicio').find("option:selected").val(),
                            habitacione_id: $('#txtNumHabitacion').find("option:selected").val(),
                            tipoHabitacion: $('#txtHabitacion').find("option:selected").text(),
                            tipoServicio: $('#txtServicio').find("option:selected").text(),
                            cantidad: $('#txtNumDias').val(),
                            numHabitacion: $('#txtNumHabitacion').find("option:selected").text(),
                            precio: $('#precioShowServicio').html(),
                            montoPago: $('#txtMontoPago').val(),
                            vueltoPago: $('#txtVueltoPago').val(),
                            telefonoPago: $('#txtTelefonoPago').val(),
                            cedulaPago: $('#txtCedulaPago').val(),
                            operadorNombre: $('#txtNombreOperador').val(),
                            status: $('#txtStatus').find("option:selected").text(),
                            observation: $('#txtOboservation').val(),
                            numServicio: $('#txtNumServicio').val(),
                            color: $('#txtColor').val(),
                            persona_id: $('#persona_id').val(),
                            user_id: {{ Auth::user()->id }},

                            formaPago: obtenerDatosReserva(),
                            '_token': $("meta[name='csrf-token']").attr("content"),
                            '_method': method,
                            '_processService': processService

                        }

                        return (nuevaReserva)
                    }

                    function EnviarInformacion(accion, objReservacion) {

                        $.ajax({
                            type: "POST",
                            url: "{{ url('reservations') }}" + accion,
                            data: objReservacion,
                            success: function(msg) {
                                console.log(msg)

                                toastr.success(msg.msg, 'Notice', {
                                    timeOut: 3000
                                })
                                $('#exampleModal').modal('toggle')
                                calendar.refetchEvents()
                            },
                            error: function() {
                                alert('Hay un error al enviar los datos...')
                            }
                        })
                    }

                    function limpiarFormulario() {

                        $('#txtID').val('')
                        $('#txtTitle').val('')
                        $('#txtFechaEntrada').val('')
                        $('#txtHoraEntrada').val('')
                        $('#txtFechaSalida').val('')
                        $('#txtHoraSalida').val('')
                        $('#txtPersonaContacto').val('')
                        $('#txtNombreCliente').val('')
                        $('#txtCedulaCliente').val('')
                        $('#txtTelefonoContacto').val('')
                        $('#txtNumAcompanantes').val(1)
                        $('#txtHabitacion').val('')
                        $('#txtServicio').val('')
                        $('#txtNumDias').val(1)
                        $('#txtNumHabitacion').val('')
                        $('#txtMontoPago').val('')
                        $('#txtTelefonoPago').val('')
                        $('#txtCedulaPago').val('')
                        $('#txtNombreOperador').val('{{ Auth::user()->name }}')
                        $('#txtStatus').val('')
                        $('#txtOboservation').val('')
                        $('#txtNumServicio').val('')
                        $('#txtColor').val('')
                        $('#persona_id').val('')
                        $('#user_id').val({{ Auth::user()->id }})

                        whenIsProceded()

                        $('#tablaPagosBody').empty();
                        $('#payDataBase').empty();
                        $('#txtNumHabitacion').empty()

                        $('#precioShow').html('0.00');
                        $('#precioShowServicio').html('0.00');
                        var tablaPagosContainer = document.getElementById("tablaPagosContainer");
                        tablaPagosContainer.style.display = "none";
                        document.getElementById("totalAbonosDolar").innerText = "";

                        var optionProcesado = document.getElementById("optionProcesado");
                        optionProcesado.style.display = "none";




                    }
                });

                function whenIsProceded(value = false, numservice = true){
                    console.log('estoy en whenIsProceded')
                    if(value == 'Procesado' || value == 'Cancelado'){
                        $('#txtMontoPago').prop("disabled", true)
                        $('#buscarClienteInput').prop("hidden", true)
                        $('#txtFechaEntrada').prop("disabled", true)
                        $('#txtHoraEntrada').prop("disabled", true)
                        $('#txtFechaSalida').prop("disabled", true)
                        $('#txtHoraSalida').prop("disabled", true)
                        $('#txtPersonaContacto').prop("disabled", true)
                        $('#txtNombreCliente').prop("disabled", true)
                        $('#txtCedulaCliente').prop("disabled", true)
                        $('#txtTelefonoContacto').prop("disabled", true)
                        $('#txtNumAcompanantes').prop("disabled", true)
                        $('#txtHabitacion').prop("disabled", true)
                        $('#txtServicio').prop("disabled", true)
                        $('#txtNumDias').prop("disabled", true)
                        $('#numHabitacion').prop("disabled", true)
                        $('#txtMontoPago').prop("disabled", true)
                        $('#txtTelefonoPago').prop("disabled", true)
                        $('#txtCedulaPago').prop("disabled", true)
                        $('#txtNombreOperador').val('{{ Auth::user()->name }}')
                        $('#txtOboservation').prop("disabled", true)
                        $('#txtStatus').prop("disabled", true)
                        $('#txtNumServicio').prop("disabled", true)
                        $('#txtColor').prop("disabled", true)
                        $('#persona_id').prop("disabled", true)
                        $('#user_id').val({{ Auth::user()->id }})
                        $('#btnModificar').prop("disabled", true)
                        $('#btnEliminar').prop("disabled", true)
                        $('#btnProcesar').prop("disabled", true)
                    }else{
                        $('#txtMontoPago').prop("disabled", true)
                        $('#buscarClienteInput').prop("hidden", false)
                        $('#txtFechaEntrada').prop("disabled", false)
                        $('#txtHoraEntrada').prop("disabled", false)
                        $('#txtFechaSalida').prop("disabled", false)
                        $('#txtHoraSalida').prop("disabled", false)
                        $('#txtPersonaContacto').prop("disabled", false)
                        $('#txtNombreCliente').prop("disabled", false)
                        $('#txtCedulaCliente').prop("disabled", false)
                        $('#txtTelefonoContacto').prop("disabled", false)
                        $('#txtNumAcompanantes').prop("disabled", false)
                        $('#txtHabitacion').prop("disabled", false)
                        $('#txtServicio').prop("disabled", false)
                        $('#txtNumDias').prop("disabled", false)
                        $('#numHabitacion').prop("disabled", false)
                        $('#txtMontoPago').prop("disabled", false)
                        $('#txtTelefonoPago').prop("disabled", false)
                        $('#txtCedulaPago').prop("disabled", false)
                        $('#txtNombreOperador').val('{{ Auth::user()->name }}')
                        $('#txtOboservation').prop("disabled", false)
                        $('#txtStatus').prop("disabled", false)
                        $('#txtColor').prop("disabled", false)
                        $('#persona_id').prop("disabled", false)
                        $('#user_id').val({{ Auth::user()->id }})
                        $('#btnModificar').prop("disabled", false)
                        $('#btnEliminar').prop("disabled", false)
                        $('#btnProcesar').prop("disabled", false)


                    }

                    if(value == 'Pendiente' && numservice == null){
                        $('#buscarClienteInput').prop("hidden", true)
                        $('#txtNombreCliente').prop("disabled", true)
                        $('#txtCedulaCliente').prop("disabled", true)
                        $('#txtServicio').prop("disabled", true)
                        $('#txtMontoPago').prop("disabled", true)
                        $('#txtNombreOperador').val('{{ Auth::user()->name }}')
                        $('#txtNumServicio').prop("disabled", true)
                        $('#txtColor').prop("disabled", true)
                        $('#user_id').val({{ Auth::user()->id }})
                        $('#btnModificar').prop("disabled", false)
                        $('#btnEliminar').prop("disabled", false)
                        $('#btnProcesar').prop("disabled", false)
                    }


                }
            </script>
            <script>
                // Obtén los elementos select y txtColor
                const select = document.getElementById('txtStatus');
                const txtColor = document.getElementById('txtColor');

                // Agrega un evento de cambio al select
                select.addEventListener('change', function() {
                // Obtén el valor seleccionado del select
                const selectedValue = select.value;

                // Asigna el color correspondiente al campo txtColor según el valor seleccionado
                if (selectedValue === 'Pendiente') {
                    txtColor.value = '#C67110';
                } else if (selectedValue === 'Procesado') {
                    txtColor.value = '#118F00';
                } else if (selectedValue === 'Cancelado') {
                    txtColor.value = '#FE0606';
                }
                });
            </script>

            <script>
                function actualizarHorasFechas() {
                    const txtServicio = document.getElementById("txtServicio");
                    const cantidad = document.getElementById("txtNumDias").value;
                    const valorServicio = txtServicio.value;
                    let horaInicio = "";
                    let horaFinal = "";
                    let fechaEntrada = new Date(document.getElementById("txtFechaEntrada").value);
                    let fechaSalida = new Date(fechaEntrada);

                    switch (valorServicio) {

                        case "3":
                            horaInicio = "14:00";
                            horaFinal = "14:00";
                            fechaSalida.setDate(fechaSalida.getDate() + parseFloat(cantidad));
                            break;
                    }

                    // Mostrar las horas de inicio y final
                    document.getElementById("txtHoraEntrada").value = horaInicio;
                    document.getElementById("txtHoraSalida").value = horaFinal;

                    // Mostrar las fechas de entrada y salida en el formato "yyyy-MM-dd"
                    document.getElementById("txtFechaEntrada").value = fechaEntrada.toISOString().split('T')[0];
                    document.getElementById("txtFechaSalida").value = fechaSalida.toISOString().split('T')[0];
                }

                $(document).ready(function() {
                    const txtServicio = document.getElementById("txtServicio");
                    txtServicio.addEventListener("change", actualizarHorasFechas);
                });
            </script>

            <script>
                function obtenerDatosReserva() {
                    var tablaPagosBody = document.getElementById("tablaPagosBody").getElementsByTagName("tr");
                    var formaPagoArray = [];


                    for (var i = 0; i < tablaPagosBody.length; i++) {
                        var tipoPago = tablaPagosBody[i].getElementsByTagName("td")[0].innerText;
                        var nombreBanco = "";
                        var referencia = "";
                        var fechaPago = "";

                        if (tipoPago === "transferencia" || tipoPago === "punto") {
                        nombreBanco = tablaPagosBody[i].getElementsByTagName("td")[1].innerText;
                        referencia = tablaPagosBody[i].getElementsByTagName("td")[2].innerText;
                        fechaPago = tablaPagosBody[i].getElementsByTagName("td")[3].innerText;
                        }

                        var montoPagado = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[4].innerText) || 0;
                        var vueltos = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[5].innerText) || 0;
                        var vueltosDolar = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[6].innerText) || 0;
                        var id = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[7].innerText) || null;
                        var montoEnDolares = calcularMontoEnDolares(montoPagado, tipoPago);
                        console.log('vueltos desde: ', vueltos)
                        console.log('vueltos desde2: ', vueltosDolar)
                        var formaPago = {
                        id: id,
                        tipoPago: tipoPago,
                        montoPagado: montoPagado,
                        montoPagadoDolar: montoEnDolares,
                        vueltos: vueltos,
                        vueltosDolar: vueltosDolar,
                        nombreBanco: nombreBanco,
                        referencia: referencia,
                        fechaPago: fechaPago,
                        tasaDolar: tasaDolar,
                        tasaPeso: tasaPeso,
                        tasaBolivar: tasaBolivar,
                        operadorNombre: $('#txtNombreOperador').val()
                        };

                        formaPagoArray.push(formaPago);
                    }

                    return formaPagoArray;
                }
            </script>

        @endpush
    @endsection
