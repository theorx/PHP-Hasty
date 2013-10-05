<?php

class HastyEngine {

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var type 
     */
    private $_instances = array();

    public function __construct() {
        require_once(Config::get('engine_path') . 'Autoloader.class.php');
        $this->get('Autoloader');
    }

    /**
     * Returns instance of a class
     * @author Lauri Orgla
     * @version 1.0
     * @param type $class
     * @return boolean
     */
    private function get($class) {
        if (!key_exists($class, $this->_instances)) {
            if (class_exists($class, true)) {
                $this->_instances[$class] = new $class();
            } else {
                Log::Add("Cannot load class " . $class);
                return false;
            }
        }
        return $this->_instances[$class];
    }

    /**
     * Runs the engine, gets requests and serves response
     * @author Lauri Orgla
     * @version 1.0
     */
    public function run() {
        $request = $this->get('Request')->parse();
        $this->get('Response')->setRequest($request);

        $authenticated = false;
        if (Request::data('api-token') != false) {
            $authenticated = Authentication::authenticate(Request::data('api-token'));
        } elseif (Request::data('api-key') != false && Request::data('api-secret') != false) {
            $authenticated = Authentication::authenticateCredentials(Request::data('api-key'), Request::data('api-secret'));
        }

        if (Config::get('authentication_enabled') == false || $authenticated || (isset($request->route[0]->class) && in_array($request->route[0]->class, Config::get('public_controllers')))) {
            $this->get('Autoloader')->appLoader($this->get('Request')->version);
            $this->get('Processor')->process($this->get('Request'));
            $this->get('Response')->respond($this->get('Processor')->result);
        } else {
            Response::forbidden();
        }
    }

}

?>