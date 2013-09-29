<?php

/**
 * Define directory separator depending on environment
 */
(PHP_OS == "Linux") ? define('DS', '/') : define('DS', '\\');
/*
 * Include config
 */
include(__DIR__ . DS . "Config.php");
/**
 * Set configuration
 */
Config::setConfiguration(include(__DIR__ . DS . '..' . DS . 'configuration.php'));
/**
 * Include SQL
 */
require_once(Config::get("engine_path") . "Sql.class.php");
/**
 * Include ErrorHandler
 */
require_once(Config::get("engine_path") . "ErrorHandler.class.php");
/**
 * Initialize ErrorHandler
 */
ErrorHandler::initialize();

/**
 * Include engine
 */
require_once(Config::get("engine_path") . "HastyEngine.class.php");
/**
 * Instantiate engine
 */
$hasty = new HastyEngine();
/**
 * run
 */
$hasty->Run();
?>