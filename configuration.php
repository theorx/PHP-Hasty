<?php

/**
 * Hasty Configuration
 */
return array(
    'mysql_host' => 'localhost',
    'mysql_username' => 'root',
    'mysql_password' => '',
    'mysql_database' => 'hasty',
    'engine_path' => __DIR__ . DS . 'Hasty' . DS . 'classes' . DS,
    'application_path' => __DIR__ . DS . 'application' . DS,
    'request_method_types' => array("get", "insert", "update", "delete"),
    'app_path' => __DIR__ . DS . 'app' . DS,
    'default_response_format' => 'json',
    'xml_response_root_node' => 'Hasty-API',
    'response_allow_print_method' => true,
    'request_get_replaces' => array("'" => '', '"' => ''),
    'request_allowed_parameters_and_types' => array('limit' => 'int', 'start' => 'int', 'type' => 'string'),
    'token_timestamp_formating' => '%Y-%m-%d %H:%M:%S',
    'token_lifetime_seconds' => 2
);
?>
