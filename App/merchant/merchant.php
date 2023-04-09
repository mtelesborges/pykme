<?php

class merchant
{

    public Core $Core;
    public string $msg;


    public function __construct($Core)
    {
        $this->Core = $Core;

        if ($Core->FrontController->Router->Controller == "merchant" && empty($Core->FrontController->Router->Action)) {
            $this->VIEW_dashboard();
        }
    }

    public function VIEW_editProduct($productId): void
    {
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $ids = array_map(fn($shop) => $shop["shop_id"], $p->getAllShopTransportByProductId($productId));
        $data["shops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($ids);
        $data["productIsInShops"] = $data["inShops"];
        $data["inShopsAvailabe"] = $data["inShops"];
        $data["WholeProduct"] = $p->getWholeProduct($productId);
        $data["productType"] = $p->getProductType();
        $data["foodAllergies"] = $p->getFoodAllergies();
        $data["productCategories"] = $p->getProductCategories();
        $data["restrictions"] = []; //$p->getAllRestrictions();//category restrictions
        $data["productRestriction"] = $p->getAllProductRestriction();
        $data["Variations"] = $p->getAllVariations();
        $data["productProperties"] = $p->getAllProperties();
        $data["productOptions"] = $p->getAllOptions();
        $data["currency"] = $p->getAllCurrencies();
        $data["priceConditions"] = $p->getAllPriceConditions();
        $data["productPhysicalInfo"] = $p->getProductPhysicalInfo($productId);
        $data["preparationTime"] = $p->getProductPreparationTime($productId, 0);
        $this->renderEditProduct($data);
    }

    private function mountAddress($order): string
    {
        $address = [
            "street" => $order["street"],
            "neighborhood" => $order["neighborhood"],
            "city" => $order["city"],
            "addressComplement" => $order["address_complement"],
            "zipCode" => $order["zip_code"],
        ];

        $addressFiltered = array_filter($address, fn($value) => $value);

        return implode(", ", array_values($addressFiltered));
    }
    public function VIEW_orders(): void
    {
        $this->hasToBeLoggedIn();
        $query = <<<SQL
            SELECT
                T1.*,
                T3.username,
                T4.code as currency_code,
                (SELECT SUM(quantity) FROM orders_products T2 WHERE T2.order_id = T1.id) as quantity,
                (SELECT SUM(amount)   FROM orders_products T2 WHERE T2.order_id = T1.id) as amount
            FROM
                            orders      T1
                INNER JOIN  user        T3 ON T3.id = T1.user_id
                INNER JOIN  currency    T4 ON T4.id = T1.currency_id
            WHERE
                T1.shop_id in (select shop_id from shops where merchant_id =?)
            ORDER BY
                id DESC
        SQL;
        $db = $this->Core->getDB();
        $orders = $db->query($query, array("i", $_SESSION["merchant"]["merchantId"]), false);

        $orders = array_map(function($order) {
            $order["address"] = $this->mountAddress($order);
            return $order;
        }, $orders);

        $data = ["orders" => $orders];

        $this->renderOrders($data);
    }

    public function VIEW_orderDetail(): void
    {
        $this->hasToBeLoggedIn();

        $orderId = $this->Core->FrontController->Router->Parameters;
        $db = $this->Core->getDB();

        $query = <<<SQL
            SELECT
                T1.*,
                T3.username,
                T4.code as currency_code,
                (SELECT SUM(quantity) FROM orders_products T2 WHERE T2.order_id = T1.id) as quantity,
                (SELECT SUM(amount)   FROM orders_products T2 WHERE T2.order_id = T1.id) as amount
            FROM
                            orders      T1
                INNER JOIN  user        T3 ON T3.id = T1.user_id
                INNER JOIN  currency    T4 ON T4.id = T1.currency_id
            WHERE
                    T1.shop_id in (select shop_id from shops where merchant_id =?)
                AND T1.id =?
            ORDER BY
                id DESC
        SQL;

        $order = $db->query($query, array("ii", $_SESSION["merchant"]["merchantId"], $orderId), false);
        $order = $order[0];
        $order["address"] = $this->mountAddress($order);

        $query = <<<SQL
            SELECT
                T1.*,
                (SELECT title FROM productDescription T3 WHERE T3.product_id = T1.product_id AND T3.default = 1 LIMIT 1) as product_name
            FROM
                            orders_products T1
                INNER JOIN  orders          T2 ON T2.id = T1.order_id
            WHERE
                T2.shop_id in (select shop_id from shops where merchant_id =?) AND
                T1.order_id =?
            ORDER BY id ASC
        SQL;
        $sequence = 0;
        $products = $db->query($query, array("ii", $_SESSION["merchant"]["merchantId"], $orderId), false);
        $products = array_map(function($product) use(&$sequence) {
            $product["sequence"] = ++$sequence;
            return $product;
        }, $products);

        $products = array_map(function($product) use ($db){
            $query = <<<SQL
                select
                    T2.title bundle_title,
                    T5.title option_title
                from
                                orders_products_bundle 			T1
                    inner join 	productOptionBundleDescription 	T2 ON T2.pob_id 					= T1.option_bundle_id
                    inner join 	orders_products_bundle_options  T3 ON T3.orders_products_bundle_id 	= T1.id
                    inner join 	productOption_has_pob			T4 ON T4.id							= T3.option_id
                    inner join 	productOptionDescription 		T5 ON T5.productOption_id 			= T4.productOption_id 
                where
                    T1.orders_products_id = ?
            SQL;

            $query = <<<SQL
                select
                    pobd.title  as bundle_title,
                    pod.title   as option_title
                from
                                    orders_products_options         opo
                    inner   join    productOption                   po      on po.id                    = opo.product_option_id 
                    left    join    productOption_has_pob           pohp    on pohp.productOption_id    = opo.product_option_id 
                    left    join    productOptionBundleDescription  pobd    on pobd.pob_id              = pohp.pob_id 
                    inner   join    productOptionDescription        pod     on pod.productOption_id     = po.id
                where
                    opo.orders_products_id = ?
            SQL;


            $items = $db->query($query, array("i", $product["id"]), false);
            $options = [];

            foreach ($items as $item) {
                $options[$item["bundle_title"]][] = $item["option_title"];
            }

            foreach ($options as $key => $value) {
                $options[$key] = implode(", ", $value);
            }

            $product["options"] = $options;
            return $product;
        }, $products);

        $total = ["amount"=> 0, "quantity" => 0];
        array_map(function($product) use (&$total) {
            $total["amount"] += $product["amount"];
            $total["quantity"] += $product["quantity"];
        }, $products);

        $data = ["products" => $products, "orderId" => $orderId, "total" => $total, "order" => $order];

        $this->renderOrderDetail($data);
    }

    public function VIEW_drivers(): void
    {
        $this->hasToBeLoggedIn();

        $db = $this->Core->getDB();

        $query = <<<SQL
            SELECT
                T1.*,
                T2.name as country_name
            FROM
                            drivers     T1
                INNER JOIN  countries   T2 ON T2.id = T1.country_id
            WHERE
                1=?
        SQL;

        $drivers = $db->query($query, array('i', 1), false);

        $data = ["drivers" => $drivers];

        $this->renderDrivers($data);
    }

    public function VIEW_balance(): void
    {
        $this->hasToBeLoggedIn();

        $query = <<<SQL
            select
                o.shop_id,
                s.name as shop_name,
                sum(op.amount) * 0.02 + 0.5 as amount,
                sum(op.quantity) as quantity
            from
                            orders 			o
                inner join 	orders_products op  on op.order_id  = o.id
                inner join  shops           s   on s.id         = o.shop_id
            where
                s.merchant_id = ?
            group by
                o.shop_id
        SQL;

        $db = $this->Core->getDB();
        $balance = $db->query($query, array("i", $_SESSION["merchant"]["merchantId"]), false);
        $data = ["balance" => $balance];
        $this->renderBalance($data);
    }

    public function VIEW_registerProduct(): void
    {
        $this->hasToBeLoggedIn();
        if ($_POST) {
            require_once("helpers/manageProducts.php");
            $p = new manageProducts($this->Core);
            $p->registerProduct();
        }
    }

    public function VIEW_updateProduct()
    {
        $this->hasToBeLoggedIn();
        if ($_POST) {
            require_once("helpers/manageProducts.php");
            $p = new manageProducts($this->Core);
            $p->updateProduct();
        }
    }

    public function VIEW_showOrderOptions()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["activeVariations"] = $this->getVariationsByIds($_POST["activeVariations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["inShops"]);
        $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $data["variationInShops"] = $_POST["variationInShops"];
        $data["Core"] = $this->Core;
        return $this->Core->FrontController->partialRender("product-order-options.php", $data);
    }

    public function VIEW_showTransportation()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["activeVariations"] = $p->getVariationsByIds($_POST["activeVariations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["inShops"]);
        $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $data["variationInShops"] = $_POST["variationInShops"];
        $data["Core"] = $this->Core;
        return $this->Core->FrontController->partialRender("product-transportation.php", $data);
    }


    public function VIEW_createProductRestriction()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $p->createProductRestriction();
        $data["activeVariations"] = $p->getVariationsByIds($_POST["activeVariations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["inShops"]);
        $data["productRestriction"] = $p->getAllProductRestriction();
        $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $data["AllVariations"] = $p->getAllVariations();
        $data["Core"] = $this->Core;
        return $this->Core->FrontController->partialRender("product-restriction-list-" . $_POST["type"] . ".php", $data);
    }

    public function VIEW_showProductRestrictions()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["activeVariations"] = $p->getVariationsByIds($_POST["variations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);
        $data["productRestriction"] = $p->getAllProductRestriction();
        $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $data["AllVariations"] = $p->getAllVariations();
        $data["equipmentRestriction"] = $p->getEquipment();
        $data["vehiclesRestriction"] = $p->getVehicles();
        $data["Core"] = $this->Core;
        return $this->Core->FrontController->partialRender("product-restrictions.php", $data);
    }

    public function VIEW_showPriceConditions()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["activeVariations"] = $p->getVariationsByIds($_POST["variations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);
        $data["priceConditions"] = $p->getAllPriceConditions();
        $data["Core"] = $this->Core;
        $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);

        return $this->Core->FrontController->partialRender("product-price-conditions.php", $data);
    }

    public function VIEW_createPriceCondition()
    {
        $this->hasToBeLoggedIn();
        if ($_POST) {
            require_once("helpers/manageProducts.php");
            $p = new manageProducts($this->Core);
            $condition = $_POST["condition"];
            $name = "createCondition" . $condition;
            $data["activePriceCondition"] = $p->$name();
            $data["priceConditions"] = $p->getAllPriceConditions();
            $data["Core"] = $this->Core;
            $data["allShops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
            $data["inShops"] = $this->getShopBasicInfoByIds($_POST["availableShops"]);

            return $this->Core->FrontController->partialRender("product-condition-" . strtolower($condition) . "-list.php", $data);

        }
    }

    public function VIEW_updateProductoptions()
    {
        /* Updates the option part on add Product Page after new option or bundle is created */
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["productOptions"] = $p->getAllOptions();
        $data["currency"] = $p->getAllCurrencies();
        $data["foodAllergies"] = $p->getFoodAllergies();
        $data["Core"] = $this->Core;
        $data["activeVariations"] = $p->getVariationsByIds($_POST["variations"]);
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);

        return $this->Core->FrontController->partialRender("product-options.php", $data);
    }

    public function VIEW_addOption()
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["bundle"] = $p->createProductOption();
        $data["Core"] = $this->Core;
        $data["foodAllergies"] = $p->getFoodAllergies();
        $data["currency"] = $p->getAllCurrencies();
        return $this->Core->FrontController->partialRender("product-get-options-by-bundle.php", $data);

    }

    public function VIEW_addOptionBundle(): void
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["productOptions"] = $p->createOptionBundle();
        $data["Core"] = $this->Core;
        $data["foodAllergies"] = $p->getFoodAllergies();
        $data["currency"] = $p->getAllCurrencies();
        $this->Core->FrontController->partialRender("product-modal-productOptions.php", $data);
    }

    public function VIEW_products(): void
    {
        $this->hasToBeLoggedIn();
        // get products
        $data["shops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        $db = $this->Core->getDB();
        $products = $db->query("SELECT * FROM products WHERE merchant_id=?", array("i", $_SESSION["merchant"]["merchantId"]), false);
        include "helpers/manageProducts.php";
        $manageproducts = new manageProducts($this->Core);
        $data["products"] = $manageproducts->getAllProductsById($products);
        $this->renderProducts($data);

    }

    public function VIEW_addVariation()
    {
        $this->hasToBeLoggedIn();

        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);

        $data["Variations"] = $p->addVariation();

        if (!empty($data["Variations"])) {
            $data["Core"] = $this->Core;
            $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);
            return $this->Core->FrontController->partialRender("product-variation.php", $data);
        }
    }

    public function VIEW_getProductImages(): void
    {
        $this->hasToBeLoggedIn();
        $data["Core"] = $this->Core;
        $data["selectedVariations"] = $this->getVariationsByIds($_POST["variations"]);
        $this->Core->FrontController->partialRender("product-images.php", $data);
    }

    public function VIEW_getPreparationTime(): void
    {
        $this->hasToBeLoggedIn();
        $data["Core"] = $this->Core;
        $data["selectedVariations"] = $this->getVariationsByIds($_POST["variations"]);
        $this->Core->FrontController->partialRender("product-preparation-time.php", $data);
    }

    public function getVariationsByIds($variationsIds): array
    {
        $db = $this->Core->getDB();
        $variations = array();
        foreach ($variationsIds as $vId) {
            $variations[$vId]["info"]["id"] = $vId;
            $default = $db->query("SELECT * FROM productVariationDescription WHERE pv_id=? AND status !='deleted' AND `default`='1'", array("i", $vId), false);
            $variations[$vId]["description"] = $default[0];
        }
        return $variations;
    }

    public function getShopBasicInfoByIds($ids)
    {
        if ($ids) {
            $shops = array();
            $db = $this->Core->getDB();
            foreach ($ids as $id) {
                $shop = $db->query("SELECT id,name,description,logo,currency_id FROM shops WHERE id=?", array("i", $id), false);
                $address = $db->query("SELECT googleString FROM address WHERE shop_id=? AND status='active'", array("i", $id), false);
                $currency = $db->query("SELECT * FROM currency WHERE id=?", array("i", $shop[0]["currency_id"]), false);
                $membership = $db->query("SELECT membership_id FROM shop_has_membership WHERE shop_id=? AND status='active'", array("i", $id), false);
                $shops[$id] = $shop[0];
                $shops[$id]["address"] = $address[0];
                $shops[$id]["currency"] = $currency[0];
                $shops[$id]["membership"] = $membership[0];
            }
            return $shops;
        }
    }

    public function VIEW_getPriceList(): void
    {
        $this->hasToBeLoggedIn();
        $db = $this->Core->getDB();
        $shops = array();
        $variations = array();

        // get shop data
        $shops = $this->getShopBasicInfoByIds($_POST["ids"]);

        //get variation data
        if (isset($_POST["variations"])) {
            $variations = $this->getVariationsByIds($_POST["variations"]);
        }

        $data["Core"] = $this->Core;
        $data["productIsInShops"] = $shops;
        $data["selectedVariations"] = $variations;
        $data["variationInShops"] = $_POST["variationInShops"];
        $data["currency"] = $db->query("SELECT * FROM currency WHERE status=? ORDER BY name", array("s", "active"), false);


        $this->Core->FrontController->partialRender("product-price-list.php", $data);
    }

    public function VIEW_createRestriction(): void
    {
        $this->hasToBeLoggedIn();

        if ($_POST) {

            require_once("helpers/manageProducts.php");
            $p = new manageProducts($this->Core);
            $type = $_POST["type"];
            $create = "createRestriction" . $type;
            if (!$p->$create()) {
                echo "<p class='card-panel red darken-1'>" . $this->Core->Translator->translate("There was an error creating your $type restriction") . "</p>";
            }
            $data = array();
            $db = $this->Core->getDB();
            $data["restrictions"][$type] = $p->getRestrictionByType($type);
            $data["Core"] = $this->Core;

            $this->Core->FrontController->partialRender("restriction-list-$type.php", $data);


        }
    }

    public function VIEW_addProduct(): void
    {
        $this->hasToBeLoggedIn();
        $data["shops"] = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        if (empty($data["shops"])) {
            header("Location:/merchant/addShop");
            die();
        }

        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["productType"] = $p->getProductType();
        $data["foodAllergies"] = $p->getFoodAllergies();
        $data["productCategories"] = $p->getProductCategories();
        $data["restrictions"] = $p->getAllRestrictions();//category restrictions
        $data["productRestriction"] = $p->getAllProductRestriction();
        // $data["Variations"]                 = $p->getAllVariations();
        $data["productProperties"] = $p->getAllProperties();
        $data["productOptions"] = $p->getAllOptions();
        $data["currency"] = $p->getAllCurrencies();
        $data["priceConditions"] = $p->getAllPriceConditions();
        $this->renderAddProducts($data);
    }


    public function VIEW_getExpiryDate()
    {
        $this->hasToBeLoggedIn();
        if ($_POST) {
            $data["Core"] = $this->Core;
            $data["inShopsAvailabe"] = $this->getShopBasicInfoByIds($_POST["shops"]);
            if (!empty($_POST["variations"])) {
                require_once("helpers/manageProducts.php");
                $p = new manageProducts($this->Core);
                $data["variationsAvailable"] = $p->getVariationsByIds($_POST["variations"]);
            }
            return $this->Core->FrontController->partialRender("product-expiry-date.php", $data);
        }
    }

    public function VIEW_getCrossSelling()
    {
        $this->hasToBeLoggedIn();
        $data["Core"] = $this->Core;
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["variations"] = $p->getAllVariations();
        $data["products"] = $p->getProductsCrossSelling();
        return $this->Core->FrontController->partialRender("product-crossselling.php", $data);
    }

    public function VIEW_getVariations()
    {
        $this->hasToBeLoggedIn();
        $data["Core"] = $this->Core;
        $data["inShops"] = $this->getShopBasicInfoByIds($_POST["shops"]);
        $data["selectedVariations"] = $_POST["selectedVariations"];
        $data["selectedVariationsShop"] = $_POST["selectedVariationsShop"];
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["Variations"] = $p->getAllVariations();

        return $this->Core->FrontController->partialRender("product-variation.php", $data);
    }

    public function VIEW_addProductCategory(): void
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $p->addNewProductCategory();
    }

    public function VIEW_searchProductCategories(): void
    {
        $this->hasToBeLoggedIn();
        require_once("helpers/manageProducts.php");
        $p = new manageProducts($this->Core);
        $data["productCategories"] = $p->searchProductCategories($_POST["shop_id"], $_POST["name"]);
        $product_id = $_POST["product_id"];
        if($product_id) $data["WholeProduct"] = $p->getWholeProduct($product_id);
        $data["Core"] = $this->Core;
        $this->Core->FrontController->partialRender("product-category-list.php", $data);
    }


    public function VIEW_addShop(): void
    {
        $this->hasToBeLoggedIn();
        $weekday = $this->Core->weekday;
        if ($_POST) {
            include_once "helpers/manageShop.php";
            $shop = new manageShop($this->Core);
            $msg = $shop->addShop();
            $this->msg = $msg;
            $this->Core->FrontController->set_url("/merchant/shops");
            $this->VIEW_shops();

        } else {

            $db = $this->Core->getDB();
            $categories = $db->query("SELECT * FROM category WHERE status=?", array("s", "active"), false);
            $subCategories = $db->query("SELECT * FROM subCategory WHERE status=?", array("s", "active"), false);
            $currency = $db->query("SELECT * FROM currency WHERE status='active' ORDER BY ? ASC", array("s", "name"), false);

            $data["cat"] = $categories;
            $data["subCat"] = $subCategories;
            $data["weekday"] = $weekday;
            $data["currency"] = $currency;


            $this->renderAddShop(null, $data);
        }
    }

    public function VIEW_shops(): void
    {
        /*
      $db = $this->Core->getDB();
      $db->query("UPDATE shops set merchant_id=? WHERE status='on'",array("i",69),true);


                      $shops = $db->query("SELECT * FROM shops WHERE status=?",array("s","on"),false);

                      for($i = 0; $i < 1000 ; $i++){
                          foreach($shops as $shop){
                              $db->query("INSERT INTO shops VALUES(0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),'on',?)",array("isssissiiiddssiiii",
                                                              $_SESSION["merchant"]["merchantId"],
                                                              $shop["name"],
                                                              $shop["description"],
                                                              $shop["distanceSystem"],
                                                              $shop["currency_id"],
                                                              $shop["logo"],
                                                              $shop["timezone"],
                                                              $shop["noDelivery"],
                                                              $shop["pykmeDelivery"],
                                                              $shop["selfDelivery"],
                                                              $shop["lat"],
                                                              $shop["lng"],
                                                              $shop["orderByEmail"],
                                                              $shop["orderbySMS"],
                                                              $shop["cashPayment"],
                                                              $shop["onlineCredit"],
                                                              $shop["onDeliveryCredit"],
                                                              $shop["categoryId"]
                                                             ),true);
                          }

                      }

                  */
        $this->hasToBeLoggedIn();
        $shops = $this->getShopsByMerchant($_SESSION["merchant"]["merchantId"]);
        if (!empty($this->msg)) {
            $msg = $this->msg;
        } else {
            $msg = null;
        }
        $this->renderShops($msg, $shops);
    }


    public function VIEW_pay_membership($shopId = null, $msg = null): void
    {

        if (!empty($shopId)) {
            $db = $this->Core->getDB();
            $prices = $db->query("SELECT * From priceMemberships WHERE status=?", array("s", "active"), false);

            for ($i = 0; $i < count($prices); $i++) {
                $currency = $db->query("SELECT * FROM currency WHERE id=?", array("i", $prices[$i]["currency_id"]), false);
                $prices[$i]["currency"] = $currency[0];
            }
            $this->renderPayMembership($prices, $msg);
        }
    }

    public function VIEW_merchantResetPassword($token): void
    {
        if ($_POST) {
            $token = $_POST["token"];
            $pass_1 = $_POST["pass1"];
            $pass_2 = $_POST["pass2"];

            $db = $this->Core->getDB();
            $query = $db->query("SELECT * FROM resetMerchantPass WHERE token=? AND changed=0", array("s", $token), false);
            if ($query) {
                $reset = $query[0];
                if ($pass_1 == $pass_2) {
                    if ($db->query("UPDATE merchantUser SET pass=? WHERE id=?", array("si", password_hash($pass_1, PASSWORD_DEFAULT), $reset["merchantId"]), true)) {
                        $msg[] = [
                            "type" => "success",
                            "text" => $this->Core->Translator->translate("Your password has been reset. Please Login.")
                        ];
                        $this->renderLogin($msg);
                    }
                }
            } else {
                echo "FATAL ERROR";
                die();
            }
        } else {
            $this->renderMerchantResetPassword();

        }
    }

    public function VIEW_resetPassword(): void
    {
        if ($_POST) {
            $email = $_POST["email"];
            $db = $this->Core->getDB();
            $results = $db->query("SELECT * FROM merchantUser WHERE email=?", array("s", $email), false);
            if ($results) {
                $merchant = $results[0];
                $token = $this->generateRandomString();
                $db->query("INSERT INTO resetMerchantPass VALUES(0,?,?,0,NOW())", array("is", $merchant["id"], $token), true);
                if ($this->sendResetEmail($email, $token)) {
                    $msg[] = [
                        "type" => "success",
                        "text" => $this->Core->Translator->translate("Password reset link was sent to email!")
                    ];
                } else {
                    $msg[] = [
                        "type" => "error",
                        "text" => $this->Core->Translator->translate("There was an error sending you an Email!")
                    ];
                }

                $this->renderResetPassword($msg);

            } else {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Sorry, this E-Mail was not found on our databse.")
                ];
                $this->renderResetPassword($msg);
            }
        } else {
            $this->renderResetPassword();
        }
    }

