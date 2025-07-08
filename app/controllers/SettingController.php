<?php

class SettingController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "setting.general";
        $data = [
            'view_js' => $view,
            'title' => 'Configuración del Sistema',
            'message' => 'Esta es la página de configuración.'
        ];

        View::render($view, $data, $this->layout);
    }

    public function load_data_setting(){

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
            $SettingModel = $this->model('SettingModel');
            $dataConfig = $SettingModel->getConfig();

            // Obtener datos

            // Validar si hay datos
            if (empty($dataConfig)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se encontraron grados o secciones.'
                ]);
                return;
            }

            // Respuesta OK
            echo json_encode([
                'status' => 'success',
                'setting' => $dataConfig,
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
 

    public function save_setting()
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
            $SettingModel = $this->model('SettingModel');
            $data = json_decode(file_get_contents('php://input'), true);
    
            // Validación básica
            if (
                empty($data['id']) ||
                empty($data['start_date']) ||
                empty($data['end_date']) ||
                empty($data['entry_time']) ||
                empty($data['exit_time']) ||
                empty($data['name_school']) ||
                empty($data['time_zone']) ||
                !isset($data['time_tolerance']) // puede ser 0
            ) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Faltan datos obligatorios.'
                ]);
                return;
            }
    
            // Llamar al modelo con parámetros individuales
            $result = $SettingModel->updateConfig(
                $data['id'],
                date('Y', strtotime($data['start_date'])), //academic_year
                $data['start_date'],
                $data['end_date'],
                $data['entry_time'],
                $data['exit_time'],
                $data['name_school'],
                $data['time_zone'],
                $data['time_tolerance']
            );
    
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Configuración guardada correctamente.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo guardar la configuración.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ocurrió un error al guardar la configuración.',
                'error' => $e->getMessage() // puedes ocultar esto en producción
            ]);
        }
    }
    
}
