<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- FILTRO MES -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white font-weight-bold">
                        <i class="fas fa-filter mr-2"></i> Filtro de Búsqueda
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light small d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle mr-2 text-info"></i>
                            Selecciona el mes y el dia que deseas consultar para ver el resumen de asistencia por grado y sección.
                        </div>


                        <div class="form-row align-items-end">


                            <div class="form-group col-md-3">
                                <label for="anio" class="font-weight-bold">
                                    <i class="fas fa-graduation-cap mr-2"></i> Año Académico:
                                </label>
                                <input id="year-academic" type="text" class="form-control" value="----" disabled>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="select-mes" class="font-weight-bold">
                                    <i class="fas fa-calendar mr-2"></i> Mes:
                                </label>
                                <select class="form-control" id="select-mes">
                                    <option value="">-- Seleccionar --</option>
                                    <!-- Otros meses -->
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="select-dia" class="font-weight-bold">
                                    <i class="fas fa-calendar-day mr-2"></i> Día:
                                </label>
                                <select class="form-control" id="select-dia">
                                    <option value="">-- Seleccionar --</option>
                                    <!-- Otros días -->
                                </select>
                            </div>




                            <div class="form-group col-md-2">
                                <button id="btnSearchRecord" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- RESUMEN GENERAL -->
                <div class="row text-center mb-4">
                    <!-- Total Estudiantes -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-dark p-3 rounded shadow-sm h-100">
                            <h6 class="text-muted mb-1">Total Estudiantes</h6>
                            <div id="total-estudiantes" class="h2 font-weight-bold text-dark">0</div>
                        </div>
                    </div>

                    <!-- Presentes  -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-success p-3 rounded shadow-sm h-100">
                            <h6 class="text-success mb-1">Asistencias</h6>
                            <div id="total-asistencias" class="h2 font-weight-bold text-success">0</div>
                        </div>
                    </div>

                    <!-- Tardanzas -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-warning p-3 rounded shadow-sm h-100">
                            <h6 class="text-warning mb-1">Tardanzas</h6>
                            <div id="total-tardanzas" class="h2 font-weight-bold text-warning">0</div>
                        </div>
                    </div>

                    <!-- Ausentes -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-danger p-3 rounded shadow-sm h-100">
                            <h6 class="text-danger mb-1">Ausentes</h6>
                            <div id="total-ausentes" class="h2 font-weight-bold text-danger">0</div>
                        </div>
                    </div>

                    <!-- Justificados -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-info p-3 rounded shadow-sm h-100">
                            <h6 class="text-info mb-1">Justificados</h6>
                            <div id="total-justificados" class="h2 font-weight-bold text-info">0</div>
                        </div>
                    </div>

                    <!-- % Asistencia -->
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-2 mb-3">
                        <div class="bg-light border-left border-primary p-3 rounded shadow-sm h-100">
                            <h6 class="text-primary mb-1">% Asistencia</h6>
                            <div id="total-porcentaje" class="h2 font-weight-bold text-primary">0%</div>
                        </div>
                    </div>
                </div>


                <!-- TABLA DETALLE -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white font-weight-bold">
                        <i class="fas fa-table mr-2"></i> Detalle por Grado y Sección
                    </div>
                    <div class="card-body bg-light">

                
                        <!-- Tabla dentro de scroll -->
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="table-result" class="table table-bordered table-hover table-sm text-center mb-0">
                                <thead class="thead-light">
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

                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                </div>

                <!-- BOTONES DE EXPORTACIÓN -->


            </div>
        </div>
    </div>
</section>