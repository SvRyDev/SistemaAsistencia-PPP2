<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">

            <div class="col-12">
                <div class="card shadow-sm border-left-secondary">
                    <div class="card-body d-flex  align-items-center">
                        <div>
                            <i class="fas fa-info-circle text-secondary me-2"></i>
                            <strong>Última actualización:</strong>
                            <span id="last-updated" class="text-muted">--</span>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-6">


                <!-- Configuración General -->
                <div class="card border-left-primary shadow mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sliders-h"></i> Configuración General
                        </div>

                    </div>

                    <div class="card-body">

                        <!-- Nombre del colegio -->
                        <div class="form-group">
                            <label>Nombre del colegio</label>
                            <input id="name-school" type="text" class="form-control"
                                placeholder="Ej: Colegio Santa María">
                        </div>

                        <!-- Sección fechas -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="mb-0 font-weight-bold">
                                Periodo académico
                            </span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-dates-check">
                                <label class="form-check-label small" for="edit-dates-check">Habilitar
                                    edición</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start-date-academic" class="form-label text-secondary">Fecha de
                                    inicio</label>
                                <input id="start-date-academic" type="date" class="form-control" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end-date-academic" class="form-label text-secondary">Fecha de
                                    finalización</label>
                                <input id="end-date-academic" type="date" class="form-control" disabled>
                            </div>
                        </div>

                        <!-- Zona horaria -->
                        <div class="form-group mt-1">
                            <label>Zona horaria</label>
                            <select id="time-zone" class="form-control" disabled>
                                <option selected>America/Lima (UTC-5)</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-6">


                <!-- Parámetros de Asistencia -->
                <div class="card border-left-success shadow mb-4">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-user-check"></i> Parámetros de Asistencia
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="mb-0 font-weight-bold">
                                Edición de horas
                            </span>
                            <div>
                                <input class="form-check-input" type="checkbox" id="edit-time-check">
                                <label class="form-check-label small" for="edit-time-check">Habilitar edición</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="text-secondary">Hora de entrada</label>
                                    <input id="entry-time" type="time" class="form-control" disabled>
                                </div>

                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="text-secondary">Hora de salida</label>
                                    <input id="exit-time" type="time" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="text-secondary">Tolerancia (minutos)</label>
                                    <input id="time-tolerance" type="number" class="form-control" inputmode="numeric"
                                        pattern="[0-9]*" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Días de clases</label>
                                <select id="school-days" class="form-control" disabled>
                                    <option>Lunes a Viernes</option>
                                    <option>Lunes a Sábado</option>
                                </select>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gestión de Usuarios -->

            <div class="col-12">
                <div class="card border-left-info shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-user-cog"></i> Configuración de Cuenta
                    </div>
                    <div class="card-body">
                        <!-- Nombre de usuario -->
                        <div class="form-group">
                            <label for="user-username">Nombre de usuario</label>
                            <input type="text" class="form-control" id="user-username" placeholder="Ej: jdoe123">
                        </div>

                        <!-- Rol (solo lectura si no se puede cambiar) -->
                        <div class="form-group">
                            <label for="user-role">Rol</label>
                            <input type="text" class="form-control" id="user-role" disabled>
                        </div>

                        <!-- Cambiar contraseña -->
                        <hr>
                        <p class="text-muted mb-2"><i class="fas fa-key me-1"></i> Cambiar contraseña</p>

                        <div class="form-group">
                            <label for="user-password-current">Contraseña actual</label>
                            <input type="password" class="form-control" id="user-password-current">
                        </div>

                        <div class="form-group">
                            <label for="user-password-new">Nueva contraseña</label>
                            <input type="password" class="form-control" id="user-password-new">
                        </div>

                        <div class="form-group mb-3">
                            <label for="user-password-confirm">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" id="user-password-confirm">
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Botón Guardar -->
        <div class="text-center pb-4">
            <button id="btnSaveSetting" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </div>
</section>

<script src="<?= assets() ?>/js/validations.js"></script>