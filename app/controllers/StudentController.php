<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }
    public $layout = 'dashboard'; // Establecer el layout por defecto


    public function index()
    {
        $view = "student.list";
        $data = [
            'view_js' => $view,
            'title' => 'Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function view_list_students()
    {
        $view = "student.list";
        $data = [
            'view_js' => $view,
            'title' => 'Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function view_manage_students()
    {
        $view = "student.manage";
        $data = [
            'view_js' => $view,
            'title' => 'Gestionar Estudiantes',
            'message' => 'Esta es la página de gestión de estudiantes.'
        ];

        View::render($view, $data, $this->layout);
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



    public function get_data_student()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        // Validar POST
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['estudiante_id'])  // ← este debe coincidir con el nombre del parámetro que envías
        ) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan parámetros requeridos.'
            ]);
            return;
        }

        // Extraer datos
        $estudianteId = (int) $_POST['estudiante_id'];

        try {
            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentById($estudianteId);


            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado.'
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Estudiante cargado exitosamente.',
                'student' => $student
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado al cargar estudiante.',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function get_detail_student()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID del estudiante no proporcionado.'
            ]);
            return;
        }

        $estudianteId = (int) $_POST['id'];

        if ($estudianteId <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID inválido.'
            ]);
            return;
        }

        // Carga del modelo
        $StudentModel = $this->model('StudentModel');
        $AttendanceModel = $this->model('AttendanceModel');

        // Buscar estudiante
        $estudiante = $StudentModel->getViewStudentById($estudianteId);

        if (!$estudiante) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Estudiante no encontrado.'
            ]);
            return;
        }

        // Obtener los últimos 15 registros de asistencia
        $asistencias = $AttendanceModel->getLastAttendanceByStudent($estudianteId, 15);

        echo json_encode([
            'status' => 'success',
            'estudiante' => $estudiante,
            'asistencias' => $asistencias
        ]);
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

                $expectedHeaders = ['nombres', 'apellidos', 'dni', 'grado', 'sección'];
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
            $requiredColumns = ['nombres', 'apellidos', 'dni', 'grado', 'seccion'];
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



            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();

            $academic_year = $config['academic_year'];


            $studentModel = $this->model('StudentModel');
            $result = $studentModel->addMultipleStudents($data['alumnos'], $academic_year);


            if (!$result['success']) {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
          
                    "message" => $result['error'] // 👈 Aquí aparece el mensaje real
                ]);
                return;
            }

            echo json_encode(["status" => "success", "message" => "Datos importados correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
        }
    }

    public function search_by_dni_or_name()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['query'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se recibió parámetro de búsqueda.'
            ]);
            return;
        }

        $query = trim($_POST['query']);

        if (strlen($query) < 2) {
            echo json_encode([
                'status' => 'error',
                'message' => 'El término de búsqueda es demasiado corto.'
            ]);
            return;
        }

        $StudentModel = $this->model('StudentModel');
        $resultados = $StudentModel->searchByDniOrName($query);

        if (empty($resultados)) {
            echo json_encode([
                'status' => 'success',
                'estudiantes' => [],
                'message' => 'No se encontraron coincidencias.'
            ]);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'estudiantes' => $resultados
        ]);
    }

    public function create_student()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        // Validación básica
        $required = ['dni_est', 'nombre_est', 'apellidos_est', 'grado_est', 'seccion_est'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios.'
                ]);
                return;
            }
        }


        $dni = trim($_POST['dni_est']);
        $nombres = mb_strtoupper(trim($_POST['nombre_est']), 'UTF-8');
        $apellidos = mb_strtoupper(trim($_POST['apellidos_est']), 'UTF-8');

        $grado_id = (int) $_POST['grado_est'];
        $seccion_id = (int) $_POST['seccion_est'];


        //Validacion de DNI
        if (!preg_match('/^\d{8}$/', $dni)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'El DNI debe tener 8 dígitos numéricos.'
            ]);
            return;
        }


        try {

            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();

            $academic_year = $config['academic_year'];

            $StudentModel = $this->model('StudentModel');


            // Validación de DNI duplicado
            if ($StudentModel->dniExists($dni)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El DNI ingresado ya está registrado.'
                ]);
                return;
            }


            $inserted = $StudentModel->createStudent(
                $nombres,
                $apellidos,
                $dni,
                $grado_id,
                $seccion_id,
                $academic_year
            );

            if ($inserted) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Estudiante registrado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo registrar al estudiante.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al registrar.',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function update_student()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        // Validación básica
        $required = ['estudiante_id', 'dni_est', 'nombre_est', 'apellidos_est', 'grado_est', 'seccion_est'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Todos los campos son obligatorios.'
                ]);
                return;
            }
        }

        $estudiante_id = (int) $_POST['estudiante_id'];
        $dni = trim($_POST['dni_est']);
        $nombres = mb_strtoupper(trim($_POST['nombre_est']), 'UTF-8');
        $apellidos = mb_strtoupper(trim($_POST['apellidos_est']), 'UTF-8');
        $grado_id = (int) $_POST['grado_est'];
        $seccion_id = (int) $_POST['seccion_est'];

        // Validación de DNI
        if (!preg_match('/^\d{8}$/', $dni)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'El DNI debe tener 8 dígitos numéricos.'
            ]);
            return;
        }

        try {
            $StudentModel = $this->model('StudentModel');

            // Validación de DNI duplicado
            if ($StudentModel->dniExists($dni, $estudiante_id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El DNI ingresado ya está registrado.'
                ]);
                return;
            }


            $updated = $StudentModel->updateStudent(
                $estudiante_id,
                $dni,
                $nombres,
                $apellidos,
                $grado_id,
                $seccion_id
            );

            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Estudiante actualizado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo actualizar al estudiante.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al actualizar.',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function delete_student()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['estudiante_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan parámetros requeridos.'
            ]);
            return;
        }

        $estudianteId = (int) $_POST['estudiante_id'];

        try {
            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentById($estudianteId);

            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado.'
                ]);
                return;
            }

            // Aquí haces la eliminación real

            $AttendanceModel = $this->model('AttendanceModel');
            $AttendanceModel->deleteByEstudianteId($estudianteId);


            $deleted = $StudentModel->deleteById($estudianteId);

            if ($deleted) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Estudiante eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo eliminar al estudiante.'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado al eliminar estudiante.',
                'error' => $e->getMessage()
            ]);
        }
    }

}
