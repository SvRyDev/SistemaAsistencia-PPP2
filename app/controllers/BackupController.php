<?php

class BackupController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "backup.options";
        $data = [
            'view_js' => $view,
            'title' => 'Copias de seguridad',
            'message' => 'Esta es la página de backups.'
        ];

        View::render($view, $data, $this->layout);
    }



}
