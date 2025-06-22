<?php

class ReportController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "report.by_student";
        $data = [
            'view_js' => $view,
            'title' => 'Reporte por Estudiante',
            'message' => 'Esta es la página de reporte.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function view_student_report()
    {
        $view = "report.by_student";
        $data = [
            'view_js' => $view,
            'title' => 'Reporte por Estudiante',
            'message' => 'Esta es la página de reporte.'
        ];

        View::render($view, $data, $this->layout);
    }

    
    public function view_group_report()
    {
        $view = "report.by_group";
        $data = [
            'view_js' => $view,
            'title' => 'Reporte por Sección',
            'message' => 'Esta es la página de reporte.'
        ];

        View::render($view, $data, $this->layout);
    }
    public function record_by_student()
    {
        if (!isAjax()) {
            return;
        }
    
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['code'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código de estudiante no proporcionado.'
            ]);
            return;
        }
    
        $codigoEstudiante = $_POST['code'];
    
        $StudentModel = $this->model('StudentModel');
        $student = $StudentModel->getStudentByCode($codigoEstudiante);
    
        if (!$student || empty($student['estudiante_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Estudiante no encontrado con el código proporcionado.'
            ]);
            return;
        }
    
        $studentId = $student['estudiante_id'];
    
        $AttendanceModel = $this->model('AttendanceModel');
        $attendanceRecords = $AttendanceModel->getRecordByStudent($studentId);
    
        $DayModel = $this->model('DayModel');
        $daysActive = $DayModel->getAllDays();
    
        echo json_encode([
            'status' => 'success',
            'message' => 'Reporte generado exitosamente.',
            'student' => $student,
            'attendance_records' => $attendanceRecords,
            'days_active' => $daysActive,
        ]);
    }
    


    public function get_by_student_code()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigoEstudiante = $_POST['codigo'];

            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByCode($codigoEstudiante);

            $AttendanceModel = $this->model('AttendanceModel');
            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado con el código proporcionado.'
                ]);
                return;
            }

            // Aquí puedes agregar la lógica para generar el reporte del estudiante
            // Por ejemplo, obtener las asistencias, calificaciones, etc.

            echo json_encode([
                'status' => 'success',
                'message' => 'Reporte generado exitosamente.',
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
