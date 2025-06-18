<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <input type="text" class="form-control" id="studentCodeInput"
                                    placeholder="Ingrese el código del estudiante">

                                <button class="btn btn-primary btn-block mt-2" id="searchStudentButton">
                                    Buscar Estudiante
                                </button>

                                <div id="studentInfo">

                                </div>
                            </div>

                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
                <!-- /.col -->

            </div>

            <div class="col-md-12">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-eye mr-2"></i>Resutados de la búsqueda
                            </div>


                            <!-- /.card-header -->
                            <div class="card-body">
                                <p>Reporte de Asistencia</p>
                                <div class="" style="overflow-x: auto;">
                                    <table id="table-resultado" class="table table-bordered table-sm" style="">
                                        <thead class="text-center">
                                            <tr class="bg-success">
                                                <th>#</th>
                                                <th>Dia</th>
                                                <th>Estado</th>
                                                <th>Observación</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <p class="mt-3">
                                    Total asistencias: <span class="text-success">2</span><br>
                                    Total inasistencias: <span class="text-danger">1</span><br>
                                    Total justificaciones: <span class="text-warning">0</span><br>
                                    Total tardanzas: <span class="text-info">0</span>
                                </p>
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