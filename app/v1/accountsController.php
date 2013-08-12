<?php

class accountsController {

    public $callable = array("user");

    public function __construct() {
        if (!Authentication::authenticate(Request::data('api-token'))) {

         //   die("failure");
        }
    }

    public function user($id = 0) {
        if ($id == 0) {
            if (Request::data('api-method') == "create") {
                if (Request::data('name') && Request::data('auth_key') && Request::data('auth_secret')) {
                    $user = new User();
                    $user->name = Request::data('name');
                    $user->auth_key = Request::data('auth_key');
                    $user->auth_secret = Request::data('auth_secret');
                    $user->create();
                }
            } else {
                $query_limit = '';
                if (Request::query('limit')) {
                    if (Request::query('start')) {
                        $query_limit = sprintf(' LIMIT %d, %d', Request::query('start'), Request::query('limit'));
                    } else {
                        $query_limit = sprintf(' LIMIT %d', Request::query('limit'));
                    }
                }
                $users_data = Sql::fetchAll('SELECT * FROM users ' . $query_limit);
                return $users_data;
            }

            return array();
        } else {
            return new user($id);
        }
    }

}

?>