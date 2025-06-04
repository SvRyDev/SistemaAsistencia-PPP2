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
                $spreadsheet = IOFactory::load($fileTmpPath);
                $worksheet = $spreadsheet->getActiveSheet();

                echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
                foreach ($worksheet->getRowIterator() as $row) {
                    echo '<tr>';
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    foreach ($cellIterator as $cell) {
                        echo '<td>' . htmlspecialchars($cell->getValue()) . '</td>';
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
