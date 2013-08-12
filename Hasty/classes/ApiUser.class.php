<?php

class ApiUser {

    private static $id = 0;

    public static function setId($id) {
        self::$id = $id;
    }

    public static function getId() {
        return self::$id;
    }

    public static function getName() {
        
    }

    public static function getRights() {
        //get rights
    }

}

?>