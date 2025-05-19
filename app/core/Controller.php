<?php
class Controller {
   //  use AuthTrait;
   protected $layout = 'default';
    protected function model($model) {
        if (file_exists(__DIR__ . '/../models/' . $model . '.php')) {
            require_once __DIR__ . '/../models/' . $model . '.php';
            return new $model();
        } else {
            die("Model $model does not exist.");
        }
    }
}
?>
