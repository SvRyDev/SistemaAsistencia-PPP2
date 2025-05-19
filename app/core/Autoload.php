<?php
spl_autoload_register(function ($className) {

    // Autoloader para clases 
    $path = __DIR__ . '/';  // Ajustamos la ruta a la raíz del proyecto
    $className = str_replace('\\', '/', $className);
    $file = $path . $className . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Autoloader could not find class $className at $file.");
    }
});