<?php

class Response {

    public function respond($input) {
        //identify output
        //parse as output
        print $this->toJson($input);
        //output
    }

    public function toJson($input) {
        return json_encode($input);
    }

    public function toXml($input) {
        
    }

    public function toCsv($input) {
        
    }

}

?>