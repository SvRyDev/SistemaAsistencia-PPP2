<section class="content">
  <div class="container-fluid">
    <div class="row">

      <!-- Columna izquierda: Información general -->
      <div class="col-md-12">
        <div class="card bg-light border-0 shadow-sm mb-4">
          <div class="card-header bg-primary">
          <strong class="text-white"><i class="fas fa-exclamation-circle mr-2"></i>Nota importante:</strong>
          </div>
          <div class="card-body py-3">
            
            <ul class=" mt-2 mb-0 text-dark pl-3">
              <li>Haz clic en <strong>Descargar Backup</strong> para generar una copia de seguridad.</li>
              <li>Selecciona un archivo válido (.sql, .zip, .gz) para restaurar la base de datos.</li>
              <li>No recargues ni cierres la página durante el proceso.</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Columna derecha: Exportar + Importar -->
      <div class="col-md-12 col-xl-6">

        <!-- Card Exportar -->
        <div class="card shadow mb-4">
          <div class="card-header bg-success text-white">
            <span class="mb-0"><i class="fas fa-upload mr-2"></i>Exportar Backup</span>
          </div>
          <div class="card-body">
            <div class="alert alert-light">
              <i class="fas fa-calendar-alt mr-1"></i>Última exportación:
              <strong><?= $fecha_exportacion ?? 'Sin registro' ?></strong>
            </div>
            <p>Haz clic en el botón para generar y descargar una copia actual de la base de datos.</p>
            <form method="POST" action="/backup/exportar">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-download mr-1"></i> Descargar Backup (.sql)
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-xl-6">
        <!-- Card Importar -->
        <div class="card shadow">
          <div class="card-header bg-warning text-dark">
            <span class="mb-0"><i class="fas fa-download mr-2"></i>Importar Backup</span>
          </div>
          <div class="card-body">
            <div class="alert alert-light">
              <i class="fas fa-calendar-check mr-1"></i>Última importación:
              <strong><?= $fecha_importacion ?? 'Sin registro' ?></strong>
            </div>
            <p>Sube un archivo de respaldo válido para restaurar la base de datos.</p>
            <ul>
              <li>Formatos permitidos: <strong>.sql</strong>, <strong>.zip</strong>, <strong>.gz</strong></li>
              <li>Generado con herramientas como <code>mysqldump</code>.</li>
            </ul>
            <form method="POST" action="/backup/importar" enctype="multipart/form-data">
              <div class="form-group">
                <label for="archivo_backup"><i class="fas fa-file-upload mr-1"></i>Seleccionar archivo:</label>
                <input type="file" name="archivo_backup" id="archivo_backup" class="form-control-file"
                  accept=".sql,.zip,.gz" required>
              </div>
              <button type="submit" class="btn btn-warning text-dark">
                <i class="fas fa-sync-alt mr-1"></i> Restaurar desde Archivo
              </button>
            </form>
          </div>
        </div>

      </div> <!-- fin col-md-8 -->

    </div> <!-- fin row -->
  </div>
</section>