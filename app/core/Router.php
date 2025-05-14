<?php

class Router
{
    private $routes = [];

    // Registrar una nueva ruta
    public function addRoute($method, $route, $controllerAction)
    {
        $this->routes[$method][$route] = $controllerAction;
    }

    // Ejecutar la ruta correspondiente
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtener el protocolo (http o https)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        // Obtener el dominio
        $host = $_SERVER['HTTP_HOST'];

        // Obtener la URI (ruta y parámetros)
        $request_uri = $_SERVER['REQUEST_URI'];

        // Combinar todo para obtener la URL completa
        $full_url = $protocol . '://' . $host . $request_uri;

        // Eliminar la base de la URL (SistemaAsistencia-PPP2) de la URI
        $full_url = str_replace(APP_URL, '', $full_url);

        // Eliminar parámetros adicionales (después de ?)
        $full_url = strtok($full_url, '?');

        // Verificar si la ruta existe
        if (isset($this->routes[$method][$full_url])) {
            list($controller, $action) = explode('@', $this->routes[$method][$full_url]);
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';


            if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    call_user_func_array([$controller, $actionName], $params);
                } else {
                    die("Action $actionName does not exist in controller $controllerName.");
                }
            } else {
                die("Controller class $controllerName does not exist.");
            }
        } else {
            die("Controller file for $controllerName does not exist.");
        }

            
        } else {
            // Ruta no encontrada
            echo ", Error: La ruta no existe. La lista de rutas registradas es: " . implode(", ", array_keys($this->routes[$method])) . " y la base es: " . $full_url;
        }
    }
}
