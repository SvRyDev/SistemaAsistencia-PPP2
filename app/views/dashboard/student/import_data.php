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
                    <label for="excelFile" class="btn btn-light shadow d-flex align-items-center w-100 m-0"
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

                <div id="previewTable"></div>
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
                  <li>El archivo debe contener las columnas: <b>codigo, nombre, apellido, edad</b>.</li>
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
        <div id="dropZone" class="border border-secondary border-dashed rounded p-5 text-center text-muted"
             style="border-style: dashed; cursor: pointer;">
          <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-secondary"></i>
          <p class="h5 m-0">Arrastra aquí tu archivo</p>
          <p class="small">(o haz clic para seleccionarlo)</p>
          <input type="file" id="dragFileInput" class="d-none" />
        </div>
      </div>
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