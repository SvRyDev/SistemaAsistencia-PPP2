<!-- Modal -->
<div class="modal fade" id="modalAuxiliar" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Encabezado -->
            <div class="modal-header" id="modalAsistenciaHeader">
                <h5 class="modal-title" id="tituloModal">Registrar Asistencia Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <!-- Cuerpo -->
            <div class="modal-body">
                <!-- Mensaje de instrucción -->
                <!-- Instrucción -->
                <div class="alert alert-info small d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle mr-2 text-white"></i>
                    En esta sección puedes registrar o editar manualmente la asistencia de un estudiante.
                </div>

                <!-- Buscador de estudiante -->
                <div class="form-group position-relative">
                    <label for="buscarEstudiante">Buscar Estudiante</label>
                    <input type="text" class="form-control" id="buscarEstudiante" placeholder="Nombre o DNI...">
                    <div id="resultadoBusqueda" class="list-group position-absolute w-100" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
                </div>
                <hr>

                <!-- Campos llenados automáticamente -->
                <form id="formEditarAsistencia" method="post">
                    <input type="hidden" name="estudiante_id" id="estudianteId">

                    <div class="form-group">
                        <label for="estudianteNombre">Estudiante Seleccionado</label>
                        <input type="text" class="form-control" id="estudianteNombre" readonly>

                        <!-- Mensaje dinámico de estado -->
                        <small id="estadoAsistenciaMensaje" class="form-text mt-1 font-weight-bold">
                            <!-- Aquí se insertará el mensaje dinámicamente -->
                        </small>
                    </div>


                    <div class="form-group">
                        <label for="fechaAsistencia">Fecha</label>
                        <input type="date" class="form-control" id="fechaAsistencia" name="fecha" value="2025-07-01" disabled>
                    </div>

                    <div class="form-group">
                        <label for="horaEntrada">Hora de Entrada</label>
                        <input type="time" class="form-control" id="mdlHoraEntrada" name="hora_entrada" value="00:00">
                    </div>

                    <div class="form-group">
                        <label for="estadoAsistencia">Estado de Asistencia</label>
                        <select class="form-control" id="estadoAsistencia" name="estado_asistencia_id">
                            <option value="" >--Seleccione--</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observacion">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="2" placeholder="Opcional..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Pie -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btnGuardarAsistencia" form="formEditarAsistencia" class="btn btn-primary">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>