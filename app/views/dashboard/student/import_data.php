      <!-- Main content -->

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-3">

              <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <div>importar desde csv</div>
                  <div>importar desde excel</div>
                  <div>
                    <form id="uploadForm" enctype="multipart/form-data">
                      <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx" required />
                      <button type="submit">Importar Excel</button>
                    </form>

                    <div id="previewTable"></div>
                  </div>
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