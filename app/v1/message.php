<?php

class message {

    public $title = "";
    public $time = "";
    public $body = "";

    public function __construct($title, $time, $body) { // give it user id, message id
        $this->title = $title;
        $this->time = $time;
        $this->body = $body;
        
        //reguest data from database
    }

}

?>
