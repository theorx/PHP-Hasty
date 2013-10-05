<?php

class Processor {

    /**
     * This is the very result from application classes. this must be sent back to requestor.
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed 
     */
    public $result;

    /**
     * All the route paths are stored in this array as Route data objects.
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed/Route 
     */
    public $route;

    /**
     * This function processes everything and assigns the result from cycleRoutes function
     * @author Lauri Orgla
     * @version 1.0
     * @param /Request $request
     */
    public function process($request) {
        $this->route = $request->route;
        $this->result = $this->cycleRoutes();
    }

    /**
     * This is recursive function for processing all objects and sub objects
     * this function can call functions from nested objects which are returned by lower level classes
     * @param mixed/int $result
     * @param int $index
     * @return mixed/object
     */
    public function cycleRoutes($result = array('invalid query'), $index = 0) {

        if ($index == 0) {
            if (isset($this->route[$index], $this->route[$index]->class) && strlen($this->route[$index]->class) > 0 && class_exists($this->route[$index]->class . 'Controller', true) == true) {

                $function = $this->route[$index]->function;
                if (!Client::hasAccess($this->route[$index]->class, ((strlen($function) == 0) ? "class" : $function), Request::data('api-method'))) {
                    Response::forbidden();
                    Response::Trap(array('location' => $this->route));
                    return;
                } else {
                    $function .= "_" . ((strlen(Request::data('api-method')) == 0) ? "read" : Request::data('api-method'));
                }

                $class_name = $this->route[$index]->class . 'Controller';
                $class = new $class_name();
                $result = $class;
                if (method_exists($class, $function) == true) {
                    $result = (strlen($this->route[$index]->param) > 0) ? $class->{$function}($this->route[$index]->param) : $class->{$function}();
                    if (is_object($result) && is_array($result) != true && isset($this->route[$index + 1])) {
                        $result = $this->cycleRoutes($result, $index + 1);
                    }
                }
            }
        } else {
            $function = $this->route[$index]->function;
            if (!Client::hasAccess(get_class($result), ((strlen($function) == 0) ? "class" : $function), Request::data('api-method'))) {
                Response::forbidden();
                Response::Trap(array('location' => $this->route));
                return;
            } else {
                $function .= "_" . ((strlen(Request::data('api-method')) == 0) ? "read" : Request::data('api-method'));
            }

            if (method_exists($result, $function) == true) {
                $result = (strlen($this->route[$index]->param) > 0) ? $result->{$function}($this->route[$index]->param) : $result->{$function}();
                if (is_object($result) && is_array($result) != true && isset($this->route[$index + 1])) {
                    $result = $this->cycleRoutes($result, $index + 1);
                }
            }
        }

        return $result;
    }

}

?>