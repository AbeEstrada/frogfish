<?php
require 'load.php';
require 'controller.php';
require 'app/config.php';
require 'app/model.php';

$new_class = ucfirst($config['route']['default_controller']);
require 'app/'.$config['route']['default_controller'].'.php';
new $new_class;