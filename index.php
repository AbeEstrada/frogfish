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
        } else {
            echo 'GET '.$this->input->get('name');
        }
    }
}

new App(array(
    '/' => 'home',
    '/hello/$name' => 'hello',
    '/hello/$name/$age' => 'hello',
    '/test' => 'test',
    '/data' => 'input'
));
