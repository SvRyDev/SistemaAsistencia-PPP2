<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto


    public function index()
    {
        $view = "student/list";
        $data = [
            'view' => $view,
            'title' => 'Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];

        View::render($view, $data,  $this->layout);
    }


    public function show()
    {
        $studentModel = $this->model('StudentModel');
        $allStudents = $studentModel->getAllStudents();

        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode($allStudents);
            return;
        }
    }

    public function view_import_data()
    {
        $view = "student/import_data";
        $data = [
            'view' => $view,
            'title' => 'Importar Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];
        View::render($view, $data, $this->layout);
    }

    public function read_from_Excel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
            $fileTmpPath = $_FILES['excelFile']['tmp_name'];
            $fileName = $_FILES['excelFile']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    
            $allowedExtensions = ['xls', 'xlsx'];
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo "<p style='color:red;'>Archivo no permitido. Solo .xls y .xlsx</p>";
                exit;
            }
    
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true); // devuelve array con letras como claves
    
                $expectedHeaders = ['codigo', 'nombre', 'apellido', 'edad'];
    
                // Obtener encabezados (primera fila del archivo)
                $headers = array_map('strtolower', array_values($rows[1])); // Primera fila
                $missing = array_diff($expectedHeaders, $headers);
    
                if (!empty($missing)) {
                    echo "<p style='color:red;'>Faltan columnas requeridas: " . implode(', ', $missing) . "</p>";
                    return;
                }
    
                // Mostrar tabla si encabezados válidos
                echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
                foreach ($rows as $index => $row) {
                    echo '<tr>';
                    foreach ($row as $cell) {
                        echo '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } catch (Exception $e) {
                echo '<p style="color:red;">Error leyendo el archivo: ' . $e->getMessage() . '</p>';
            }
        } else {
            echo '<p>No se recibió ningún archivo.</p>';
        }
    }
    
}
