<?php

class UserController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto


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

    public function view_manage_users()
    {
        $view = "user.manage";
        $data = [
            'view_js' => $view,
            'title' => 'Gestión de Usuarios',
            'message' => 'Esta es la página de vista de usuarios.'
        ];

        View::render($view, $data, $this->layout);
    }

    public function get_all_users()
    {
        $userModel = $this->model('UserModel');
        $allUsers = $userModel->getAllUsers();

        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['data' => $allUsers]);
            return;
        }
    }

    public function get_user()
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
        $userId = $_POST['user_id'] ?? null;
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
            return;
        }

        // Consultar al modelo
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($userId);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
            return;
        }

        // Respuesta exitosa
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $user]);
    }


    public function create_user()
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
        $required = ['nombre_user', 'role_id_user', 'estatus_user', 'password_user'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios.'
                ]);
                return;
            }
        }
    
        // Sanitizar entradas
        $nombre = mb_strtoupper(trim($_POST['nombre_user']), 'UTF-8');
        $role_id = (int) $_POST['role_id_user'];
        $estatus = $_POST['estatus_user'];
        $password = trim($_POST['password_user']);
    
        // Validar longitud mínima de contraseña
        if (strlen($password) < 6) {
            echo json_encode([
                'status' => 'error',
                'message' => 'La contraseña debe tener al menos 6 caracteres.'
            ]);
            return;
        }
    
        try {
            $UserModel = $this->model('UserModel');
    
            // Validar duplicidad de nombre de usuario
            if ($UserModel->usernameExists($nombre)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El nombre de usuario ya está registrado.'
                ]);
                return;
            }
    
            // Hashear la contraseña de forma segura
            $hashedPassword = hash(ENCRYPT, $password);;
    
            // Crear el usuario
            $created = $UserModel->createUser($nombre, $hashedPassword, $role_id, $estatus);
    
            if ($created) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario creado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo registrar el usuario.'
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
    

    public function update_user()
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
        $required = ['user_id_user', 'nombre_user', 'role_id_user', 'estatus_user'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Todos los campos obligatorios deben estar completos.'
                ]);
                return;
            }
        }
    
        $user_id = (int) $_POST['user_id_user'];
        $nombre = mb_strtoupper(trim($_POST['nombre_user']), 'UTF-8');
        $role_id = (int) $_POST['role_id_user'];
        $estatus = trim($_POST['estatus_user']);  // "Activo" o "Inactivo"
        $password = isset($_POST['password_user']) ? trim($_POST['password_user']) : null;
    
        // Validar estatus permitido
        if (!in_array($estatus, ['Activo', 'Inactivo'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'El estatus debe ser "Activo" o "Inactivo".'
            ]);
            return;
        }
    
        try {
            $UserModel = $this->model('UserModel');
    
            // Validar duplicado (excluyendo al usuario actual)
            if ($UserModel->usernameExistsForOther($nombre, $user_id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El nombre de usuario ya está en uso por otro usuario.'
                ]);
                return;
            }
    
            // Si se proporciona nueva contraseña y es válida, hashearla
            $hashedPassword = null;
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'La contraseña debe tener al menos 6 caracteres.'
                    ]);
                    return;
                }
                $hashedPassword = hash(ENCRYPT, $password);;
            }
    
            // Actualizar
            $updated = $UserModel->updateUser($user_id, $nombre, $hashedPassword, $role_id, $estatus);
    
            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario actualizado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo actualizar el usuario.'
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
    



    public function delete_user()
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
    
        $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
    
        if ($user_id <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID de usuario inválido.'
            ]);
            return;
        }
    
        try {
            $UserModel = $this->model('UserModel');
    
            // ⚠️ Si quieres proteger algunos roles (como admin), verifica aquí:
            $user = $UserModel->getUserById($user_id);
            if (!$user) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El usuario no existe.'
                ]);
                return;
            }
    
            if ($user['protegido'] == 1) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Este usuario está protegido y no puede eliminarse.'
                ]);
                return;
            }
    
            $deleted = $UserModel->deleteUserById($user_id);
    
            if ($deleted) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo eliminar el usuario.'
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
