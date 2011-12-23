<?php
require 'frogfish.php';

class App extends Frogfish {
    public function home() {
        echo '<h1>It works!</h1>';
    }
    
    public function test() {
        echo 'This is a test';
    }
    
    public function hello($name, $age=null) {
        echo 'Hello '.$name.', your age is '.$age;
    }
    
    public function input() {
        if ($_POST) {
            echo 'POST '.$this->input->post('name');
        } else if (isset($_GET['name'])) {
            echo 'GET '.$this->input->get('name');
        } else {
            echo 'GET';
        }
    }
    
    public function views() {
        $this->load->view('views/home', array('nice'=>'like this one.'));
    }
}

new App(array( // Format: route => function
    '/' => 'home',
    '/hello/$name' => 'hello',
    '/hello/$name/$age' => 'hello',
    '/test' => 'test',
    '/data' => 'input',
    '/views' => 'views'
));
