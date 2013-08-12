<?php

class authController {

    public function __construct() {
        //no authentication required to access this
    }

    public function request_token() {
        return Authentication::generateToken(Request::data('auth_key'), Request::data('auth_secret'));
    }

    public function validate() {
        return Authentication::validateToken(Request::data('api-token'));
    }

}

?>