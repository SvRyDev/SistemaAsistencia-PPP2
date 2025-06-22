<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="row">

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-8 order-2 order-md-1">
                        <div class="card">
                            <div class="card-header bg-secondary font-weight-bold">
                                <i class="fas fa-file-alt"></i> Formulario de Búsqueda
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="alert alert-light small" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    En esta sección puedes consultar el historial de asistencia de un estudiante
                                    seleccionando su DNI y el mes deseado.

                                </div>


                                <div class="mb-3">
                                    <i class="fas fa-id-card"></i>
                                    <label for="" class="label-control">DNI del Estudiante:</label>
                                    <input type="text" class="form-control" id="studentCodeInput"
                                        placeholder="Ej.12345678" value="STU001">


                                </div>

                                <div class="mb-3">
                                    <i class="fas fa-calendar-alt"></i>
                                    <label for="" class="label-control">Mes:</label>
                                    <select class="form-control" id="grado" name="grado">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="0">Enero</option>
                                        <option value="1">Febrero</option>
                                        <option value="2">Marzo</option>
                                        <option value="3">Abril</option>
                                    </select>
                                </div>



                                <button class="btn btn-primary btn-block mt-2" id="searchStudentButton">
                                    <i class="fas fa-search"></i> Consultar
                                </button>


                            </div>

                            <!-- /.card-body -->
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 order-1 order-md-2">


                        <div class="card">
                            <div class="card-header bg-info text-white font-weight-bold">
                                <i class="fas fa-user-graduate mr-2"></i> Información del Estudiante
                            </div>
                            <div class="card-body">
                                <div id="info-student-contain">
                                    <div class="w-100">
                                        <div class="text-center mr-4 mb-4">
                                            <i class="fas fa-user-circle fa-9x text-secondary"></i>
                                        </div>
                                    </div>

                                    <div class="mr-3 ml-3">
                                        <div class="mb-3 d-flex align-items-center">
                                            <i class="fas fa-user mr-2 text-primary"></i>
                                            <strong class="w-25 mr-2">Nombre:</strong>
                                            <span id="result-nombre-est" class="text-muted">VILCAPUMA POMAROLA JAVIAR
                                                LINO</span>
                                        </div>
                                        <div class="mb-3 d-flex align-items-center">
                                            <i class="fas fa-id-card mr-2 text-success"></i>
                                            <strong class="w-25 mr-2">Código:</strong>
                                            <span id="result-codigo-est" class="text-muted">STU70087799</span>
                                        </div>
                                        <div class="mb-3 d-flex align-items-center">
                                            <i class="fas fa-graduation-cap mr-2 text-warning"></i>
                                            <strong class="w-25 mr-2">Grado:</strong>
                                            <span id="result-grado-est" class="text-muted">TERCER GRADO A</span>
                                        </div>
                                        <div class=" d-flex align-items-center">
                                            <i class="fas fa-graduation-cap mr-2 text-warning"></i>
                                            <strong class="w-25 mr-2">Sección:</strong>
                                            <span id="result-seccion-est" class="text-muted">A</span>
                                        </div>
                                    </div>
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


                                <div class="card shadow border p-2 rounded ">
                                    <div id="result-contanin">

                                        <div class="card-body">
                                            <div class="" style="overflow-x: auto;">
                                                <table id="table-resultado" class="table table-bordered table-sm"
                                                    style="">
                                                    <thead class="text-center">
                                                        <tr class="bg-light">
                                                            <th>#</th>
                                                            <th>Dia</th>
                                                            <th>Estado</th>
                                                            <th>Observación</th>
                                                        </tr>

                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>




                                        <div class="pl-4 pr-4">
                                            <p class="mt-0">
                                                Total asistencias: <span class="badge badge-success">2</span>
                                                <br>
                                                Total inasistencias: <span class="text-danger">1</span><br>
                                                Total justificaciones: <span class="text-warning">0</span><br>
                                                Total tardanzas: <span class="text-info">0</span>
                                            </p>

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