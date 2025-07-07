<section class="content">
    <div class="container-fluid">


        <!-- Filtros y Vista previa -->
        <div class="row">
            <!-- Filtros -->
            <div class="col-xl-6 col-lg-5 col-md-12 mb-4">
                <div class="row">

                    <div class="col-12">
                        <div class="card shadow-sm border-left-primary">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0 card-title">
                                    <i class="fas fa-id-card mr-2"></i> Buscar Carnet Individual
                                </h3>
                            </div>

                            <div class="card-body">
                                <form id="carnetIndividualForm">

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="anio_individual">
                                                <i class="fas fa-calendar-alt"></i> Año Académico
                                            </label>
                                            <input type="text" class="form-control" id="anio_individual"
                                                name="anio_individual" value="----" disabled>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="dni_estudiante">
                                                <i class="fas fa-id-card"></i> DNI del Estudiante
                                            </label>
                                            <input type="text" class="form-control" id="dni_estudiante"
                                                name="dni_estudiante" placeholder="Ingrese DNI" maxlength="8"
                                                data-validate="numeric" required>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>




                    <div class="col-12">

                        <div class="row">



                            <div class="col-md-4 col-lg-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-1">
                                        <div class="w-100">
                                            <div class="form-group m-0">
                                                <!-- Label como botón de ancho completo -->

                                                <label id="btn-generate-carnets"
                                                    class="btn btn-light shadow d-flex align-items-center w-100 m-0"
                                                    style="cursor: pointer; border-radius: 0;">
                                                    <!-- Ícono blanco con fondo verde -->

                                                    <span
                                                        class="bg-success text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                                        style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 4px;">
                                                        <i class="fas fa-id-card-alt fa-2x"></i>
                                                    </span>
                                                    <span>Buscar Carnet</span>
                                                </label>

                                                <!-- Input oculto -->
                                                <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx"
                                                    required class="d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-lg-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-1">
                                        <div class="w-100" data-toggle="modal" data-target="#previewModal">
                                            <div class="form-group m-0">
                                                <!-- Label como botón de ancho completo -->
                                                <label id="btn-vista-previa"
                                                    class="btn btn-light shadow d-flex align-items-center w-100 m-0"
                                                    style="cursor: pointer; border-radius: 0;">
                                                    <!-- Ícono blanco con fondo azul -->
                                                    <span
                                                        class="bg-info text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                                        style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 4px;">
                                                        <i class="fas fa-eye fa-2x"></i>
                                                    </span>
                                                    <span>Vista Previa</span>
                                                </label>

                                                <!-- Input oculto -->
                                                <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx"
                                                    required class="d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-1">

                                        <div class="w-100">
                                            <div class="form-group m-0">
                                                <!-- Label como botón de ancho completo -->

                                                <label id="btn-export-pdf"
                                                    class="btn btn-light shadow d-flex align-items-center w-100 m-0 disabled"
                                                    style="cursor: not-allowed; pointer-events: none; opacity: 0.6; border-radius: 0;">
                                                    <!-- Ícono blanco con fondo verde -->

                                                    <span
                                                        class="bg-danger text-white d-flex align-items-center justify-content-center mr-3 flex-shrink-0 "
                                                        style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; border-radius: 4px;">
                                                        <i class="fas fa-file-pdf fa-2x"></i>
                                                    </span>
                                                    <span>Decargar PDF</span>
                                                </label>

                                                <!-- Input oculto -->
                                                <input type="file" name="excelFile" id="excelFile" accept=".xls,.xlsx"
                                                    required class="d-none">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-6 col-lg-7 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0 card-title"><i class="fas fa-th mr-2"></i> Carnets generados</h5>
                    </div>
                    <div class="card-body">
                        <div id="carnet-alert" class="alert alert-light align-items-center shadow-sm" role="alert"
                            style="display:none;">
                            <i class="fas fa-check-circle text-success mr-2 fa-lg"></i>
                            <span>Carga Realizada.</span>
                        </div>
                        <div class="position-relative" style="">

                            <!-- Iframe de PDF -->
                            <iframe id="preview-carnets" name="preview-carnets" class="shadow"
                                style="width:100%; height:100%; border: none; z-index: 2;"></iframe>
                        </div>



                    </div>
                </div>
            </div>
        </div>

        <!-- Carnets generados -->
        <div class="row">

        </div>

    </div>

    <!-- Modal de vista ampliada -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-id-card"></i> Ejemplo Carnet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?= assets() ?>/img/static/ejemplar-carnet.png" class="w-100">
                </div>
            </div>
        </div>
    </div>
</section>