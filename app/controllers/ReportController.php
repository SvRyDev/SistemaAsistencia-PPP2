<?php

class ReportController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "report.by_student";
        $data = [
            'view_js' => $view,
            'title' => 'Reportes',
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
            $codigoEstudiante = $_POST['code'];

            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByCode($codigoEstudiante);

            $studentId = $student['estudiante_id'] ?? null;

            if (!$studentId) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado con el código proporcionado.'
                ]);
                return;
            }

            $AttendanceModel = $this->model('AttendanceModel');
            $attendanceRecords = $AttendanceModel->getRecordByStudent($studentId);

            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado con el código proporcionado.'
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Reporte generado exitosamente.',
                'student' => $student,
                'attendance_records' => $attendanceRecords
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código de estudiante no proporcionado.'
            ]);
        }
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
