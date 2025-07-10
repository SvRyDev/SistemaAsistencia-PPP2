<div class="modal fade" id="modalRoleForm" tabindex="-1" role="dialog" aria-labelledby="modalRoleFormLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formRole">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title" id="modalRoleFormLabel">
            <span id="roleModalTitle">Nuevo Rol</span>
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="role_id" id="role_id">

          <div class="form-group">
            <label for="nombre_rol">Nombre del Rol</label>
            <input type="text" class="form-control" name="nombre_rol" id="nombre_rol" data-validate="uppercase" required>
          </div>

          <div class="form-group">
            <label for="descripcion_rol">Descripción</label>
            <textarea class="form-control" name="descripcion_rol" id="descripcion_rol" rows="2"></textarea>
          </div>

          <div class="form-group">
            <label for="color_rol">Color del Badge</label>
            <select class="form-control" id="color_rol" name="color_rol" required>
              <option value="">Seleccione un color</option>
              <option value="badge-primary" class="bg-primary text-white">Azul</option>
              <option value="badge-secondary" class="bg-secondary text-white">Gris</option>
              <option value="badge-success" class="bg-success text-white">Verde</option>
              <option value="badge-danger" class="bg-danger text-white">Rojo</option>
              <option value="badge-warning" class="bg-warning text-dark">Amarillo</option>
              <option value="badge-info" class="bg-info text-white">Celeste</option>
              <option value="badge-dark" class="bg-dark text-white">Negro</option>
            </select>
            <small class="form-text text-muted">Este color se usará como fondo del badge para el rol.</small>
          </div>


          <div class="form-group border rounded p-3">
            <label class="font-weight-bold mb-2">Permisos del Rol</label>
            <div id="permisosContainer" class="px-1">
              <!-- Aquí se cargan los checkboxes -->
              <div class="text-muted">Cargando permisos...</div>
            </div>

          </div>


        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>