<?php

class User {

    public $id, $name, $auth_key, $auth_secret;

    public function __construct($id = 0) {
        $d = A;
        if ($id != 0) {
            $user_data = Sql::fetch('SELECT * FROM users WHERE id = :id', array(':id' => $id));
            if (isset($user_data->id)) {
                foreach ($user_data as $key => $val) {
                    $this->{$key} = $val;
                }
            } else {
                Response::Trap(array("message" => "user not found"));
            }
        }
    }

    public function posts($id = 0) {
        if ($id == 0) {
            return array("all_posts" => 1);
        } else {
            return new Post($id);
        }
    }

    public function friends($input = 0) {
        
    }

    public function user_create() {
        Sql::query('INSERT INTO users (name, auth_key, auth_secret) VALUES(:name, :auth_key, :auth_secret)', array(
            ':name' => $this->name,
            ':auth_key' => $this->auth_key,
            ':auth_secret' => $this->auth_secret
        ));
        $this->id = Sql::getLastInsertId();
    }

    public function delete() {
        
    }

    public function message_read() {
        return new message("test", "right now noob!", "this is message body... yo!");
    }

}

?>