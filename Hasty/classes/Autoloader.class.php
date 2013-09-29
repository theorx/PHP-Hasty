<?php

class Autoloader {

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $loader_configuration
     */
    public function __construct() {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param String $class
     */
    public function load($class) {

        $paths = array(
            Config::get('engine_path') . $class . '.class.php',
            Config::get('engine_path') . $class . '.do.php',
        );

        foreach ($paths as $path) {
            if (file_exists($path) && !class_exists($class)) {
                include($path);
                return;
            }
        }
        //log system loading problems
    }

    /**
     * Version defines api version
     * this function must be called with version from incoming request.
     * @author Lauri Orgla
     * @version 1.0
     * @param string $version
     * @return type
     */
    public function appLoader($version) {
        spl_autoload_register(function($class) use($version) {
                    $paths = array(
                        Config::get('app_path') . $version . DS . $class . 'Controller.php',
                        Config::get('app_path') . $version . DS . $class . '.php');
                    foreach ($paths as $path) {
                        if (file_exists($path) && !class_exists($class)) {
                            include($path);
                            return;
                        }
                    }
                });
        return;
    }

}

?>