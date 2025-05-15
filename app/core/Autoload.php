<?php
spl_autoload_register(function ($className) {


    
    // Autoloader para clases 
    $path = "C:/xampp/htdocs/SistemaAsistencia-PPP2/app/controllers/";
    echo "La ruta es" . $path ."<br>";  // Ajustamos la ruta a la raíz del proyecto
    echo "El classname es" . $className ."<br>";  // Ajustamos la ruta a la raíz del proyecto
    $className = str_replace('\\', '/', $className);
    $file = $path . $className . '.php';
    echo "El archivo es: " . $file; // Debugging



    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Autoloader could not find class $className at $file");
    }
});
