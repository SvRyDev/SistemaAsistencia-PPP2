<?php
class View
{
    public static function render($view, $data = [])
    {
        $viewPath = str_replace('.', '/', $view);
        if (file_exists(__DIR__ . '/../views/' . $viewPath . '.php')) {
            require_once __DIR__ . '/../views/' . $viewPath . '.php';
        } else {
            die("View does not exist.");
        }
    }
}
