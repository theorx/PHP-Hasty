<?php

class Config {

    /**
     * @author Lauri Orgla
     * stores configuration data
     * @var mixed 
     */
    private static $configuration;

    /**
     * @author Lauri Orgla
     * @param type $configuration
     */
    public static function setConfiguration($configuration) {
        self::$configuration = $configuration;
    }

    /**
     * @author Lauri Orgla
     * @param string $key
     * @return mixed|string
     */
    public static function get($key) {
        if (isset(self::$configuration[$key])) {
            return self::$configuration[$key];
        }
        
        //log
        echo '<h1>Config::get(' . $key . ') is not defined</h1>';
    }

}

?>