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


    public function get_config_attendance()
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        try {

            $StudentModel = $this->model('StudentModel');
            $totalStudents = $StudentModel->getTotalStudents();

            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();

            $DayModel = $this->model('DayModel');
            $currentDate = date('Y-m-d');
            $dayActive = $DayModel->validDayActive($currentDate);

            if ($config) {
                echo json_encode([
                    'status' => 'success',
                    'setting' => $config,
                    'day_active' => $dayActive,
                    'total_students' => $totalStudents,
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontró configuración de asistencia.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error interno del servidor.',
                'details' => $e->getMessage()
            ]);
        }
    }



    public function register_new_day()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $currentDate = date('Y-m-d');
            $nameDay = date('l', strtotime($currentDate));

            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();


            if (!$config || !isset($config['entry_time'], $config['exit_time'], $config['time_tolerance'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Faltan parámetros de configuración.'
                ]);
                return;
            }


            $AttendanceModel = $this->model('AttendanceModel');
            $result = $AttendanceModel->registerNewDay(
                $currentDate,
                $nameDay,
                $config['entry_time'],
                $config['exit_time'],
                $config['time_tolerance']
            );

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Nuevo día de asistencia registrado exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al registrar el nuevo día de asistencia.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
        }
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