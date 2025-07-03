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

    public function get_list_attendance_last_day()
    {
        // Validar si la solicitud es AJAX
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no autorizado.'
            ]);
            return;
        }

        // Validar método HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
            return;
        }

        // Definir tipo de respuesta
        header('Content-Type: application/json');

        try {
            $currentDate = date('Y-m-d');

            $DayModel = $this->model('DayModel');
            $dayActive = $DayModel->validDayActive($currentDate);

            // Verificar si se obtuvo un día activo válido
            if (!$dayActive || empty($dayActive['dia_fecha_id'])) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontró un día activo válido.'
                ]);
                return;
            }

            $AttendanceModel = $this->model('AttendanceModel');
            $listAttendance = $AttendanceModel->getAllAttendanceByDate($dayActive['dia_fecha_id']);

            echo json_encode([
                'status' => 'success',
                'day_active' => $dayActive,
                'list_attendance' => $listAttendance
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error interno del servidor.',
                'details' => $e->getMessage()
            ]);
        }
    }



    public function get_list_status_attendance()
    {
        if (!isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Petición no válida.']);
            return;
        }

        header('Content-Type: application/json');

        $StatusAModel = $this->model('StatusAttendanceModel');
        $allStatus = $StatusAModel->getAllStatus();

        echo json_encode([
            'status' => 'success',
            'estados' => $allStatus
        ]);
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
        if (!isAjax()) return;

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigoEstudiante = $_POST['codigo'];

            // Obtener estudiante
            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByCode($codigoEstudiante);

            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado con el código proporcionado.'
                ]);
                return;
            }

            // Obtener configuración
            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();

            date_default_timezone_set($config['time_zone'] ?? 'America/Lima');

            $currentDate = date('Y-m-d');
            $currentTime = date('H:i');

            // Validar si el día está activo
            $DayModel = $this->model('DayModel');
            $dayActive = $DayModel->validDayActive($currentDate);

            if (!$dayActive) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El día actual no está habilitado para registrar asistencia.'
                ]);
                return;
            }

            // Obtener estados de asistencia
            $StatusAttendaceModel = $this->model('StatusAttendanceModel');
            $statusAttendance = $StatusAttendaceModel->getAllStatus();

            // Mapear estados por nombre en minúscula
            $estadoMap = [];
            foreach ($statusAttendance as $estadoData) {
                $estadoMap[strtolower($estadoData['nombre_estado'])] = $estadoData;
            }

            // Configuración de horario
            $entry_time    = $config['entry_time'];               // Ej: 07:30
            $exit_time     = $config['exit_time'];                // Ej: 13:00
            $tolerance_min = intval($config['time_tolerance']);   // Ej: 10

            $max_punctual_time = date("H:i", strtotime("+{$tolerance_min} minutes", strtotime($entry_time)));

            // Determinar estado según la hora
            if ($currentTime <= $max_punctual_time) {
                $estadoSeleccionado = $estadoMap['presente'] ?? null;
            } elseif ($currentTime > $max_punctual_time && $currentTime <= $exit_time) {
                $estadoSeleccionado = $estadoMap['tarde'] ?? null;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Fuera del horario permitido para registrar asistencia. El current time es ' . $currentTime . 'y el max_puntual_time es ' . $max_punctual_time
                ]);
                return;
            }

            if (!$estadoSeleccionado) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo determinar el estado de asistencia.'
                ]);
                return;
            }

            // Registrar asistencia
            $AttendanceModel = $this->model('AttendanceModel');



            // Verificar si ya tiene asistencia registrada para hoy
            $yaRegistrado = $AttendanceModel->getRegisteredByStudentAndDate(
                $student['estudiante_id'],
                $dayActive['dia_fecha_id']
            );

            if ($yaRegistrado) {
                echo json_encode([
                    'status' => 'warning',
                    'message' => 'Este estudiante ya ha registrado su asistencia hoy.',
                    'student' => $student,
                    'hora_registrada' => $yaRegistrado['hora_entrada'],

                ]);
                return;
            }


            $AttendanceModel->registerAttendance(
                $student['estudiante_id'],
                $dayActive['dia_fecha_id'],
                $currentTime,
                $estadoSeleccionado['id_estado']
            );

            echo json_encode([
                'status' => 'success',
                'message' => 'Asistencia registrada correctamente.',
                'student' => $student,
                'estado' => $estadoSeleccionado,
                'hora_actual' => $currentTime,
                'limite_puntualidad' => $max_punctual_time
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código de estudiante no proporcionado.'
            ]);
        }
    }

    public function save_attendance()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Acceso no permitido']);
            return;
        }

        // Obtener datos del POST
        $estudianteId = $_POST['estudiante_id'] ?? null;
        $horaEntrada = $_POST['hora_entrada'] ?? null;
        $estadoAsistenciaId = $_POST['estado_asistencia_id'] ?? null;
        $observacion = $_POST['observacion'] ?? null;

        // Validación básica
        if (!$estudianteId || !$horaEntrada || !$estadoAsistenciaId) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan campos obligatorios (estudiante, hora o estado).'
            ]);
            return;
        }

        // Obtener fecha actual del servidor
        $currentDate = date('Y-m-d');


        // Validar si el día está activo
        $DayModel = $this->model('DayModel');
        $dayActive = $DayModel->validDayActive($currentDate);

        if (!$dayActive) {
            echo json_encode([
                'status' => 'error',
                'message' => 'El día actual no está habilitado para registrar asistencia.'
            ]);
            return;
        }
        $fecha_id = $dayActive['dia_fecha_id'];

        // Modelo
        $asistenciaModel = $this->model('AttendanceModel');
        $registro = $asistenciaModel->getRegisteredByStudentAndDate($estudianteId, $fecha_id);

        if ($registro) {
            // Actualizar asistencia
            $updated = $asistenciaModel->updateAttendance(
                $estudianteId,
                $fecha_id, // asegúrate del nombre correcto de la PK
                $horaEntrada,
                $estadoAsistenciaId,
                $observacion
            );

            echo json_encode([
                'status' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Asistencia actualizada correctamente.' : 'Error al actualizar asistencia.'
            ]);
        } else {
            // Insertar nueva asistencia
            $created = $asistenciaModel->createAttendance(
                $estudianteId,
                $fecha_id,
                $horaEntrada,
                $estadoAsistenciaId,
                $observacion
            );

            echo json_encode([
                'status' => $created ? 'success' : 'error',
                'message' => $created ? 'Asistencia registrada correctamente.' : 'Error al registrar asistencia.'
            ]);
        }
    }


    public function edit_attendance_registered()
    {
        if (!isAjax()) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Acceso no permitido.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método inválido.'
            ]);
            return;
        }

        $estudianteId = $_POST['estudiante_id'] ?? null;
        $fecha = date('Y-m-d'); // Fecha actual del servidor en formato YYYY-MM-DD


        if (!$estudianteId || !$fecha) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Datos incompletos.'
            ]);
            return;
        }

        // Buscar el ID de día correspondiente a la fecha
        $DayModel = $this->model('DayModel');
        $dia = $DayModel->validDayActive($fecha); // Devuelve fila con dia_fecha_id, etc.

        if (!$dia || empty($dia['dia_fecha_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No hay día registrado con esa fecha.'
            ]);
            return;
        }

        $diaFechaId = $dia['dia_fecha_id'];

        // Consultar si ya existe un registro de asistencia
        $AttendanceModel = $this->model('AttendanceModel');
        $asistencia = $AttendanceModel->getRegisteredByStudentAndDate($estudianteId, $diaFechaId);

        if ($asistencia) {
            echo json_encode([
                'status' => 'found',
                'message' => 'Asistencia ya registrada.',
                'data' => [
                    'asistencia_id' => $asistencia['asistencia_estudiante_id'],
                    'hora_entrada' => $asistencia['hora_entrada'],
                    'hora_salida' => $asistencia['hora_salida'],
                    'estado_asistencia_id' => $asistencia['estado_asistencia_id'],
                    'observacion' => $asistencia['observacion']
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'not_found',
                'message' => 'No hay asistencia registrada. Puedes crearla.',
            ]);
        }
    }
}
