<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Backup</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">💾 Gestión de Backup</h4>
    </div>
    <div class="card-body">

      <!-- Fechas de acciones recientes -->
      <div class="alert alert-info">
        <p class="mb-1">📤 Última exportación: <strong><?= $fecha_exportacion ?? 'Sin registro' ?></strong></p>
        <p class="mb-0">📥 Última importación: <strong><?= $fecha_importacion ?? 'Sin registro' ?></strong></p>
      </div>

      <!-- Exportar backup -->
      <h5>📤 Exportar Backup</h5>
      <p>Haz clic para generar y descargar una copia actual de la base de datos.</p>
      <form method="POST" action="/backup/exportar">
        <button type="submit" class="btn btn-success mb-4">⬇️ Descargar Backup (.sql)</button>
      </form>

      <hr>

      <!-- Importar backup -->
      <h5>📥 Importar Backup</h5>
      <p>Sube un archivo de respaldo válido para restaurar la base de datos.</p>
      <ul>
        <li>Formatos aceptados: <strong>.sql</strong>, <strong>.zip</strong>, <strong>.gz</strong></li>
        <li>Debe ser un respaldo generado por herramientas como <code>mysqldump</code>.</li>
      </ul>
      <form method="POST" action="/backup/importar" enctype="multipart/form-data">
        <div class="form-group">
          <label for="archivo_backup">Seleccionar archivo:</label>
          <input type="file" name="archivo_backup" id="archivo_backup" class="form-control-file" accept=".sql,.zip,.gz" required>
        </div>
        <button type="submit" class="btn btn-warning">♻️ Restaurar desde Archivo</button>
      </form>

    </div>
  </div>
</div>

</body>
</html>
