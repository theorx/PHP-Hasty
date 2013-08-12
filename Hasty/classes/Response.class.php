<?php

class Response {

    /**
     * @author Lauri Orgla
     * @version 1.0
     * @var /Request 
     */
    private $request;

    /**
     * Used for converting extension to function which converts output
     * @author Lauri Orgla
     * @version 1.0
     * @var mixed
     */
    private $conversion_map = array(
        'json' => 'toJson',
        'xml' => 'toXml',
        'phpserialize' => 'toSerialized',
        'print' => 'toPrint',
        'json64' => 'toJson64',
        'xml64' => 'toXml64'
    );

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
        $extension = strtolower($this->request->extension);
        if (key_exists($extension, $this->conversion_map) == true && method_exists($this, $this->conversion_map[$extension]) == true) {
            print $this->{$this->conversion_map[$extension]}($input);
        } else if (key_exists(Config::get('default_response_format'), $this->conversion_map) == true && method_exists($this, $this->conversion_map[Config::get('default_response_format')]) == true) {
            print $this->{$this->conversion_map[Config::get('default_response_format')]}($input);
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
     * Return result as print_r for development purposes.
     * @author Lauri Orgla
     * @version 1.0
     * @param mixed $input
     * @return type
     */
    public function toPrint($input) {
        if (Config::get('response_allow_print_method') == true) {
            return "<pre>" . print_r($input, true) . "</pre>";
        }
        return json_encode("print method disabled");
    }

}

?>