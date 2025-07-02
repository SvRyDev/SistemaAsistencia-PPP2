<!-- Modal -->
<div class="modal fade" id="modalAuxiliar" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Encabezado -->
            <div class="modal-header">
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


                <form id="formEditarAsistencia" method="post">
                    <div class="form-group">
                        <label for="estudianteNombre">Estudiante</label>
                        <input type="text" class="form-control" id="estudianteNombre" value="Nombre o Dni">
                        <input type="hidden" name="estudiante_id" value="123">
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
                            <option value="" disabled>--Seleccione--</option>
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
                <button type="submit" form="formEditarAsistencia" class="btn btn-primary">Guardar</button>
            </div>

        </div>
    </div>
</div>