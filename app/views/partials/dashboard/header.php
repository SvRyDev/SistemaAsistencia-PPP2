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



</head>

<body class="hold-transition sidebar-mini layout-fixed">

  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Preloader -->
    <!-- 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
-->
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a class="nav-link">San Luis - Lunes, 17 de Junio de 2025 - 14:32:15</a>
        </li>

      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">

        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Administrador</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
            <span class="float-right text-muted text-sm">logout</span>
          </a>

        </div>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="<?= assets() ?>/img/static/logo_.png" alt="AdminLTE Logo" class="brand-image elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">SIST. DE ASISTENCIA</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= dist() ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Administrador</a>
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

            <li class="nav-header">ESTUDIANTES</li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/student" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Lista Estudiantes
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/student" class="nav-link">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>
                  Registrar Estudiante
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/student/import" class="nav-link">
                <i class="nav-icon fas fa-file-import"></i>
                <p>
                  Importar Estudiantes
                </p>
              </a>
            </li>

            <li class="nav-header">CARNET DE ASISTENCIA</li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/carnet" class="nav-link">
                <i class="nav-icon fas fa-id-card"></i>
                <p>
                  Generar Carnet
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/" class="nav-link">
                <i class="nav-icon fas fa-layer-group"></i>
                <p>
                  Generación por Lote
                </p>
              </a>
            </li>
            <li class="nav-header">ASISTENCIAS</li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/attendance" class="nav-link">
                <i class="nav-icon far fa-image"></i>
                <p>
                  Asistencia
                </p>
              </a>
            </li>
            <li class="nav-header">REPORTES</li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/report/student" class="nav-link">
                <i class="nav-icon fas fa-id-badge"></i>
                <p>
                  R. Estudiante
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/report/group" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  R. por Grado y Sección
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url() ?>/report" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  R. General
                </p>
              </a>
            </li>

            <li class="nav-header">CNOFIGURACIÓN</li>

            <li class="nav-item">
              <a href="<?= base_url() ?>/report/group" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                  Configuración
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="https://adminlte.io/docs/3.1/" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>Backup</p>
              </a>
            </li>

          </ul>
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