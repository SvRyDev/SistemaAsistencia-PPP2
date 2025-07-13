<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Asistencia - Dashboard</title>

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" />
  <link rel="stylesheet" href="<?= plugins() ?>/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?= plugins() ?>/toastr/toastr.min.css">


  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= plugins() ?>/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= plugins() ?>/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= plugins() ?>/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= plugins() ?>/datatables-buttons/css/buttons.bootstrap4.min.css">


  <link rel="stylesheet" href="<?= plugins() ?>/datatables-select/css/select.bootstrap4.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= plugins() ?>/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= plugins() ?>/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= plugins() ?>/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= dist() ?>/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= plugins() ?>/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= plugins() ?>/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?= plugins() ?>/summernote/summernote-bs4.min.css">

  <!-- icono -->
  <link rel="icon" href="<?= assets() ?>/img/static/icon.png" type="image/x-icon">

</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed  layout-fixed">

  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Preloader -->
    <!-- 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
-->
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a class="nav-link" id="reloj-dinamico">San Luis - Lunes, 17 de Junio de 2025 - 14:32:15</a>
        </li>

      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto align-items-center">

        <li class="nav-item d-none d-md-block">
          <span class="badge badge-pill <?= $_SESSION['rol_color_badge'] ?> px-3 py-1" style="font-size: .9rem;">
          <i class="fas fa-user-shield mr-1"></i> <?= $_SESSION['rol_nombre'] ?>
          </span>
        </li>

        <!-- Botón pantalla completa -->
        <li class="nav-item ml-3">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>


        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?= $_SESSION['rol_nombre'] ?></span>

          <div class="dropdown-divider"></div>

          <a href="#" class="dropdown-item">
            <i class="fas fa-user-cog mr-2 text-primary"></i> Configurar cuenta
            <span class="float-right text-muted text-sm">ajustes</span>
          </a>

          <div class="dropdown-divider"></div>

          <button id="logoutBtn" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Cerrar Sesión
            <span class="float-right text-muted text-sm">logout</span>
          </button>
        </div>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url() ?>/" class="brand-link">
        <img src="<?= assets() ?>/img/static/logo_.png" alt="AdminLTE Logo" class="brand-image elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">SIST. DE ASISTENCIA</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image d-flex align-items-center justify-content-center">
            <i class="fas fa-user-circle fa-2x text-white"></i>
          </div>
          <div class="info">
            <a href="#" class="d-block"><?= $_SESSION['user_nombre'] ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


            <?php if (hasGroupPermission('INICIO')): ?>
              <li class="nav-header">INICIO</li>
              <?php if (hasPermission('ver_dashboard')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/home" class="nav-link">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                  </a>
                </li>
              <?php endif; ?>
            <?php endif; ?>



            <!-- ESTUDIANTES -->
            <?php if (hasGroupPermission('ESTUDIANTES')): ?>
              <li class="nav-header">ESTUDIANTES</li>

              <?php if (hasPermission('ver_estudiantes')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/student" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Listado de Estudiantes</p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('gestionar_estudiantes')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/student/manage" class="nav-link">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <p>Gestión Estudiantes</p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('importar_estudiantes')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/student/import" class="nav-link">
                    <i class="nav-icon fas fa-file-import"></i>
                    <p>Importar Estudiantes</p>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>

            <?php if (hasGroupPermission('CARNETS')): ?>
              <li class="nav-header">CARNETS</li>

              <?php if (hasPermission('generar_carnet')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/carnet/individual" class="nav-link">
                    <i class="nav-icon fas fa-id-card"></i>
                    <p>
                      Generar Carnet
                    </p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('generar_carnet_lote')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/carnet/grupal" class="nav-link">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>
                      Generación por Lote
                    </p>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>


            <?php if (hasGroupPermission('ASISTENCIA')): ?>
              <li class="nav-header">ASISTENCIAS</li>

              <?php if (hasPermission('registrar_asistencia')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/attendance" class="nav-link">
                    <i class="nav-icon far fa-image"></i>
                    <p>
                      Registro
                    </p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('consultar_asistencia')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/attendance/query" class="nav-link">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>
                      Consultas
                    </p>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>


            <?php if (hasGroupPermission('REPORTES')): ?>
              <li class="nav-header">REPORTES</li>
              <?php if (hasPermission(' reporte_resumen_diario')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/report/resume/daily" class="nav-link">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>
                      Resumen Diario
                    </p>
                  </a>
                </li>
              <?php endif; ?>
              <?php if (hasPermission('reporte_individual')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/report/student" class="nav-link">
                    <i class="nav-icon fas fa-id-badge"></i>
                    <p>
                      Estudiante
                    </p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('reporte_por_aula')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/report/group" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Grado y Seccion
                    </p>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>


            <?php if (hasGroupPermission('CONFIGURACION')): ?>
              <li class="nav-header">CONFIGURACIÓN</li>

              <?php if (hasPermission('config_parametros')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/setting" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                      Parámetros del sistema
                    </p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('config_usuarios_roles')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/user/manage" class="nav-link">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>Usuarios y Roles</p>
                  </a>
                </li>
              <?php endif; ?>

              <?php if (hasPermission('copia_seguridad')): ?>
                <li class="nav-item">
                  <a href="<?= base_url() ?>/backup" class="nav-link">
                    <i class="nav-icon fas fa-database"></i>
                    <p>Copia de Seguridad</p>
                  </a>
                </li>
              <?php endif; ?>

            <?php endif; ?>


          </ul>
          <br>
          <br>

        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $data['title'] ?></h1>
              <!--      <span class="text-muted">Seleccione los filtros y genere carnets para los estudiantes</span> -->
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['title'] ?></li>

              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->