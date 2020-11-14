<?php

spl_autoload_register(function ($class){
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if(is_readable($file)){
        require $root . '/' . str_replace('\\', '/', $class) . '.php';
    }
});

$router = new Core\Router();

$router->add('/', ['Controller' => 'Home', 'action' => 'index']);
$router->add('/{controller}/{action}');
$router->add('/{controller}/{id:\d+}/{action}');
$router->add('/admin/{controller}/{action}');

$router->dispatch($_SERVER['REQUEST_URI']);