<?php
class Controller {
    public $load;
    public $model;
    
    public $url;
    public $urlparams = array();
    
    function __construct() {
        $this->load = new Load();
        $this->model = new Model();
        
        if (isset($_SERVER['PATH_INFO'])) { // Nice URLs, get arguments
            $this->url = explode('/', $_SERVER['PATH_INFO']);
            $this->urlparams = array_slice($this->url, 2);
        }
        
        if (isset($this->url[1]) && $this->url[1]) {
            if (substr($this->url[1], 0, 1) != '_') {// Protect private functions starting with underscore
                call_user_func_array(array($this, $this->url[1]), $this->urlparams); // Execute function from URL with params
            } else {
                header('HTTP/1.0 404 Not Found');
            }
        } else {
            $this->index();
        }
    }
}