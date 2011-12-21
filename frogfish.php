<?php
class Frogfish {
    private $config;
    private $urls = array();
    private $routes = array();
    private $action;
    
    public function __construct($routes, $config=array()) {
        $this->config = $config;
        $this->routes = $routes;
        
        $this->router();
    }
    
    private function url() {
        if (isset($_SERVER['PATH_INFO'])) {
            $this->urls = explode('/', $_SERVER['PATH_INFO']);
            array_shift($this->urls);
        }
    }
    
    private function router() {
        $this->url();
        
        $match = false;
        
        if (!array_key_exists('/', $this->routes)) {
            $this->routes['/'] = 'index';
        }
        
        if (count($this->urls) == 1 && empty($this->urls[0])) {
            $match = true;
            $this->action = $this->routes['/'];
        } else {
            $params = array();
            
            foreach ($this->routes as $url => $action) {
                preg_match_all('/(\$?)(\w+|\*)/', $url, $route, PREG_PATTERN_ORDER);
                $route = $route[0];
                
                if (count($this->urls) <= count($route) || end($route) == '*') {
                    if (end($route) == '*') {
                        $last = count($route)-1;
                        unset($route[$last]);
                    }
                    
                    $matches = 0;
                    for ($i=0; $i < count($route); $i++) { 
                        if (isset($this->urls[$i]) && $route[$i] == $this->urls[$i]) {
                            $matches++;
                        } else if (isset($this->urls[$i]) && substr($route[$i], 0, 1) == '$') {
                            $matches++;
                        }
                    }
                    
                    if (count($route) == $matches) {
                        $match = true;
                        $this->action = $action;
                        break;
                    }
                }
            }
        }
        
        if ($match) {
            if (is_callable(array($this, $this->action)) && substr($this->action, 0, 1) != '_') {
                call_user_func_array(array($this, $this->action), array());
            } else {
                $this->error(404);
            }
        } else {
            $this->error(404);
        }
    }
    
    private function error($e) {
        switch ($e) {
            case 404:
                header('HTTP/1.0 404 Not Found');
                break;
                
            case 500:
            default:
                header('HTTP/1.1 500 Internal Server Error');
                break;
        }
    }
}