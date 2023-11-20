<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><b class="text-warning">FORMAS DE PAGO</b></h3>

    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">

            <div class="form-group col-md-3">
                <label class="text-black" for="txtCedulaPago">Cedula<span class="text-danger">(*)</span></label>
                <input class="form-control" type="text" name="txtCedulaPago" id="txtCedulaPago"
                    placeholder="txtCedulaPago">
            </div>

            <div class="form-group col-md-3">
                <label class="text-black" for="txtTelefonoPago">Telefono:</label>
                <input class="form-control" type="text" name="txtTelefonoPago" id="txtTelefonoPago"
                    placeholder="txtTelefonoPago">
            </div>

            <div class="form-group col-md-3">
                <label class="text-black" for="txtMontoPago">Monto pagado:<span class="text-danger">(*)</span></label>
                <input required class="form-control" type="number" name="txtMontoPago" id="txtMontoPago"
                    placeholder="txtMontoPago">
            </div>

            <div class="form-group col-md-3">
                <label class="text-black" for="txtPrecio">Total a pagar:<h3 class="box-title"><b
                            class="text-warning">$<b id="precioShow">0.00</b></b></h3></label>
                <input required class="form-control" type="hidden" name="txtPrecio" id="txtPrecio"
                    placeholder="txtPrecio">
                <input required class="form-control" type="hidden" name="txtVueltoPago" id="txtVueltoPago"
                    placeholder="txtVueltoPago">

            </div>
            <div class="form-group col-md-12">
                <label class="text-black" for="tipoPago">
                    <h5 class="box-title"><b class="text-primary">AGREGAR PAGO</b><span class="text-danger">(*)</span>:
                    </h5>
                </label>
                <select class="form-control" id="tipoPago" onchange="mostrarCamposPago()">
                    <option value="">Agregar Pago...</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Punto">Punto</option>
                    <option value="Dolar">Dólar</option>
                    <option value="Peso">Peso</option>
                    <option value="Bolivar">Bolívar</option>
                </select>
                <span id="txtHabitacionMesagge" class="text-red"></span>
            </div>

            <div id="camposPago" style="display: none;">
                <!-- Los campos de pago se generarán dinámicamente aquí -->
            </div>
        </div>


        <!-- Tabla de Pagos -->
        <div class="card" id="tablaPagosContainer" style="display: none;">
            <div class="card-header text-black">
                Resumen de pagos
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Banco</th>
                                <th>Ref</th>
                                <th>Fecha</th>
                                <th>Abono</th>
                                <th>Vtos</th>
                                <th class="hidden">Vueltos</th>
                            </tr>
                        </thead>
                        <tbody id="payDataBase"></tbody>
                        <tbody id="tablaPagosBody">
                            <!-- Los detalles de los pagos se generarán dinámicamente aquí -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th colspan="3">Total Abonos (Dólar)</th>
                                <th id="totalAbonosDolar">0.00</th>

                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th  colspan="3"><button class="btn btn-success" id="btnProcesar">Procesar servicio</button></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group col-md-8 hidden">
            <label class="text-black" for="txtBancoPago">Banco:</label>
            <input class="form-control" type="text" name="txtBancoPago" id="txtBancoPago" placeholder="txtBancoPago">
        </div>

        <div class="form-group col-md-4 hidden">
            <label class="text-black" for="txtFechaPago">Fecha:</label>
            <input class="form-control" type="date" name="txtFechaPago" id="txtFechaPago" placeholder="txtFechaPago">
        </div>

        <div class="form-group col-md-8 hidden">
            <label class="text-black" for="txtReferenciaPago">Referencia:</label>
            <input class="form-control" type="text" name="txtReferenciaPago" id="txtReferenciaPago"
                placeholder="txtReferenciaPago">
        </div>





    </div>
</div>
</div>

