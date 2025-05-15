<?php
// Definir la zona horaria a Lima, Perú
date_default_timezone_set('America/Lima');

// Database configuration (adjust with your actual database credentials)
/*
define('DB_HOST', 'autorack.proxy.rlwy.net:29010');
define('DB_USER', 'root');
define('DB_PASS', 'gbVXLuLgMfHSYYdrLqYIrdDcpPQgxtBG');
define('DB_NAME', 'railway');
*/
// Database configuration (adjust with your actual database credentials)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_sistema_matricula');

// Application URL
define('APP_URL', 'http://localhost/SistemaAsistencia-PPP2');
define('BASE_PATH', 'SistemaAsistencia-PPP2');


define('DEFAULT_CONTROLLER', 'HomeController');

// Nombre Empresa
define('NOMBRE_EMPRESA','Montalván');

//
define('TELEFONO_EMPRESA','+51 327 327 873');

//
define('CORREO_EMPRESA','montalvan@gmail.com');


define('DIRECCION_EMPRESA','Calle Los Deportistas 123, Cañete, Perú');


//
define('HORARIO_ATENCION','Lunes a Domingo, 9:00 AM - 6:00 PM');


define('STYLE_URL', 'http://localhost/sistema-reserva-deporte/public/css');

//REVISAR -  Encriptation pattron
define('ENCRYPT', 'sha256');

// Definir una constante para la hora actual
define('CURRENT_TIME', date('Y-m-d H:i:s'));

//Clave de Encriptacion
define('ENC_KEY', 'QGZ3c2dEbmFmdGVyYXNobG9ja3RoaXMwZGVsYXZlYQ==');

?>
