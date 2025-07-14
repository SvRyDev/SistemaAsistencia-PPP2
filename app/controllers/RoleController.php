<?php

class RoleController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto
    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }

    public function index()
    {
        $view = "user.main";
        $data = [
            'view_js' => $view,
            'title' => 'Usuarios',
            'message' => 'Esta es la página de vista de usuarios.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function get_all_roles()
    {
        $roleModel = $this->model('RoleModel');
        $allRoles = $roleModel->getAllRoles();

        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['data' => $allRoles]);
            return;
        }
    }


    public function get_role()
    {
        // Validar si es AJAX
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
            return;
        }

        // Validar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
            return;
        }

        // Validar existencia del ID
        $roleID = $_POST['role_id'] ?? null;
        if (!$roleID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID de rol no proporcionado.']);
            return;
        }

        // Consultar al modelo
        $roleModel = $this->model('RoleModel');
        $permissionModel = $this->model('PermissionModel');
        $role = $roleModel->getRoleById($roleID);
        $permissions = $permissionModel->getPermissionByRoleId($role['role_id']);

        if (!$role) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Rol no encontrado.']);
            return;
        }

        $data = [
            'role' => $role,
            'permissions' => $permissions,
        ];

        // Respuesta exitosa
        header('Content-Type: application/json');
        echo json_encode(
            [
                'status' => 'success',
                'data' => $data,
            ]
        );
    }



    public function create_role()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        $required = ['nombre_rol', 'color_rol'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Nombre del rol y color son obligatorios.'
                ]);
                return;
            }
        }

        $nombre = mb_strtoupper(trim($_POST['nombre_rol']), 'UTF-8');
        $descripcion = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
        $icono = ''; // Si usas íconos, puedes agregar campo luego
        $color = trim($_POST['color_rol']);
        $protegido = 0; // por defecto no protegido
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

        try {
            $RoleModel = $this->model('RoleModel');

            if ($RoleModel->roleNameExists($nombre)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El nombre del rol ya existe.'
                ]);
                return;
            }

            $roleId = $RoleModel->createRole($nombre, $descripcion, $icono, $color, $protegido);

            if (!$roleId) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo crear el rol.'
                ]);
                return;
            }

            if (!empty($permisos)) {
                $RoleModel->assignPermissions($roleId, $permisos);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Rol creado correctamente.'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado.',
                'error' => $e->getMessage()
            ]);
        }
    }





    public function update_role()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        // Validar campos obligatorios
        $required = ['role_id', 'nombre_rol', 'color_rol'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID del rol, nombre y color son obligatorios.'
                ]);
                return;
            }
        }

        $roleId = (int) $_POST['role_id'];
        $nombre = mb_strtoupper(trim($_POST['nombre_rol']), 'UTF-8');
        $descripcion = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
        $icono = ''; // Si lo usás
        $color = trim($_POST['color_rol']);
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

        try {
            $RoleModel = $this->model('RoleModel');

            // Validar si existe otro rol con el mismo nombre
            if ($RoleModel->roleNameExists($nombre, $roleId)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Ya existe otro rol con ese nombre.'
                ]);
                return;
            }

            // Actualizar rol
            $updated = $RoleModel->updateRole($roleId, $nombre, $descripcion, $icono, $color);

            if (!$updated) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo actualizar el rol.'
                ]);
                return;
            }

            // Actualizar permisos: primero eliminar los actuales
            $RoleModel->clearPermissions($roleId);

            // Luego asignar nuevos si hay
            if (!empty($permisos)) {
                $RoleModel->assignPermissions($roleId, $permisos);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Rol actualizado correctamente.'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete_role()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }
    
        header('Content-Type: application/json');
    
        $role_id = isset($_POST['role_id']) ? (int) $_POST['role_id'] : 0;
    
        if ($role_id <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de rol inválido.'
            ]);
            return;
        }
    
        try {
            $RoleModel = $this->model('RoleModel');
    
            // ⚠️ Si quieres proteger algunos roles (como admin), verifica aquí:
            $role = $RoleModel->getRoleById($role_id);
            if (!$role) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El rol no existe.'
                ]);
                return;
            }
    
            if ((int)$role['protegido'] === 1) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Este rol está protegido y no puede eliminarse.'
                ]);
                return;
            }

            if ($RoleModel->roleHasUsers($role_id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se puede eliminar el rol porque está asignado a uno o más usuarios.'
                ]);
                return;
            }
            
            $RoleModel->clearPermissions($role_id);
            $deleted = $RoleModel->deleteRoleById($role_id);
    
            if ($deleted) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Rol eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo eliminar el rol.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado.',
                'error' => $e->getMessage()
            ]);
        }
    }
    
}

