<?php include('modal/details-data.php'); ?>
<!-- /.modal -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- Filtros de búsqueda -->
            <div class="col-12">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0 card-title"><i class="fas fa-filter mr-2"></i> Filtros de búsqueda</h3>
                    </div>
                    <div class="card-body">

                        <!-- Mensaje informativo -->
                        <div class="alert alert-light small align-items-center" role="alert">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Seleccione una <strong>fecha</strong>, un <strong>grado</strong> y una
                            <strong>sección</strong> para consultar las asistencias.
                        </div>

                        <!-- Formulario de filtros -->
                        <form id="attendanceForm">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="fecha_grupo"><i class="fas fa-calendar-alt"></i> Fecha</label>
                                    <input type="date" class="form-control" id="fecha_grupo" name="fecha_grupo">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="grado_grupo"><i class="fas fa-graduation-cap"></i> Grado</label>
                                    <select class="form-control" id="grado_grupo" name="grado_grupo">
                                        <option value="">Seleccione grado</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="seccion_grupo"><i class="fas fa-layer-group"></i> Sección</label>
                                    <select class="form-control" id="seccion_grupo" name="seccion_grupo">
                                        <option value="">Seleccione sección</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fas fa-search"></i> Consultar Asistencias
                            </button>
                        </form>

                    </div>
                </div>
            </div>


            <!-- Resultados de asistencia -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title"><i class="fas fa-list-alt mr-2"></i> Resultados de Asistencia</h3>
                    </div>
                    <div class="card-body">
                        <div id="alert-info" class="alert alert-light align-items-center" role="alert">
                        <i class="fas fa-calendar-alt text-info mr-2"></i>
                        Por favor, seleccione una fecha para consultar los registros de asistencia.
                            <!-- Se llenará dinámicamente -->
                        </div>


                        <hr>

                        <table id="table_attendance" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Estudiante</th>
                                    <th>Grado</th>
                                    <th>Sección</th>
                                    <th>Estado</th>
                                    <th>Más Detalles</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>


                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </div>
</section>
<!-- /.content -->