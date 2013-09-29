<?php

class HastyEngine {

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var type 
     */
    private $_instances = array();

    /**
     * 
     */
    public function __construct() {
        require_once(Config::get('engine_path') . 'Autoloader.class.php');
        $this->Autoloader();
    }

    /**
     * Returns instance of a class
     * @author Lauri Orgla
     * @version 1.0
     * @param type $class
     * @return boolean
     */
    private function getInstance($class) {
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
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public function Request() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public function Processor() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public function Response() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * Sets up autoloader for loading all classes automatically
     * @author Lauri Orgla
     * @version 1.0
     * @return type
     */
    public function Autoloader() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * Runs the engine, gets requests and serves response
     * @author Lauri Orgla
     * @version 1.0
     */
    public function Run() {
        $request = $this->Request()->parse();
        $this->Response()->setRequest($request);
        Authentication::authenticate(Request::data('api-token'));

        if (Config::get('authentication_enabled') == false || Authentication::authenticate(Request::data('api-token')) || (isset($request->route[0]->class) && in_array($request->route[0]->class, Config::get('public_controllers')))) {
            $this->Autoloader()->appLoader($this->Request()->version);
            $this->Processor()->process($this->Request());

            $this->Response()->respond($this->Processor()->result);
        } else {
            $this->Response()->respond(array("msg" => "forbidden"));
            /// has to be removed
            //fallback to default response template
        }
    }

}

?>