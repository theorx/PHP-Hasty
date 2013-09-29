<?php

class rpcController {
    
    public function __construct(){
        
    }
    
    public function activate_read($type = null){
        
        for($i = 0; $i < 1275; $i++){
            $data[] = md5($i);
        }
        
        return array("status" => "active", "data" => $data);
    }
    
    public function test_read($type = null){
        
        
        //crazy calculations 
        
        $result = 0.2354235235235;
        return array("message" => "dafuq?", "result" => $result);
        
    }
    
}

?>