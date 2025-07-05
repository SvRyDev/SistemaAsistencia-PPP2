<?php

class BackupController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "backup.options";
        $data = [
            'view_js' => $view,
            'title' => 'Copias de seguridad',
            'message' => 'Esta es la página de backups.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function export()
    {
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $filepath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        // Ruta absoluta a mysqldump en XAMPP
        $mysqldump = MYSQLDUMP_PATH;

        // Si usas contraseña, colócala aquí. Si no, deja vacío.
        $passwordPart = DB_PASS !== '' ? '-p' . DB_PASS : '';

        // Comando con ruta completa
        $command = "$mysqldump -u " . DB_USER . " {$passwordPart} " . DB_NAME . " > " . escapeshellarg($filepath);

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($filepath)) {
            http_response_code(500);
            echo "Error al generar el backup. Código: $resultCode<br>Comando: $command<br>Salida:<br>" . implode("<br>", $output);
            return;
        }

        // Descargar archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');
        readfile($filepath);
        unlink($filepath);
        exit;
    }




    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo_backup'])) {
            http_response_code(400);
            echo "Solicitud inválida.";
            return;
        }

        $nombre = $_FILES['archivo_backup']['name'];
        $tmp = $_FILES['archivo_backup']['tmp_name'];
        $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        if ($extension !== 'sql') {
            http_response_code(400);
            echo "Solo se aceptan archivos .sql";
            return;
        }

        $contenido = file_get_contents($tmp);
        if (!$contenido) {
            http_response_code(400);
            echo "No se pudo leer el archivo.";
            return;
        }

        // Validación básica contra comandos peligrosos
        if (preg_match('/\b(DROP DATABASE|CREATE DATABASE|USE)\b/i', $contenido)) {
            http_response_code(400);
            echo "El archivo contiene comandos no permitidos.";
            return;
        }

        if ($_FILES['archivo_backup']['size'] > 5 * 1024 * 1024) { // 5MB
            http_response_code(400);
            echo "El archivo es demasiado grande.";
            return;
        }

        if (trim($contenido) === '') {
            http_response_code(400);
            echo "El archivo está vacío.";
            return;
        }
        
        try {
            $model = $this->model('BackupModel');
            $model->importarSQL($contenido);
            echo "Backup importado correctamente.";
        } catch (Exception $e) {
            http_response_code(500);
            echo "❌ Error: " . $e->getMessage();
        }
    }
    
    


}
