<?php
class Application extends Controller {
    function __construct() {
        parent::__construct();
        
    }
    
    function index() {
        $data = $this->model->user_info();
        $this->load->view('someview.php', $data);
    }
    
    function hello($name) {
        echo 'Hello '.$name.'!';
    }
}