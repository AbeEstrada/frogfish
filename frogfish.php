<?php
/**
 * Frogfish: A PHP micro-framework to create fast web sites with the minimal code.
 *
 * @copyright Copyright (c) 2011, Abraham Estrada <abraham.estrada@gmail.com>
 * @license   THE BEER-WARE LICENSE, http://people.freebsd.org/~phk/
 * @link      https://github.com/Abe/frogfish
 */
class Frogfish {
    private $_config = array();
    private $_url = array();
    private $_routes = array();
    private $_action;
    
    protected $input;
    
    /**
     * Constructor.
     *
     * @param array $routes Routes and functions
     * @param array $config Configuration options
     */
    public function __construct($routes, $config=array()) {
        $this->_config = $config;
        $this->_routes = $routes;
        
        $this->input = new FrogfishInput();
        
        $this->_router();
    }
    
    private function _router() {
        if (isset($_SERVER['PATH_INFO'])) {
            preg_match_all('/(\$?)(\w+|\*)/', $_SERVER['PATH_INFO'], $urls, PREG_PATTERN_ORDER); // Get the url
            $this->_url = $urls[0];
        }
        
        $match = false;
        
        if (!array_key_exists('/', $this->_routes)) {
            $this->_routes['/'] = 'index'; // Default action
        }
        
        if (count($this->_url) == 1 && empty($this->_url[0])) { // Home
            $match = true;
            $this->_action = strtolower($this->_routes['/']);
        } else {
            $params = array();
            
            foreach ($this->_routes as $url => $action) {
                preg_match_all('/(\$?)(\w+|\*)/', $url, $route, PREG_PATTERN_ORDER); // Get routes
                $route = $route[0];
                
                if (count($this->_url) <= count($route) || end($route) == '*') { // Get the number of parameters required
                    if (end($route) == '*') {
                        $last = count($route)-1;
                        unset($route[$last]);
                    }
                    
                    $matches = 0;
                    for ($i=0; $i < count($route); $i++) { // Compare parameters
                        if (isset($this->_url[$i]) && $route[$i] == $this->_url[$i]) {
                            $matches++;
                        } else if (isset($this->_url[$i]) && substr($route[$i], 0, 1) == '$') {
                            $matches++;
                            array_push($params, $this->_url[$i]);
                        }
                    }
                    
                    if (count($route) == $matches) {
                        $match = true;
                        $this->_action = strtolower($action);
                        break;
                    }
                }
            }
        }
        
        if ($match) {
            if (is_callable(array($this, $this->_action)) && substr($this->_action, 0, 1) != '_') {
                call_user_func_array(array($this, $this->_action), $params); // Method call
            } else {
                $this->_error(404);
            }
        } else {
            $this->_error(404);
        }
    }
    
    private function _error($e) {
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

class FrogfishInput { // Return sanitized data
    private function xss_clean($input) {
        if (is_array($input)) {
            foreach($input as $var => $val) {
                $output[$var] = xss_clean($val);
            }
        } else {
            $input = trim($input);
            $input = (get_magic_quotes_gpc()) ? stripslashes($input) : $input ;
            $output = htmlentities($input, ENT_QUOTES, 'UTF-8');
        }
        return $output;
    }
    public function get($input) {
        return $this->fetch($_GET, $input);
    }
    
    public function post($input) {
        return $this->fetch($_POST, $input);
    }
    
    private function fetch($data, $input) {
        if (isset($data[$input])) {
            $output = $this->xss_clean($data[$input]);
        } else {
            $output = false;
        }
        return $output;
    }
}