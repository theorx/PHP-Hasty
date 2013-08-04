<?php

class Config {

    private static $configuration;

    public static function setConfiguration($configuration) {
        self::$configuration = $configuration;
    }

    public static function get($key) {
        if (isset(self::$configuration[$key])) {
            return self::$configuration[$key];
        }
        echo '<h1>Config::get(' . $key . ') is not defined</h1>';
    }

}

?>