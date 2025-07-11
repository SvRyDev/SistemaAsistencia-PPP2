<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!-- Total Estudiantes -->
      <div class="col-md-6 col-lg-3 col-6">
        <div class="small-box bg-primary">
          <div class="inner">
            <h4 class="mb-0 mt-2">Estudiantes</h4>
            <h1 class="font-weight-bold mb-0" id="total-estudiantes">0</h1>
            <small style="font-size: 90%;">Registrados</small>
          </div>
          <div class="icon">
            <i class="fas fa-users fa-3x"></i>
          </div>
          <div class="small-box-footer text-white-50 text-end pe-3">

          </div>
        </div>
      </div>

      <!-- Asistencias del Día -->
      <div class="col-md-6 col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h4 class="mb-0 mt-2">Asistencias</h4>
            <h1 class="font-weight-bold mb-0">
              <span id="asistencias-dia">0</span>
              <sup style="font-size: 20px">(<span id="asistencias-porcentaje">0</span>%)</sup>
            </h1>
            <small style="font-size: 90%;" id="asistencias-fecha">-</small>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle fa-3x"></i>
          </div>
          <div class="small-box-footer text-white-50 text-end pe-3">

          </div>
        </div>
      </div>

      <!-- Tardanzas del Día -->
      <div class="col-md-6 col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h4 class="mb-0 mt-2">Tardanzas</h4>
            <h1 class="font-weight-bold mb-0">
              <span id="tardanzas-dia">0</span>
              <sup style="font-size: 20px">(<span id="tardanzas-porcentaje">0</span>%)</sup>
            </h1>
            <small style="font-size: 90%;" id="tardanzas-fecha">-</small>
          </div>
          <div class="icon">
            <i class="fas fa-clock fa-3x"></i>
          </div>
          <div class="small-box-footer text-white-50 text-end pe-3">

          </div>
        </div>
      </div>

      <!-- Justificaciones del Día -->
      <div class="col-md-6 col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h4 class="mb-0 mt-2">Justificaciones</h4>
            <h1 class="font-weight-bold mb-0">
              <span id="justificados-count">0</span>
              <sup style="font-size: 20px">(<span id="justificados-percent">0</span>%)</sup>
            </h1>
            <small style="font-size: 90%;" id="justificaciones-fecha">-</small>
          </div>
          <div class="icon">
            <i class="fas fa-calendar-check fa-3x"></i>
          </div>
          <div class="small-box-footer text-white-50 text-end pe-3">

          </div>
        </div>
      </div>




      <!-- /.row -->
      <!-- Main row -->
      <div class="col-12">
        <div class="row">


          <!-- Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-sm-12 col-md-5 col-lg-4 order-1 order-md-1 order-lg-1">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title mr-3">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Lista de asistencia
                </h3>
                <span id="fechaDistribucion" class="badge badge-primary ml-auto"></span>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <canvas id="pieAsistenciaDistribucion"
                      style="min-height: 300px; height: 100%; width: 100%;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="col-sm-12 col-md-12 col-lg-8 order-2 order-md-3 order-lg-2">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title mr-3">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Registro de Asistencias
                </h3>
                <span id="fechaRegistro" class="badge badge-primary ml-auto"></span>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <!-- Cabecera y lista en scroll horizontal -->
                    <div class="table-responsive" style="overflow-x: auto;">
                      <div style="">
                        <!-- Cabecera fija -->
                        <div class="list-group-item pt-2 pb-2 bg-dark text-left">
                          <div class="container-fluid">
                            <div class="row text-truncate">
                              <!-- En xs no se muestra, solo desde sm -->
                              <div class="col-1 col-sm-1 font-weight-bold text-white d-none d-sm-block small">#</div>
                              <div class="col-2 col-sm-2 text-white d-none d-sm-block small">Código</div>

                              <!-- Nombres se adapta: 12 columnas en XS, 6 en SM+ -->
                              <div class="col-8 col-sm-5 text-white small">Nombres</div>

                              <div class="col-1 col-sm-1 text-white d-none d-sm-block small">Grado</div>

                              <div class="col-1 col-sm-1 text-white d-none d-sm-block small">Hora</div>

                              <!-- Estado siempre visible -->
                              <div class="col-3 col-sm-2 text-white text-right small">Estado</div>
                            </div>
                          </div>
                        </div>

                        <!-- Lista dinámica -->
                        <div id="listaAsistencia" class="list-group" style="max-height: 260px; overflow-y: auto;">
                          <!-- Items generados por JS -->
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>


            </div>
          </section>

          <section class="col-sm-12 col-md-7 col-lg-7 order-3 order-md-2 order-lg-3">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title mr-2">
                  <i class="fas fa-user-check mr-1"></i>
                  Asistencia General
                </h3>
                <span class="badge badge-info ml-auto">Últimos 15 dias</span>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="row">
                  <!-- Gráfico de Asistencia General -->
                  <div class="col-12">
                    <canvas id="lineAsistenciaGeneral" style="min-height: 300px; height: 100%; width: 100%;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div><!-- /.card -->

          </section>

          <!-- /.Left col -->
          <section class="col-sm-12 col-md-12 col-lg-5 order-4 order-md-4">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title m-0 mr-3">
                  <i class="fas fa-file-alt mr-1"></i>
                  Justificaciones Diarias
                </h3>
                <span class="badge badge-info ml-auto">Últimos 15 dias</span>
              </div>

              <div class="card-body">
                <div class="chart">
                  <canvas id="barJustificacionesDiarias"
                    style="min-height: 300px; height: 300px; max-height: 400px; width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </section>
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-sm-12 col-md-12 col-lg-12 order-5 order-md-5">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title m-0 mr-3">
                  <i class="fas fa-chart-bar mr-1"></i>
                  Asistencia Mensual
                </h3>
                <span id="fechaDistribucionMensual" class="badge badge-primary ml-auto"></span>
              </div>

              <div class="card-body">
                <div class="chart">
                  <canvas id="barAsistenciaMensual"
                    style="min-height: 300px; height: 300px; max-height: 400px; width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </section>

        </div>



        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->