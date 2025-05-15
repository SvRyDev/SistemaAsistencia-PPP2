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

            // Ubicación del archivo del controlador
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';

            echo "Pasando por el controlador: $controllerFile<br>";

            // Verificar si el archivo existe
            if (file_exists($controllerFile)) {
                echo "Se encontró el archivo :D<br>";
                require_once $controllerFile;

                // Verificar si la clase existe
                if (class_exists($controller)) {
                    $controllerInstance = new $controller();

                    // Verificar si la acción existe
                    if (method_exists($controllerInstance, $action)) {
                        // Llamar la acción con parámetros si es necesario
                        $params = []; // Define this as needed or extract parameters from the URL
                        call_user_func_array([$controllerInstance, $action], $params);
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
