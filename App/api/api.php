<?php

use Firebase\JWT\JWT;
class api{
    public Core $Core;
    public $secret = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLGTFqaS34Q4oR1ew=';
    public $userId;

    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const TAXI = 'TAXI';
    private const PACKAGE = 'PACKAGE';

    public function __construct($Core) {
        $this->Core = $Core;
    }

    public function VIEW_activeDeliveries() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $driverId = $_GET["driver_id"] ?? null;

        $db = $this->Core->getDB();

        $sql = <<<SQL
            select o.id as order_id, o.shop_id, s.name as shop_name, s.lat, s.lng from orders o inner join shops s on s.id = o.shop_id where o.driver_id = ?
        SQL;

        $deliveries = $db->query($sql, array("i", $driverId), false);

        echo json_encode($deliveries, JSON_NUMERIC_CHECK);
    }

    public function VIEW_deliveries() {

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $db = $this->Core->getDB();

        $lat = ($_GET["lat"] ?? 0.000000);
        $lng = ($_GET["lng"] ?? 0.000000);

        $sql = <<<SQL
            /**
             * https://www.movable-type.co.uk/scripts/latlong.html
             * a = sin²(Δφ/2) + cos φ1 ⋅ cos φ2 ⋅ sin²(Δλ/2)
             * c = 2 ⋅ atan2( √a, √(1−a) )
             * d = R ⋅ c
             */
            with cte_distance as (
                with cte as (
                    select
                        o.id as order_id,
                        s.id as shop_id,
                        s.lat,
                        s.lng,
                        s.name as shop_name,
                        (select count(1) from orders_products op where op.order_id= o.id) as quantity_product,
                        
                        s.lat * 3.14/180 as driver_shop_q1,
                        $lat * 3.14/180 as driver_shop_q2,
                        ($lat - s.lat) * 3.14/180 driver_shop_d1,
                        ($lng - s.lng) * 3.14/180 driver_shop_d2,
            
                        o.lat * 3.14/180 as order_shop_q1,
                        s.lat * 3.14/180 as order_shop_q2,
                        (s.lat - o.lat) * 3.14/180 order_shop_d1,
                        (s.lng - o.lng) * 3.14/180 order_shop_d2
                    from
                                    orders 	o
                        inner join 	shops 	s on s.id = o.shop_id
                    where
                        o.driver_id is null
                        and 1=?
                )
                select
                    *,
                    atan2(sqrt(a_driver_shop), sqrt(1-a_driver_shop)) * 2 * 6371 as distance_driver_shop,
                    atan2(sqrt(a_order_shop), sqrt(1-a_order_shop)) * 2 * 6371 as distance_order_shop,
                    ((atan2(sqrt(a_driver_shop), sqrt(1-a_driver_shop)) * 2 * 6371) + (atan2(sqrt(a_order_shop), sqrt(1-a_order_shop)) * 2 * 6371)) as distance
                from
                    (select
                        *,
                        sin(driver_shop_d1/2) * sin(driver_shop_d1/2) + cos(driver_shop_q1) * cos(driver_shop_q2) * sin(driver_shop_d2/2) * sin(driver_shop_d2/2) as a_driver_shop,
                        sin(order_shop_d1/2) * sin(order_shop_d1/2) + cos(order_shop_q1) * cos(order_shop_q2) * sin(order_shop_d2/2) * sin(order_shop_d2/2) as a_order_shop
                    from 
                        cte
                    ) as a
            ) select order_id, shop_id, shop_name, quantity_product, distance_driver_shop, distance_order_shop, distance, lat, lng from cte_distance order by distance limit 20
        SQL;

        $deliveries = $db->query($sql, array("i", 1), false);

        echo json_encode($deliveries, JSON_NUMERIC_CHECK);
    }

    public function VIEW_startDelivery() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        $driverId = $data["driver_id"] ?? null;
        $orderId = $data["order_id"] ?? null;
        $vehicleId = $data["vehicle_id"] ?? null;

        if(empty($driverId)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        if(empty($orderId)) {
            http_response_code(422);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        if(empty($vehicleId)) {
            http_response_code(422);
            echo json_encode(["message" => "Vehicle is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $sql = <<<SQL
            update orders set driver_id = ?, vehicle_id = ?, delivery_started_at = CURRENT_TIMESTAMP where id = ?
        SQL;

        $db->query($sql, array('iii', $driverId, $vehicleId, $orderId), true);

        http_response_code(204);
    }


    public function VIEW_finishDelivery() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        $driverId = $data["driver_id"];
        $orderId = $data["order_id"];

        if(empty($driverId)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        if(empty($orderId)) {
            http_response_code(422);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $sql = <<<SQL
            update orders set driver_id = ?, delivery_finished_at = CURRENT_TIMESTAMP where id = ?
        SQL;

        $db->query($sql, array('ii', $driverId, $orderId));

        http_response_code(204);
    }

    public function VIEW_drivers() {
        $db = $this->Core->getDB();

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET, self::POST];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        if ($method == self::GET) {
            $vehicles = $db->query("SELECT * FROM drivers WHERE 1=?", array("i", 1), false);
            http_response_code(200);
            echo json_encode($vehicles, JSON_NUMERIC_CHECK);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        $username       = $data->username       ?? null;
        $name           = $data->name           ?? null;
        $password       = $data->password       ?? null;

        $user = $db->query("SELECT * FROM `user` WHERE username = ?", array('s', $username), false)[0];

        if ($method == 'POST') {
            if (empty($username)) {
                http_response_code(422);
                echo json_encode(["message" => "Username is required."]);
                return;
            }

            if (empty($name)) {
                http_response_code(422);
                echo json_encode(["message" => "Name is required."]);
                return;
            }

            if (empty($password) && empty($user)) {
                http_response_code(422);
                echo json_encode(["message" => "Password is required."]);
                return;
            }

            if (!empty($user)) {
                $userId = $user["id"];
                $driver = $db->query("select * from drivers where user_id = ?", array('i', $userId), false)[0];

                if(!empty($driver)) {
                    http_response_code(422);
                    echo json_encode(["message" => "Driver already exists."]);
                    return;
                }

            } else {
                $sql = <<<SQL
                    INSERT INTO user(username, password, `join`) VALUES (?, ?, CURRENT_DATE);
                SQL;
                $db->query($sql, array('ss', $username, password_hash($password, PASSWORD_DEFAULT)), true);
                $userId = $db->insert_id;
            }

            $sql = <<<SQL
                INSERT INTO drivers (user_id, `name`) VALUES (?, ?);
            SQL;

            $db->query($sql, array('ss', $userId, $name), true);

            http_response_code(201);
            echo json_encode(["id" => $db->insert_id]);

        }

        // password_hash($pass_1, PASSWORD_DEFAULT)
    }

    public function VIEW_vehicles() {
        $db = $this->Core->getDB();

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET, self::POST];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        if ($method == self::GET) {
            $driverId = $_GET["driver_id"] ?? null;
            if($driverId) {
                $vehicles = $db->query("SELECT * FROM vehicles WHERE driver_id = ?", array("i", $driverId), false);
            } else {
                $vehicles = $db->query("SELECT * FROM vehicles WHERE 1=?", array("i", 1), false);
            }
            http_response_code(200);
            echo json_encode($vehicles, JSON_NUMERIC_CHECK);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        $driverId       = $data->driver_id       ?? null;
        $type           = $data->type            ?? null;
        $lat            = $data->lat             ?? 0;
        $lng            = $data->lng             ?? 0;
        $hasBag         = $data->has_bag         ?? 0;
        $maxVolume      = $data->max_volume      ?? null;
        $maxDistance    = $data->max_distance    ?? null;
        $quantitySeat   = $data->quantity_seat   ?? null;

        if (empty($driverId)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        if (!in_array($type, [self::TAXI, self::PACKAGE])) {
            http_response_code(422);
            echo json_encode(["message" => "Type should be TAXI or PACKAGE."]);
            return;
        }

        $sql = <<<SQL
            INSERT INTO vehicles (
                driver_id,
                type,
                lat,
                lng,
                has_bag,
                max_volume,
                max_distance,
                quantity_seat
            ) values (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        SQL;

        $db->query($sql, array("iiiiiiii", $driverId, $type, $lat, $lng, $hasBag, $maxVolume, $maxDistance, $quantitySeat), true);
        http_response_code(201);
        echo json_encode(["id" => $db->insert_id], JSON_NUMERIC_CHECK);
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

