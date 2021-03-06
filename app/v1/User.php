<?php

class User {

    public $id, $name, $auth_key, $auth_secret;

    public function __construct($id = 0) {

        if ($id != 0) {
            $user_data = Sql::fetch('SELECT * FROM users WHERE id = :id', array(':id' => $id));
            if (isset($user_data->id)) {
                foreach ($user_data as $key => $val) {
                    $this->{$key} = $val;
                }
            }
        }
    }

    public function create() {
        Sql::query('INSERT INTO users (name, auth_key, auth_secret, user_group) VALUES(:name, :auth_key, :auth_secret, :user_group)', array(
            ':name' => $this->name,
            ':auth_key' => $this->auth_key,
            ':auth_secret' => $this->auth_secret,
            ':user_group' => 5
        ));

        return Sql::getLastInsertId();
    }

    public function posts_read($id = 0) {
        if ($id == 0) {
            return array("all_posts" => 1);
        } else {
            return new Post($id);
        }
    }

    public function friends_read($input = 0) {
        return "yo";
    }

    public function user_create() {
        Sql::query('INSERT INTO users (name, auth_key, auth_secret) VALUES(:name, :auth_key, :auth_secret)', array(
            ':name' => $this->name,
            ':auth_key' => $this->auth_key,
            ':auth_secret' => $this->auth_secret
        ));
        $this->id = Sql::getLastInsertId();
    }

    public function message_read() {
        return new message("test", "right now noob!", "this is message body... yo!");
    }

}

?>