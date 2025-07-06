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
        $sqlFilename = 'backup_' . date('Ymd_His') . '.sql';
        $sqlFilepath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $sqlFilename;

        $zipFilename = $sqlFilename . '.zip';
        $zipFilepath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFilename;

        // Ruta a mysqldump (definida en tu config)
        $mysqldump = MYSQLDUMP_PATH;
        $passwordPart = DB_PASS !== '' ? '-p' . DB_PASS : '';

        $command = "$mysqldump -u " . DB_USER . " {$passwordPart} " . DB_NAME . " > " . escapeshellarg($sqlFilepath);
        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($sqlFilepath)) {
            http_response_code(500);
            echo "Error al generar el backup. Código: $resultCode<br>Comando: $command<br>Salida:<br>" . implode("<br>", $output);
            return;
        }

        // Crear archivo ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipFilepath, ZipArchive::CREATE) !== TRUE) {
            unlink($sqlFilepath); // limpiar
            http_response_code(500);
            echo "No se pudo crear el archivo ZIP.";
            return;
        }

        $zip->addFile($sqlFilepath, $sqlFilename);
        $zip->close();
        unlink($sqlFilepath); // eliminar el .sql después de comprimirlo

        // Descargar ZIP
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
        header('Content-Length: ' . filesize($zipFilepath));
        header('Pragma: public');
        readfile($zipFilepath);
        unlink($zipFilepath); // limpiar zip después de enviar

        // ✅ Registrar fecha de exportación
        $configModel = $this->model('SettingModel');
        $configModel->updateDateExport();

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

        if (!in_array($extension, ['sql', 'zip'])) {
            http_response_code(400);
            echo "Solo se aceptan archivos .sql o .zip";
            return;
        }

        // Verifica tamaño
        if ($_FILES['archivo_backup']['size'] > 10 * 1024 * 1024) { // 10MB
            http_response_code(400);
            echo "El archivo es demasiado grande.";
            return;
        }

        // Leer contenido del archivo
        if ($extension === 'sql') {
            $contenido = file_get_contents($tmp);
        } elseif ($extension === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($tmp) === true) {
                $contenido = '';
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    if (strtolower(pathinfo($entry, PATHINFO_EXTENSION)) === 'sql') {
                        $contenido = $zip->getFromIndex($i);
                        break;
                    }
                }
                $zip->close();

                if (!$contenido) {
                    http_response_code(400);
                    echo "El archivo ZIP no contiene un archivo .sql válido.";
                    return;
                }
            } else {
                http_response_code(400);
                echo "No se pudo abrir el archivo ZIP.";
                return;
            }
        }

        // Validaciones del contenido .sql
        if (trim($contenido) === '') {
            http_response_code(400);
            echo "El archivo está vacío.";
            return;
        }

        if (preg_match('/\b(DROP DATABASE|CREATE DATABASE|USE)\b/i', $contenido)) {
            http_response_code(400);
            echo "El archivo contiene comandos no permitidos.";
            return;
        }

        try {
            $model = $this->model('BackupModel');
            $model->importarSQL($contenido);

            // ✅ Actualizar fecha de importación
            $configModel = $this->model('SettingModel');
            $configModel->updateDateImport();

            echo "Backup importado correctamente.";
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public function reset()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método no permitido']);
            return;
        }

        try {
            $model = $this->model('BackupModel');
            $model->resetSystem();

            echo json_encode(['message' => 'Sistema restaurado correctamente.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error al restaurar: ' . $e->getMessage()]);
        }
    }





}
