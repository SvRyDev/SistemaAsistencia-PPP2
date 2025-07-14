<?php


class AttendanceController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto
    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }
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

    public function view_fetch_list_by_day()
    {
        $view = "attendance.fetch";
        $data = [
            'view_js' => $view,
            'title' => 'Consulta Asistencia',
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


    public function fetch_by_filters()
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

        try {
            // Obtener los parámetros enviados
            $fecha = $_POST['fecha'] ?? null;
            $grado = $_POST['grado'] ?? null;
            $seccion = $_POST['seccion'] ?? null;


            // Validación: la fecha es obligatoria
            if (!$fecha) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'La fecha es obligatoria.']);
                return;
            }

            // Si grado o seccion están vacíos, interpretarlos como "todos"
            $grado = empty($grado) ? null : $grado;
            $seccion = empty($seccion) ? null : $seccion;

            $DayModel = $this->model('DayModel');
            $dia = $DayModel->validDayActive($fecha); // Esta función deberías tenerla

            if (!$dia || empty($dia['dia_fecha_id'])) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontró información para la fecha proporcionada.'
                ]);
                return;
            }

            $AttendanceModel = $this->model('AttendanceModel');
            $lista = $AttendanceModel->getAttendanceByFilter(
                $dia['dia_fecha_id'],
                $grado,
                $seccion
            );

            echo json_encode([
                'status' => 'success',
                'data' => $lista
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error del servidor.',
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


            $nameDay = ['Sunday'=>'Domingo','Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles','Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado'][date('l', strtotime($currentDate))];

 
            $SettingModel = $this->model('SettingModel');
            $config = $SettingModel->getConfig();
            $status = 1;

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
                $config['time_tolerance'],
                $status
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
        if (!isAjax())
            return;

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigoEstudiante = $_POST['codigo'];

            // Obtener estudiante
            $StudentModel = $this->model('StudentModel');
            $student = $StudentModel->getStudentByCode($codigoEstudiante);

            if (!$student) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado.'
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
            $entry_time = $config['entry_time'];               // Ej: 07:30
            $exit_time = $config['exit_time'];                // Ej: 13:00
            $tolerance_min = intval($config['time_tolerance']);   // Ej: 10

            $max_punctual_time = date("H:i", strtotime("+{$tolerance_min} minutes", strtotime($entry_time)));

            // Determinar estado según la hora
            if ($currentTime <= $max_punctual_time) {
                $estadoSeleccionado = $estadoMap['asistido'] ?? null;
            } elseif ($currentTime > $max_punctual_time && $currentTime <= $exit_time) {
                $estadoSeleccionado = $estadoMap['tarde'] ?? null;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Fuera del horario permitido para registrar asistencia.',
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
                    'message' => 'El estudiante ya está registrado.',
                    'student' => $student,
                    'hora_registrada' => $yaRegistrado['hora_entrada'],

                ]);
                return;
            }


            $AttendanceModel->createAttendance(
                $student['estudiante_id'],
                $dayActive['dia_fecha_id'],
                $currentTime,
                $estadoSeleccionado['id_estado']
            );

            echo json_encode([
                'status' => 'success',
                'message' => 'Asistencia registrada.',
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

    public function close_day_register_abstents()
    {
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

        // Modelos necesarios
        $StudentModel = $this->model('StudentModel');
        $AttendanceModel = $this->model('AttendanceModel');

        // Obtener todos los estudiantes activos
        $allStudents = $StudentModel->getAllStudents();

        // Obtener IDs de estudiantes con asistencia registrada hoy
        $presentStudentIds = $AttendanceModel->getIDStudentByDate($dayActive['dia_fecha_id']);

        $absentStudents = [];

        // Filtrar los estudiantes que no están en la lista de asistencias registradas
        foreach ($allStudents as $student) {
            if (!in_array($student['estudiante_id'], $presentStudentIds)) {
                $absentStudents[] = $student['estudiante_id'];
            }
        }

        // Registrar faltas
        foreach ($absentStudents as $studentId) {
            $AttendanceModel->createAttendance(
                $studentId,
                $dayActive['dia_fecha_id'],
                null,
                3
            );
        }

        // Cambiar estado del día a cerrado o inactivo
        $DayModel->closeDay($currentDate);

        echo json_encode([
            'status' => 'success',
            'message' => 'El día ha sido cerrado. Se registraron ' . count($absentStudents) . ' faltas automáticamente.'
        ]);
    }

    public function re_open_day()
    {
        if (!isAjax()) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Acceso no permitido']);
            return;
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentDate = date('Y-m-d');

            // Validar si el día está activo
            $DayModel = $this->model('DayModel');
            $dayActive = $DayModel->validDayActive($currentDate);

            if (!$dayActive) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'El día actual no está habilitado para reabrir.'
                ]);
                return;
            }

            // Reabrir el día
            $result = $DayModel->reOpenDay($currentDate);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Día reabierto exitosamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al reabrir el día.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido.'
            ]);
        }
    }
}
