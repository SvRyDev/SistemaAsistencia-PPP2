<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!-- Total Estudiantes -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
          <div class="inner">
            <h4 class="mb-0 mt-2">Estudiantes</h4>
            <h1 class="font-weight-bold mb-0">120</h1>
            <small style="font-size: 90%;">Registrados</small>
          </div>
          <div class="icon">
            <i class="fas fa-users fa-3x"></i>
          </div>
          <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Asistencias del Día -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h4 class="mb-0 mt-2">Asistencias</h4>
            <h1 class="font-weight-bold mb-0">96<sup style="font-size: 20px">%</sup></h1>
            <small style="font-size: 90%;">Hoy - 03 Julio</small>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle fa-3x"></i>
          </div>
          <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Tardanzas del Día -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h4 class="mb-0 mt-2">Tardanzas</h4>
            <h1 class="font-weight-bold mb-0">8</h1>
            <small style="font-size: 90%;">Hoy - 03 Julio</small>
          </div>
          <div class="icon">
            <i class="fas fa-clock fa-3x"></i>
          </div>
          <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Promedio Asistencia Mensual -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h4 class="mb-0 mt-2">Prom. Asistencia</h4>
            <h1 class="font-weight-bold mb-0">91<sup style="font-size: 20px">%</sup></h1>
            <small style="font-size: 90%;">Mes de Julio</small>
          </div>
          <div class="icon">
            <i class="fas fa-calendar-check fa-3x"></i>
          </div>
          <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>


    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
      <section class="col-lg-4 connectedSortable">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mr-3">
              <i class="fas fa-chart-pie mr-1"></i>
              LIsta de asistencia
            </h3>
            <span id="fechaDistribucion" class="badge badge-primary ml-auto"></span>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <canvas id="pieAsistenciaDistribucion" style="min-height: 300px; height: 100%; width: 100%;"></canvas>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="col-lg-8 connectedSortable">
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
              <div id="listaAsistencia" style="max-height: 300px; overflow-y: auto;"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="col-lg-7 connectedSortable">
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
      <section class="col-lg-5 connectedSortable">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title m-0 mr-3">
              <i class="fas fa-file-alt mr-1"></i>
              Justificaciones Diarias
            </h3>
            <span id="fechaJustificaciones" class="badge badge-primary ml-auto"></span>
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
      <section class="col-lg-12 connectedSortable">
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
 




      <!-- right col -->
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->