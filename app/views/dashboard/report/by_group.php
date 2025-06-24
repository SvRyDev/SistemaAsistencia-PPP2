<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-12">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header bg-secondary text-white font-weight-bold d-flex align-items-center">
                                <i class="fas fa-users fa-lg mr-2"></i>
                                Consulta de Grupo de Estudiantes
                            </div>

                            <div class="card-body">
                                <div class="alert alert-light small " role="alert">
                                    <i class="fas fa-info-circle mr-2 text-info"></i>
                                    Selecciona el <strong>grado</strong>, <strong>sección</strong> y
                                    <strong>mes</strong> para consultar el grupo.
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="select-grado" class="font-weight-bold">
                                            <i class="fas fa-layer-group mr-1"></i> Grado:
                                        </label>
                                        <select id="select-grado" class="form-control">
                                            <option value="">-- Seleccionar Grado --</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="select-seccion" class="font-weight-bold">
                                            <i class="fas fa-door-open mr-1"></i> Sección:
                                        </label>
                                        <select id="select-seccion" class="form-control">
                                            <option value="">-- Seleccionar Sección --</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="select-mes" class="font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-1"></i> Mes:
                                        </label>
                                        <select id="select-mes" class="form-control">
                                            <option value="">-- Seleccionar Mes --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button class="btn btn-primary btn-block" id="searchGroupButton">
                                        <i class="fas fa-search mr-1"></i> Buscar Grupo
                                    </button>
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
                            <div class="card-header">
                                <i class="fas fa-eye mr-2"></i>Resutados de la búsqueda
                            </div>


                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- Botones y filtros quedan arriba -->
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <div id="table-buttons-wrapper"></div>
                                </div>

                                <!-- Tabla dentro de scroll -->
                                <div class="table-responsive shadow rounded" style="overflow-x: auto;">
                                    <table id="table-resultado" class="table table-bordered table-sm mb-0" style="min-width: 1000px;">
                                        <thead class="text-center thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Grado</th>
                                                <th>Sección</th>
                                                <!-- ... columnas dinámicas -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- Resumen debajo -->
                                <div class="mt-3">
                                    <p>
                                        Total asistencias: <span class="text-success">2</span><br>
                                        Total inasistencias: <span class="text-danger">1</span><br>
                                        Total justificaciones: <span class="text-warning">0</span><br>
                                        Total tardanzas: <span class="text-info">0</span>
                                    </p>
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