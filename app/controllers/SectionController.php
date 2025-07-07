<?php


class SectionController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

   

    public function get_total_sections()
    {
        // Verificar si la solicitud es Ajax y POST
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isAjax()) {
            http_response_code(($_SERVER['REQUEST_METHOD'] !== 'POST') ? 405 : 403);
            echo json_encode([
                'status' => 'error',
                'message' => ($_SERVER['REQUEST_METHOD'] !== 'POST') 
                    ? 'Método no permitido.' 
                    : 'Acceso no autorizado.'
            ]);
            return;
        }
    
        header('Content-Type: application/json');
    
        // Obtener modelo y ejecutar consulta
        $SectionModel = $this->model('SectionModel');
        $sections = $SectionModel->getAllSections();
    
        if (!empty($sections)) {
            echo json_encode([
                'status' => 'success',
                'sections' => $sections
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron grados registrados.'
            ]);
        }
    }
    

}
