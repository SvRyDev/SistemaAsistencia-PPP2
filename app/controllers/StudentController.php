<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto


    public function index()
    {
        $view = "student.list";
        $data = [
            'view_js' => $view,
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


    public function get_total_students()
    {
        $studentModel = $this->model('StudentModel');
        $totalStudents = $studentModel->getTotalStudents();

        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode($totalStudents);
            return;
        }
    }

    public function view_import_data()
    {
        $view = "student.import_data";
        $data = [
            'view_js' => $view,
            'title' => 'Importar Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];
        View::render($view, $data, $this->layout);
    }


    public function read_from_Excel()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
            $fileTmpPath = $_FILES['excelFile']['tmp_name'];
            $fileName = $_FILES['excelFile']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowedExtensions = ['xls', 'xlsx'];
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Archivo no permitido. Solo .xls y .xlsx']);
                return;
            }

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);

                $expectedHeaders = ['dni', 'nombres', 'apellidos', 'grado', 'sección'];
                $headers = array_map('strtolower', array_values($rows[1]));
                $missing = array_diff($expectedHeaders, $headers);

                if (!empty($missing)) {
                    http_response_code(422);
                    echo json_encode(['status' => 'error', 'message' => 'Faltan columnas requeridas: ' . implode(', ', $missing)]);
                    return;
                }

                // Optional: puedes filtrar datos, o simplemente devolverlos
                $data = array_slice($rows, 1); // quitar encabezado

                echo json_encode(['status' => 'success', 'message' => 'Archivo leído correctamente.', 'data' => $data]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Error leyendo el archivo: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'No se recibió ningún archivo.']);
        }
    }


    public function import_data_file()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['alumnos']) && is_array($data['alumnos']) && count($data['alumnos']) > 0) {

            // Validar columnas requeridas para cada alumno
            $requiredColumns = ['dni', 'nombres', 'apellidos', 'grado', 'seccion'];
            foreach ($data['alumnos'] as $index => $alumno) {
                foreach ($requiredColumns as $col) {
                    if (!isset($alumno[$col]) || empty($alumno[$col])) {
                        http_response_code(400);
                        echo json_encode([
                            "status" => "error",
                            "message" => "Falta o está vacío el campo '$col' en el registro #" . ($index + 1)
                        ]);
                        return;
                    }
                }
            }

            $studentModel = $this->model('StudentModel');
            $result = $studentModel->addMultipleStudents($data['alumnos']);

            if (!$result) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Error al insertar los estudiantes"]);
                return;
            }

            echo json_encode(["status" => "success", "message" => "Datos importados correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
        }
    }



}
