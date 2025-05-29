<?php

class HomeController extends Controller
{

    public  $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'message' => 'Esta es la página de inicio del sistema.'
        ];


        View::render('main.home', $data,  $this->layout);
    }


    public function details()
    {
        View::render('main.details', [],  $this->layout);
    }

    public function param($id)
    {
        // Aquí puedes manejar el parámetro $id
        echo "El ID es: " . htmlspecialchars($id);
    }
    public function detalle($tipo, $nivel, $id)
    {
        // Aquí puedes manejar los parámetros $tipo, $nivel e $id
        echo "Tipo: " . htmlspecialchars($tipo) . ", Nivel: " . htmlspecialchars($nivel) . ", ID: " . htmlspecialchars($id);

        $data = [
            'tipo' => htmlspecialchars($tipo),
            'nivel' => htmlspecialchars($nivel),
            'id' => htmlspecialchars($id)

        ];
        View::render('detalle', $data);  // Cargar la vista "detalle.php"
    }
}
