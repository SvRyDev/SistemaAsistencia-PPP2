<!-- Modal de Registro/Edición -->
<div class="modal fade" id="modalEstudiante" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="formEstudiante" class="w-100">
      <div class="modal-content shadow rounded-lg">

        <!-- Encabezado -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title mb-0" id="modalEstudianteTitle">
            <i class="fas fa-user-graduate mr-2"></i> Registro de Estudiante
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- Cuerpo del modal -->
        <div class="modal-body py-4">
          <!-- ID oculto -->
          <input type="hidden" name="estudiante_id" id="estudiante_id">

          <!-- Código -->
          <div class="form-group">
            <label for="codigo_est">Código</label>
            <input name="codigo_est" id="codigo_est" class="form-control" type="text" readonly>
          </div>

          <!-- DNI y Nombres -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="dni_est">DNI</label>
              <input name="dni_est" id="dni_est" class="form-control" type="text" maxlength="8" data-validate="numeric"  placeholder="Ej. 12345678"
                autocomplete="off" required>
            </div>

            <div class="form-group col-md-6">
              <label for="nombre_est">Nombres</label>
              <input name="nombre_est" id="nombre_est" class="form-control" type="text" data-validate="alpha" placeholder="Ej. JUAN CARLOS" required>
            </div>
          </div>

          <!-- Apellidos -->
          <div class="form-group">
            <label for="apellidos_est">Apellidos</label>
            <input name="apellidos_est" id="apellidos_est" class="form-control" type="text" placeholder="Ej. PÉREZ GÓMEZ" data-validate="alpha" required>
          </div>

          <!-- Grado y Sección -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="grado_est">Grado</label>
              <select name="grado_est" id="grado_est" class="custom-select" required>
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="seccion_est">Sección</label>
              <select name="seccion_est" id="seccion_est" class="custom-select" required>
                <option value="">Seleccione</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="submit" id="btnGuardar" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Guardar
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
        </div>

      </div>
    </form>
  </div>
</div>