    public function VIEW_dashboard(): void
    {
        $this->hasToBeLoggedIn();

        $db = $this->Core->getDB();

        $hasShops = $db->query("SELECT * FROM shops WHERE merchant_id=?", array("i", $_SESSION["merchant"]["merchantId"]), false);
        $hasProducts = $db->query("SELECT * FROM products WHERE merchant_id=?", array("i", $_SESSION["merchant"]["merchantId"]), false);
        $data = array();
        if (empty($hasShops)) {
            $shop = "0";
        } else {
            $shops = count($hasShops);
        }

        if (empty($hasProducts)) {
            $products = "0";
        } else {
            $products = count($hasProducts);
        }

        $getMerchantName = $db->query("SELECT name FROM merchantUser WHERE id=?", array("i", $_SESSION["merchant"]["merchantId"]), false);
        $name = $getMerchantName[0]["name"];

        $data = [
            "shops" => $shops,
            "products" => $products,
            "name" => $name
        ];


        $msg = null;
        $this->renderDashboard($msg, $data);

    }

    public function VIEW_login(): void
    {
        if ($_POST) {

            $email = $_POST["email"];
            $pass = $_POST["pass"];

            $db = $this->Core->getDB();
            $query = $db->query("SELECT * FROM merchantUser WHERE email=?", array("s", $email), false);
            $merchant = $query[0];
            if (password_verify($pass, $merchant["pass"])) {
                $_SESSION["merchant"]["merchantId"] = $merchant["id"];
                $_SESSION['merchant']['loggedIn'] = true;
                header("Location: /merchant/dashboard");
                session_write_close();
                session_regenerate_id(true);
            } else {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Email or Password wrong!"),
                ];
                $this->renderLogin($msg);
            }
        } else {
            $this->renderLogin();
        }
    }

    public function VIEW_verify(): void
    {
        if ($_POST) {
            $SMScode = $_POST["code"];
            $merchantId = $_POST["merchantId"];

            $db = $this->Core->getDB();

            $query = $db->query("SELECT * FROM merchantUser WHERE status='pending' AND id=?", array("i", $merchantId), false);
            $merchant = $query[0];
            if ($merchant["activationTries"] < 3) {
                if (password_verify($SMScode, $merchant['activationNumber'])) {


                    $db->query("UPDATE merchantUser SET status='active' WHERE id=?", array("i", $merchantId), true);
                    $_SESSION['merchant']["merchantId"] = $merchant["id"];
                    $_SESSION['merchant']['loggedIn'] = true;
                    mkdir("/View/upload/merchant/" . $merchant["id"], 0777);
                    header("Location: /merchant/dashboard");
                    session_write_close();
                    session_regenerate_id(true);
                    exit();

                } else {
                    $db->query("UPDATE merchantUser SET activationTries = activationTries + 1 WHERE id=?", array("i", $merchantId), true);
                    $msg[] = [
                        "type" => "error",
                        "text" => $this->Core->Translator->translate("Wrong verification code try again!"),
                        "id" => $merchantId
                    ];
                    $this->renderVerify($msg);
                }
            } else {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("You tried more then 3 Times to activate the account, your Account has been blocked. Please contact us at support@pykme.com")
                ];
                $this->renderSignup($msg);
            }

        } else {
            $this->renderVerify();
        }
    }

    public function VIEW_verifyAgain(): void
    {
        // @TODO not the best way to do it!!
        $merchantId = $_POST["merchantId"];
        $db = $this->Core->getDB();

        $code = mt_rand(100000, 999999);
        $hash = password_hash($code, PASSWORD_DEFAULT);
        if ($db->query("UPDATE merchantUser SET activationNumber=? WHERE id=? AND status='pending'", array("si", $hash, $merchantId), true)) {
            $query = $db->query("SELECT * FROM merchantUser WHERE status='pending' AND id=?", array("i", $merchantId), false);
            $merchant = $query[0];
            if ($this->sendVerificationCode($merchant['name'], $merchant['phone'], $code)) {
                $msg[] = [
                    "type" => "success",
                    "text" => $this->Core->Translator->translate("We sent you a new Code!")
                ];
                $this->renderVerify($msg);
            }
        } else {
            echo "Merchant not found";
        }

    }

    public function VIEW_signup(): void
    {
        if ($_POST) {


            $name = $_POST['name'];
            $fName = $_POST['fName'];
            $birthday = $_POST['birthday'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $pass_1 = $_POST['pass_1'];
            $pass_2 = $_POST['pass_2'];
            $full_number = $_POST["full_number"];
            $membership = $_POST["membership"];

            require_once("Core/SMS.php");
            $sms = new sms();
            if ($sms->checkPhone($full_number)) {
            } else {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate('Phonenumber is not valid or not mobile!')
                ];
                $this->renderSignup($msg);
                die();
            }

            if (empty($name) or empty($fName) or empty($birthday) or empty($email) or empty($full_number) or empty($pass_1) or empty($pass_2)) {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate('One or more Fields are empty')
                ];
                $this->renderSignup($msg);
                die();
            }

            if ($pass_1 === $pass_2) {

                $db = $this->Core->getDB();
                $checkEmail = $db->query("SELECT * FROM merchantUser WHERE email=?", array("s", $email), false);
                $checkPhone = $db->query("SELECT * FROM merchantUser WHERE phone=?", array("s", $full_number), false);
                if (!empty($checkEmail) || !empty($checkPhone)) {
                    $msg[] = [
                        "type" => "error",
                        "text" => $this->Core->Translator->translate("Email or Phone is registred")
                    ];
                    $this->renderSignup($msg);

                } else {
                    $code = mt_rand(100000, 999999);
                    $hash = password_hash($code, PASSWORD_DEFAULT);
                    if ($db->query("INSERT INTO merchantUser VALUES(0,?,?,?,?,?,?,?,'pending','0',NOW(),?)", array("ssssssss", $name, $fName, $birthday, $email, $full_number, password_hash($pass_1, PASSWORD_DEFAULT), $hash, $this->Core->Tracker->ip), true)) {
                        $merchant = $db->query("SELECT * FROM merchantUser WHERE email=? and phone=?", array("ss", $email, $full_number), false);

                        if ($this->sendVerificationCode($name, $full_number, $code)) {
                            if ($this->sendVerificationEmail($merchant[0])) {
                                header("Location: /merchant/verify/" . $merchant[0]["id"]);
                            } else {
                                $msg[] = [
                                    "type" => "error",
                                    "text" => $this->Core->Translator->translate("Email is invalid")
                                ];
                                $db->query("DELETE FROM merchantUser WHERE id=?", array("i", $merchant[0]["id"]), true);
                                $this->renderSignup($msg);
                                die();
                            }
                        } else {
                            $msg[] = [
                                "type" => "error",
                                "text" => $this->Core->Translator->translate("Phone is invalid")
                            ];
                            $db->query("DELETE FROM merchantUser WHERE id=?", array("i", $merchant[0]["id"]), true);
                            $this->renderSignup($msg);
                            die();
                        }
                    }
                }
            }

        } else {
            $this->renderSignup();
        }
    }

    public function VIEW_updateStatus(): void
    {
        $this->hasToBeLoggedIn();
        $shopId = $_POST["shopId"];
        $getStatus = $_POST["status"];

        if (!$getStatus) {
            $status = "off";
        }

        if ($getStatus) {
            $status = "on";
        }
        $db = $this->Core->getDB();
        if ($this->merchantHasShop($shopId)) {
            if ($db->query("UPDATE shops SET status=? WHERE id=?", array("si", $status, $shopId), true)) {
                $msg[] = [
                    "type" => "success",
                    "text" => $this->Core->Translator->translate("Status changed!") . " " . $subCat
                ];
            } else {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Could not update status") . " " . $subCat
                ];
            }
            $this->msg = $msg;
            $this->Core->FrontController->set_url("/merchant/shops");
            $this->VIEW_shops();

        }
    }

    public function VIEW_editshop(): void
    {
        $this->hasToBeLoggedIn();

        $shopId = $_POST["shopId"];
        if ($this->merchantHasShop($shopId)) {
            if (isset($_POST["load"])) {
                // post load is the name of part thats beeing called to update before user sumbited new data
                $partToEdit = "load_" . $_POST["load"];
            } elseif (!empty($_POST["edit"])) {
                //post edit is the name of part thats beeing called to updated after user submited new data
                $partToEdit = "edit_" . $_POST["edit"];
            }

            include_once("helpers/manageShop.php");
            $manageShop = new manageShop($this->Core);
            $response = $manageShop->$partToEdit($shopId);

            if (!empty($response)) {
                $this->msg = $response;
                $this->Core->FrontController->set_url("/merchant/shops");
                $this->VIEW_shops();

            }


        }

    }

    public function VIEW_chargeMembership($param): void
    {
        $this->hasToBeLoggedIn();


        $plan_id = $_POST["plan_id"];
        $currency = json_decode($_POST["currency"]); // Array
        $shop_id = $_POST["shop_id"];
        $token_id = $_POST["token_id"];
        $valid_until = null;
        $months = null;
        if ($this->merchantHasShop($shop_id)) {
            if ($plan_id == 1) {
                $membership = "1 Month";
                $valid_until = date("Y-m-d h:i:s", strtotime("now +30 days"));
                $months = 1;
            } elseif ($plan_id == 2) {
                $membership = "12 Months";
                $valid_until = date("Y-m-d h:i:s", strtotime("now +12 month"));
                $months = 12;
            }

            $db = $this->Core->getDB();
            $payments = $db->query("SELECT * FROM paidMemberships WHERE shop_id=?", array("i", $shop_id), false);

            if (!empty($payments)) {
                foreach ($payments as $payment) {
                    if ($payment["valid_until"] > date("Y-m-d h:i:s", strtotime("now +1 Month"))) {
                        $msg[] = [
                            "type" => "error",
                            "text" => $this->Core->Translator->translate("Your Membership is still valid for 1 Month. Please pay later;")
                        ];
                        $this->msg = $msg;
                        $this->Core->FrontController->set_url("/merchant/shops");
                        $this->VIEW_shops();
                        exit();
                        // @TODO test this
                    }
                }
            }


            $payment = $this->Core->getPayment();
            $charge = null;
            try {
                $charge = $payment->Stripe->charges->create([
                    'amount' => $currency->amount,
                    'currency' => $currency->code,
                    'description' => '#' . $shop_id . ' Professional Membership for ' . $membership,
                    'source' => $token_id,
                ]);
            } catch (\Exception $e) {
                $msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Your card was declined")
                ];
                $this->Core->FrontController->set_url("/merchant/pay_membership/" . $shop_id);
                $this->VIEW_pay_membership($shop_id, $msg);


                exit;
            }


            if ($charge["paid"]) {
                if ($db->query("INSERT INTO paidMemberships VALUES(0,?,?,?,?,NOW(),?,?,?,'Stripe')", array("isisssi", $shop_id, $charge["id"], $charge["amount_captured"], $charge["currency"], $valid_until, $charge["receipt_url"], $months), true)) {
                    if ($db->query("UPDATE shop_has_membership SET paid='1' WHERE shop_id=? AND status='active'", array("i", $shop_id), true)) {
                        $msg[] = [
                            "type" => "success",
                            "text" => $this->Core->Translator->translate("Your Membership has been paid! Thank you for supporting pykme! Here is your Receipt:") . "<a href='" . $charge["receipt_url"] . "' target='_blank' style='color:#000'>" . $this->Core->Translator->translate("See the Receipt") . "</a>"
                        ];
                        $this->msg = $msg;
                        $this->Core->FrontController->set_url("/merchant/shops");
                        $this->VIEW_shops();

                    } else {
                        echo "did not work2";
                    }
                } else {
                    echo "did not work1";
                }
            } else {
                echo "did not work";
            }
        }

    }

    /*
    HELPER FUNCTIONS
    */

    public function merchantHasShop($sId): bool
    {
        $this->hasToBeLoggedIn();
        $db = $this->Core->getDB();
        $query = $db->query("SELECT id, merchant_id FROM shops WHERE id=? AND merchant_id=?", array("ii", $sId, $_SESSION["merchant"]["merchantId"]), false);

        if (!empty($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function hasToBeLoggedIn()
    {
        if (isset($_SESSION["merchant"]["merchantId"]) && $_SESSION['merchant']['loggedIn']) {
            return true;
        } else {
            header("Location: /merchant/login");
            die();
            exit();
        }
    }

    public function getShopsByMerchant($mId): array
    {
        $db = $this->Core->getDB();
        $shops = $db->query("SELECT * FROM shops WHERE merchant_id=? AND status!='deleted'", array("i", $mId), false);
        $allShops = array();
        if (!empty($shops)) {
            foreach ($shops as $shop) {
                $i = $shop["id"];
                $allShops[$i] = $shop;
                $address = $db->query("SELECT googleString FROM address WHERE shop_id=? AND status='active' ORDER BY id DESC LIMIT 1", array("i", $shop["id"]), false);
                $allShops[$i]["address"] = $address[0];

                if ($db->query("SELECT * FROM deliverySameOpening WHERE shop_id=? AND status='active'", array("i", $shop["id"]), false)) {
                    $allShops[$i]["deliverySameOpening"] = true;
                } else {
                    $allShops[$i]["deliverySameOpening"] = false;
                }

                $membership = $db->query("SELECT * FROM shop_has_membership WHERE shop_id=? AND status='active'", array("i", $shop["id"]), false);
                $allShops[$i]["membership"] = $membership[0];

            }
        }
        return $allShops;

    }

    public function generateRandomString($length = 80): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function sendResetEmail($emailMerchant, $token): bool
    {
        $email = $this->Core->Email;

        $subject = $this->Core->Translator->translate("Reset your Password");
        $textRaw = $this->Core->Translator->translate("Reset your password with this Link:<br/>%link%");
        $link = "https://" . $_SERVER['HTTP_HOST'] . "/merchant/MerchantResetPassword/" . $token;

        $text = str_replace(
            array("%link%"),
            array($link),
            $textRaw
        );
        if ($email->send($emailMerchant, $subject, $text)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendVerificationEmail($merchant): bool
    {
        $email = $this->Core->Email;

        $subject = $this->Core->Translator->translate("Your Account was activated");
        $textRaw = $this->Core->Translator->translate("Hallo %name% ! <br/> Welcome to pykme.com. You Account is active now");

        $text = str_replace(
            array("%name%"),
            array($merchant["name"]),
            $textRaw
        );

        if ($email->send($merchant["email"], $subject, $text)) {
            return true;
        } else {
            return false;
        }

    }

    public function merchantIsLoggedIn(): bool
    {

        if ($_SESSION['merchant']['loggedIn'] === true) {
            return true;
        } else {
            return false;
        }
    }

    public function sendVerificationCode($name, $full_number, $code): bool
    {

        $translateMsg = $this->Core->Translator->translate("Hallo %name%! Your verification code is: %verifycode%");

        $msgSMS = str_replace(
            array("%name%", "%verifycode%"),
            array($name, $code),
            $translateMsg
        );

        require_once("Core/SMS.php");
        $sms = new sms();
        if ($sms->sendSMS($full_number, $msgSMS)) {
            return true;
        } else {
            return false;
        }
    }

    public function VIEW_logout(): void
    {
        session_destroy();
        header("Location:/");
    }


    /*
    RENDER FUNCTIONS
    */
    public function renderDrivers($data = null, $msg = null)
    {
        $view = [
            "File" => "merchant-drivers.php",
            "PageTitle" => $this->Core->Translator->translate("Drivers"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Drivers"),
            "Design" => "Merchant",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderBalance($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-balance.php",
            "PageTitle" => $this->Core->Translator->translate("Balance"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Balance"),
            "Design" => "Merchant",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderOrders($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-orders.php",
            "PageTitle" => $this->Core->Translator->translate("Orders"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Orders"),
            "Design" => "Merchant",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderOrderDetail($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-order-detail.php",
            "PageTitle" => $this->Core->Translator->translate("Order Detail"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Orders"),
            "Design" => "Merchant",
            "style" => null,
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderAddProducts($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-addproduct.php",
            "PageTitle" => $this->Core->Translator->translate("Add Product"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Products"),
            "Design" => "Merchant",
            "style" => array("croppie.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "croppie.js", "addProduct.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderEditProduct($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-editproduct.php",
            "PageTitle" => $this->Core->Translator->translate("Edit Product"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Products"),
            "Design" => "Merchant",
            "style" => array("croppie.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "croppie.js", "addProduct.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderProducts($data = null, $msg = null): void
    {
        $view = [
            "File" => "merchant-products.php",
            "PageTitle" => $this->Core->Translator->translate("Products"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Products"),
            "Design" => "Merchant",
            "style" => array("croppie.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "croppie.js"),
            "Keywords" => array(/*
                                            $this->Core->Translator->translate("Delivery"),
                                            $this->Core->Translator->translate("Restaurants")
                        */
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg,
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderPayMembership($prices, $msg = null): void
    {
        $view = [
            "File" => "merchant-paymembership.php",
            "PageTitle" => $this->Core->Translator->translate("Pay Membership"),
            "PageDescription" => $this->Core->Translator->translate("Pay pykme Membership"),
            "Design" => "Merchant",
            "style" => null/*array("")*/,
            "JS" => array(""),
            "Keywords" => array(/*
									$this->Core->Translator->translate("Delivery"),
									$this->Core->Translator->translate("Restaurants")
					*/
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg,
            "Prices" => $prices
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderMerchantResetPassword($msg = null): void
    {
        $view = [
            "File" => "merchant-reset_2.php",
            "PageTitle" => $this->Core->Translator->translate("Merchant Reset Password"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Lost Password"),
            "Design" => "Signup",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js"),
            "Keywords" => array(/*
									$this->Core->Translator->translate("Delivery"),
									$this->Core->Translator->translate("Restaurants")
					*/
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderResetPassword($msg = null): void
    {
        $view = [
            "File" => "merchant-reset_1.php",
            "PageTitle" => $this->Core->Translator->translate("Merchant Reset Password"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Lost Password"),
            "Design" => "Signup",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js"),
            "Keywords" => array(/*
									$this->Core->Translator->translate("Delivery"),
									$this->Core->Translator->translate("Restaurants")
					*/
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderLogin($msg = null): void
    {
        $view = [
            "File" => "merchant-login.php",
            "PageTitle" => $this->Core->Translator->translate("Merchant Login"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Login"),
            "Design" => "Signup",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js"),
            "Keywords" => array(/*
									$this->Core->Translator->translate("Delivery"),
									$this->Core->Translator->translate("Restaurants")
					*/
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderVerify($msg = null): void
    {
        $view = [
            "File" => "merchant-verify.php",
            "PageTitle" => $this->Core->Translator->translate("Merchant Verify"),
            "PageDescription" => $this->Core->Translator->translate("Merchant verification"),
            "Design" => "Signup",
            "style" => null/*array("")*/,
            "JS" => array("jquery.validate.min.js"),
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Restaurants")
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderSignup($msg = null): void
    {
        $view = [
            "File" => "merchant-signup.php",
            "PageTitle" => $this->Core->Translator->translate("Merchant Signup"),
            "PageDescription" => $this->Core->Translator->translate("Welcome to pykme.com"),
            "Design" => "Signup",
            "CSS" => array("intlTelInput.min.css"),
            "JS" => array("jquery.validate.min.js", "intlTelInput.min.js"),
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Restaurants")
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderDashboard($msg = null, $data = null): void
    {
        $view = [
            "File" => "merchant-dashboard.php",
            "PageTitle" => $this->Core->Translator->translate("Dashboard"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Dashboard"),
            "Design" => "Merchant",
            "CSS" => null,
            "JS" => null /*array("jquery.validate.min.js")*/,
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Restaurants")
            ),
            "Data" => array("results"),
            "Core" => $this->Core,
            "Message" => $msg,
            "Data" => $data
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderShops($msg = null, $data = null): void
    {
        $view = [
            "File" => "merchant-shops.php",
            "PageTitle" => $this->Core->Translator->translate("My Shops"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Shops"),
            "Design" => "Merchant",
            "CSS" => array("intlTelInput.min.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "intlTelInput.min.js"),
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Restaurants")
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

    public function renderAddShop($msg = null, $data = null): void
    {
        $view = [
            "File" => "merchant-addshop.php",
            "PageTitle" => $this->Core->Translator->translate("Add Shop"),
            "PageDescription" => $this->Core->Translator->translate("Merchant Shops"),
            "Design" => "Merchant",
            "CSS" => array("intlTelInput.min.css", "addShop.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "intlTelInput.min.js"),
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Restaurants")
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => $msg
        ];
        $this->Core->FrontController->render($view);
    }

}