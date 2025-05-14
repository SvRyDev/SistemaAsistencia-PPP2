<?php

class HomeController extends Controller {
    public function index() {
        View::render('home');  // Cargar la vista "home.php"
    }
}
