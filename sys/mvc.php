<?php
require 'load.php';
require 'controller.php';
require 'app/config.php';
require 'app/model.php';

require 'app/'.$config['route']['default_controller'].'.php';
new $config['route']['default_controller'];