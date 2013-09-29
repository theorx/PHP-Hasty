<?php

class authController {

    public function __construct() {
        //no authentication required to access this
    }

    public function request_token_read() {
        return Authentication::generateToken(Request::data('auth_key'), Request::data('auth_secret'));
    }

    public function validate_read() {
        return Authentication::validateToken(Request::data('api-token'));
    }

}

?>