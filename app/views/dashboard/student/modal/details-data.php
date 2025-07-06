<!-- Modal Detalle Estudiante -->
<div class="modal fade" id="modalDetalleEstudiante" tabindex="-1" role="dialog"
    aria-labelledby="tituloDetalleEstudiante" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <!-- Encabezado -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloDetalleEstudiante">
                    <i class="fas fa-user mr-2"></i> Detalles del Estudiante
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body p-4">
                <!-- Información básica -->
                <h6 class="mb-3 text-dark"><i class="fas fa-id-card mr-2"></i> Información del Estudiante</h6>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> <span class="text-muted"
                                    id="est-id">-</span></li>
                            <li class="list-group-item"><strong>Código:</strong> <span class="text-muted"
                                    id="est-codigo">-</span>
                            </li>
                            <li class="list-group-item"><strong>Nombre:</strong> <span class="text-muted"
                                    id="est-nombres">-</span>
                            </li>
                            <li class="list-group-item"><strong>Apellidos:</strong> <span class="text-muted"
                                    id="est-apellidos">-</span></li>
                        </ul>
                    </div>
                    <div class="col-md-6 mb-1">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>DNI:</strong> <span class="text-muted"
                                    id="est-dni">-</span>
                            </li>
                            <li class="list-group-item"><strong>Grado:</strong> <span class="text-muted"
                                    id="est-grado">-
                                    Grado</span></li>
                            <li class="list-group-item"><strong>Sección:</strong> <span class="text-muted"
                                    id="est-seccion">-</span></li>
                            <li class="list-group-item"><strong>Fecha de Registro:</strong> <span class="text-muted"
                                    id="est-fecha">-</span></li>
                        </ul>
                    </div>
                </div>

                <hr>

                <!-- Tabla de asistencias -->
                <h6 class="mb-3 text-dark"><i class="fas fa-calendar-alt mr-2"></i> Asistencia Semanal</h6>
                <!-- #region -->

                <!-- Tabla de asistencia -->
                <!-- Leyenda -->
                <div class="mb-3 small text-muted">
                    <span class="badge badge-success ml-2"><i class="fas fa-check"></i></span> Asistió
                    <span class="badge badge-info ml-2"><i class="fas fa-clock"></i></span> Tardanza
                    <span class="badge badge-warning ml-2"><i class="fas fa-comment"></i></span> Justificado
                    <span class="badge badge-danger ml-2"><i class="fas fa-times"></i></span> Falta
                </div>

                <!-- Contenedor de asistencia -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light font-weight-bold">
                        Últimos 15 días de asistencia
                    </div>
                    <div class="card-body p-2">
                        <div class="row text-center" id="bloqueAsistencia15">
                            <!-- Aquí se insertan los bloques -->
                        </div>
                    </div>
                </div>






            </div>

            <!-- Pie -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>