<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Escaneo de Asistencia</title>
  <link rel="stylesheet" href="<?= dist() ?>/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body,
    html {
      height: 100%;
      margin: 0;
      background: linear-gradient(135deg, rgb(255, 255, 255), rgb(28, 34, 44));
      color: #fff;
    }

    .body-content {
      max-width: 600px;
      margin: auto;
      padding: 40px 20px;
      text-align: center;
    }

    h2 {
      font-size: 32px;
      margin-bottom: 30px;
      color: rgb(0, 55, 158);
    }

    #focusWarning {
      display: flex;
      align-items: center;
      justify-content: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.27);
      color: white;

      z-index: 9999;
      font-size: 20px;
    }

    input#codigoEstudiante {
      width: 100%;
      font-size: 24px;
      padding: 14px;
      margin-bottom: 20px;
      border: none;
      border-radius: 8px;
      outline: none;
      text-align: center;
      background-color: #f8f9fa;
      color: #212529;
    }

    button[type="submit"] {
      background-color: #28a745;
      color: white;
      padding: 12px 25px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button[type="submit"]:hover {
      background-color: #218838;
    }

    span#mensajeRespuesta,
    span#nombre,
    span#apellido,
    span#codigo {
      display: block;
      font-size: 18px;
      margin-top: 10px;
      color: #fff;
    }


    #resultadoBusqueda {
      margin-top: 20px;
      padding: 20px;

      border-radius: 8px;

    }
  </style>
</head>

<body>
  <div class="body-content">
    <div id="focusWarning">⚠️ Esta ventana perdió el foco. Haz clic aquí</div>

    <h2 class="animate__animated animate__infinite animate__headShake animate__slow">📷 Escaneo de Asistencia</h2>

    <form id="formulario">
      <input type="text" id="codigoEstudiante" placeholder="Escanea tu código aquí" required autofocus maxlength="11">
      <br />
      <button type="submit">Registrar</button>
    </form>

    <div id="resultadoBusqueda">


      <span id="mensajeRespuesta"></span>
      <span id="nombre"></span><span id="apellido"></span>
      <span id="codigo"></span>
    </div>




    <div class="mb-3">
  <span id="horaEntrada" class="badge badge-info p-2"></span>
  <span id="horaSalida" class="badge badge-info p-2 ml-2"></span>
  <span id="tolerancia" class="badge badge-warning p-2 ml-2"></span>
</div>

  </div>

  <!-- jQuery -->
  <script src="<?= plugins() ?>/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?= plugins() ?>/jquery-ui/jquery-ui.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= plugins() ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    const base_url = "<?= base_url() ?>";
  </script>

  <script src="<?= assets() ?>/js/views/<?= $data['view_js'] ?>.js"></script>
</body>

</html>