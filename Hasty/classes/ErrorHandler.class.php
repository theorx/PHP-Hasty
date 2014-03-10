<?php

class ErrorHandler {

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var boolean
     */
    private static $initialized = false;

    /**
     * Sets up error handling
     * @author Lauri Orgla
     * @version 1.0
     * @return boolean
     */
    public static function initialize() {
        if (!self::$initialized) {
            register_shutdown_function("ErrorHandler::fatal");
            set_error_handler("ErrorHandler::error");
            set_exception_handler("ErrorHandler::exception");
            ini_set("display_errors", 0);
            error_reporting(E_ALL ^ E_NOTICE);
        }
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     */
    public static function fatal() {
        $error = error_get_last();
        if ($error["type"] == E_ERROR)
            self::error($error["type"], $error["message"], $error["file"], $error["line"]);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param Exception $e
     */
    public static function exception(Exception $e) {
        self::saveException($e);

        if (Config::get('error_exception_output') == true) {
            print "<div style='text-align: left;'>";
            print "<h2 style='color: rgb(190, 50, 50);'>Exception Occurred:</h2>";
            print "<table style='width: 800px; display: inline-block;'>";
            print "<tr style='background-color:rgb(230,230,230);'><th style='width: 80px;'>Type</th><td>" . get_class($e) . "</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Message</th><td>{$e->getMessage()}</td></tr>";
            print "<tr style='background-color:rgb(230,230,230);'><th>File</th><td>{$e->getFile()}</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Line</th><td>{$e->getLine()}</td></tr>";
            print "<tr style='background-color:rgb(240,240,240);'><th>Trace</th><td><pre>" . $e->getTraceAsString() . "</pre></td></tr>";
            print "</table></div>";
            exit();
        }
    }

    /**
     * Saves exception to database
     * @author Lauri Orgla
     * @version 1.0
     * @param Exception $e
     */
    private static function saveException(Exception $e) {
        Sql::query('INSERT INTO exceptionlog (timestamp, type, message, file, line, trace) VALUES(:timestamp, :type, :message, :file, :line, :trace)', array(
            ':timestamp' => time(),
            ':type' => get_class($e),
            ':message' => $e->getMessage(),
            ':file' => $e->getFile(),
            ':line' => $e->getLine(),
            ':trace' => $e->getTraceAsString()
        ));
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param int $num
     * @param string $str
     * @param string $file
     * @param int $line
     * @param type $context
     */
    public static function error($num, $str, $file, $line, $context = null) {
        self::exception(new ErrorException($str, 0, $num, $file, $line));
    }

}

?>