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
            'username'  => $post['username'],                     // User name
            'userId'    => $picker["id"]
        ];

        $token = JWT::encode(
            $payload,
            self::SECRET,
            self::ALGORITHM
        );

        $driver = $db->query("SELECT * FROM drivers WHERE user_id=?",array("i",$picker["id"]),false);

        $driverId = null;

        if (!empty($driver)) {
            $driverId = $driver[0]["id"];
        }

        echo json_encode([
            "token" => $token,
            "driver_id" => $driverId,
            "user" => [
                "id" => $picker["id"],
                "name" =>  $picker["name"],
                "username" =>  $picker["username"],
            ]
        ]);

    }

    public function VIEW_createOrder() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::POST];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data)) {
            http_response_code(403);
            echo json_encode(["message" => "Order is required."]);
            return;
        }

        $shopId = $data["shopId"] ?? null;
        $userId = $data["userId"] ?? null;
        $vehicleId = $data["vehicleId"] ?? null;
        $lat = $data["lat"] ?? null;
        $lng = $data["lng"] ?? null;
        $complement = $data["complement"] ?? null;
        $driverId = $data["driverId"] ?? null;
        $neighborhood = $data["neighborhood"] ?? null;
        $street = $data["street"] ?? null;
        $zipCode = $data["zipCode"] ?? null;
        $city = $data["city"] ?? null;
        $transportationType = $data["transportationType"] ?? null;

        $cart = $data["cart"] ?? null;

        $sql = <<<SQL
            INSERT INTO orders (shop_id, user_id, driver_id, vehicle_id, lat, lng, zip_code, city, street, neighborhood, address_complement, type, currency_id, payment_type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL;

        $db = $this->Core->getDB();

        $db->query($sql, array("iiiiddssssssis", $shopId, 1, $driverId, $vehicleId, $lat, $lng, $zipCode, $city, $street, $neighborhood, $complement, $transportationType, 2, 'CREDIT_CARD'), true);
        $orderId = $db->insert_id;

        foreach ($cart as $item) {
            $sql = <<<SQL
                INSERT INTO orders_products(order_id, product_id, amount, quantity)
                VALUES(?, ?, ?, ?)
            SQL;

            $db->query($sql, array("iiii", $orderId, $item["id"], $item["amount"], $item["quantity"]), true);

            $productId = $db->insert_id;

            $sql = <<<SQL
                INSERT INTO orders_products_options(orders_products_id, product_option_id) VALUES(?, ?)
            SQL;

            foreach ($item["options"] as $option) {
                $db->query($sql, array("ii", $productId, $option["id"]), true);
            }
        }

        http_response_code(202);
        echo json_encode(["order_id" => $orderId] , JSON_NUMERIC_CHECK);
    }

    public function VIEW_avaiableDrivers() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $lat = ($_GET["lat"] ?? 0.000000);
        $lng = ($_GET["lng"] ?? 0.000000);

        $sql = <<<SQL
            with cte_distance as (
                with cte as (
                    select
                        drivers.id as driver_id,
                        -- distance order to shop
                        shops.lat * 3.14/180 as order_shop_q1,
                        $lat * 3.14/180 as order_shop_q2,
                        ($lat - shops.lat) * 3.14/180 order_shop_d1,
                        ($lng - shops.lng) * 3.14/180 order_shop_d2,

                        -- distance driver to shop
                        drivers.lat * 3.14/180 as driver_shop_q1,
                        shops.lat * 3.14/180 as driver_shop_q2,
                        (shops.lat - drivers.lat) * 3.14/180 driver_shop_d1,
                        (shops.lng - drivers.lng) * 3.14/180 driver_shop_d2
                    from
                                    shops
                        cross join	drivers
                    where
                        shops.id = 59
                )
                select
                    *,
                    round(atan2(sqrt(a_driver_shop), sqrt(1-a_driver_shop)) * 2 * 6371, 2) as distance_driver_shop,
                    round(atan2(sqrt(a_order_shop), sqrt(1-a_order_shop)) * 2 * 6371, 2) as distance_order_shop
                from
                    (select
                        *,
                        sin(driver_shop_d1/2) * sin(driver_shop_d1/2) + cos(driver_shop_q1) * cos(driver_shop_q2) * sin(driver_shop_d2/2) * sin(driver_shop_d2/2) as a_driver_shop,
                        sin(order_shop_d1/2) * sin(order_shop_d1/2) + cos(order_shop_q1) * cos(order_shop_q2) * sin(order_shop_d2/2) * sin(order_shop_d2/2) as a_order_shop
                    from
                        cte
                    ) as a
            )
            select
                cte_distance.driver_id,
                vehicles.id as vehicle_id,
                users.name as driver_name,
                ifnull(distance_driver_shop, 0) as distance_driver_shop,
                ifnull(distance_order_shop, 0) as distance_order_shop,
                ifnull(distance_driver_shop, 0) + ifnull(distance_order_shop, 0) as distance,
                ifnull(distance_driver_shop, 0) + ifnull(distance_order_shop, 0) * ifnull(vehicles.price_per_distance, 0) as price
            from
                            cte_distance
                inner join  drivers 	on drivers.id = cte_distance.driver_id
                inner join  users 	    on users.id = drivers.user_id 
                inner join  vehicles    on vehicles.id = drivers.vehicle_id
            where
                vehicles.max_distance is null or
                vehicles.max_distance = 0 or
                vehicles.max_distance <= (ifnull(distance_driver_shop, 0) + ifnull(distance_order_shop, 0))
                and 1=?
            order by
                    distance limit 20
        SQL;

        $db = $this->Core->getDB();

        $drivers = $db->query($sql, array("i", 1), false);

        echo json_encode($drivers , JSON_NUMERIC_CHECK);

    }

    public function VIEW_drivers() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::POST];

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

        $name = $data["name"] ?? null;
        $username = $data["username"] ?? null;
        $password = $data["password"] ?? null;

        if(empty($name)) {
            http_response_code(422);
            echo json_encode(["message" => "Name is required."]);
            return;
        }

        if(empty($username)) {
            http_response_code(422);
            echo json_encode(["message" => "Username is required."]);
            return;
        }

        if(empty($password)) {
            http_response_code(422);
            echo json_encode(["message" => "Passowrd is required."]);
            return;
        }

        $db = $this->Core->getDB();

        $sql = <<<SQL
            INSERT INTO users (name, username, password)
            VALUES(?, ?, ?)
        SQL;

        $db->query($sql, array("sss", $name, $username, password_hash($password, PASSWORD_ARGON2I)), true);
        $userId = $db->insert_id;
        $sql = <<<SQL
            INSERT INTO drivers (user_id)
            VALUES(?)
        SQL;

        $db->query($sql, array("i", $userId), true);

        echo json_encode(["driver_id" => $db->insert_id, "user_id" => $userId] , JSON_NUMERIC_CHECK);

    }

    public function VIEW_findOrder() {
        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        $orderId = ($_GET["id"] ?? null);

        $sql = <<<SQL
            select * from orders where id = ?
        SQL;

        $db = $this->Core->getDB();

        $order = $db->query($sql, array("i", $orderId), false);

        if (sizeof($order) != 0) {
            echo json_encode($order[0], JSON_NUMERIC_CHECK);
            return;
        }

        echo json_encode([], JSON_NUMERIC_CHECK);
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
        $vehicleId = $_GET["vehicle_id"];

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
                        
                        o.zip_code,
                        o.city,
                        o.street,
                        o.street_number,
                        o.neighborhood,
                        o.address_complement,
                        
                        s.lat * 3.14/180 as driver_shop_q1,
                        $lat * 3.14/180 as driver_shop_q2,
                        ($lat - s.lat) * 3.14/180 driver_shop_d1,
                        ($lng - s.lng) * 3.14/180 driver_shop_d2,
            
                        o.lat * 3.14/180 as order_shop_q1,
                        s.lat * 3.14/180 as order_shop_q2,
                        (s.lat - o.lat) * 3.14/180 order_shop_d1,
                        (s.lng - o.lng) * 3.14/180 order_shop_d2,
                        (select sum(amount) from orders_products op where op.order_id = o.id) as order_amount
                    from
                                    orders 	o
                        inner join 	shops 	s on s.id = o.shop_id
                    where
                        o.driver_id = ? and o.status = 'CREATED'
                )
                select
                    *,
                    round(atan2(sqrt(a_driver_shop), sqrt(1-a_driver_shop)) * 2 * 6371, 2) as distance_driver_shop,
                    round(atan2(sqrt(a_order_shop), sqrt(1-a_order_shop)) * 2 * 6371, 2) as distance_order_shop
                from
                    (select
                        *,
                        sin(driver_shop_d1/2) * sin(driver_shop_d1/2) + cos(driver_shop_q1) * cos(driver_shop_q2) * sin(driver_shop_d2/2) * sin(driver_shop_d2/2) as a_driver_shop,
                        sin(order_shop_d1/2) * sin(order_shop_d1/2) + cos(order_shop_q1) * cos(order_shop_q2) * sin(order_shop_d2/2) * sin(order_shop_d2/2) as a_order_shop
                    from 
                        cte
                    ) as a
            ) 
            select
                order_id,
                shop_id,
                shop_name, 
                quantity_product,
                ifnull(distance_driver_shop, 0) as distance_driver_shop,
                ifnull(distance_order_shop, 0) as distance_order_shop,
                ifnull(distance_driver_shop, 0) + ifnull(distance_order_shop, 0) as distance,
                ifnull((select (ifnull(distance_driver_shop, 0) + ifnull(distance_order_shop, 0)) * v.price_per_distance from vehicles v where v.id = ?), 0) as price_distance,
                (ifnull(order_amount, 0) * .10) as price_order,
                lat,
                lng,
                zip_code,
                city,
                street,
                street_number,
                neighborhood,
                address_complement
            from
                cte_distance
            order by distance limit 20
        SQL;

        $deliveries = $db->query($sql, array("ii", $token->userId, $vehicleId), false);

        echo json_encode($deliveries ?? [], JSON_NUMERIC_CHECK);
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
                status = 'DRIVER_ACCEPTED',
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
            update orders set delivery_started_at = null, status = 'DRIVER_REJECTED' where id = ?
        SQL;

        $db->query($sql, array('i', $orderId), true);

        http_response_code(204);
    }

    public function VIEW_vehicles() {
        $token = $this->checkToken();
        if(!$token){
            http_response_code(401);
            return;
        }

        $db = $this->Core->getDB();

        $method = $_SERVER['REQUEST_METHOD'];
        $methodsAlloweds = [self::GET, self::POST, self::PUT];

        if (!in_array($method, $methodsAlloweds)) {
            http_response_code(405);
            return;
        }

        if ($method == self::GET) {
            $vehicles = $db->query("SELECT v.* FROM vehicles v inner join drivers d on d.id = v.driver_id WHERE d.user_id = ?", array("i", $token->userId), false);
            http_response_code(200);
            echo json_encode($vehicles ?? [], JSON_NUMERIC_CHECK);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        $id                 = $data->id                 ?? null;
        $driverId           = $data->driver_id          ?? null;
        $price_per_distance = $data->price_per_distance ?? 0;
        $name               = $data->name               ?? null;
        $type               = $data->type               ?? null;
        $lat                = $data->lat                ?? 0;
        $lng                = $data->lng                ?? 0;
        $hasBag             = $data->has_bag            ?? 0;
        $maxVolume          = $data->max_volume         ?? null;
        $maxDistance        = $data->max_distance       ?? null;
        $quantitySeat       = $data->quantity_seat      ?? null;

        if ($method == self::PUT) {

            if (empty($id)) {
                http_response_code(422);
                echo json_encode(["message" => "Vehicle is required."]);
                return;
            }

            if(!empty($driverId)) {
                $sql = "update vehicles set driver_id = ? where id = ?";
                $db->query($sql, array("ii", $driverId, $id), true);
            }

            if(!empty($price_per_distance)) {
                $sql = "update vehicles set price_per_distance = ? where id = ?";
                $db->query($sql, array("ii", $price_per_distance, $id), true);
            }

            if(!empty($name)) {
                $isAllowed = $db->query("select 1 from vehicles where name = ?", array('s', $name), false);

                if(!empty($isAllowed)) {
                    http_response_code(422);
                    echo json_encode(["message" => "Vehicle with this name already exists."]);
                    return;
                }

                $sql = "update vehicles set name = ? where id = ?";
                $db->query($sql, array("si", $name, $id), true);
            }

            if(!empty($type)) {
                $sql = "update vehicles set type = ? where id = ?";
                $db->query($sql, array("si", $type, $id), true);
            }

            if(!empty($lat)) {
                $sql = "update vehicles set  lat = $lat where id = ?";
                $db->query($sql, array("i", $id), true);
            }

            if(!empty($lng)) {
                $sql = "update vehicles set lng = $lng where id = ?";
                $db->query($sql, array("i", $id), true);
            }

            if(!empty($hasBag)) {
                $sql = "update vehicles set has_bag = ? where id = ?";
                $db->query($sql, array("ii", $hasBag, $id), true);
            }

            if(!empty($maxVolume)) {
                $sql = "update vehicles set max_volume = ? where id = ?";
                $db->query($sql, array("ii", $maxVolume, $id), true);
            }

            if(!empty($maxDistance)) {
                $sql = "update vehicles set max_distance = ? where id = ?";
                $db->query($sql, array("ii", $maxDistance, $id), true);
            }

            if(!empty($quantitySeat)) {
                $sql = "update vehicles set quantity_seat = ? where id = ?";
                $db->query($sql, array("ii", $quantitySeat, $id), true);
            }

            http_response_code(204);
            return;
        }

        if (empty($driverId)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver is required."]);
            return;
        }

        $driverExists = $db->query("select 1 from drivers where id = ?", array('i', $driverId), false);

        if(empty($driverExists)) {
            http_response_code(422);
            echo json_encode(["message" => "Driver not exists."]);
            return;
        }

        if (empty($name)) {
            http_response_code(422);
            echo json_encode(["message" => "Name is required."]);
            return;
        }

        if (!in_array($type, [self::TAXI, self::PACKAGE])) {
            http_response_code(422);
            echo json_encode(["message" => "Type should be TAXI or PACKAGE."]);
            return;
        }

        $isAllowed = $db->query("select 1 from vehicles where name = ?", array('s', $name), false);

        if(!empty($isAllowed)) {
            http_response_code(422);
            echo json_encode(["message" => "Vehicle with this name already exists."]);
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
                quantity_seat,
                price_per_distance,
                name
            ) values (
                ?,
                ?,
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

        $db->query($sql, array("isiiiiiiis", $driverId, $type, $lat, $lng, $hasBag, $maxVolume, $maxDistance, $quantitySeat, $price_per_distance, $name), true);
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

