<?php

use Firebase\JWT\JWT;
class api{
    public $Core;
    public $secret = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLGTFqaS34Q4oR1ew=';
    public $userId;
    public function __construct($Core) {
        $this->Core = $Core;
    }

    public function VIEW_vehicles() {
        $db = $this->Core->getDB();
        $vehicles = $db->query("SELECT * FROM vehicles WHERE 1=?", array("i", 1), false);
        echo json_encode($vehicles, JSON_NUMERIC_CHECK);
    }

    public function VIEW_vehiclesOptions() {

        $db = $this->Core->getDB();

        if (array_key_exists("vehicle_id", $_GET)) {
            $vehicleId = $_GET["vehicle_id"];
            if ($vehicleId) {
                $sql = <<<SQL
                    SELECT * FROM vehicles_options WHERE vehicle_id = ?
                SQL;
                $vehicles = $db->query($sql, array("i", $_GET["vehicle_id"]), false);
                echo json_encode($vehicles, JSON_NUMERIC_CHECK);
                return;
            }
        }

        $vehicles = $db->query("SELECT * FROM vehicles_options WHERE 1=?", array("i", 1), false);
        echo json_encode($vehicles, JSON_NUMERIC_CHECK);
    }
    
    public function VIEW_pickers($action){
        $db = $this->Core->getDB();
        
        if($action == "checkLogin"){
            if($this->checkToken()){
                echo json_encode("valid");
            }
        }
        
        if($action == "logout"){
            if($this->checkToken()){
                $db->query("UPDATE pickerUser SET online=0 WHERE id=?",array("i",$this->userId),true);
            }
        }
        
        if($action == "login"){
            require_once('Vendors/Composer/vendor/autoload.php');
            $post = json_decode(file_get_contents('php://input'));
            $picker = $db->query("SELECT `id` FROM pickerUser WHERE mobile=? AND password=? LIMIT 1",array("ss",$post->{'mobile'},$post->{'password'}),false);
            if($picker){
                $secretKey  = $this->secret;
                $issuedAt   = new DateTimeImmutable();
                $expire     = $issuedAt->modify('+2880 minutes')->getTimestamp();   // 2 days  
                $serverName = "pykme.com";
                $username   = $post->{'mobile'};                                           

                $data = [
                    'iat'       => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
                    'iss'       => $serverName,                       // Issuer
                    'nbf'       => $issuedAt->getTimestamp(),         // Not before
                    'exp'       => $expire,                           // Expire
                    'userName'  => $username,                         // User name
                    'userId'    => $picker[0]["id"]
                ];
                
                echo json_encode(JWT::encode(
                        $data,
                        $secretKey,
                        'HS512'
                    ));    
            }   
        }
        
        if($action == "position"){
            $post = json_decode(file_get_contents('php://input'));
            if($this->checkToken() && $post){
                $db->query("UPDATE pickerUser SET position=? ,last_log=NOW(), ready=1 WHERE id=?",array("si",$post->{'lt'}.",".$post->{'lg'},$this->userId),true);
            }
        }
        
        if($action == "end-position"){
             if($this->checkToken()){
                 echo json_encode("trackend");
                $db->query("UPDATE pickerUser SET ready=0 WHERE id=?",array("i",$this->userId),true);
                
            }
        }
        
        if($action == "basic-info"){
            if($this->checkToken()){
                $picker = $db->query("SELECT id,mobile FROM pickerUser WHERE id=? LIMIT 1",array("i",$this->userId),false);
                echo json_encode($picker[0]);
            }else{
                
            }
        }
    }
    
    private function checkToken(){
        require_once('Vendors/Composer/vendor/autoload.php');
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            echo json_encode("expired");
            return false;
            /*header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';*/
        }
        $jwt = $matches[1];
        if (! $jwt) {
            // No token was able to be extracted from the authorization header
            echo json_encode("expired");
            return false;
            /*
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
             */
        }
        $secretKey  = $this->secret;
        try {
        $token = JWT::decode($jwt, $secretKey, ['HS512']);
        } catch (Exception $e) {
            echo json_encode("expired");
            return false;
            die();
        }
        $now = new DateTimeImmutable();
        $serverName = "pykme.com";

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            echo json_encode("expired");
            return false;
        }else{
            $this->userId = $token->userId;
            return true;
        }

    }
        
   
}

