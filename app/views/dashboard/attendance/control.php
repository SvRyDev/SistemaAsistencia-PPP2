<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <div class="card">
                    <!-- /.card-header -->

                    <div class="card-header bg-success text-white">
                        <i class="fas fa-clipboard-check mr-2"></i> Informacion del Día
                    </div>
                </div>

            </div>
        </div>


        <!-- Resumen del día -->
        <div class="row mb-3">
            <!-- Hora actual -->
            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                <div class="card shadow border-left-info bg-light">
                    <div class="card-body d-flex align-items-center p-3">
                        <!-- Ícono en círculo -->
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center mr-3"
                            style="width: 60px; height: 60px; border-radius: 50%;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>

                        <!-- Hora y fecha -->
                        <div class="text-left flex-grow-1">
                            <div class="display-4 font-weight-bold text-dark mb-0" id="hora-actual">--:--</div>
                            <span class="h6 text-muted" id="fecha-actual">----</span>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Día habilitado y Ventana de asistencia -->
            <div class="col-sm-6 col-md-8 col-lg-8 col-xl-3">
                <!-- Día Habilitado -->
                <div class="card shadow mb-2">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <span
                                class="bg-primary text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                style="width: 50px; height: 50px; border-radius: 4px;">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </span>
                            <div class="text-left">
                                <div class="font-weight-bold text-dark mb-1">Día Habilitado</div>
                                <span id="dia-activo" class="badge badge-secondary badge-pill px-3 py-1">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ventana de Asistencia -->
                <div class="card shadow">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <span
                                class="bg-dark text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                style="width: 50px; height: 50px; border-radius: 4px;">
                                <i class="fas fa-door-closed fa-2x"></i>
                            </span>
                            <div class="text-left">
                                <div class="font-weight-bold text-dark mb-1">Ventana de Asistencia</div>
                                <span id="estadoVentana" class="badge badge-danger badge-pill px-3 py-1">No
                                    Abierta</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado Actual -->
            <div class="col-sm-12 col-md-6 col-lg-12 col-xl-6">
                <div class="card shadow border-left-secondary bg-light">
                    <div class="card-header bg-light">
                        <i class="fas fa-info-circle mr-2"></i> Estado Actual
                    </div>
                    <div class="card-body d-flex align-items-center p-3">
                        <div id="iconEstado" class="bg-secondary text-white d-flex align-items-center justify-content-center mr-3"
                            style="width: 60px; height: 60px; border-radius: 50%;">
                            <i id="iconoAsistencia" class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div class="text-left flex-grow-1">
                            <div id="estadoDiaRegistro" class="h3 font-weight-bold mb-0">----</div>
                            <small class="text-muted">Estado de Asistencia</small>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Horarios -->
            <div class="col-sm-12 col-md-6 col-lg-12 col-xl-5 mt-2">
                <div class="card shadow border-left-warning">
                    <div class="card-body py-3">
                        <div class="row text-center">

                            <!-- Entrada -->
                            <div class="col-4 ">
                                <i class="fas fa-sign-in-alt fa-lg text-primary mb-1"></i>
                                <div class="small text-muted">Entrada</div>
                                <span id="time-entry" class="badge badge-primary px-3 py-1 font-weight-bold">--:--</span>
                            </div>

                            <!-- Tolerancia -->
                            <div class="col-4 ">
                                <i class="fas fa-clock fa-lg text-warning mb-1"></i>
                                <div class="small text-muted">Tolerancia</div>
                                <span id="time-tolerance" class="badge badge-warning px-3 py-1 font-weight-bold">--</span>
                            </div>

                            <!-- Salida -->
                            <div class="col-4">
                                <i class="fas fa-sign-out-alt fa-lg text-danger mb-1"></i>
                                <div class="small text-muted">Salida</div>
                                <span id="time-finish" class="badge badge-danger px-3 py-1 font-weight-bold">--:--</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 mt-2">
                <div class="card shadow border-left-primary">
                    <div class="card-body">
                        <div class="row justify-content-center text-center">
                            <!-- Habilitar Periodo -->
                            <div class="col-auto mb-2">
                                <button id="btnOpenDay" class="btn btn-success">
                                    <i class="fas fa-toggle-on mr-1"></i> Aperturar Dia
                                </button>
                            </div>

                            <!-- Abrir Ventana -->
                            <div class="col-auto mb-2">
                                <button id="btnOpenAttendance" class="btn btn-info" id="btnOpenAttendance">
                                    <i class="fas fa-door-open mr-1"></i> Abrir Ventana
                                </button>
                            </div>

                            <!-- Marcar Asistencia Manual -->
                            <div class="col-auto mb-2">
                                <button id="btnOpenManualRegister" class="btn btn-secondary" id="btnMarcarManual">
                                    <i class="fas fa-user-check mr-1"></i> Marcar Manual
                                </button>
                            </div>

                            <!-- Editar Asistencia -->
                            <div class="col-auto mb-2">
                                <button id="btnOpenEditor" class="btn btn-dark" id="btnEditarAsistencia">
                                    <i class="fas fa-edit mr-1"></i> Editar Asistencia
                                </button>
                            </div>

                            <!-- Registrar Justificación -->
                            <div class="col-auto mb-2">
                                <button id="btnOpenJustify" class="btn btn-warning">
                                    <i class="fas fa-file-signature mr-1"></i> Registrar Justificación
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <!-- Acciones -->
        <!-- 
                <div class="row">
            <div class="col-lg-12 col-md-12">

                <div class="card">
           

                    <div class="card-header bg-success text-white">
                        <i class="fas fa-clipboard-check mr-2"></i> Registro de Asistencia
                    </div>
                </div>

            </div>
        </div>
        -->
        <!-- Conteos y Tabla: lado a lado -->


        <div class="card">
            <!-- /.card-header -->

            <div class="card-header bg-secondary text-white">
                <i class="fas fa-clipboard-check mr-2"></i> Registro de Asistencia
            </div>
        </div>




        <div class="row d-flex align-items-stretch">

            <!-- Conteo de estudiantes + Pie chart -->
            <div class="col-lg-12 col-xl-3 mb-3 d-flex flex-column">
                <!-- Contadores -->
                <div class="row mb-3 flex-grow-1">

                    <!-- Temprano -->
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-6 mb-3">
                        <div class="text-center p-2 bg-white border-left border-success rounded shadow h-100">
                            <h6 class="text-success font-weight-bold mb-1">Temprano</h6>
                            <div class="h5 font-weight-bold text-success mb-0" id="contadorTemprano">0</div>
                            <small id="porcentajeTemprano" class="text-muted">0%</small>

                        </div>
                    </div>

                    <!-- Tardíos -->
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-6 mb-3">
                        <div class="text-center p-2 bg-white border-left border-info rounded shadow h-100">
                            <h6 class="text-info font-weight-bold mb-1">Tardíos</h6>
                            <div class="h5 font-weight-bold text-info mb-0" id="contadorTardios">0</div>
                            <small id="porcentajeTardios" class="text-muted">0%</small>
                        </div>
                    </div>

                    <!-- Justificados -->
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-6 mb-3">
                        <div class="text-center p-2 bg-white border-left border-warning rounded shadow h-100">
                            <h6 class="text-warning font-weight-bold mb-1">Justificados</h6>
                            <div class="h5 font-weight-bold text-warning mb-0" id="contadorJustificados">0</div>
                            <small id="porcentajeJustificados" class="text-muted">0%</small>
                        </div>
                    </div>

                    <!-- Restantes -->
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-6 mb-3">
                        <div class="text-center p-2 bg-white border-left border-danger rounded shadow h-100">
                            <h6 class="text-danger font-weight-bold mb-1">Restantes</h6>
                            <div class="h5 font-weight-bold text-danger mb-0" id="contadorRestantes">0</div>
                            <small id="porcentajeRestantes" class="text-muted">0%</small>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                        <div class="text-center p-2 bg-white border-left border-primary rounded shadow h-100">
                            <h6 class="text-primary font-weight-bold mb-1">Total de Estudiantes</h6>
                            <div id="total_estudiantes" class="h5 font-weight-bold text-primary mb-0">0</div>
                            <small class="text-muted">100%</small>
                        </div>
                    </div>


                    <!-- Gráfico -->
                    <div class="col-xl-12">
                        <div class="card flex-grow-1 shadow">
                            <div class="card-header bg-secondary text-white">
                                <i class="fas fa-chart-pie mr-2"></i> Gráfico de Asistencia
                            </div>
                            <div class="card-body">
                                <canvas id="pieAsistencia"></canvas>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- Tabla de registros -->
            <div class="col-lg-12 col-xl-9 mb-3 d-flex">
                <div class="card shadow  w-100 h-100">
                    <div class="card-header border-left-dark bg-light">
                        <i class="fas fa-list-alt mr-2"></i> Listado del Estudiantes
                    </div>
                    <div class="card-body table-responsive">
                        <div class="list-group" id="listaAsistencia">
                            <!-- Aquí se mostrarán los estudiantes registrados -->


                            <div
                                class="list-group-item pt-2 pb-2 animate__animated animate__fadeInUp  bg-dark text-left">

                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-1 font-weight-bold ">
                                            #
                                        </div>
                                        <div class="col-md-2 text-white">
                                            Código
                                        </div>
                                        <div class="col-md-4">
                                            Nombres
                                        </div>

                                        <div class="col-md-1 text-white">
                                            Grado
                                        </div>

                                        <div class="col-md-1 text-white">
                                            Sección
                                        </div>
                                        <div class="col-md-2 text-white">
                                            Hora Llegada
                                        </div>
                                        <div class="col-md-1 text-white">
                                            Estado
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>





        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>