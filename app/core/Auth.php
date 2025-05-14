<?php 
// Verificar si el usuario está autenticado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirigir si no está autenticado
    exit();
}


// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['role'] !== 'admin') {
    echo "No tienes permiso para acceder a esta página";
    exit();
}
