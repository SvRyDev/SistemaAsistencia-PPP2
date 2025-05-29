<?php

class StudentController extends Controller
{

    public  $layout = 'dashboard'; // Establecer el layout por defecto

    public function index()
    {
        $data = [
            'title' => 'Estudiantes',
            'message' => 'Esta es la página de vista de estudiantes.'
        ];

        View::render('student.list', $data,  $this->layout);
    }


    public function show()
    {
        $studentModel = $this->model('StudentModel');
        $allStudents = $studentModel->getAllStudents();

        if (isAjax()) {
            header('Content-Type: application/json');
            echo json_encode($allStudents);
            return;
        }
    }
}
