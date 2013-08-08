<?php

class HastyEngine {

    private $_instances = array();

    public function __construct() {
        require_once(Config::get('engine_path') . 'Autoloader.class.php');
        $autoload = new Autoloader();
    }

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

    public function HTTPResponse() {
        return $this->getInstance(__FUNCTION__);
    }

    public function Request() {
        return $this->getInstance(__FUNCTION__);
    }

    public function Processor() {
        return $this->getInstance(__FUNCTION__);
    }

    public function Response() {
        return $this->getInstance(__FUNCTION__);
    }

    public function Run() {
        $this->Request()->parse();
        $this->Processor()->process($this->Request());
        $this->Response()->respond($this->Processor()->result);
    }

}

?>