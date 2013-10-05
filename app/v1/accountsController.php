<?php

class accountsController {

    public $callable = array("user", "test");

    public function __construct() {
        
    }

    public function user_update() {
        //check if info exists


        if (Request::data('data') != false) {
            $json = json_decode(Request::data('data'));
            $data = $json[0];
            if (isset($data->id, $data->name, $data->auth_key, $data->auth_secret)) {
                Sql::query('UPDATE users SET name= :name, auth_key=:auth_key, auth_secret=:auth_secret WHERE id=:id', array(
                    ':id' => $data->id,
                    ':name' => $data->name,
                    ':auth_secret' => $data->auth_secret,
                    ':auth_key' => $data->auth_key
                ));

                return array("success" => true);
            }
        }
        return array("success" => false);
    }

    public function user_read($id = 0) {
        if ($id == 0) {
            return array("records" => Sql::smartFetchAll('SELECT * FROM users '), "record_count" => Sql::fetch('SELECT COUNT(*) as count FROM users')->count);
        } else {
            return new User($id);
        }
    }

    public function user_delete($id = 0) {
        return "loll!!";
        if ($id != 0) {
            Sql::query("DELETE FROM users WHERE id = :id", array(":id" => $id));
            return array("msg" => "User " . $id . " is successfully deleted");
        } else {
            Sql::query("DELETE FROM users ");
            return array("msg" => "All users deleted");
        }
    }

    public function user_create() {
        print_r($_POST);
    }

}

?>