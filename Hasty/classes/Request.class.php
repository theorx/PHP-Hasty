<?php

class Request {

    /**
     * Array of routes
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed/Route 
     */
    public $route = null;

    /**
     * Version determines the path from where controller will be loaded
     * @author 1.0
     * @version 1.0
     * @var type 
     */
    public $version = null;

    /**
     * Extension used for parsing final output type
     * @author Lauri Orgla
     * @version 1.0
     * @var string 
     */
    public $extension = null;

    /**
     *
     * @var type 
     */
    private static $query_params = array();

    /**
     * Parses incoming request 
     * @author Lauri Orgla
     * @version 1.0
     */
    public function parse() {
        $parts = (isset($_GET['api-path']) == true) ? explode("/", $_GET['api-path']) : false;
        if (is_array($parts)) {
            foreach ($parts as $part) {

                if (strpos($part, ".") > 0) {

                    $pieces = explode(".", $part);

                    if (count($pieces) == 2) {
                        $this->extension = $pieces[1];
                        $_GET['api-path'] = str_replace("." . $this->extension, "", $_GET['api-path']);
                    }
                    break;
                }
            }
        }
        $this->parseRoute();
    }

    /**
     * Parses route for processor class to process controllers / sub functions conrrectly.
     * @author Lauri Orgla
     * @version 1.0
     */
    private function parseRoute() {

        $parts = (isset($_GET['api-path']) == true) ? explode("/", $_GET['api-path']) : false;

        $routes = array();
        for ($i = 3; $i < count($parts) + 2; $i += 2) {
            $route = new Route();
            if ($i == 3) { // only first Route has to have class
                $this->version = $parts[$i - 3];
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

    /**
     * Function for getting get parameters from api request
     * @author Lauri Orgla
     * @version 1.0
     * @param string $offset
     * @return boolean|string
     */
    public static function query($offset) {
        if (key_exists($offset, Config::get('request_allowed_parameters_and_types')) && isset($_GET[$offset])) {
            if (key_exists($offset, self::$query_params)) {
                return self::$query_params[$offset];
            } else {
                $filter_type = Config::get('request_allowed_parameters_and_types')[$offset];
                $filtered_value = "";
                switch ($filter_type) {
                    case 'int': {
                            $filtered_value = intval($_GET[$offset]);
                            break;
                        }
                    case 'string': {
                            $searches = array();
                            $replaces = array();
                            foreach (Config::get('request_get_replaces') as $search => $replace) {
                                $searches[] = $search;
                                $replaces[] = $replace;
                            }
                            $filtered_value = str_replace($searches, $replaces, $_GET[$offset]);
                            break;
                        }
                }
                self::$query_params[$offset] = $filtered_value;
            }
            return self::$query_params[$offset];
        }

        return false;
    }

    /**
     * Get post data from request
     * @author Lauri Orgla
     * @version 1.0
     * @param string $offset
     * @return mixed|string
     */
    public static function data($offset = null) {
        if ($offset == null && isset($_POST)) {
            return $_POST;
        } else if (isset($_POST[$offset])) {
            return $_POST[$offset];
        } else {
            return false;
        }
    }

}

?>