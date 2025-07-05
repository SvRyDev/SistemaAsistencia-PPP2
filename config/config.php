<?php
// Definir la zona horaria a Lima, Perú
date_default_timezone_set('America/Lima');


// Database configuration (adjust with your actual database credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_asistencia_estudiantes');

// Application URL
define('APP_URL', 'http://localhost/SistemaAsistencia-PPP2');
define('BASE_PATH', 'SistemaAsistencia-PPP2');

// Ruta de PATH Mysql
define('MYSQLDUMP_PATH', 'C:\\xampp\\mysql\\bin\\mysqldump.exe');


define('DEFAULT_CONTROLLER', 'HomeController');

// Nombre Empresa
define('NOMBRE_EMPRESA','SisAsist');

//
define('TELEFONO_EMPRESA','+51 327 327 873');

//
define('CORREO_EMPRESA','seerviart@gmail.com');


define('DIRECCION_EMPRESA','Calle Comercio s/n, San Luis, Cañete');

//
define('HORARIO_ATENCION','Lunes a Viernes de 8:00 am a 5:00 pm');


//REVISAR -  Encriptation pattron
define('ENCRYPT', 'sha256');

// Definir una constante para la hora actual
define('CURRENT_TIME', date('Y-m-d H:i:s'));

//Clave de Encriptacion
define('ENC_KEY', 'QGZ3c2dEbmFmdGVyYXNobG9ja3RoaXMwZGVsYXZlYQ==');

?>
