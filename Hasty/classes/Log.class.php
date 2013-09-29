<?php

class Log {

    const DEBUG = 'DEBUG';
    const TIMER = 'TIMER';
    const ACTION = 'ACTION';
    const WARNING = 'WARNING';
    const NOTIFICATION = 'NOTIFICATION';

    /**
     * @author Lauri Orgla
     * @version 1.0
     * Running timers
     * @var Array 
     */
    private static $_timers = array();

    /**
     * Stores logs in database
     * @author Lauri Orgla
     * @version 1.0
     * @param string $message
     * @param string $type
     * @param string $file
     * @param int $line
     */
    public static function Add($message, $type = self::ACTION, $file = '', $line = '') {
        if ($file == '' || $line == '') {
            $trace = debug_backtrace();
            if (isset($trace[0])) {
                $file = $trace[0]['file'];
                $line = $trace[0]['line'];
            }
        }
        Sql::query('INSERT INTO logs (timestamp, type, message, file, line) VALUES(:timestamp, :type, :message, :file, :line)', array(
            ':timestamp' => time(),
            ':type' => $type,
            ':message' => $message,
            ':file' => $file,
            ':line' => $line
        ));
    }

    /**
     * This function has to be called twice with same parameter to save timer's lapse.
     * @author Lauri Orgla
     * @version 1.0
     * @param string $identifier
     * @return boolean
     */
    public static function Time($identifier) {
        if (Config::get('log_timers')) {
            if (in_array($identifier, array_keys(self::$_timers))) {
                $lapse = (microtime(true) - self::$_timers[$identifier]);
                $trace = debug_backtrace();
                if (isset($trace[0])) {
                    $file = $trace[0]['file'];
                    $line = $trace[0]['line'];
                }
                Log::Add(sprintf("Timer %s lapse %s", $identifier, $lapse), Log::TIMER, $file, $line);
                unset(self::$_timers[$identifier]);
            } else {
                self::$_timers[$identifier] = microtime(true);
            }
        }
        return true;
    }

}

?>