<?php

class Post {

    public $subject = "Hi mom!";
    public $sender = "553242";
    public $receiver = "553221";
    public $timestamp = "Jun 14, 2001";
    public $body = "Hi mom how have you been? i havent heard from you a while.. could you contact me please?";
    public $headers = false;
    private $post_id = 0;

    public function __construct($post_id) {
        $this->post_id = $post_id;
    }

    public function delete() {
        return array("message number" => $this->post_id, "msg" => "This post has been deleted.");
    }

}

?>