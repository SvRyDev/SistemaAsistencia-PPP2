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
        $mes = isset($_POST['mes']) ? (int) $_POST['mes'] : null;

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


    public function record_by_group()
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
            !isset($_POST['grado_id']) ||
            !isset($_POST['seccion_id']) ||
            !isset($_POST['mes'])
        ) {

            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan parámetros requeridos.'
            ]);
            return;
        }

        // Extraer datos
        $gradoId = (int) $_POST['grado_id'];
        $seccionId = (int) $_POST['seccion_id'];
        $mes = (int) $_POST['mes'];

        // Validaciones
        if ($gradoId <= 0 || $seccionId <= 0 || $mes < 1 || $mes > 12) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Parámetros inválidos.'
            ]);
            return;
        }

        try {
            // Obtener datos
            $ReportModel = $this->model('ReportModel');
            $DayModel = $this->model('DayModel');
            $StudentModel = $this->model('StudentModel');

            $students = $StudentModel->getStudentsByGradeAndSection($gradoId, $seccionId);
            $records = $ReportModel->getRecordByGroup($gradoId, $seccionId, $mes);
            $daysActive = $DayModel->getAllDays(); // Si deseas mostrar calendario también

            echo json_encode([
                'status' => 'success',
                'message' => 'Reporte de grupo generado exitosamente.',
                'records' => $records,
                'days_active' => $daysActive,
                'students' => $students
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error inesperado al generar el reporte.',
                'error' => $e->getMessage() // Quitar en producción
            ]);
        }
    }


    public function load_data_for_group_filter()
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

        try {
            // Cargar modelos
            $SectionModel = $this->model('SectionModel');
            $GradeModel = $this->model('GradeModel');

            // Obtener datos
            $secciones = $SectionModel->getAllSections();
            $grados = $GradeModel->getAllGrades();

            // Validar si hay datos
            if (empty($grados) || empty($secciones)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron grados o secciones.'
                ]);
                return;
            }

            // Respuesta OK
            echo json_encode([
                'status' => 'success',
                'grados' => $grados,
                'secciones' => $secciones
            ]);
        } catch (Exception $e) {
            // Manejo de excepciones
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ocurrió un error al cargar los datos.',
                'error' => $e->getMessage() // opcional, quitar en producción
            ]);
        }
    }


}
