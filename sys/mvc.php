<?php
require 'load.php';
require 'controller.php';
require 'app/model.php';

$apps_dir = './app';
if ($handle = opendir($apps_dir)) {
    while (false !== ($file = readdir($handle))) {
        $file_path = $apps_dir.'/'.$file;
        $path_parts = pathinfo($file);
        if ($path_parts['extension'] == 'php') {
            $content = file_get_contents($file_path);
            if (strpos($content, 'extends Controller') !== false) { // Find all controller files
                require $file_path;
                $new_class = ucfirst(substr($file, 0, strlen($file)-4));
                new $new_class; // Create new controller
            }
        }
    }
    closedir($handle);
}