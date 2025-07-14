<?php

class DashboardController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }


    public function get_data_attendance_percent()
    {
        if (!isAjax()) {
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Metodo no admitido.'
            ]);
            return;
        }

        $attendanceModel = $this->model('AttendanceModel');
        $sumaryDailyAttendance = $attendanceModel->getDailyAttendanceSummary();

        echo json_encode([
            'status' => 'success',
            'message' => 'Reporte generado exitosamente.',
            'attendance_summary' => $sumaryDailyAttendance,

            
        ]);
    }

}
