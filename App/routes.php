<?php

$router = new Core\Router();

// index
$router->add('', ['controller' => 'Home', 'action' => 'index']);
// route generic controller/action stuff
$router->add('{controller}', ['action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{token:[\da-f]+}');

$router->dispatch($_SERVER['QUERY_STRING']);
