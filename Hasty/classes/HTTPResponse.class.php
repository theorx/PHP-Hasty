<?php

/**
 * @author Lauri Orgla
 * @version 1.0
 * @package Engine
 */
class HTTPResponse {

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const TIMEOUT = 408;
    const SERVICE_UNAVAILIBLE = 503;

    /**
     * Allows response code to be set only once.
     * @var booleam 
     */
    private $response_code_set = false;

    /**
     * Sets response code for response.
     * Response code should be only set once and for that we use $response_code_set variable
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @param integer $code
     * @return boolean
     */
    private function setResponseCode($code) {
        if (!$this->response_code_set) {
            http_response_code($code);
            return true;
        }
        return false;
    }

    /**
     * Should be used when action/request was successful
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function successful() {
        return $this->setResponseCode(self::OK);
    }

    /**
     * Should be used when authentication was successful
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function accepted() {
        return $this->setResponseCode(self::ACCEPTED);
    }

    /**
     * Should be used when user is not authenticated
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function unauthorized() {
        return $this->setResponseCode(self::UNAUTHORIZED);
    }

    /**
     * Should be used when timestamp differs more than allowed tolerance
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function timeout() {
        return $this->setResponseCode(self::TIMEOUT);
    }

    /**
     * Should be used when user is trying to use forbidden function
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function forbidden() {
        return $this->setResponseCode(self::FORBIDDEN);
    }

    /**
     * Should be used when user request is invalid
     * @author Lauri Orgla
     * @version 1.0
     * @package Engine
     * @return boolean
     */
    public function invalid() {
        return $this->setResponseCode(self::NOT_FOUND);
    }

}

?>