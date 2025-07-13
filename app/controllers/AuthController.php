<?php

class AuthController extends Controller
{

    public $layout = 'auth'; // Establecer el layout por defecto

    public function view_login()
    {
        $view = "main.login";
        $data = [
            'view_js' => $view,
            'title' => 'Login',
            'message' => 'Esta es la página de incio del Dashboard.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function auth()
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

        $usuario = trim($_POST['usuario'] ?? '');
        $clave = trim($_POST['clave'] ?? '');

        if (empty($usuario) || empty($clave)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Debe ingresar usuario y clave.'
            ]);
            return;
        }

        try {
            $AuthModel = $this->model('AuthModel');
            $user = $AuthModel->getUserByNombre($usuario);

       

            if (!$user) {
                echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
                return;
            }


            $hashedInput = hash(ENCRYPT, $clave);

            if ($hashedInput !== $user['password']) {
                echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta. ']);
                return;
            }


            if ($user['estatus'] !== 'Activo') {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El usuario está inactivo.'
                ]);
                return;
            }

            // Iniciar sesión, por ejemplo:
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['rol_id'] = $user['role_id'];
            $_SESSION['rol_nombre'] = $user['rol_nombre'];
            $_SESSION['rol_icono'] = $user['rol_icono'];
            $_SESSION['rol_color_badge'] = $user['rol_color_badge'];

            $arrayPermisos = $this->model('AuthModel')->getPermissionsDetalisByRoleId($user['role_id']);

            $_SESSION['permissions'] = $arrayPermisos;

            echo json_encode([
                'status' => 'success',
                'message' => 'Inicio de sesión exitoso.',
                'redirect' => APP_URL . '/home'
                
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

    public function logout()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }
    
        session_unset();       // Limpia todas las variables de sesión
        session_destroy();     // Destruye la sesión
    
        echo json_encode([
            'status' => 'success',
            'message' => 'Sesión cerrada exitosamente.',
            'redirect' => APP_URL . '/login'
        ]);
    }
    
}
