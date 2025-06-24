<div class="container-fluid mt-4">

  <!-- 🔍 Filtros -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white">
      <strong>📅 Filtro de Asistencia</strong>
    </div>
    <div class="card-body">
      <form class="form-row align-items-end">
        <div class="form-group col-md-3">
          <label for="select-mes">Mes</label>
          <select class="form-control" id="select-mes">
            <option value="">Todos</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <!-- Resto de meses -->
          </select>
        </div>
        <div class="form-group col-md-2">
          <button type="button" class="btn btn-primary btn-block">
            <i class="fas fa-search mr-1"></i> Buscar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 📊 Resumen General -->
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card shadow border-0 bg-light">
        <div class="card-body py-3">
          <h6 class="text-muted">Total Estudiantes</h6>
          <h3 class="text-dark mb-0">480</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow border-0 bg-light">
        <div class="card-body py-3">
          <h6 class="text-success">Presentes</h6>
          <h3 class="text-success mb-0">420</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow border-0 bg-light">
        <div class="card-body py-3">
          <h6 class="text-danger">Ausentes</h6>
          <h3 class="text-danger mb-0">50</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow border-0 bg-light">
        <div class="card-body py-3">
          <h6 class="text-warning">Justificados</h6>
          <h3 class="text-warning mb-0">7</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- 📋 Tabla Detalle -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <span><strong>📌 Detalle por Grado y Sección</strong></span>
      <small class="text-light">Resumen agrupado por aula</small>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="table-resumen" class="table table-bordered table-hover table-sm text-center mb-0">
          <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Grado</th>
              <th>Sección</th>
              <th>Total</th>
              <th>Presentes</th>
              <th>Ausentes</th>
              <th>Justificados</th>
              <th>Tardanzas</th>
              <th>% Asistencia</th>
            </tr>
          </thead>
          <tbody>
            <!-- Datos dinámicos -->
            <tr>
              <td>1</td>
              <td>1°</td>
              <td>A</td>
              <td>30</td>
              <td>27</td>
              <td>2</td>
              <td>1</td>
              <td>0</td>
              <td>90%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 💾 Botones Exportación -->
  <div class="d-flex justify-content-end mb-5">
    <button class="btn btn-outline-success btn-sm mr-2">
      <i class="fas fa-file-excel"></i> Excel
    </button>
    <button class="btn btn-outline-danger btn-sm mr-2">
      <i class="fas fa-file-pdf"></i> PDF
    </button>
    <button class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-print"></i> Imprimir
    </button>
  </div>

</div>
