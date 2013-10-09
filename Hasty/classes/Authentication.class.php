<?php

class Authentication {

    /**
     * generateToken generates unique access token for defined period of time
     * @author Lauri Orgla
     * @version 1.0
     * @param string $auth_key
     * @param string $auth_secret
     * @return type
     */
    public static function generateToken($auth_key, $auth_secret) {
        $user_data = Sql::fetch('SELECT u.id, u.name, u.auth_key, u.auth_secret, u.user_group FROM users AS u WHERE u.auth_key = :auth_key', array(
                    ':auth_key' => $auth_key
        ));

        if (isset($user_data->auth_secret) && strlen($user_data->auth_secret) > 0 && $user_data->auth_secret == $auth_secret) {
            $new_token = md5(md5(rand(0, rand(0, 128000)))) . md5(time() + rand(128000, rand(0, 256000)));
            Sql::query('INSERT INTO auth_tokens (owner, token, created, expires, ip) VALUES(:owner, :token, :created, :expires, :ip)', array(
                ':owner' => $user_data->id,
                ':token' => $new_token,
                ':created' => time(),
                ':expires' => (time() + Config::get('token_lifetime_seconds')),
                ':ip' => $_SERVER['REMOTE_ADDR']
            ));

            return array("api-token" => $new_token);
        }

        return array('message' => 'invalid authentication data');
    }

    /**
     * authenticateCredentials
     * @author Lauri Orgla
     * @version 1.0
     * @param type $token
     * @return type
     */
    public static function authenticateCredentials($auth_key, $auth_secret) {
        $user_data = Sql::fetch('SELECT  u.id, u.name, u.auth_key, u.auth_secret, u.user_group FROM users AS u WHERE u.auth_key = :auth_key', array(
                    ':auth_key' => $auth_key
        ));

        if (isset($user_data->auth_secret) && strlen($user_data->auth_secret) > 0 && $user_data->auth_secret == $auth_secret) {
            Client::$id = $user_data->id;
            return true;
        }
        return false;
    }

    /**
     * validateToken validates given token against database.
     * @author Lauri Orgla
     * @version 1.0
     * @param type $token
     * @return type
     */
    public static function validateToken($token) {
        $return_data = array("status" => false);

        $result = Sql::fetch('SELECT at.id, at.owner, at.token, at.created, at.expires, at.ip FROM auth_tokens AS at WHERE at.token = :token', array(':token' => $token));
        if (isset($result->id) && $result->id > 0 && $result->expires > time()) {
            $return_data['created'] = strftime(Config::get('token_timestamp_formating'), $result->created);
            $return_data['expires'] = strftime(Config::get('token_timestamp_formating'), $result->expires);
            $return_data['status'] = true;
        }

        return $return_data;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $token
     */
    public static function authenticate($token) {
        $result = Sql::fetch('SELECT at.id, at.owner, at.token, at.created, at.expires, at.ip  FROM auth_tokens AS at WHERE token = :token', array(':token' => $token));
        if (isset($result->id) && $result->id > 0 && $result->expires > time()) {
            Client::$id = $result->owner;
            return true;
        }
        return false;
    }

}

?>