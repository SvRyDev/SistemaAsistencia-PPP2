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

    public function report_by_student()
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
