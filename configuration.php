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
    'app_path' => __DIR__ . DS . 'app' . DS,
    'default_response_format' => 'json',
    'xml_response_root_node' => 'Hasty-API',
    'request_get_replaces' => array("'" => '', '"' => ''),
    'request_allowed_parameters_and_types' => array('limit' => 'int', 'start' => 'int', 'type' => 'string'),
    'token_timestamp_formating' => '%Y-%m-%d %H:%M:%S',
    'token_lifetime_seconds' => 1440,
    'public_controllers' => array('auth', 'user', 'accounts', 'userManagement', 'userGroups'),
    'authentication_enabled' => false,
    'error_exception_output' => false,
    'save_error_exception_data' => true,
    'cache_path' => __DIR__ . DS . 'Hasty' . DS . 'cache' . DS,
);
?>
