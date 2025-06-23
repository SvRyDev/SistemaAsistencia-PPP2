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
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['dni_est'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código de estudiante no proporcionado.'
            ]);
            return;
        }
    
        $DNIEstudiante = $_POST['dni_est'];
        $mes = isset($_POST['mes']) ? (int)$_POST['mes'] : null;
    
        if (!$mes || $mes < 1 || $mes > 12) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Mes inválido.'
            ]);
            return;
        }
    
        $StudentModel = $this->model('StudentModel');
        $student = $StudentModel->getStudentByDNI($DNIEstudiante);
    
        if (!$student || empty($student['estudiante_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Estudiante no encontrado con el código proporcionado.'
            ]);
            return;
        }
    
        $studentId = $student['estudiante_id'];
    
        $ReportModel = $this->model('ReportModel');
        $attendanceRecords = $ReportModel->getRecordByStudent($studentId, $mes);
    
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
    


    public function get_by_student_dni()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dni_est'])) {
            $DNIEstudiante = $_POST['dni_est'];

            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByDNI($DNIEstudiante);

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