@push('sciptsMain')
    <script>
    // Variables para las tasas de conversión
    var tasaDolar = 1;
    var tasaPeso = 4500;
    var tasaBolivar = 32;
    // Variables para almacenar el último tipo de pago y monto pagado en la misma moneda
    var ultimoTipoPago = "";
    var ultimoMontoPagado = 0;
    // Función para agregar el pago a la tabla o actualizar el monto si ya existe un pago en la misma moneda
    function agregarPago() {
        var tipoPago = document.getElementById("tipoPago").value;
        var txtMontoPago = document.getElementById("txtMontoPago").value || 0;
        var nombreBanco = document.getElementById("nombreBanco") ? document.getElementById("nombreBanco").value : "";
        var referencia = document.getElementById("referencia") ? document.getElementById("referencia").value : "";
        var fechaPago = document.getElementById("fechaPago") ? document.getElementById("fechaPago").value : "";
        var montoPagado = 0;
        var vueltos = 0;
        if (tipoPago === "Transferencia") {
            montoPagado = document.getElementById("montoPagado").value || 0;
            agregarPagoPorSeparado(null, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos);
        } else if (tipoPago === "Punto") {
            montoPagado = document.getElementById("montoPagado").value || 0;
            agregarPagoPorSeparado(null, tipoPago, "", referencia, "", montoPagado, vueltos);
        } else if (tipoPago === "Dolar") {
            montoPagado = document.getElementById("montoDolar").value || 0;
            vueltos = document.getElementById("vueltos").value || 0;
            agregarPagoEnTabla(null, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos);
        } else if (tipoPago === "Peso") {
            montoPagado = document.getElementById("montoPesos").value || 0;
            vueltos = document.getElementById("vueltos").value || 0;
            agregarPagoEnTabla(null, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos);
        } else if (tipoPago === "Bolivar") {
            montoPagado = document.getElementById("montoBolivar").value || 0;
            vueltos = document.getElementById("vueltos").value || 0;
            agregarPagoEnTabla(null, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos);
        }
        // Mostrar tabla de pagos si tiene datos
        var tablaPagosContainer = document.getElementById("tablaPagosContainer");
        tablaPagosContainer.style.display = "block";
        // Actualizar total de abonos en dólares
        actualizarTotalAbonos();
        // Reiniciar campos de pago
        document.getElementById("camposPago").innerHTML = "";
        document.getElementById("tipoPago").value = "";
        // Actualizar el último tipo de pago y monto pagado en la misma moneda
        ultimoTipoPago = tipoPago;
        ultimoMontoPagado = montoPagado;
    }
    // Función para agregar un pago por separado (Transferencia y Punto)
    function agregarPagoPorSeparado(id, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos) {
        var filaPago = document.createElement("tr");
        filaPago.dataset.tipo = tipoPago;
        filaPago.innerHTML = `
            <td>${tipoPago || ''}</td>
            <td>${nombreBanco || ''}</td>
            <td>${referencia || ''}</td>
            <td>${fechaPago || ''}</td>
            <td>${montoPagado || ''}</td>
            <td>${vueltos || ''}</td>
            <td class="text hidden">${calcularMontoEnDolares(vueltos, tipoPago)}</td>
            <td class="text hidden">${id || null}</td>
            <td><button class="btn btn-danger btn-xs" onclick="eliminarPago(this)" data-idregistro="" data-idcaja="">X</button></td>
        `;
        var tablaPagosBody = document.getElementById("tablaPagosBody");
        tablaPagosBody.appendChild(filaPago);
    }
    // Función para agregar un pago en la tabla (Dolar, Peso, Bolivar)
    function agregarPagoEnTabla(id, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos,caja_id) {
        var tablaPagosBody = document.getElementById("tablaPagosBody");
        var filaPagoExistente = document.querySelector(`#tablaPagosBody tr[data-tipo="${tipoPago}"]`);
        if (filaPagoExistente) {
            var montoExistente = parseFloat(filaPagoExistente.querySelector("td:nth-child(5)").innerText);
            var vueltoExistente = parseFloat(filaPagoExistente.querySelector("td:nth-child(6)").innerText);
            // Actualizar monto existente y vuelto
            filaPagoExistente.querySelector("td:nth-child(5)").innerText = parseFloat(montoExistente) + parseFloat(
                montoPagado);
            filaPagoExistente.querySelector("td:nth-child(6)").innerText = parseFloat(vueltoExistente) + parseFloat(
                vueltos);
        } else {
            var filaPago = document.createElement("tr");
            filaPago.dataset.tipo = tipoPago;
            filaPago.innerHTML = `
                <td>${tipoPago || ''}</td>
                <td>${nombreBanco || ''}</td>
                <td>${referencia || ''}</td>
                <td>${fechaPago || ''}</td>
                <td>${montoPagado || 0}</td>
                <td>${vueltos || 0}</td>
                <td class="text">${calcularMontoEnDolares(vueltos, tipoPago)}</td>
                <td class="text">${id || null}</td>
                <td><button class="btn btn-danger btn-xs btnEliminarProcesado" onclick="eliminarPago(this)" data-idregistro="${id}" data-idcaja="${caja_id}">X</button></td>
            `;
            tablaPagosBody.appendChild(filaPago);
        }
    }

    // Función para agregar un pago por separado (Transferencia y Punto)
    function agregarPagoFromDataBase(id, tipoPago, nombreBanco, referencia, fechaPago, montoPagado, vueltos,caja_id) {
        var filaPago = document.createElement("tr");
        filaPago.dataset.tipo = tipoPago;
        filaPago.innerHTML = `
            <td>${tipoPago || ''}</td>
            <td>${nombreBanco || ''}</td>
            <td>${referencia || ''}</td>
            <td>${fechaPago || ''}</td>
            <td>${montoPagado || 0}</td>
            <td>${vueltos || 0}</td>
            <td class="text">${calcularMontoEnDolares(vueltos, tipoPago)}</td>
            <td class="text">${id || null}</td>
            <td><button class="btn btn-warning btn-xs btnEliminarProcesado" onclick="eliminarPago(this)" data-idregistro="${id}" data-idcaja="${caja_id}" disabled>X</button></td>
        `;

        let ver = caja_id == {{ $caja_id }} ? 'Son iguales' : 'No son iguales'

        var payDataBase = document.getElementById("payDataBase");
        payDataBase.appendChild(filaPago);
    }

    function activeBtnProcessService(){
                        let precioShow = $('#txtPrecio').val()
                        let txtMontoPago = $('#txtMontoPago').val()
                        let txtStatus = $('#txtStatus').val()


                        precioShow = parseFloat(precioShow)
                        txtMontoPago = parseFloat(txtMontoPago)
                        console.log('precioShow: ', precioShow);
                        console.log('txtMontoPago: ', txtMontoPago);
                        if(txtMontoPago === precioShow) {
                            $('#btnProcesar').prop("disabled", false)
                        }else {
                            $('#btnProcesar').prop("disabled", true)
                        }

                        if(txtStatus == 'Procesado'){
                            $('#btnProcesar').prop("disabled", true)
                            $('#tipoPago').prop("disabled", true)
                            $('.btnEliminarProcesado').prop("disabled", true)
                        }else {
                            $('#btnProcesar').prop("disabled", false)
                            $('#tipoPago').prop("disabled", false)
                            $('.btnEliminarProcesado').prop("disabled", false)
                        }

                        return result = txtMontoPago == precioShow ? true : false

                    }
    // Función para actualizar el total de abonos en dólares
    function actualizarTotalAbonos() {
        var totalAbonos = 0.00;
        var vueltosPago = 0.00;
        var tablaPagosBody = document.getElementById("tablaPagosBody").getElementsByTagName("tr");
        var payDataBase = document.getElementById("payDataBase").getElementsByTagName("tr");

        for (var i = 0; i < tablaPagosBody.length; i++) {
            var montoPagado = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[4].innerText) || 0;
            var vueltos = parseFloat(tablaPagosBody[i].getElementsByTagName("td")[5].innerText) || 0;
            var tipoPago = tablaPagosBody[i].getElementsByTagName("td")[0].innerText;
            var montoEnDolares = calcularMontoEnDolares(montoPagado, tipoPago);
            var vueltosEnDolares = calcularMontoEnDolares(vueltos, tipoPago);
            totalAbonos += montoEnDolares - vueltosEnDolares;
            vueltosPago += parseFloat(vueltosEnDolares);


            console.log("vueltos ", vueltos)
            console.log("vueltosPago ", vueltosPago)
        }
        for (var i = 0; i < payDataBase.length; i++) {
            var montoPagado = parseFloat(payDataBase[i].getElementsByTagName("td")[4].innerText) || 0;
            var vueltos = parseFloat(payDataBase[i].getElementsByTagName("td")[5].innerText) || 0;
            var tipoPago = payDataBase[i].getElementsByTagName("td")[0].innerText;
            var montoEnDolares = calcularMontoEnDolares(montoPagado, tipoPago);
            var vueltosEnDolares = calcularMontoEnDolares(vueltos, tipoPago);
            totalAbonos += montoEnDolares - vueltosEnDolares;
            vueltosPago += parseFloat(vueltosEnDolares);
            console.log("vueltos ", vueltos)
            console.log("vueltosPago ", vueltosPago)
        }
        document.getElementById("totalAbonosDolar").innerText = totalAbonos.toFixed(2);
        document.getElementById("txtMontoPago").value = totalAbonos.toFixed(2);
        document.getElementById("txtVueltoPago").value = vueltosPago.toFixed(2);
        activeBtnProcessService();
    }




    // Función para calcular el monto en dólares
    function calcularMontoEnDolares(monto, tipoPago) {
        switch (tipoPago) {
            case "Punto":
            case "Transferencia":
            case "Bolivar":
                return (parseFloat(monto) / tasaBolivar).toFixed(2);
            case "Peso":
                return (parseFloat(monto) / tasaPeso).toFixed(2);
            case "Dolar":
                return parseFloat(monto).toFixed(2);
            default:
                return 0.00;
        }
    }
    // Función para mostrar los campos de pago correspondientes según el tipo de pago seleccionado
    function mostrarCamposPago() {
        var tipoPago = document.getElementById("tipoPago").value;
        var camposPago = document.getElementById("camposPago");
        // Reiniciar campos de pago
        camposPago.innerHTML = "";
        if (tipoPago === "Transferencia") {
            camposPago.innerHTML = `
                <div class="form-group col-md-8">
                    <label class="text-black" for="nombreBanco">Nombre de Banco:</label>
                    <input type="text" class="form-control" id="nombreBanco">
                </div>
                <div class="form-group col-md-4">
                    <label class="text-black" for="fechaPago">Fecha:</label>
                    <input type="date" class="form-control" id="fechaPago">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="referencia">Referencia:</label>
                    <input type="text" class="form-control" id="referencia">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="montoPagado">Monto Pagado:</label>
                    <input type="number" class="form-control" id="montoPagado">
                </div>
                <div class="form-group col-md-2">
                    <label class="text-transparent" for="nombreBanco">N:</label>
                    <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
                </div>
            `;
        } else if (tipoPago === "Punto") {
            camposPago.innerHTML = `
                <div class="form-group col-md-5">
                    <label class="text-black" for="referencia">Referencia:</label>
                    <input type="text" class="form-control" id="referencia">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="montoPagado">Monto Pagado:</label>
                    <input type="number" class="form-control" id="montoPagado">
                </div>
                <div class="form-group col-md-2">
                    <label class="text-transparent" for="nombreBanco">N:</label>
                    <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
                </div>
            `;
        } else if (tipoPago === "Dolar") {
            camposPago.innerHTML = `
                <div class="form-group col-md-5">
                    <label class="text-black" for="montoDolar">Monto Pagado en Dólar:</label>
                    <input type="number" class="form-control" id="montoDolar">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="vueltos">Vueltos:</label>
                    <input type="number" class="form-control" id="vueltos">
                </div>
                <div class="form-group col-md-2">
                    <label class="text-transparent" for="nombreBanco">N:</label>
                    <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
                </div>
            `;
        } else if (tipoPago === "Peso") {
            camposPago.innerHTML = `
                <div class="form-group col-md-5">
                    <label class="text-black" for="montoPesos">Monto Pagado en Pesos:</label>
                    <input type="number" class="form-control" id="montoPesos">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="vueltos">Vueltos:</label>
                    <input type="number" class="form-control" id="vueltos">
                </div>
                <div class="form-group col-md-2">
                    <label class="text-transparent" for="nombreBanco">N:</label>
                    <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
                </div>
            `;
        } else if (tipoPago === "Bolivar") {
            camposPago.innerHTML = `
                <div class="form-group col-md-5">
                    <label class="text-black" for="montoBolivar">Monto Pagado en Bolívar:</label>
                    <input type="number" class="form-control" id="montoBolivar">
                </div>
                <div class="form-group col-md-5">
                    <label class="text-black" for="vueltos">Vueltos:</label>
                    <input type="number" class="form-control" id="vueltos">
                </div>
                <div class="form-group col-md-2">
                    <label class="text-transparent" for="nombreBanco">N:</label>
                    <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
                </div>
            `;
        }
        // Mostrar campos de pago
        camposPago.style.display = "block";
    }
    // Función para eliminar un pago de la tabla
    function eliminarPago(btn, idRegistro, idCaja) {
        var idRegistro = btn.getAttribute("data-idregistro");
        var idCaja = btn.getAttribute("data-idcaja");

        console.log(idRegistro)
        console.log(idCaja)
        console.log({{ $caja_id }})

        // Verificar si el registro proviene de la base de datos
        if (idRegistro) {
            // Verificar si el registro pertenece a la caja actual
            if (idCaja == {{ $caja_id }}) {
            // Eliminar el registro de la base de datos
            eliminarDetallePago(idRegistro);
            }
        }

        // Eliminar la fila de la tabla
        var filaPago = btn.closest("tr");
        filaPago.remove();

        // Actualizar total de abonos en dólares
        actualizarTotalAbonos();
    }

    function eliminarDetallePago(idRegistro) {
        // Realizar la solicitud AJAX para eliminar el registro
        $.ajax({
            url: "{{ route('eliminar.pago') }}",
            type: 'Get', // O el método HTTP adecuado para tu caso
            data: { id: idRegistro }, // Pasar el ID del registro a eliminar como datos en la solicitud
            success: function(response) {
            // Manejar la respuesta exitosa de la solicitud
            console.log('Registro eliminado correctamente');
            },
            error: function(xhr, status, error) {
            // Manejar errores en caso de que la solicitud falle
            console.error('Error al eliminar el registro:', error);
            }
        });
        }
</script>
