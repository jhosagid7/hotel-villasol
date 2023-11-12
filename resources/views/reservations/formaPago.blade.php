<div style="z-index: 1050;" class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="modalPagoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPagoLabel">Agregar Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formPago">
          <div class="form-group">
            <label for="metodoPago">Método de Pago:</label>
            <select class="form-control" id="metodoPago" onchange="mostrarCamposPago()">
              <option value="transferencia">Transferencia</option>
              <option value="punto">Punto</option>
              <option value="dolar">Dólar</option>
              <option value="pesos">Pesos</option>
              <option value="bolivar">Bolívar</option>
            </select>
          </div>
          <div class="form-group">
            <label for="nombreBanco">Nombre de Banco:</label>
            <input type="text" class="form-control" id="nombreBanco">
          </div>
          <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" class="form-control" id="referencia">
          </div>
          <div class="form-group">
            <label for="montoPagado">Monto Pagado:</label>
            <input type="text" class="form-control" id="montoPagado">
          </div>
          <div class="form-group" id="vueltosContainer" style="display: none;">
            <label for="vueltos">Vueltos:</label>
            <input type="text" class="form-control" id="vueltos">
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="agregarPago()">Agregar</button>
      </div>
    </div>
  </div>
</div>
