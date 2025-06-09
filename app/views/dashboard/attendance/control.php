<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <label class="form-label">Aperturar Asistencia</label>
                                <br>


                                <button class="btn btn-primary btn-block mt-2" id="btnOpenAttendance">
                                    Abrir ventana de Asistencia
                                </button>
                                <br>




                                <div class="card bg-light mb-4 shadow-sm">
                                    <div class="card-body">
                                        <p class="mb-3">
                                            <strong>Estado de ventana:</strong>

                                            <span id="estadoVentana" class="badge badge-secondary ml-2">No
                                                abierta</span>
                                        </p>

                                        <hr>

                                        <p class="mb-3 font-weight-bold">Resumen de Asistencia</p>

                                        <ul class="list-unstyled mb-0">
                                            <li>
                                                <span class="text-dark">🎓 Total de Estudiantes:</span>
                                                <span id="totalEstudiantes" class="badge badge-dark ml-2">0</span>
                                            </li>
                                            <li class="mt-2">
                                                <span class="text-primary">📝 Registrados:</span>
                                                <span id="totalRegistrados" class="badge badge-primary ml-2">0</span>
                                            </li>
                                            <li class="mt-2">
                                                <span class="text-success">✅ Asistencias:</span>
                                                <span id="totalAsistencias" class="badge badge-success ml-2">0</span>
                                            </li>
                                            <li class="mt-2">
                                                <span class="text-warning">⏰ Tardanzas:</span>
                                                <span id="totalTardanzas" class="badge badge-warning ml-2">0</span>
                                            </li>
                                            <li class="mt-2">
                                                <span class="text-danger">📄 Justificados:</span>
                                                <span id="totalJustificados" class="badge badge-danger ml-2">0</span>
                                            </li>
                                            <li class="mt-2">
                                                <span class="text-secondary">❌ Restantes:</span>
                                                <span id="totalRestantes" class="badge badge-secondary ml-2">0</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <!-- /.col -->

            </div>

            <div class="col-lg-6 col-md-12">

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">

                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle text-primary mr-2"></i> Estado del Sistema
                                </h6>
                            </div>
                            <div class="card-body">



                                <div class="card mt-3">

                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-external-link-alt text-secondary mr-2"></i>
                                                    Ventana Auxiliar</span>
                                                <span id="estadoVentana" class="badge badge-secondary">No abierta</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-calendar-check text-success mr-2"></i>
                                                    Asistencia</span>
                                                <span id="estadoAsistencia" class="badge badge-danger">No
                                                    iniciada</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>




                            </div>

                        </div>
                    </div>
                </div>




                <!-- /.col -->
            </div>


            <div class="col-lg-12 col-md-12">

                <div class="row">


                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->

                            <div class="card-header">
                                <i class="fas fa-clipboard-check mr-2"></i> Registro de Asistencias
                            </div>

                            <div class="card-body">

                                <div class="list-group" id="listaAsistencia">

                                    <!-- Aquí se mostrarán los estudiantes registrados -->



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