<?php

class Processor {

    public $result;
    public $route;

    public function process(Request $request) {
        $this->route = $request->route;
        $this->result = $this->cycleRoutes();
    }

    public function cycleRoutes($result = 0, $index = 0) {

        if ($index == 0) {
            if (isset($this->route[$index], $this->route[$index]->class) && strlen($this->route[$index]->class) > 0 && class_exists($this->route[$index]->class . "Controller", true) == true) {
                $class_name = $this->route[$index]->class . "Controller";
                $class = new $class_name();
                if (method_exists($class, $this->route[$index]->function) == true) {
                    $result = (strlen($this->route[$index]->param) > 0) ? $class->{$this->route[$index]->function}($this->route[$index]->param) : $class->{$this->route[$index]->function}();
                    if (is_object($result) && is_array($result) != true && isset($this->route[$index + 1])) {
                        $result = $this->cycleRoutes($result, $index + 1);
                    }
                }
            }
        } else {
            if (method_exists($result, $this->route[$index]->function) == true) {
                $result = (strlen($this->route[$index]->param) > 0) ? $result->{$this->route[$index]->function}($this->route[$index]->param) : $result->{$this->route[$index]->function}();
                if (is_object($result) && is_array($result) != true && isset($this->route[$index + 1])) {
                    $result = $this->cycleRoutes($result, $index + 1);
                }
            }
        }

        return $result;
    }

}

?>