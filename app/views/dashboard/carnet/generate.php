<button id="btnDownload" class="btn btn-primary">Descargar Carnet PDF</button>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <label class="form-label">Código de Estudiante</label>
                                <br>
                                <input type="text" class="form-control" id="studentCode"
                                    placeholder="Ingrese el código del estudiante">


                                <button class="btn btn-primary btn-block mt-2" id="searchStudentButton">
                                    Buscar Estudiante
                                </button>

                                <button class="btn btn-success btn-block mt-2" id="generatePreviewCarnetButton">
                                    Generar Carnet

                                </button>
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

                            <div class="card-header">
                                <i class="fas fa-eye mr-2"></i> Vista Previa
                            </div>


                            <div class="card-body p-0">
                                <iframe id="preview-carnet" style="width:100%; height:400px; border:none;"></iframe>

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
                            <!-- /.card-header -->

                            <div class="card-header">
                                <i class="fas fa-eye mr-2"></i> Vista Previa <span class="text-muted">(Revisa los datos
                                    antes de importar)</span>
                            </div>

                            <div class="card-body">


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