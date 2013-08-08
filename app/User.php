<?php

class User {

    public $username = null;
    public $status = null;
    public $age = null;
    public $post_count = 0;

    public function posts($id = 0) {
        if ($id == 0) {
            return array("all_posts" => 1);
        } else {
            return new Post($id);
        }
    }

    public function friends($input = 0) {
        
    }

}

?>