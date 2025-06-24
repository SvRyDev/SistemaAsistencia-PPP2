<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-12">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header bg-primary text-white font-weight-bold d-flex align-items-center">
                                <i class="fas fa-users fa-lg mr-2"></i>
                                Consulta de Grupo de Estudiantes
                            </div>

                            <div class="card-body">
                                <div class="alert alert-info small" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Selecciona el <strong>grado</strong>, <strong>sección</strong> y
                                    <strong>mes</strong> para consultar el grupo.
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="select-grado" class="font-weight-bold text-primary">
                                            <i class="fas fa-layer-group mr-1"></i> Grado:
                                        </label>
                                        <select id="select-grado" class="form-control">
                                            <option value="">-- Seleccionar Grado --</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="select-seccion" class="font-weight-bold text-primary">
                                            <i class="fas fa-door-open mr-1"></i> Sección:
                                        </label>
                                        <select id="select-seccion" class="form-control">
                                            <option value="">-- Seleccionar Sección --</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="select-mes" class="font-weight-bold text-primary">
                                            <i class="fas fa-calendar-alt mr-1"></i> Mes:
                                        </label>
                                        <select id="select-mes" class="form-control">
                                            <option value="">-- Seleccionar Mes --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button class="btn btn-success btn-lg px-4" id="searchGroupButton">
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
                                <p>Reporte de Asistencia</p>
                                <div class="" style="overflow-x: auto;">
                                    <table id="table-resultado" class="table-bordered table-sm" style="">
                                        <thead class="text-center">
                                            <tr class="bg-success">
                                            <th>#</th>
                                            <th>Código</th>
        <th>Nombre</th>
        <th>Grado</th>
        <th>Sección</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <p class="mt-3">
                                    Total asistencias: <span class="text-success">2</span><br>
                                    Total inasistencias: <span class="text-danger">1</span><br>
                                    Total justificaciones: <span class="text-warning">0</span><br>
                                    Total tardanzas: <span class="text-info">0</span>
                                </p>
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