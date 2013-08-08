<?php

class accountsController {

    public function user($id = 0) {
        if ($id == 0) {
            return array("all users");
            //return all
        } else {
            //return selected if exists

            $user = new user($id);
            $user->age = 22;
            $user->post_count = 45;
            $user->status = "online";
            $user->username = "OrX";
            return $user;
        }
    }

}

?>