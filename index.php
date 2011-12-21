<?php
require 'frogfish.php';

class App extends Frogfish {
    public function happy() {
        echo '<h1>It works!</h1>';
    }
    
    public function test() {
        echo 'This is a test';
    }
}

new App(array(
    '/' => 'happy',
    '/test' => 'test',
));
