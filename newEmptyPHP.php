<?php

class DB {
    private static $_instance;
    
    private function __construct(){
        //avoid instantiation since this is singleton
    }
    
    public static function getDB(){
        //get database configuration if required
        if(!isset(self::$_instance)){
            self::$_instance = new PDO("mysql:host=localhost;dbname=database", "dbuser", "dbpw");
        }
        return self::$_instance;
    }
    
}

class AccountManager {
    
   /**
    * This function is used for registering a member to database
    * @param string $username
    * @param string $password
    * @return string
    */
   public function register($username, $password){
       //implement password encryption
       
       $stmt = DB::getDB()->prepare("INSERT INTO members (user, pass) VALUES(:username, :password)");
       $stmt->execute(array(
           ":username" => $username,
           ":password" => md5($password) // nest in md5 with salt or any other digest hashing algorithm
       ));
       
       //get last insert ID
       return sprintf("You have created user with id: %d", DB::getDB()->lastInsertId());
   }   
   
   //add functions to edit, delete, view, disable users?
    
}

$manager = new AccountManager();
$manager->register("Username", "yourpassword");

?>