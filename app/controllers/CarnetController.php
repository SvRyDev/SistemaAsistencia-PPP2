<?php

use Dompdf\Dompdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class CarnetController extends Controller
{
    public function __construct()
    {
        Auth::checkAuth(); // Verifica si el usuario está autenticado
    }
    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "carnet.individual_generate";
        $data = [
            'view_js' => $view,
            'title' => 'Carnet de Estudiante',
            'message' => 'Esta es la página de carnet.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function view_individual_generate_carnet()
    {
        $view = "carnet.individual_generate";
        $data = [
            'view_js' => $view,
            'title' => 'Carnet de Estudiante',
            'message' => 'Esta es la página de carnet.'
        ];

        View::render($view, $data, $this->layout);
    }

    public function view_grupal_generate_carnet()
    {
        $view = "carnet.grupal_generate";
        $data = [
            'view_js' => $view,
            'title' => 'Generador de Carnets por Lotes',
            'message' => 'Esta es la página de carnet.'
        ];

        View::render($view, $data, $this->layout);
    }

    public function generate_carnet_grupal()
    {


        // Validación de método y parámetros
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            empty($_POST['grado_grupo']) ||
            empty($_POST['seccion_grupo'])
        ) {
            echo 'Faltan parámetros requeridos.';
            return;
        }

        // Capturar los datos
        $gradoId = $_POST['grado_grupo'];
        $seccionId = $_POST['seccion_grupo'];


        $StudenModel = $this->model('StudentModel');
        $students = $StudenModel->getStudentsByGradeAndSection($gradoId, $seccionId);


        $ConfigModel = $this->model('SettingModel');
        $academic_year = $ConfigModel->getConfig()['academic_year'];

        if (empty($students)) {
            echo 'No se encontraron estudiantes para ese grado y sección.';
            return;
        }



        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);


        // Armar contenido HTML con una tabla para colocar carnets
        $html = '<html><head><style>
            table { border-collapse: collapse; width: 100%; }
            td { padding: 30px; vertical-align: top; }
            .carnet-card { width: 85mm; height: 55mm; margin: auto; }
        </style></head><body>';
        $html .= '<table>';

        $columns = 2; // 2 carnets por fila
        $count = 0;

        foreach ($students as $index => $student) {
            if ($count % $columns == 0) {
                $html .= '<tr>'; // abrir fila nueva
            }

            // Generar barcode para cada estudiante
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($student['codigo'], $generator::TYPE_CODE_128));

            // Usar View::fetch para la plantilla de carnet, pasar datos
            $carnet_html = View::fetch('dashboard.carnet.template-card', [
                'barcode' => $barcode,
                'name' => explode(' ', trim($student['nombres']))[0] . ' ' . $student['apellidos'],
                'student_id' => $student['codigo'],
                'grado' => $student['grado_nombre'],
                'seccion' => $student['seccion'],
                'emision' => date('d/m/Y'),
                'anio' => $academic_year,

            ]);

            $html .= '<td><div class="carnet-card">' . $carnet_html . '</div></td>';

            $count++;
            if ($count % $columns == 0) {
                $html .= '</tr>'; // cerrar fila
            }
        }

        // Si la última fila no está completa, cerrar la fila
        if ($count % $columns != 0) {
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();




        // ✅ Muy importante: devolver solo PDF, sin nada antes
        $pdfOutput = $dompdf->output();
        $descargar = isset($_POST['descargar']) && $_POST['descargar'] == 1;

        header("Content-Type: application/pdf");

        if ($descargar) {
            header("Content-Disposition: attachment; filename=\"carnets.pdf\"");
        } else {
            header("Content-Disposition: inline; filename=\"carnets.pdf\"");
        }

        header("Content-Length: " . strlen($pdfOutput));
        echo $pdfOutput;
        exit;


    }

    public function generate_carnet_individual()
    {


        // Validación de método y parámetros
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            empty($_POST['dni_estudiante'])
        ) {
            echo 'Faltan parámetros requeridos.';
            return;
        }

        // Capturar los datos
        $dniEstudiante = $_POST['dni_estudiante'];



        $StudenModel = $this->model('StudentModel');
        $student = $StudenModel->getStudentByDNI($dniEstudiante);


        $ConfigModel = $this->model('SettingModel');
        $academic_year = $ConfigModel->getConfig()['academic_year'];

        if (empty($student)) {
            echo 'No se encontro el estudiante con el Dni ingresado.';
            return;
        }



        // Armar contenido HTML con una tabla para colocar carnets
        $html = '<html><head><style>
            table { border-collapse: collapse; width: 100%; }
            td { padding: 30px; vertical-align: top; }
            .carnet-card { width: 85mm; height: 55mm; margin: auto; }
        </style></head><body>';
        $html .= '<table>';


        $html .= '<tr>'; // abrir fila nueva


        // Generar barcode para cada estudiante
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($student['codigo'], $generator::TYPE_CODE_128));

        // Usar View::fetch para la plantilla de carnet, pasar datos
        $carnet_html = View::fetch('dashboard.carnet.template-card', [
            'barcode' => $barcode,
            'name' => explode(' ', trim($student['nombres']))[0] . ' ' . $student['apellidos'],
            'student_id' => $student['codigo'],
            'grado' => $student['grado_nombre'],
            'seccion' => $student['seccion'],
            'emision' => date('d/m/Y'),
            'anio' => $academic_year,

        ]);

        $html .= '<td><div class="carnet-card">' . $carnet_html . '</div></td>';


        $html .= '</tr>'; // cerrar fila

        $html .= '</table></body></html>';



        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();


        $descargar = isset($_POST['descargar']) && $_POST['descargar'] == 1;

        // ✅ Muy importante: devolver solo PDF, sin nada antes
        $pdfOutput = $dompdf->output();

        header("Content-Type: application/pdf");

        if ($descargar) {
            header("Content-Disposition: attachment; filename=\"carnets.pdf\"");
        } else {
            header("Content-Disposition: inline; filename=\"carnets.pdf\"");
        }

        header("Content-Length: " . strlen($pdfOutput));
        echo $pdfOutput;
        exit;

    }

    public function preview_carnet_individual()
    {


        // Validación de método y parámetros
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            empty($_POST['dni_estudiante'])
        ) {
            echo 'Faltan parámetros requeridos.';
            return;
        }

        // Capturar los datos
        $dniEstudiante = $_POST['dni_estudiante'];



        $StudenModel = $this->model('StudentModel');
        $student = $StudenModel->getStudentByDNI($dniEstudiante);


        $ConfigModel = $this->model('SettingModel');
        $academic_year = $ConfigModel->getConfig()['academic_year'];

        if (empty($student)) {
            echo 'No se encontro el estudiante con el Dni ingresado.';
            return;
        }



        // Armar contenido HTML con una tabla para colocar carnets
        $html = '<html><head><style>

              .carnet-card {    
        width: 20px;
        height: 30px; /* Mantén proporción similar a 85mm x 55mm */
        margin: 40px auto 40px auto;
        overflow: visible !important;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        transform-origin: top center;
        transition: transform 0.3s ease;
        transform: scale(1.7);
    }
    @media (max-width: 900px) {
        .carnet-card {
            transform: scale(1.5);
        }
    }
    @media (max-width: 700px) {
        .carnet-card {
            transform: scale(1.2);
        }
    }
    @media (max-width: 500px) {
        .carnet-card {
            transform: scale(1);
        }
    }
    @media (max-width: 350px) {
        .carnet-card {
            transform: scale(0.8);
        }
    }
            </style></head><body>';

        // Generar barcode para cada estudiante
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($student['codigo'], $generator::TYPE_CODE_128));

        // Usar View::fetch para la plantilla de carnet, pasar datos
        $carnet_html = View::fetch('dashboard.carnet.template-card', [
            'barcode' => $barcode,
            'name' => explode(' ', trim($student['nombres']))[0] . ' ' . $student['apellidos'],
            'student_id' => $student['codigo'],
            'grado' => $student['grado_nombre'],
            'seccion' => $student['seccion'],
            'emision' => date('d/m/Y'),
            'anio' => $academic_year,

        ]);

        $html .= '<div class="carnet-card">' . $carnet_html . '</div>';


        $html .= '</body></html>';



        echo $html;
        exit;
    }


    public function preview_single_carnet_pdf()
    {
        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);

        $student = [
            'student_id' => 'sl12345678c',
            'name' => 'Arsstuo Saravia Reyes',
        ];


        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($student['student_id'], $generator::TYPE_CODE_128));

        $carnet_html = View::fetch('dashboard.carnet.template-card', [
            'barcode' => $barcode,
            'name' => $student['name'],
            'student_id' => $student['student_id'],
        ]);

        $html = '<html><head><style>
            body { margin: 0; }
            .carnet-card { width: 85mm; height: 55mm; margin: auto; }
        </style></head><body>';
        $html .= $carnet_html;
        $html .= '</body></html>';

        $dompdf->loadHtml($html);

        $width = 85 / 25.4 * 72;
        $height = 55 / 25.4 * 72;
        $dompdf->setPaper([0, 0, $width, $height]);

        $dompdf->render();

        // Importante: no forzar descarga, solo mostrar
        header("Content-Type: application/pdf");
        echo $dompdf->output();
    }



}
