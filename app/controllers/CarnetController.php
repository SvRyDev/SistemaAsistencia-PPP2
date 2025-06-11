<?php

use Dompdf\Dompdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class CarnetController extends Controller
{

    public $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $view = "carnet.generate";
        $data = [
            'view_js' => $view,
            'title' => 'Carnet de Estudiante',
            'message' => 'Esta es la página de carnet.'
        ];

        View::render($view, $data, $this->layout);
    }


    public function generate_carnet_pdf()
    {
        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);

        // Datos de ejemplo para 6 estudiantes
        $students = [
            ['student_id' => 'sl12345678c', 'name' => 'Arsstuo Saravia Reyes'],
            ['student_id' => 'sl87654321a', 'name' => 'María Pérez'],
            ['student_id' => 'sl12345678c', 'name' => 'Arsstuo Saravia Reyes'],
            ['student_id' => 'sl87654321a', 'name' => 'María Pérez'],
            ['student_id' => 'sl12345678c', 'name' => 'Arsstuo Saravia Reyes'],
            ['student_id' => 'sl87654321a', 'name' => 'María Pérez'],
            ['student_id' => 'sl87654321a', 'name' => 'María Pérez'],
            ['student_id' => 'sl12345678c', 'name' => 'Arsstuo Saravia Reyes'],
            ['student_id' => 'sl87654321a', 'name' => 'María Pérez'],

        ];

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
            $barcode = base64_encode($generator->getBarcode($student['student_id'], $generator::TYPE_CODE_128));

            // Usar View::fetch para la plantilla de carnet, pasar datos
            $carnet_html = View::fetch('dashboard.carnet.template-card', [
                'barcode' => $barcode,
                'name' => $student['name'],
                'student_id' => $student['student_id'],
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
        $dompdf->stream("carnets_multiples.pdf", ["Attachment" => true]);
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
