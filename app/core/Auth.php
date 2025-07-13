<?php

class Auth
{
    // Verificar si el usuario está autenticado
    public static function checkAuth()
    {

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/login");
            exit();
        }



    }

    // Verificar si el usuario tiene un rol específico
    public static function checkRole($requiredRole)
    {

        if ($_SESSION['role'] !== $requiredRole) {
            http_response_code(403);
            die("No tienes permiso para acceder a esta página.");
        }
    }

    // Verificar si el usuario tiene un permiso específico
    public static function checkPermission($requiredPermission)
    {
        $userPermissions = $_SESSION['permissions'] ?? [];

        // Buscar el permiso en el array de permisos
        $hasPermission = false;
        foreach ($userPermissions as $permission) {
            if ($permission['nombre'] === $requiredPermission) {
                $hasPermission = true;
                break;
            }
        }

        // Si no se encuentra el permiso, denegar el acceso
        if (!$hasPermission) {
            http_response_code(403);
            die("No tienes permiso para realizar esta acción. La sesión es: " . json_encode($_SESSION['permissions'], JSON_PRETTY_PRINT));
        }
    }
}