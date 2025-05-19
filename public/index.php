<?php

// require_once '../vendor/autoload.php'; 
require_once '../app/core/Autoload.php';

require_once '../config/config.php';
require_once '../app/helpers/helpers.php';

// Assuming Router.php is in /app/core
require_once '../app/core/Router.php';

// Run the router
$router = new Router();
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/home', 'HomeController@index');
$router->addRoute('GET', '/about', 'HomeController@about');
$router->addRoute('GET', '/param/{id}', 'HomeController@param');
$router->addRoute('GET', '/user/{tipo}/buscar/{nivel}/{id}', 'HomeController@detalle');

$router->run()

?>
