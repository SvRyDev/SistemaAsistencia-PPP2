<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro de Asistencia - Público</title>
  <link rel="stylesheet" href="<?= dist() ?>/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= plugins() ?>/flipclock/css/flipclock.css">
  <link rel="stylesheet" href="<?= plugins() ?>/animate/css/animate.min.css" />
  <link rel="stylesheet" href="<?= plugins() ?>/fontawesome-free/css/all.min.css">

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      background: linear-gradient(135deg, #e0e7ff 0%, #1c222c 100%);
      background-image: url('<?= assets() ?>/img/static/fondo-ie-san-luis.jpg');
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      width: 100vw;
      overflow-x: hidden;
    }

    .container.py-5 {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100vw;
      max-width: 100vw;
      padding: 0 !important;
    }

    .row.justify-content-center {
      width: 100vw;
      max-width: 100vw;
      margin: 0;
      display: flex;
      align-items: stretch;
      min-height: 80vh;
    }

    .col-lg-6 {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-width: 0;
      min-height: 100%;
      padding: 0 2vw;
    }

    .public-container {
      max-width: 1000px;
      width: 100%;
      margin: 0 auto;
      text-align: center;
      position: relative;
      animation: fadeInDown 1s;
      min-height: 90%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      row-gap: 20px;
      flex: 1;
    }

    .public-container-child {
      background: rgba(0, 0, 0, 0.60);
      border-radius: 22px;
      box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.37);
      padding: 48px 32px 40px 32px;
      height: 100%;
      text-align: center;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-40px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .public-title {
      font-size: 2.9rem;
      font-weight: 800;
      color:rgb(255, 255, 255);
      margin-bottom: 22px;
      letter-spacing: 1.5px;
      text-shadow: 0 4px 16px #0004;
    }

    .public-subtitle {
      font-size: 1.5rem;
      color: #b5e0ff;
      margin-bottom: 32px;
      font-weight: 500;
    }

    .public-form input[type="text"] {
      width: 100%;
      font-size: 1.7rem;
      padding: 18px 14px;
      margin-bottom: 22px;
      border: none;
      border-radius: 10px;
      outline: none;
      text-align: center;
      background-color: #f8f9fa;
      color: #212529;
      box-shadow: 0 4px 16px #0002;
      transition: box-shadow 0.2s;
    }

    .public-form input[type="text"]:focus {
      box-shadow: 0 6px 24px #00b4d855;
    }

    .public-form button[type="submit"] {
  background: linear-gradient(90deg, #00b4d8 0%, #0077b6 100%);
  color: white;
  padding: 16px 0;
  width: 100%;
  font-size: 1.3rem;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  margin-bottom: 14px;
  transition: background 0.3s, box-shadow 0.2s;
  box-shadow: 0 4px 16px #0002;
}

.public-form button[type="submit"]:hover {
  background: linear-gradient(90deg, #0077b6 0%, #00b4d8 100%);
  box-shadow: 0 8px 32px #00b4d855;
}



    .public-status {
      margin-top: 18px;

      border-radius: 18px;

      min-height: 120px;
      box-shadow: 0 8px 32px #0003;
      font-size: 1.18rem;
      color: #222;
      transition: background 0.3s, color 0.3s;

      position: relative;
      overflow: hidden;
      font-weight: 500;
    }

    .card-header.success {
  background: linear-gradient(90deg, #00c853 0%, #00e676 100%);
  color: #ffffff;
  border-color: #00c853;
}

.card-header.error {
  background: linear-gradient(90deg, #d50000 0%,rgb(255, 81, 0) 100%);
  color: #fff5f5;
  border-color: #d50000;
}

.card-header.info {
  background: linear-gradient(90deg, #0288d1 0%, #00bcd4 100%);
  color: #e0f7fa;
  border-color: #0288d1;
}

    .public-status .estado-label {
      font-size: 2.0em;
      font-weight: 600;
      margin-bottom: 14px;
      display: inline-block;
      padding: 0px 32px;
      max-width: 250px;
      border-radius: 24px;
      letter-spacing: 1.2px;
      box-shadow: 0 4px 16px #0002;
      background: #fff2;
      color: black;
      border: 2px solid transparent;
      text-shadow: 0 2px 8px #0005;
      margin-top: 0px;
      margin-bottom: 0px;
      transition: background 0.3s, color 0.3s, border 0.3s;
    }

    .public-status .estado-label.temprano {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      border-color: #43e97b;
      text-shadow: 0 2px 8px #0007;
    }

    .public-status .estado-label.tarde {
      background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
      color: #222;
      border-color: #ffc107;
      text-shadow: 0 2px 8px #fff3;
    }

    .public-status .hora-label {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 10px;
      display: block;
      color: #0077b6;
    }

    .public-status .nombre-label,
    .public-status .apellido-label,
    .public-status .codigo-label {
      font-size: 1.4rem;
      font-weight: 500;
      margin-bottom: 4px;
      color: #222;
      letter-spacing: 0.5px;
    }

    .public-status .msg-label {
      margin-top: 14px;
      font-size: 1.13rem;
      color: #222;
      font-weight: 400;
    }

    .public-clock {
      margin: 0px auto 0 auto;
      width: 100%;
      background: rgba(0, 0, 0, 0.18);
      border-radius: 12px;
      box-shadow: 0 2px 8px #0001;
      padding: 10px 0 2px 0;
    }


    @media (max-width: 900px) {
      .public-container {
        max-width: 98vw;
        padding: 18px 2vw 18px 2vw;
      }

      .row.justify-content-center {
        flex-direction: column;
        min-height: 90%;
      }

      .col-lg-6 {
        padding: 0 1vw;
      }
    }

    @media (max-width: 600px) {
      .public-container {
        max-width: 100vw;
        padding: 10px 1vw 10px 1vw;
      }

      .public-clock {
        width: 100%;
      }

      .public-status {
        padding: 18px 4px 12px 4px;
        font-size: 1.01rem;
      }
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
      font-weight: 600;
      letter-spacing: 1px;
      display: none;
    }


    .card-container {
      max-width: 650px;
      min-height: 300px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e0e7ff 100%);
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      padding: 20px;
      color: #222;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-header {
      width: 100%;
      height: 105px;

      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 15px 15px 0 0;
    }

    .student-icon {
      font-size: 5rem;
      color: #fff;
      text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .card-body {
      flex: 1;
      width: 100%;
      background: #fff;
      border-radius: 0 0 15px 15px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .status-icon {
      color: rgb(255, 255, 255);

      font-size: 5rem !important;
    }

    .status-message {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 10px;
      text-align: center;
    }

    .status-details {
      font-size: 2rem;
      text-align: left;
      width: 100%;
      padding: 0 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .estado-label,
    .hora-label,
    .nombre-label,
    .apellido-label,
    .codigo-label {
      margin-bottom: 5px;
    }



    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }
  </style>
</head>

<body>


  <div class="container py-5">


    <!-- Contenido en dos columnas -->
    <div class="row justify-content-center">
      <!-- Columna izquierda: Formulario -->
      <div class="col-lg-6 p-2 d-flex">


        <!--
        <div id="focusWarning">⚠️ Esta ventana perdió el foco. Haz clic aquí</div>

 -->
        <div class="public-container animate__animated animate__fadeInDown">
          <div class="public-container-child">


            <!-- Logo centrado arriba -->
            <div class="text-center mb-4">
              <img src="<?= assets() ?>/img/static/logo_.png" alt="Logo Colegio" style="max-height: 150px;">
            </div>

            <div class="public-title animate__animated animate__pulse animate__infinite animate__slow">Registro de
              Asistencia
            </div>

            <div class="public-subtitle">Escanea tu código o ingresa tu ID para registrar tu asistencia</div>
            <form id="formulario" class="public-form mb-2">
              <input class="mb-4" type="text" id="codigoEstudiante" placeholder="Escanea tu código aquí" required
                autofocus maxlength="11" autocomplete='off' data-validate="uppercase">
              <button type="submit">Registrar Asistencia</button>
            </form>

  <!-- 
            <div class="mb-3 mt-3">
              <span id="horaEntrada" class="badge badge-info p-2"></span>
              <span id="horaSalida" class="badge badge-info p-2 ml-2"></span>
              <span id="tolerancia" class="badge badge-warning p-2 ml-2"></span>

            </div>
             -->
          </div>
        </div>

      </div>

      <!-- Columna derecha: Reloj y estado -->
      <div class="col-lg-6 d-flex flex-column align-items-center">
        <!-- Reloj -->


        <div class="public-container text-center animate__animated animate__fadeInRight" style="width: 100%;">

          <div class="public-clock mb-0 ">
            <div id="clock" class="clock d-flex justify-content-center align-items-center"></div>
          </div>

          <!-- Estado del estudiante -->
          <div class="public-container-child">
            <!-- filepath: c:\xampp\htdocs\SistemaAsistencia-PPP2\app\views\dashboard\attendance\register.php -->
            <div id="public-status" class="public-status "
              style="display: none;">
              <div class="card-container p-1">
                <!-- Parte superior: Ícono de estudiante -->
                <div class="card-header">
                  <i class="status-icon" style=""></i> <!-- Ícono de estado -->
                </div>

                <!-- Parte inferior: Detalles -->
                <div class="card-body">
                  <div class="status-message"></div>
                  <div class="status-details">
                    <div class="estado-label text-center"></div>
                    <div class="hora-label"></div>
                    <div class="nombre-label"></div>
                    <div class="apellido-label"></div>
                    <div class="codigo-label"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  <!-- jQuery -->
  <script src="<?= plugins() ?>/jquery/jquery.min.js"></script>
  <script src="<?= plugins() ?>/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?= plugins() ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    const base_url = "<?= base_url() ?>";
  </script>
  <script src="<?= assets() ?>/js/utils.js"></script>
  <script src="<?= plugins() ?>/flipclock/js/flipclock.js"></script>
  <script src="<?= assets() ?>/js/views/<?= $data['view_js'] ?>.js"></script>
</body>

</html>