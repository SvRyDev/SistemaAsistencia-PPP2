<!-- Modal Detalle Asistencia -->
<div class="modal fade" id="modalDetalleAsistencia" tabindex="-1" role="dialog" aria-labelledby="modalDetalleAsistenciaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title" id="modalDetalleAsistenciaLabel">
          <i class="fas fa-user-check mr-2"></i> Detalle de Asistencia del Estudiante
        </h6>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Información del estudiante -->
        <div class="row mb-1">
          <div class="col-md-4 mb-2">
            <small class="text-muted">ID Asistencia</small>
            <div class="font-weight-bold" id="detalle-id">—</div>
          </div>
          <div class="col-md-4 mb-2">
            <small class="text-muted">Código Estudiante</small>
            <div class="font-weight-bold" id="detalle-codigo">—</div>
          </div>
          <div class="col-md-4 mb-2">
            <small class="text-muted">Nombre Completo</small>
            <div class="font-weight-bold" id="detalle-estudiante">—</div>
          </div>
        </div>

        <!-- Grado y Sección -->
        <div class="row mb-1">
          <div class="col-md-6 mb-2">
            <small class="text-muted">Grado</small>
            <div class="font-weight-bold" id="detalle-grado">—</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Sección</small>
            <div class="font-weight-bold" id="detalle-seccion">—</div>
          </div>
        </div>

        <hr>

        <!-- Detalle de asistencia -->
        <div class="row">
          <div class="col-md-4 mb-1">
            <small class="text-muted">Día</small>
            <div class="font-weight-bold" id="detalle-dia">—</div>
          </div>
          <div class="col-md-4 mb-1">
            <small class="text-muted">Hora de Entrada</small>
            <div class="font-weight-bold" id="detalle-hora">—</div>
          </div>
          <div class="col-md-4 mb-1">
            <small class="text-muted">Estado de Asistencia</small>
            <div id="detalle-estado" class="font-weight-bold">—</div>
          </div>
        </div>

        <div class="row mt-1">
          <div class="col-md-12">
            <small class="text-muted">Observación</small>
            <div class="border rounded p-2" id="detalle-observacion">—</div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
