<?php

class PermissionController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto


    public function get_all_permissions(){
        $PermissionModel = $this->model('PermissionModel');
        $allPermission = $PermissionModel->getAllPermissions();
    
        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['data' => $allPermission]);
            return;
        }
    }
    

}
