<?php
require 'load.php';
require 'controller.php';
require 'app/config.php';
require 'app/model.php';

if (isset($_SERVER['PATH_INFO'])) { // Nice URLs, get arguments
    $url = explode('/', $_SERVER['PATH_INFO']);
    $urlparams = array_slice($url, 3);
}

if (isset($url[1]) && $url[1]) {
    $file = 'app/'.$url[1].'.php';
    if (file_exists($file)) {
        require $file;
        $new_class = ucfirst($url[1]);
        $controller = new $new_class;
        
        if (isset($url[2]) && $url[2] && substr($url[2], 0, 1) != '_') { // Protect private functions starting with underscore
            call_user_func_array(array($controller, $url[2]), $urlparams); // Execute function from URL with params
        } else {
            $controller->index();
        }
    } else {
        header('HTTP/1.0 404 Not Found');
    }
} else {
    require 'app/'.$config['route']['default_controller'].'.php';
    $new_class = ucfirst($config['route']['default_controller']);
    $controller = new $new_class;
    $controller->index();
}