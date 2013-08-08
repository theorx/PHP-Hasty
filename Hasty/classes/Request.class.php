<?php

class Request {

    public $route = null;

    public function __construct() {
        
    }

    public function parse() {
        //parse 

        $this->parseRoute();
    }

    public function parseRoute() {

        $parts = (isset($_GET['api-path']) == true) ? explode("/", $_GET['api-path']) : false;


        $routes = array();
        for ($i = 2; $i < count($parts) + 1; $i += 2) {
            $route = new Route();
            if ($i == 2) { // only first Route has to have class
                $route->class = (isset($parts[$i - 2])) ? $parts[$i - 2] : "";
                $route->function = (isset($parts[$i - 1])) ? $parts[$i - 1] : "";
                $route->param = (isset($parts[$i])) ? $parts[$i] : "";
            } else {
                $route->function = (isset($parts[$i - 1])) ? $parts[$i - 1] : "";
                $route->param = (isset($parts[$i])) ? $parts[$i] : "";
            }
            $routes[] = $route;
        }

        $this->route = $routes;
    }

}

?>