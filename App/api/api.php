<?php

use Firebase\JWT\JWT;
class api{
    public Core $Core;
    private const SECRET = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLGTFqaS34Q4oR1ew=';
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const TAXI = 'TAXI';
    private const PACKAGE = 'PACKAGE';
    private const ALGORITHM = 'HS512';

    public function __construct($Core) {
        $this->Core = $Core;
    }

    public function VIEW_login() {

        require_once('Vendors/Composer/vendor/autoload.php');

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::POST];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $db = $this->Core->getDB();

        $post = json_decode(file_get_contents('php://input'), true);

        $data = $db->query("SELECT * FROM users WHERE username=? LIMIT 1",array("s", $post['username']),false);

        if(empty($data)) {
            echo json_encode(["message" => "Picker not found."]);
            http_response_code(401);
            return;
        }

        $picker = $data[0];

        if(!password_verify($post['password'], $picker['password'])) {
            echo json_encode(["message" => "Invalid password."]);
            http_response_code(401);
            return;
        }

        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+2880 minutes')->getTimestamp();   // 2 days
        $serverName = "pykme.com";

        $payload = [
            'iat'       => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'       => $serverName,                       // Issuer
            'nbf'       => $issuedAt->getTimestamp(),         // Not before
            'exp'       => $expire,                           // Expire
            'username'  => $post['username'],                         // User name
            'userId'    => $picker["id"]
        ];

        $token = JWT::encode(
            $payload,
            self::SECRET,
            self::ALGORITHM
        );

        echo json_encode([
            "token" => $token,
            "user" => [
                "id" => $picker["id"],
                "name" =>  $picker["name"],
                "username" =>  $picker["username"],
            ]
        ]);

    }

    public function VIEW_activeDeliveries() {
        $token = $this->checkToken();

        if(!$token){
            http_response_code(401);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $db = $this->Core->getDB();

        $sql = <<<SQL
            select
                o.id as order_id,
                o.shop_id,
                s.name as shop_name,
                s.lat,
                s.lng
            from
                            orders  o
                inner join  shops   s on s.id = o.shop_id
                inner join  drivers d on d.id = o.driver_id
            where
                    d.user_id = ?
              and (o.delivery_finished_at is null or o.delivery_finished_at = '')
        SQL;

        $deliveries = $db->query($sql, array("i", $token->userId), false);

        echo json_encode($deliveries ?? [], JSON_NUMERIC_CHECK);
    }

    public function VIEW_deliveries() {

        $token = $this->checkToken();

        if(!$token){
            http_response_code(401);
            return;
        }

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
        $token = $this->checkToken();

        if(!$token){
            http_response_code(401);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data)) {
            http_response_code(403);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        $orderId = $data["order_id"] ?? null;
        $vehicleId = $data["vehicle_id"] ?? null;

        if(empty($orderId)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        if(empty($vehicleId)) {
            http_response_code(403);
            echo json_encode(["message" => "Vehicle is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $isStarted = $db->query("select 1 from orders where id =? and delivery_started_at is not null", array('i', $orderId), false);

        if(!empty($isStarted)) {
            http_response_code(403);
            echo json_encode(["message" => "Order already started."]);
            return;
        }

        $hasActiveOrder = $db->query("select 1 from orders where driver_id = (select id from drivers where user_id = ?) and delivery_started_at is not null and delivery_finished_at is null", array('i', $token->userId), false);

        if(!empty($hasActiveOrder)) {
            http_response_code(403);
            echo json_encode(["message" => "Driver has active order."]);
            return;
        }

        $sql = <<<SQL
            update
                orders
            set 
                driver_id = (select id from drivers where user_id = ?),
                vehicle_id = ?,
                delivery_started_at = CURRENT_TIMESTAMP
            where id = ?
        SQL;

        $db->query($sql, array('iii', $token->userId, $vehicleId, $orderId), true);

        http_response_code(204);
    }


    public function VIEW_finishDelivery() {
        $token = $this->checkToken();

        if(!$token){
            http_response_code(401);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $orderId = $data["order_id"] ?? null;

        if(empty($orderId)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $isStarted = $db->query("select 1 from orders where id =? and delivery_started_at is not null", array('i', $orderId), false);

        if(empty($isStarted)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is not started."]);
            return;
        }

        $isFinished = $db->query("select 1 from orders where id =? and delivery_finished_at is not null", array('i', $orderId), false);

        if(!empty($isFinished)) {
            http_response_code(403);
            echo json_encode(["message" => "Order already finished."]);
            return;
        }

        $isAllowed = $db->query("select 1 from orders where driver_id = (select id from drivers where user_id = ?) and id = ?", array('ii', $token->userId, $orderId), false);

        if(empty($isAllowed)) {
            http_response_code(403);
            return;
        }

        $sql = <<<SQL
            update orders set driver_id = (select id from drivers where user_id = ?), delivery_finished_at = CURRENT_TIMESTAMP where id = ?
        SQL;

        $db->query($sql, array('ii', $token->userId, $orderId), true);

        http_response_code(204);
    }

    public function VIEW_cancelDelivery() {
        $token = $this->checkToken();

        if(!$token){
            http_response_code(401);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $orderId = $data["order_id"] ?? null;

        if(empty($orderId)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $isAllowed = $db->query("select 1 from orders where driver_id = (select id from drivers where user_id = ?) and id = ?", array('ii', $token->userId, $orderId), false);

        if(empty($isAllowed)) {
            http_response_code(403);
            echo json_encode(["message" => "Not allowed."]);
            return;
        }

        $isFinished = $db->query("select 1 from orders where  id = ? and delivery_finished_at is not null", array('i', $orderId), false);

        if(!empty($isFinished)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is finished."]);
            return;
        }

        $sql = <<<SQL
            update orders set driver_id = null, delivery_started_at = null where id = ?
        SQL;

        $db->query($sql, array('i', $orderId), true);

        http_response_code(204);
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
            $userId = $_GET["user_id"] ?? null;

            if($driverId) {
                $vehicles = $db->query("SELECT * FROM vehicles WHERE driver_id = ?", array("i", $driverId), false);
            } elseif($userId) {
                $vehicles = $db->query("SELECT v.* FROM vehicles v inner join drivers d on d.id = v.driver_id WHERE d.user_id = ?", array("i", $userId), false);
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
    
    private function checkToken(){
        require_once('Vendors/Composer/vendor/autoload.php');

        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return;
        }

        $jwt = $matches[1];

        if (!$jwt) {
            return;
        }

        try {
            $token = JWT::decode($jwt, self::SECRET, [self::ALGORITHM]);
            return $token;
        } catch (Exception $e) {}
    }
}

