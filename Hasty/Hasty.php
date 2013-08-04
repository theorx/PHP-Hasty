<?php

/**
 * Define directory separator depending on environment
 */
(PHP_OS == "Linux") ? define('DS', '/') : define('DS', '\\');

include(__DIR__ . DS . "Config.php");
Config::setConfiguration(include(__DIR__ . DS . '..' . DS . 'configuration.php'));
require_once(Config::get("engine_path") . "HastyEngine.class.php");

$hasty = new HastyEngine();
$hasty->HTTPResponse();
$hasty->RequestA();
?>