<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistema de Asistencia</title>
    <link rel="stylesheet" href="<?= plugins() ?>/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= dist() ?>/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= plugins() ?>/fontawesome-free/css/all.min.css">

    <!-- icono -->
    <link rel="icon" href="<?= assets() ?>/img/static/icon.png" type="image/x-icon">
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('<?= assets() ?>/img/static/fondo-ie-san-luis.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow-x: hidden;
            /* solo oculta el scroll horizontal */
        }

        /* Fondo difuminado */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.19);
            z-index: 1;
        }

        /* Wrapper que permite desplazamiento vertical */
        .login-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Hace que el login-card se mantenga centrado pero también permita crecer si se necesita */
        .login-card {
            background: rgba(255, 255, 255, 0.79);
            padding: 2.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);

            opacity: 0;
            transform: translateY(40px);
            animation: cardFadeIn .5s ease-out 0.3s forwards;
        }



        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .login-card img.logo {
            max-width: 90px;
            margin-bottom: 1.5rem;
        }

        .login-card h4 {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .login-card .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
        }

        .form-group i {
            position: absolute;
            top: 10px;
            left: 15px;
            color: #aaa;
        }

        .form-control {
            padding-left: 2.5rem;
            border-radius: 2rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .btn-primary {
            border-radius: 2rem;
            padding: 0.5rem;
            margin-top: 1rem;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #888;
        }

        .glass-diagonal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 430px;
            height: 560px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 0;
            border-radius: 1rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.25);

            opacity: 0;
            transform: translate(-50%, -60%) scale(0.95);
            animation: fadeSlideIn 1s ease-out forwards;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="overlay "></div>

    <div class="login-wrapper">
        <div class="glass-diagonal"></div>

        <div class="login-card shadow shadow-lg">
            <img src="<?= assets() ?>/img/static/logo_.png" alt="Logo Colegio" class="logo" />
            <h4>Sistema de Asistencia</h4>
            <p class="subtitle">Control y seguimiento diario de la asistencia estudiantil</p>

            <form id="loginForm">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" id="usuario" placeholder="Usuario" />
                </div>
                <div class="form-group mt-3">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="clave" placeholder="Contraseña" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </form>


            <div class="footer-text">
                © 2025 desarrollador por <strong>A. S. R.</strong>

            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="<?= plugins() ?>/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= plugins() ?>/jquery-ui/jquery-ui.min.js"></script>



    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="<?= plugins() ?>/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="<?= plugins() ?>/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="<?= plugins() ?>/toastr/toastr.min.js"></script>


    <script>
        const base_url = "<?= base_url() ?>";
    </script>

    <script src="<?= assets() ?>/js/utils.js"></script>

    <script src="<?= assets() ?>/js/views/<?= $data['view_js'] ?>.js"></script>
</body>

</html>