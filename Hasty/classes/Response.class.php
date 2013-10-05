<?php

class Response {

    const OK = 600;
    const ERROR = 700;
    const FORBIDDEN = 900;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var /Request 
     */
    private $request;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * Indicates whether trap function has been called
     * @var boolean 
     */
    private static $trap = false;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed|string 
     */
    private static $trap_data;

    /**
     * Used for converting extension to function which converts output
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed
     */
    private $conversion_map = array(
        'json' => array('function' => 'toJson', 'header' => /* 'text/json' */ false),
        'xml' => array('function' => 'toXml', 'header' => 'text/xml'),
        'phpserialize' => array('function' => 'toSerialized', 'header' => false),
        'json64' => array('function' => 'toJson64', 'header' => false),
        'xml64' => array('function' => 'toXml64', 'header' => false),
        'html' => array('function' => 'toHtml', 'header' => 'text/html')
    );

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var integer 
     */
    private static $response_code = 100;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var string 
     */
    private static $response_status = self::OK;

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $message
     * @return boolean
     */
    public static function error($message) {
        self::$response_code = self::ERROR;
        self::$response_status = "ERROR";
        self::Trap($message);
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @return boolean
     */
    public static function forbidden() {
        self::$response_code = self::FORBIDDEN;
        self::$response_status = 'FORBIDDEN';
        self::Trap('Forbidden access');
        return true;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $request
     */
    public function setRequest(&$request) {
        $this->request = $request;
    }

    /**
     * Respond prints out output from api call. and parses conversion type
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     */
    public function respond($input) {
        if (self::$trap) {
            $input = self::$trap_data;
        }

        $response = new ResponseObject();
        $response->time = time();
        $response->data = $input;
        $response->status = self::$response_status;
        $response->code = self::$response_code;
        $input = $response;

        if (isset($this->request->extension)) {
            $extension = strtolower($this->request->extension);
        } else {
            $extension = "";
        }
        if (key_exists($extension, $this->conversion_map) == true && method_exists($this, $this->conversion_map[$extension]['function']) == true) {
            $this->setHeader($this->conversion_map[$extension]['header']);
            print $this->{$this->conversion_map[$extension]['function']}($input);
        } else if (key_exists(Config::get('default_response_format'), $this->conversion_map) == true && method_exists($this, $this->conversion_map[Config::get('default_response_format')]['function']) == true) {
            print $this->{$this->conversion_map[Config::get('default_response_format')]['function']}($input);
        } else {
            print json_encode(Config::get('conversion_failure_message')); // fallback default to json.
        }
        exit();
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return string
     */
    public function toJson($input) {
        return json_encode($input);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return string
     */
    public function toSerialized($input) {
        return serialize($input);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return string
     */
    public function toJson64($input) {
        return base64_encode(json_encode($input));
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return string
     */
    public function toXml($input) {
        $xmlgen = new XmlGenerator();

        return $xmlgen->xml($input);
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return string
     */
    public function toXml64($input) {
        $xmlgen = new XmlGenerator();

        return base64_encode($xmlgen->xml($input));
    }

    /**
     * 
     * @param type $input
     * @return type
     */
    public function toHtml($input) {
        $template = Config::get('engine_path') . DS . '..' . DS . 'templates' . DS . 'htmlOutput.php';
        $route_parts;
        foreach ($this->request->route as $node) {
            (strlen($node->class) > 0 ? $route_parts[] = $node->class : false);
            (strlen($node->function) > 0 ? $route_parts[] = $node->function : false);
            (strlen($node->param) > 0 ? $route_parts[] = $node->param : false);
        }
        $route = implode("/", $route_parts);
        if (file_exists($template)) {
            ob_start();
            include($template);
            $input = ob_get_contents();
            ob_end_clean();
        }
        return $input;
    }

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @param type $data
     * @return boolean
     */
    public static function Trap($data) {
        self::$trap = true;
        self::$trap_data = $data;

        return true;
    }

    /**
     * Sets content type with header
     * @author Lauri Orgla
     * @version 1.0
     * @param type $header
     * @return boolean;
     */
    public function setHeader($header) {
        if ($header != false) {
            header(sprintf('content-type: %s', $header));
            return true;
        }
        return false;
    }

}

?>