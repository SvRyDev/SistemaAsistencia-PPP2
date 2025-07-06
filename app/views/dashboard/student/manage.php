 <div class="modal fade" id="modal_new_student">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Estudiante</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="">


                <div class="form-group">
                  <label for="nombre_est">Dni</label>
                  <input name="nombre_est" id="nombre_est" class="form-control" type="text">
                </div>

                <div class="form-group">
                  <label for="nombre_est">Nombres</label>
                  <input name="nombre_est" id="nombre_est" class="form-control" type="text">
                </div>

                <div class="form-group">
                  <label for="nombre_est">Apellido</label>
                  <input name="nombre_est" id="nombre_est" class="form-control" type="text">
                </div>

                <div class="row">
                  <div class="form-group col-6">
                    <label for="nombre_est">Grado</label>
                    <select name="nombre_est" id="nombre_est" class="custom-select">
                      <option value="1">1°</option>
                      <option value="2">2°</option>
                      <option value="3">3°</option>
                      <option value="4">3°</option>
                      <option value="5">3°</option>
                    </select>
                  </div>
                  <div class="form-group col-6">
                    <label for="nombre_est">Sección</label>
                    <select name="nombre_est" id="nombre_est" class="custom-select">
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="C">C</option>
                      <option value="D">D</option>
                      <option value="E">E</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="exampleInputFile">Foto</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="exampleInputFile">
                      <label class="custom-file-label" for="exampleInputFile">Seleccionar Foto</label>
                    </div>
                    
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary">Guardar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Relacion de Estudiantes</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_student">
                    <i class="fas fa-user-plus"></i> Nuevo Estudiante
                  </button>
                  <div class="btn btn-success"><i class="fas fa-book"></i> Importar Estudiantes</div>

                  <hr>
                  <table id="table_student" class="table table-bordered table-striped">

                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->