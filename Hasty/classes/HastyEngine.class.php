<?php

class HastyEngine {

    /**
     *
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
     * 
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
     * 
     * @return type
     */
    public function HTTPResponse() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * 
     * @return type
     */
    public function Request() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * 
     * @return type
     */
    public function Processor() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * 
     * @return type
     */
    public function Response() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * 
     * @return type
     */
    public function Autoloader() {
        return $this->getInstance(__FUNCTION__);
    }

    /**
     * 
     */
    public function Run() {
        $this->Request()->parse();

        $this->Autoloader()->appLoader($this->Request()->version);
        $this->Processor()->process($this->Request());
        $request = $this->Request();
        $this->Response()->setRequest($request);
        $this->Response()->respond($this->Processor()->result);
    }

}

?>