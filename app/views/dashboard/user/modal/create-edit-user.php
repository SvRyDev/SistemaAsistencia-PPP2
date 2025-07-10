<div class="modal fade" id="modalUserForm" tabindex="-1" role="dialog" aria-labelledby="modalUserFormLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formUser">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalUserFormLabel">
             <span id="userModalTitle">Nuevo Usuario</span>
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          
          <input type="hidden" name="user_id_user" id="user_id_user">

          <div class="form-group">
            <label for="nombre_user">Nombre de Usuario <small class="text-muted">(sin espacios)</small></label>
            <input type="text" class="form-control" name="nombre_user" id="nombre_user" data-validate="uppercase no-space" required>
          </div>


          <div class="form-group">
            <label for="role_id_user">Rol</label>
            <select class="form-control" name="role_id_user" id="role_id_user" required>
              <option value="">Seleccione un rol</option>
              <!-- Cargar dinámicamente -->
            </select>
          </div>

          <div class="form-group">
            <label for="password_user">Contraseña <small class="text-muted">(solo al crear o cambiar)</small></label>
            <input type="password" class="form-control" name="password_user" id="password_user">
          </div>

          <div class="form-group">
            <label for="estatus_user">Estado</label>
            <select class="form-control" name="estatus_user" id="estatus_user">
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
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
