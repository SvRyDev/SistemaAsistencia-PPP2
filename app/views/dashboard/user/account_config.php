<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-10">

        <!-- Header de Bienvenida -->
        <div class="card mb-4 shadow-sm">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-user-circle fa-3x text-info mr-3"></i>
            <div>
              <h5 class="mb-0">Hola, <?= $_SESSION['user_nombre'] ?> 👋</h5>
              <small class="text-muted">Desde aquí puedes gestionar tu cuenta.</small>
            </div>
          </div>
        </div>

        <!-- Información del Usuario -->
        <div class="card border-left-primary shadow mb-4">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-id-badge"></i> Información de Usuario
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="user-username">Nombre de usuario</label>
              <input type="text" value="<?= $_SESSION['user_nombre'] ?>" class="form-control" id="user-username" disabled>
            </div>
            <div class="form-group">
              <label for="user-role">Rol asignado</label>
              <input type="text" value="<?= $_SESSION['rol_nombre'] ?>" class="form-control" id="user-role" disabled>
            </div>
          </div>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="card border-left-danger shadow mb-4">
          <div class="card-header bg-danger text-white">
            <i class="fas fa-lock"></i> Seguridad / Cambio de Contraseña
          </div>
          <div class="card-body">

            <div class="alert alert-light small text-muted mb-3">
              <i class="text-info fas fa-info-circle mr-1"></i>
              La nueva contraseña debe tener al menos 6 caracteres.
            </div>

            <div class="form-group">
              <label for="user-password-current">Contraseña actual</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" class="form-control" id="user-password-current" placeholder="">
              </div>
            </div>

            <div class="form-group">
              <label for="user-password-new">Nueva contraseña</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="user-password-new" placeholder="">
              </div>
              <small class="form-text text-muted">Debe tener al menos 6 caracteres.</small>
            </div>

            <div class="form-group mb-3">
              <label for="user-password-confirm">Confirmar nueva contraseña</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" id="user-password-confirm" placeholder="">
              </div>
            </div>

            <div class="text-right">
              <button id="btnUpdateConfigAccount" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar Cambios
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
