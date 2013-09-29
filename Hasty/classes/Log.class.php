<?php

class Log {

    private static $_timers = array();

    public static function Add($message) {
        echo "<h2>$message</h2>";
    }

    public static function Time($identifier) {
        if (Config::get('log_timers')) {
            if (in_array($identifier, array_keys(self::$_timers))) {
                $lapse = (microtime(true) - self::$_timers[$identifier]);
                Log::Add(sprintf("Timer %s lapse %s", $identifier, $lapse));
                echo $lapse;
                unset(self::$_timers[$identifier]);
            } else {
                self::$_timers[$identifier] = microtime(true);
            }
        }
        return true;
    }

}

?>