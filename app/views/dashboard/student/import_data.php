<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-12">

        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-xs-12">

            <div class="card">
              <!-- /.card-header -->
              <div class="card-body p-1">
                <form id="uploadForm" enctype="multipart/form-data" class="w-100">
                  <div class="form-group m-0">
                    <!-- Label como botón de ancho completo -->

                    <label id="btn-import-excel" class="btn btn-light shadow d-flex align-items-center w-100 m-0"
                      style="cursor: pointer; border-radius: 0;">
                      <!-- Ícono blanco con fondo verde -->

                      <span
                        class="bg-success text-white d-flex align-items-center justify-content-center mr-2 flex-shrink-0"
                        style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 4px;">
                        <i class="fas fa-file-excel fa-2x"></i>
                      </span>
                      <span>Importar desde Excel</span>
                    </label>

                    <!-- Input oculto -->
                    <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx" required class="d-none">
                  </div>
                </form>

    
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.card -->

          <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-xs-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body p-1">
                
                <form id="uploadCSVForm" enctype="multipart/form-data" class="w-100">
                  <div class="form-group m-0">
                    <!-- Label como botón de ancho completo -->
                    <label for="csvFile" class="btn btn-light shadow d-flex align-items-center w-100 m-0"
                      style="cursor: pointer; border-radius: 0;">
                      <!-- Ícono blanco con fondo verde -->
                      <span
                        class="bg-secondary text-white d-flex align-items-center justify-content-center mr-2 flex-shrink-0"
                        style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 4px;">
                        <i class="fas fa-file-csv fa-2x"></i>
                      </span>
                      <span>Importar desde CSV</span>
                    </label>

                    <!-- Input oculto -->
                    <input type="file" name="csvFile" id="csvFile" accept=".csv" required class="d-none">
                  </div>
                </form>

                <div id="previewTableCSV"></div>
              </div>

              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.card -->

          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <b>Nota importante:</b>
                <br>
                <ul>
                  <li>El archivo debe contener las columnas: <b>Nombres, Apellidos, Dni, Grado y Sección</b>.</li>
                  <li>El formato de fecha debe ser compatible con el sistema.</li>
                  <li>Los datos serán validados antes de ser importados.</li>
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
              <div class="card-body">

                <!-- Drop Zone -->
                <div id="dropZone" class="border border-secondary rounded p-5 text-center text-muted"
                  style="border-style: dashed; cursor: pointer;">
                  <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-secondary"></i>
                  <p class="h5 m-0">Arrastra aquí tu archivo</p>
                  <p class="small">(o haz clic para seleccionarlo)</p>
                </div>

                <!-- Input + Botón -->
                <div class="form-row mt-4 align-items-center">
                  <div class="col-md-9">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="dragFileInput">
                      <label class="custom-file-label" for="dragFileInput">Selecciona un archivo</label>
                    </div>
                  </div>

                  <div class="col-md-3 text-right">
                    <button class="btn btn-primary btn-block" id="loadFileButton">
                      Cargar <i class="fas fa-upload ml-2"></i>
                    </button>
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

              <div id="div-button-import" class="card-header d-none">
                <button class="btn btn-success btn-block mb-2" id="importButton">
                  Importar <i class="fas fa-upload ml-2"></i>
                </button>

                <span class="text-muted" id="countDataFounded">
                  <i class="fas fa-exclamation-circle mr-2"></i>Se Encontraron <span class="text-bold" id="countData">0</span> Registros.
                </span>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->

              <div class="card-header">
                <i class="fas fa-eye mr-2"></i> Vista Previa <span class="text-muted">(Revisa los datos antes de importar)</span>
              </div>

              <div class="card-body">
                <div id="previewContainer" class="table-responsive">

                  <table style="font-size: 15px;" class="table table-bordered table-striped" id="previewTableData">
                    <thead>
                      <tr>

                        <th>#</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Dni</th>
                        <th>Grado</th>
                        <th>Seccion</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>

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
<!-- /.content -->