<?php

class HomeController extends Controller
{

    public  $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "main.home";
        $data = [
            'view_js' => $view,
            'title' => 'Vista Rápida - Dashboard',
            'message' => 'Esta es la página de incio del Dashboard.'
        ];

        View::render($view, $data, $this->layout);
    }
}
