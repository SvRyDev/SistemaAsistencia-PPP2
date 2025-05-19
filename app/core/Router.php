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

        // Eliminar la base de la URL (APP_URL)
        $full_url = str_replace(APP_URL, '', $full_url);

        // Eliminar parámetros adicionales (después de ?)
        $full_url = strtok($full_url, '?');

        $found = false;
        $matches = [];

        if (!isset($this->routes[$method])) {
            die("No routes registered for method $method");
        }

        // Buscar coincidencias entre las rutas registradas
        foreach ($this->routes[$method] as $route => $controllerAction) {
            // Convertir {param} en un grupo regex (por ejemplo: /usuarios/{id} => /usuarios/([a-zA-Z0-9_-]+))
            $routePattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9_-]+)', $route);
            $routePattern = '#^' . $routePattern . '$#';

            if (preg_match($routePattern, $full_url, $matches)) {
                array_shift($matches); // Eliminar la coincidencia completa
                list($controller, $action) = explode('@', $controllerAction);
                $found = true;
                break;
            }
        }

        if ($found) {
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
       
                require_once $controllerFile;

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();

                    if (method_exists($controllerInstance, $action)) {
                        call_user_func_array([$controllerInstance, $action], $matches);
                    } else {
                        die("Action $action not found in $controller class");
                    }
                } else {
                    die("Controller class $controller not found");
                }
            } else {
                die("Controller file $controllerFile not found");
            }
        } else {
            die("Route not found for $method $full_url");
        }
    }
}
