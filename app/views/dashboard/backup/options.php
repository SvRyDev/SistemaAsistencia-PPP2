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


            <ul>
              <li class="mb-2">
                Esta sección permite exportar (guardar) o importar (restaurar) el estado completo de la base de datos
                del sistema. Estas operaciones son <strong>sensibles al sistema</strong> y deben ser realizadas con
                <strong>responsabilidad y precaución</strong>.
              </li>

              <li class="mb-1">
                Restaurar un respaldo <u>sobrescribirá permanentemente</u> toda la información existente. Asegúrate de
                que el archivo sea confiable, reciente y generado desde una fuente autorizada.
              </li>
              <li class="mb-0">
                Realiza exportaciones periódicas, guarda los respaldos en lugares seguros (disco externo, nube), no
                edites manualmente los archivos `.sql`, y verifica que el contenido no contenga comandos peligrosos.
                Si no estás seguro de cómo proceder, consulta con soporte técnico.
              </li>
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

            <!-- Última exportación -->
            <div class="alert alert-light">
              <i class="fas fa-calendar-alt mr-1"></i>Última exportación:
              <strong id="fecha_exportacion">Sin registro</strong>
            </div>

            <!-- Descripción -->
            <p class="mb-2">
              Haz clic en el botón para generar y descargar una copia actual de la base de datos.

            </p>
            <ul class="small">
              <li>Almacena los archivos en lugares seguros (disco externo, nube, etc.).</li>
              <li>Realiza respaldos con frecuencia, especialmente antes de realizar cambios importantes.</li>
            </ul>


            <!-- Botón de exportación -->
            <button id="btnExportBackup" class="btn btn-success">
              <i class="fas fa-download mr-1"></i> Descargar Backup (.zip)
            </button>



          </div>
        </div>
      </div>


      <div class="col-md-12 col-xl-6">
        <!-- Card Importar -->
        <div class="card shadow">
          <div class="card-header bg-info text-dark">
            <span class="mb-0"><i class="fas fa-download mr-2"></i>Importar Backup</span>
          </div>
          <div class="card-body">

            <!-- Última importación -->
            <div class="alert alert-light">
              <i class="fas fa-calendar-check mr-1"></i>Última importación:
              <strong id="fecha_importacion">Sin registro</strong>
            </div>



            <!-- Instrucciones -->
            <p class="mb-1">Selecciona un archivo de respaldo válido para restaurar la base de datos:</p>
            <ul class="small mb-3">
              <li>Formatos permitidos: <strong>.sql</strong>, <strong>.zip</strong>.</li>
              <li>Tamaño máximo: <strong>5MB</strong></li>

            </ul>

            <!-- Formulario -->
            <form id="formImportBackup" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <input type="file" name="archivo_backup" class="form-control-file mb-2" required />
              </div>
              <button type="submit" id="btnImportBackup" class="btn btn-info">
                <i class="fas fa-sync-alt mr-1"></i> Restaurar desde Archivo
              </button>
            </form>

          </div>
        </div>
      </div>

      <div class="col-md-12 col-xl-12">
  <!-- Card Restaurar Sistema -->
  <div class="card shadow">
  <div class="card-header bg-danger text-white">
    <i class="fas fa-redo-alt mr-2"></i> Restaurar Sistema (Limpiar Datos)
  </div>
  <div class="card-body">
    <p>Esta acción eliminará de forma permanente los siguientes registros:</p>
    <ul class="small text-danger mb-2">
      <li>📛 Estudiantes</li>
      <li>🎫 Carnets emitidos</li>
      <li>📝 Registros de asistencia</li>
      <li>👤 Usuarios (excepto administrador)</li>
    </ul>

    <p class="small mb-2">
      Además, se <u>restablecerá la configuración del sistema</u> a sus valores predeterminados (nombre de la institución, año académico, fechas, horarios, etc.).
    </p>



    <div class="alert alert-warning small mb-0">
      <strong><i class="fas fa-exclamation-triangle"></i> Advertencia:</strong>
      Esta acción <u>no puede deshacerse</u>. Asegúrate de haber realizado un backup antes de continuar.
    </div>

    <button id="btnResetSystem" class="btn btn-danger mt-3">
      <i class="fas fa-trash-alt mr-1"></i> Restaurar Sistema
    </button>
  </div>
</div>

</div>



    </div> <!-- fin row -->
  </div>
</section>