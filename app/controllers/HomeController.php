<?php

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }
    public  $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        Auth::checkPermission('ver_dashboard'); // Verifica si el usuario tiene el permiso
        $view = "main.home";
        $data = [
            'view_js' => $view,
            'title' => 'Vista Rápida - Dashboard',
            'message' => 'Esta es la página de incio del Dashboard.'
        ];

        View::render($view, $data, $this->layout);
    }
}
