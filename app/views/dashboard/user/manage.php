<?php include('modal/create-edit-user.php'); ?>
<?php include('modal/create-edit-role.php'); ?>
<!-- /.modals -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">
                    <i class="fas fa-users-cog mr-2"></i> Administración de Usuarios y Roles
                </h3>
            </div>

            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab">
                            <i class="fas fa-user"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab">
                            <i class="fas fa-user-tag"></i> Roles
                        </a>
                    </li>
                </ul>

     
                <!-- Tab content -->
                <div class="tab-content mt-3">
                    <!-- Usuarios -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel">
                        <div class="mb-2 text-right">
                            <button class="btn btn-success btn-sm btn-new-user" id="btnOpenUserModal">
                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                            </button>
                        </div>
                        <table id="table_users" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contraseña</th>
                                    <th>Rol(es)</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>

                    </div>

                    <!-- Roles -->
                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        <div class="mb-2 text-right">
                            <button class="btn btn-primary btn-sm btn-new-role">
                                <i class="fas fa-plus-circle"></i> Nuevo Rol
                            </button>
                        </div>
                        <table id="table_roles" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Rol</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos simulados -->
                                <tr>
                                    <td>1</td>
                                    <td>Administrador</td>
                                    <td>Acceso total al sistema</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#modalEditRole"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>