<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="row">

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-8 order-2 order-md-1">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white font-weight-bold">
                                <i class="fas fa-file-alt mr-2"></i> Formulario de Búsqueda
                            </div>

                            <div class="card-body">
                                <div class="alert alert-light small d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle mr-2 text-info"></i>
                                    En esta sección puedes consultar el historial de asistencia de un estudiante
                                    seleccionando su DNI y el mes deseado.
                                </div>

                                <div class="form-group">
                                    <label for="studentDNIInput" class="font-weight-bold">
                                        <i class="fas fa-id-card mr-2"></i> DNI del Estudiante:
                                    </label>
                                    <input type="text" class="form-control" id="studentDNIInput"
                                        placeholder="Ej. 12345678" value="12345678" maxlength="8">
                                </div>

                                <div class="form-group">
                                    <label for="select-mes" class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2"></i> Mes:
                                    </label>
                                    <select class="form-control" id="select-mes" name="select-mes">
                                        <option value="">-- Seleccionar --</option>
                                    </select>
                                </div>

                                <button class="btn btn-primary btn-block mt-3" id="searchStudentButton">
                                    <i class="fas fa-search mr-1"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 order-1 order-md-2">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white font-weight-bold">
                                <i class="fas fa-user-graduate mr-2"></i> Información del Estudiante
                            </div>
                            <div class="card-body">

                                <div id="student-info-loader" class="text-center py-4" style="display: none;">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="sr-only">Cargando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Cargando información del estudiante...</p>
                                </div>


                                <div id="info-student-contain">
                                    <div class="text-center mb-4">
                                        <i class="fas fa-user-circle fa-7x text-secondary"></i>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-user mr-2 text-primary"></i>
                                            <strong class="mr-2">Nombre:</strong>
                                            <span id="result-nombre-est" class="text-muted ml-auto">-</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-id-card mr-2 text-success"></i>
                                            <strong class="mr-2">Código:</strong>
                                            <span id="result-codigo-est" class="text-muted ml-auto">-</span>
                                        </li>

                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-graduation-cap mr-2 text-warning"></i>
                                            <strong class="mr-2">Grado:</strong>
                                            <span id="result-grado-est" class="text-muted ml-auto">-</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.col -->

            </div>

            <div class="col-md-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header font-weight-bold">
                                <i class="fas fa-eye mr-2"></i>Record de Asistencia del Estudiante
                            </div>


                            <!-- /.card-header -->
                            <div class="card-body bg-light">


                                <div class="card shadow border-0 rounded-lg">
                                    <div class="card-body">

                                        <!-- Tabla de resultados -->
                                        <div class="table-responsive mb-4">
                                            <table id="table-resultado" class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Mes</th>
                                                        <th>Día</th>
                                                        <th>Estado</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>

                                        <!-- Totales de asistencia -->
                                        <div class="row text-center">
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <div class="bg-light border-left border-success p-3 rounded shadow-sm">
                                                    <h6 class="text-success mb-1">Asistencias</h6>
                                                    <div
                                                        class="h2 mb-0 font-weight-bold text-success total-asistencias">
                                                        0</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <div class="bg-light border-left border-danger p-3 rounded shadow-sm">
                                                    <h6 class="text-danger mb-1">Inasistencias</h6>
                                                    <div
                                                        class="h2 mb-0 font-weight-bold text-danger total-inasistencias">
                                                        0</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <div class="bg-light border-left border-warning p-3 rounded shadow-sm">
                                                    <h6 class="text-warning mb-1">Justificados</h6>
                                                    <div
                                                        class="h2 mb-0 font-weight-bold text-warning total-justificados">
                                                        0</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <div class="bg-light border-left border-info p-3 rounded shadow-sm">
                                                    <h6 class="text-info mb-1">Tardanzas</h6>
                                                    <div class="h2 mb-0 font-weight-bold text-info total-tardanzas">0
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                            </div>


                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <!-- /.col -->

            </div>
        </div>
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>