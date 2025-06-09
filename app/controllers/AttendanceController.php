<?php


class AttendanceController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "attendance.control";
        $data = [
            'view_js' => $view,
            'title' => 'Asistencia de Estudiante',
            'message' => 'Esta es la página de asistencia.'
        ];

        View::render($view, $data, $this->layout);
    }

    public function open_attendance()
    {
        $view = "attendance.register";
        $data = [
            'view_js' => $view,
            'title' => 'Aperturar Asistencia',
            'message' => 'Esta es la página para aperturar asistencia.'
        ];

        View::renderWithoutLayout($view, $data, $this->layout);
    }


    public function register_attendance()
    {
        if (!isAjax()) {
            return;
        }
    
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigoEstudiante = $_POST['codigo'];
    
            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByCode($codigoEstudiante);
    
            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado con el código proporcionado.'
                ]);
                return;
            }
    

            echo json_encode([
                'status' => 'success',
                'message' => 'Asistencia registrada para el estudiante.',
                'student' => $student
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código de estudiante no proporcionado.'
            ]);
        }
    }
    

}