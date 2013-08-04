<?php

class Autoloader {

    /**
     * @author Lauri Orgla
     * @package Engine
     * @version 1.0
     * @param type $loader_configuration
     */
    public function __construct() {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * @author Lauri Orgla
     * @package Engine
     * @version 1.0
     * @param String $class
     */
    public function load($class) {

        $paths = array(Config::get('engine_path') . $class . '.class.php');

        foreach ($paths as $path) {
            if (file_exists($path) && !class_exists($class)) {
                include($path);
                return;
            }
        }
        Log::add("Autoloader cannot load class: " . $class);
    }

}

?>