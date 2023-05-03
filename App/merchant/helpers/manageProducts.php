<?php

class manageProducts
{

    public Core $Core;

    public function __construct($Core)
    {
        $this->Core = $Core;

    }

    public function getAllProductsById($products): array
    {
        $data["products"] = array();
        foreach ($products as $product) {
            $data["products"][] = $this->getProductById($product["id"]);
        }
        return $data["products"];
    }


    public function getProductsByCategory($shopId, $catId, $propertiesName = [], $allergiesName = []): array
    {
        $db = $this->Core->getDB();

        $products = array();
        if (!empty($propertiesName) || !empty($allergiesName)) {
            $sql = <<<SQL
                select
                    shp.product_id, shp.variation_id
                from
                    shop_has_products 		shp
                where
                    shp.shop_id=?               AND
                    shp.productCategory_id=?    AND
                    shp.status='active'
            SQL;

            $arguments = [$shopId, $catId];

            if (!empty($propertiesName)) {
                $sql = <<<SQL
                    $sql
                    and
                    shp.product_id not in (
                        select
                            php.product_id
                        from
                                        product_has_properties  php
                            inner join 	productProperties 		pp 	on pp.id 			= php.property_id
                        where
                            php.product_id = shp.product_id
                            and pp.name in (?)
                    )
                SQL;

                $arguments[] = "'" . implode("','", $propertiesName) . "'";
            }

            if (!empty($allergiesName)) {
                $sql = <<<SQL
                    $sql
                    and
                    shp.product_id not in (
                        select
                            pha.product_id
                        from
                                        product_has_allergies   pha
                            inner join  foodAllergies           fa on fa.id = pha.allergy_id
                        where
                                pha.product_id = shp.product_id
                            and fa.name in (?) 
                    )
                SQL;

                $arguments[] = "'" . implode("','", $allergiesName) . "'";
            }

            $sql = <<<SQL
                $sql
                ORDER BY position ASC
            SQL;

            array_unshift($arguments, implode("", array_map(fn($item) => is_numeric($item) ? "i" : "s", $arguments)));
            $getProducts = $db->query($sql, $arguments, false);
        } else {
            $getProducts = $db->query("SELECT product_id,variation_id FROM shop_has_products WHERE shop_id=? AND productCategory_id=? AND status='active' ORDER BY position ASC", array("ii", $shopId, $catId), false);
        }

        if ($getProducts) {
            foreach ($getProducts as $p) {

                if ($p["variation_id"] == 0) {
                    // default product
                    $products[$p["product_id"]]["default"] = $this->getProductById($p["product_id"], $shopId);
                } else {
                    // variation
                    $products[$p["product_id"]]["variation"][$p["variation_id"]] = $this->getVariationById($p["variation_id"]);
                }

            }
        }
        return $products;
    }

    public function getAllProductOptions($pId): array
    {
        $db = $this->Core->getDB();
        $getOptions = $db->query("SELECT * FROM product_has_options WHERE product_id=? AND status='active'", array("i", $pId), false);
        $options = [];
        foreach ($getOptions as $key => $o) {
            $options[$key]["Info"] = $o;
            //$options[$key][] = $this->getOptionBundleById($o["optionBundle_id"]);
        }
        return $options;
    }

    public function getProductOptions($pId, $varId, $shopId): array
    {
        $db = $this->Core->getDB();
        $getOptions = $db->query("SELECT optionBundle_id FROM product_has_options WHERE product_id=? AND variation_id=? AND shop_id=? AND status='active'", array("iii", $pId, $varId, $shopId), false);
        $options = [];
        foreach ($getOptions as $o) {
            $options = $this->getOptionBundleById($o["optionBundle_id"]);
        }
        return $options;

    }

    public function getAllProductProperties($pId): array
    {
        $db = $this->Core->getDB();
        $getProperties = $db->query("SELECT * FROM product_has_properties WHERE product_id=? AND status='active'", array("i", $pId), false);
        $properties = [];

        foreach ($getProperties as $key => $pr) {
            $properties[$key]["Info"] = $pr;
            $query = $db->query("SELECT * FROM productProperties WHERE id=? AND status='active'", array("i", $pr["property_id"]), false);
            $properties[$key] = $query[0];
        }
        return $properties;
    }

    public function getProductProperties($pId, $shopId = 0): array
    {
        $db = $this->Core->getDB();

        $getProperties = $db->query("SELECT property_id FROM product_has_properties WHERE product_id=? AND status='active'", array("i", $pId), false);
        $properties = [];

        foreach ($getProperties as $pr) {
            $query = $db->query("SELECT name FROM productProperties WHERE id=? AND status='active'", array("i", $pr["property_id"]), false);
            $properties[] = $query[0];
        }
        return $properties;
    }

    public function getAllProductAllergies($pId, $shopId = 0): array
    {
        $db = $this->Core->getDB();

        $getAllergies = $db->query("SELECT * FROM product_has_allergies WHERE product_id=?", array("i", $pId), false);
        $allergies = [];
        foreach ($getAllergies as $key => $a) {
            $allerfies[$key]["Info"] = $a;
            $query = $db->query("SELECT * FROM foodAllergies WHERE id=? LIMIT 1", array("i", $a["allergy_id"]), false);
            $allergies[$key] = $query[0];
        }
        return $allergies;
    }


    public function getProductAllergies($pId, $shopId = 0): array
    {
        $db = $this->Core->getDB();

        $getAllergies = $db->query("SELECT allergy_id FROM product_has_allergies WHERE product_id=?", array("i", $pId), false);
        $allergies = [];
        foreach ($getAllergies as $a) {
            $query = $db->query("SELECT name FROM foodAllergies WHERE id=? LIMIT 1", array("i", $a["allergy_id"]), false);
            $allergies[] = $query[0];
        }
        return $allergies;
    }

    public function getAllProductPriceConditions($pId)
    {
        $db = $this->Core->getDB();
        $priceConditions = [];
        $getConditions = $db->query("SELECT * FROM product_has_conditions WHERE product_id=? AND status='active'", array("i", $pId), false);

        foreach ($getConditions as $key => $c) {

            $query = $db->query("SELECT * FROM priceConditions WHERE id=? AND status='active'", array("i", $c["condition_id"]), false);
            if (!empty($query)) {
                $priceConditions[$key]["Info"] = $c;
                $priceConditions[$key] = $query[0];
            }
        }
        // unserialize Objects
        foreach ($priceConditions as $key => $pc) {
            $priceConditions[$key]["object"] = unserialize($pc["object"]);
        };
        return $priceConditions;
    }

    public function getProductPriceConditions($pId): array
    {
        $db = $this->Core->getDB();
        $priceConditions = [];
        $getConditions = $db->query("SELECT condition_id FROM product_has_conditions WHERE product_id=? AND status='active'", array("i", $pId), false);

        foreach ($getConditions as $c) {
            $query = $db->query("SELECT id,type,object FROM priceConditions WHERE id=? AND status='active'", array("i", $c["condition_id"]), false);
            if (!empty($query)) {
                $priceConditions[] = $query[0];
            }
        }
        // unserialize Objects
        foreach ($priceConditions as $key => $pc) {
            $priceConditions[$key]["object"] = unserialize($pc["object"]);
        };
        return $priceConditions;
    }

    public function getProductRestrictions($pId): array
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        // normal restrictions
        $getRestrictions = $db->query("SELECT restriction_id FROM product_has_restrictions WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getRestrictions)) {
            foreach ($getRestrictions as $r) {
                $query = $db->query("SELECT id,type,object FROM productRestrictions WHERE id=? AND status='active'", array("i", $r["restriction_id"]), false);
                if (!empty($query)) {
                    $restrictions[] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getAllProductRestrictions($pId): array
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        // normal restrictions
        $getRestrictions = $db->query("SELECT * FROM product_has_restrictions WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getRestrictions)) {
            foreach ($getRestrictions as $key => $r) {
                $query = $db->query("SELECT * FROM productRestrictions WHERE id=? AND status='active'", array("i", $r["restriction_id"]), false);
                if (!empty($query)) {
                    $restrictions[$key]["Info"] = $r;
                    $restrictions[$key] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getProductEquipment($pId): array
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        // equipment
        $getEquipment = $db->query("SELECT equipment_id FROM product_has_equipment WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getEquipment)) {
            foreach ($getEquipment as $e) {
                $query = $db->query("SELECT id,name FROM restriction_Equipment WHERE id=? AND status='active'", array("i", $e["equipment_id"]), false);
                if (!empty($query)) {
                    $restrictions[] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getAllProductEquipment($pId): array
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        // equipment
        $getEquipment = $db->query("SELECT * FROM product_has_equipment WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getEquipment)) {
            foreach ($getEquipment as $key => $e) {
                $query = $db->query("SELECT * FROM restriction_Equipment WHERE id=? AND status='active'", array("i", $e["equipment_id"]), false);
                if (!empty($query)) {
                    $restrictions[$key]["Info"] = $e;
                    $restrictions[$key] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getProductVehicles($pId): array
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        //exclude vehicles
        $getVehicles = $db->query("SELECT vehicle_id FROM product_exclude_vehicles WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getVehicles)) {
            foreach ($getVehicles as $v) {
                $query = $db->query("SELECT id,name,green FROM vehicles WHERE id=? AND status='active'", array("i", $v["vehicle_id"]), false);
                if (!empty($query)) {
                    $restrictions[] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getAllProductVehicles($pId)
    {
        $db = $this->Core->getDB();
        $restrictions = [];
        //exclude vehicles
        $getVehicles = $db->query("SELECT * FROM product_exclude_vehicles WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getVehicles)) {
            foreach ($getVehicles as $key => $v) {
                $query = $db->query("SELECT * FROM vehicles WHERE id=?", array("i", $v["vehicle_id"]), false);
                if (!empty($query)) {
                    $restrictions[$key]["Info"] = $v;
                    $restrictions[$key] = $query[0];
                }
            }
        }
        return $restrictions;
    }

    public function getProductTransport($pId, $varId, $shopId)
    {
        $db = $this->Core->getDB();

        $getTransport = $db->query("SELECT hasTransportation, hasTransportOnly FROM product_has_shop_transport WHERE product_id=? AND variation_id=? AND shop_id=? AND status='active'", array("iii", $pId, $varId, $shopId), false);
        if (!empty($getTransport)) {
            return $getTransport[0];
        }
    }

    public function getAllProductTransport($pId)
    {
        $db = $this->Core->getDB();
        $getTransport = $db->query("SELECT * FROM product_has_shop_transport WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getTransport)) {
            return $getTransport;
        }
    }

    public function getProductOrderOptions($pId, $varId, $shopId)
    {
        $db = $this->Core->getDB();

        $getOrderOptions = $db->query("SELECT onlyCredit,onlyCash FROM product_has_orderOptions WHERE product_id=? AND variation_id=? AND shop_id=? AND status='active'", array("iii", $pId, $varId, $shopId), false);
        if (!empty($getOrderOptions)) {
            return $getOrderOptions[0];
        }
    }

    public function getAllProductOrderOptions($pId)
    {
        $db = $this->Core->getDB();

        $getOrderOptions = $db->query("SELECT * FROM product_has_orderOptions WHERE product_id=? AND status='active'", array("i", $pId), false);
        if (!empty($getOrderOptions)) {
            return $getOrderOptions;
        }
    }

    public function getProductExpiryDates($pId, $varId, $shopId)
    {
        $db = $this->Core->getDB();
        $getExpiry = $db->query("SELECT date,product_amount FROM product_has_expiry WHERE product_id=? AND variation_id=? AND shop_id=? AND status='active'", array("iii", $pId, $varId, $shopId), false);
        return $getExpiry;
    }

    public function getAllProductExpiryDates($pId)
    {
        $db = $this->Core->getDB();
        $getExpiry = $db->query("SELECT * FROM product_has_expiry WHERE product_id=? AND status='active'", array("i", $pId), false);
        return $getExpiry;
    }

    public function getProductPreparationTime($pId, $varId)
    {
        $db = $this->Core->getDB();
        $getPreparation = $db->query("SELECT * FROM product_has_time WHERE product_id=? AND variation_id=? AND status='active'", array("ii", $pId, $varId), false);
        return $getPreparation[0];
    }

    public function getAllProductPreparationTime($pId): array
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM product_has_time WHERE product_id=? AND status='active'", array("i", $pId), false);
    }

    private function mapProductCrosseling($pId, $product): array
    {
        return [
            "product_id" => $pId,
            "product" => $product,
            "description" => $this->getProductDescriptions($pId),
            "inShops" => $this->getProductsInShops($pId),
            "prices" => $this->getProductPrices($pId),
            "imgs" => $this->getProductImages($pId)
        ];
    }

    public function getProductCrossSelling($pId, $varId, $shopId): array
    {
        $db = $this->Core->getDB();
        $getCrossSelling = $db->query("SELECT product FROM product_has_crossselling WHERE product_id=? AND variation=? AND shop=? AND status='active'", array("iii", $pId, $varId, $shopId), false);

        if (empty($getCrossSelling)) {
            return [];
        }

        return array_map(fn($item) => $this->mapProductCrosseling($pId, $item["product"]), $getCrossSelling);
    }

    public function getAllProductCrossSelling($pId): array
    {
        $db = $this->Core->getDB();
        $getCrossSelling = $db->query("SELECT * FROM product_has_crossselling WHERE product_id=? AND status='active'", array("i", $pId), false);

        if (empty($getCrossSelling)) {
            return [];
        }

        return array_map(fn($item) => $this->mapProductCrosseling($pId, $item["product"]), $getCrossSelling);
    }

    public function getAllProductCategotries($pId): array
    {
        $db = $this->Core->getDB();

        $query = $db->query("SELECT * FROM shop_has_products WHERE product_id=?", array("i", $pId), false);

        $categories = array();

        // get categories ID
        foreach ($query as $result) {
            if (!in_array($result["productCategory_id"], $categories)) {
                $categories[] = $result["productCategory_id"];
            }
        }

        // Get Results
        $result = array();

        foreach ($categories as $key => $cId) {

            $cat = $db->query("SELECT * FROM productCategory WHERE id=?", array("i", $cId), false);

            $result[$key]["Info"] = $cat[0];

            $shopHasCategory = $db->query("SELECT * FROM shop_has_productCategory WHERE productCategory_id = ?", array("i", $cId), false);

            $result[$key]["ShopHasCategory"] = $shopHasCategory;

            if ($cat[0]["hasRestrictions"] == 1) {
                $hasRestrictions = $db->query("SELECT * FROM productCategoryHasRestrictions WHERE productCategory_id=?", array("i", $cId), false);
                foreach ($hasRestrictions as $hR) {
                    var_dump($hR);
                    $restrictionType = $hR['restrictionType'];
                    $restriction = $db->query("SELECT * FROM restriction_$restrictionType WHERE id=?", array("i", $hR["restriction_id"]), false);
                    $result[$key]["Restrictions"][] = $restriction[0];
                }
            }
        }
        return $result;
    }

    public function getWholeProduct($productId): array
    {
        $db = $this->Core->getDB();
        $getProduct = $db->query("SELECT * FROM products WHERE id=? AND status='active' LIMIT 1", array("i", $productId), false);
        $p = $getProduct[0];
        $product["Info"] = $p;

        $product["Descriptions"] = $this->getAllProductDescriptions($productId);
        $product["Prices"] = $this->getAllProductPrices($productId);
        $product["PreparationTime"] = $this->getAllProductPreparationTime($productId);
        $product["Categories"] = $this->getAllProductCategotries($productId);

        if (in_array($p["type_id"], [1, 2, 3])) {
            // get additional info
            $getAdditionalInfo = $db->query("SELECT object FROM productAdditionalInfo WHERE product_id=? AND status='active'", array("i", $productId), false);
            if (!empty($getAdditionalInfo)) {
                $product["AdditionalInfo"] = unserialize($getAdditionalInfo[0]["object"]);
            }
        }

        if ($p["has_options"] == 1) {
            $product["Options"] = $this->getAllProductOptions($productId);
        }

        if ($p["has_properties"] == 1) {
            $product["Properties"] = $this->getAllProductProperties($productId);
        }

        if ($p["has_allergic"] == 1) {
            $product["Allergies"] = $this->getAllProductAllergies($productId);
        }

        if ($p["has_conditions"] == 1) {
            $product["PriceConditions"] = $this->getAllProductPriceConditions($productId);
        }

        if ($p["has_restrictions"] == 1) {
            $product["Restrictions"] = $this->getAllProductRestrictions($productId);
        }

        if ($p["has_img"] == 1) {
            $product["Photos"] = $this->getAllProductImages($productId);
        }

        if ($p["has_transport"] == 1) {
            $product["Transport"] = $this->getAllProductTransport($productId);
        }

        /*
        if($p["has_variations"] == 1){
            
        }
         */

        if ($p["has_orderOptions"] == 1) {
            $product["OrderOptions"] = $this->getAllProductOrderOptions($productId);
        }

        if ($p["has_expiry"] == 1) {
            $product["ExpiryDate"] = $this->getAllProductExpiryDates($productId);
        }

        if ($p["has_equipment"] == 1) {
            $product["Equipment"] = $this->getAllProductEquipment($productId);
        }

        if ($p["exclude_vehicles"] == 1) {
            $product["Vehicles"] = $this->getAllProductVehicles($productId);
        }

        if ($p["has_crossselling"] == 1) {
            $product["CrossSelling"] = $this->getAllProductCrossSelling($productId);
        }

        return $product;

    }

    public function getProductById($pId, $shopId = 0): array
    {
        $db = $this->Core->getDB();
        $getProduct = $db->query("SELECT * FROM products WHERE id=? AND status='active' LIMIT 1", array("i", $pId), false);
        $p = $getProduct[0];
        $product["Info"] = $p;

        $product["Description"] = $this->getProductDescriptions($pId, $this->Translator->langId);

        $product["Prices"] = $this->getProductPrices($pId, $this->Core->currentCurrencyId, $shopId);

        $product["PreparationTime"] = $this->getProductPreparationTime($pId, 0);

        if (in_array($p["type_id"], [1, 2, 3])) {
            // get additional info
            $getAdditionalInfo = $db->query("SELECT object FROM productAdditionalInfo WHERE product_id=? AND status='active'", array("i", $pId), false);
            if (!empty($getAdditionalInfo)) {
                $product["AdditionalInfo"] = $getAdditionalInfo[0];
            }
        }

        if ($p["has_options"] == 1) {
            $product["Options"] = $this->getProductOptions($pId, "0", $shopId);
        }

        if ($p["has_properties"] == 1) {
            $product["Properties"] = $this->getProductProperties($pId, $shopId);
        }

        if ($p["has_allergic"] == 1) {
            $product["Allergies"] = $this->getProductAllergies($pId, $shopId);
        }

        if ($p["has_conditions"] == 1) {
            $product["PriceConditions"] = $this->getProductPriceConditions($pId);
        }

        if ($p["has_restrictions"] == 1) {
            $product["Restrictions"] = $this->getProductRestrictions($pId);
        }

        if ($p["has_img"] == 1) {
            $product["Photos"] = $this->getProductImages($pId, "0");
        }

        if ($p["has_transport"] == 1) {
            // @TODO get sizes
            $product["Transport"] = $this->getProductTransport($pId, "0", $shopId);
        }

        /*
        if($p["has_variations"] == 1){
            
        }
         */

        if ($p["has_orderOptions"] == 1) {
            $product["OrderOptions"] = $this->getProductOrderOptions($pId, "0", $shopId);
        }

        if ($p["has_expiry"] == 1) {
            $product["ExpiryDate"] = $this->getProductExpiryDates($pId, "0", $shopId);
        }

        if ($p["has_equipment"] == 1) {
            $product["Equipment"] = $this->getProductEquipment($pId);
        }

        if ($p["exclude_vehicles"] == 1) {
            $product["Vehicles"] = $this->getProductVehicles($pId);
        }

        if ($p["has_crossselling"] == 1) {
            $product["CrossSelling"] = $this->getProductCrossSelling($pId, "0", $shopId);
        }
        return $product;
    }


    public function getProductByIds($getProducts, $shopId): array
    {
        $products = [];
        foreach ($getProducts as $p) {
            $products[] = $this->getProductById($p["product_id"], $p["variation_id"], $shopId);
        }
        return $products;
    }


    public function getProductsCrossSelling(): array
    {
        // for add Product
        $db = $this->Core->getDB();

        $getP = $db->query("SELECT * FROM products WHERE merchant_id=? AND status ='active'", array("i", $_SESSION["merchant"]["merchantId"]), false);
        $products = [];
        if ($getP) {
            foreach ($getP as $p) {
                $pId = $p["id"];
                $products[$p["id"]]["product_id"] = $p["id"];
                $products[$p["id"]]["description"] = $this->getProductDescriptions($pId);
                $products[$p["id"]]["inShops"] = $this->getProductsInShops($pId);
                $products[$p["id"]]["prices"] = $this->getProductPrices($pId);
                $products[$p["id"]]["imgs"] = $this->getProductImages($pId);
            }
        }
        return $products;
    }

    public function getProductImages($pId, $varId = 0): array
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM product_has_images WHERE product_id=? AND variation_id=? AND status='active'", array("is", $pId, $varId), false) ?? [];
    }

    public function getAllProductImages($pId): array
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM product_has_images WHERE product_id=? AND status='active'", array("i", $pId), false);
    }

    public function getProductPrices($pId, $currency = "default", $shopId = null)
    {
        $db = $this->Core->getDB();

        if ($currency == "default") {
            return $db->query("SELECT * FROM product_has_price WHERE product_id=? AND isDefault=1 ", array("i", $pId), false);
        }

        if (empty($shopId)) {
            return null;
        }

        $result = $db->query("SELECT * FROM product_has_price WHERE product_id=? AND currency_id=? AND shop_id=?", array("iii", $pId, $currency, $shopId), false);

        // if currency not found return default
        if (!empty($result)) {
            return $result;
        }

        return $db->query("SELECT * FROM product_has_price WHERE product_id=? AND isDefault=1 AND shop_id=?", array("ii", $pId, $shopId), false);

    }

    public function getAllProductPrices($pId): array
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM product_has_price WHERE product_id=?", array("i", $pId), false);
    }

    public function getProductsInShops($pId, $catId = 0): array
    {
        $db = $this->Core->getDB();

        if ($catId == 0) {
            return $db->query("SELECT * FROM shop_has_products WHERE product_id=? AND status='active'", array("i", $pId), false);
        }

        return $db->query("SELECT * FROM shop_has_products WHERE product_id=? AND productCategory_id=? AND status='active'", array("ii", $pId, $catId), false);
    }

    public function getProductDescriptions($pId, $lang = "default")
    {
        $db = $this->Core->getDB();
        if ($lang == "default") {
            $description = $db->query("SELECT * FROM productDescription WHERE product_id=? AND `default`=1 AND status='active' LIMIT 1", array("i", $pId), false);
            return $description[0];
        }

        $desc = $db->query("SELECT * FROM productDescription WHERE product_id=? AND lang_id=? AND status='active' LIMIT 1", array("ii", $pId, $lang), false);

        if (empty($desc)) {
            $desc = $db->query("SELECT * FROM productDescription WHERE product_id=? AND `default`=1 AND status='active' LIMIT 1", array("i", $pId), false);
        }

        return $desc[0];
    }

    public function getAllProductDescriptions($pId)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM productDescription WHERE product_id=? AND status='active'", array("i", $pId), false);
    }


    public function registerProduct()
    {

        $product = json_decode($_POST["product"]);
        if (empty($product)) {
            header("Location:/merchant/products");
            die();
        }
        $db = $this->Core->getDB();

        $has_options = $product->Options ? 1 : 0;
        $has_orderOptions = $product->OrderOptions ? 1 : 0;
        $has_conditions = !empty($product->PriceConditions) ? 1 : 0;
        $has_properties = !empty($product->BasicInformation->typeInformation->properties) ? 1 : 0;
        $has_allergic = $product->BasicInformation->typeInformation->noAllergies ? 1 : 0;

        $vehicles = $db->query("SELECT id FROM vehicles WHERE 1=?", array("s", 1), false);
        $nVehicles = count($vehicles[0]);

        $exclude_vehicles = $nVehicles != count($product->RestrictionVehicles) ? 1 : 0;
        $has_restrictions = !empty($product->Restrictions) ? 1 : 0;
        $has_equipment = !empty($product->RestrictionEquipment) ? 1 : 0;
        $has_img = !empty($product->Images) ? 1 : 0;
        $has_transport = $product->Transportation ? 1 : 0;
        $has_variations = !empty($product->Variations) ? 1 : 0;
        $has_expiry = !empty($product->ExpiryDates) ? 1 : 0;
        $has_crossselling = !empty($product->CrossSelling) ? 1 : 0;

        $createdProduct = $db->query("INSERT INTO products VALUES (0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NOW(),NOW(),'pending')", array("iiiiiiiiiiiiiii",
            $_SESSION["merchant"]["merchantId"],
            $product->BasicInformation->typeInformation->type,
            $has_options,
            $has_properties,
            $has_allergic,
            $has_conditions,
            $has_restrictions,
            $has_img,
            $has_transport,
            $has_variations,
            $has_orderOptions,
            $has_expiry,
            $has_equipment,
            $exclude_vehicles,
            $has_crossselling
        ), true);
        $pId = $db->insert_id;

        if ($createdProduct) {
            /////////////////////////////////////////////////////////////////
            // BASIC INFORMATION
            /////////////////////////////////////////////////////////////////
            // description
            $hasDescription = false;
            foreach ($product->BasicInformation->descriptions as $d) {
                if ($db->query("INSERT INTO productDescription VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iissi", $pId, $d->lang_id, $d->name, $d->description, intval($d->isDefault)), true)) {
                    $hasDescription = true;
                }
            }
            if (!$hasDescription) {
                echo $this->Core->Translator->translate("No description");
                die();
            }


            //productAdditionalInfo
            if (isset($product->BasicInformation->typeInformation) && ($product->BasicInformation->typeInformation->type == 1 || $product->BasicInformation->typeInformation->type == 2 || $product->BasicInformation->typeInformation->type == 3)) {
                // exclude type "others" id=4

                //allergies
                if ($has_allergic) {
                    foreach ($product->BasicInformation->typeInformation->allergies as $id) {
                        $db->query("INSERT INTO product_has_allergies VALUES (0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $id), true);
                    }
                }
                //propeties
                if ($has_properties) {
                    foreach ($product->BasicInformation->typeInformation->properties as $proId) {
                        $db->query("INSERT INTO product_has_properties VALUES (0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $proId), true);
                    }
                }


                $addInfo = $product->BasicInformation->typeInformation;
                //clean object
                unset($addInfo->properties);
                unset($addInfo->noAllergies);
                unset($addInfo->allergies);
                $object = serialize($addInfo);
                $db->query("INSERT INTO productAdditionalInfo VALUES (0,?,?,NOW(),NOW(),'active')", array("is", $pId, $object), true);
            }


            /////////////////////////////////////////////////////////////////
            // SHOP CATEGORY
            /////////////////////////////////////////////////////////////////
            $has_category = false;
            foreach ($product->ShopCategories as $c) {
                $cId = $c->catId;
                foreach ($c->hasShops as $shopId) {
                    // get product position in category
                    $position = $db->query("SELECT position FROM shop_has_products WHERE shop_id=? AND productCategory_id=? AND status !='deleted' ORDER BY id DESC LIMIT 1", array("ii", $shopId, $cId), false);
                    if (empty($position[0])) {
                        $pos = 0;
                    } else {
                        $pos = $position[0]["position"];
                    }
                    $newPosition = $pos + 1;

                    if ($db->query("INSERT INTO shop_has_products VALUES (0,?,0,?,?,?,NOW(),NOW(),'active')", array("iiii", $pId, $shopId, $cId, $newPosition), true)) {
                        $has_category = true;
                    }
                    foreach ($product->Variations as $v) {
                        foreach ($v->inShops as $VshopId) {
                            if ($VshopId == $shopId) {
                                $newPosition = 0;
                                $db->query("INSERT INTO shop_has_products VALUES (0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $v->varId, $shopId, $cId, $newPosition), true);
                            }
                        }
                    }

                }
            }
            if (!$has_category) {
                die();
            }
            /////////////////////////////////////////////////////////////////
            // VARIATIONS
            /////////////////////////////////////////////////////////////////
            if ($has_variations) {
                foreach ($product->Variations as $v) {
                    $varId = $v->varId;
                    foreach ($v->inShops as $shopId) {
                        $db->query("INSERT INTO product_has_variation VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iii", $pId, $varId, $shopId), true);
                    }
                }
            }

            /////////////////////////////////////////////////////////////////
            // TRANSPORTATION
            /////////////////////////////////////////////////////////////////
            if ($has_transport) {
                // Save shop and variations with transport
                foreach ($product->Transportation->transportationShops as $tranShop) {
                    $shopId = $tranShop->shopId;
                    foreach ($tranShop->variations as $v) {
                        // default product has v->varId = 0
                        $db->query("INSERT INTO product_has_shop_transport VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $shopId, $v->varId, intval($v->hasTransportation), intval($v->hasTransportOnly)), true);
                    }
                }
                // save weight and dimensions
                foreach ($product->Transportation->dimensions as $d) {
                    // default product has varId = 0
                    $db->query("INSERT INTO productPhysicalInfo VALUES(0,?,?,?,?,?,?,?,?,NOW(),NOW(),'active')", array("iidsddds", $pId, $d->varId, $d->weight, $d->weightSystem, $d->width, $d->height, $d->depth, $d->distanceSystem), true);
                }
            }

            /////////////////////////////////////////////////////////////////
            // PRODUCT OPTIONS
            /////////////////////////////////////////////////////////////////
            if ($has_options) {
                foreach ($product->Options as $po) {
                    $shopId = $po->shopId;
                    $default = $po->options->optionsDefault;
                    $variation = $po->options->optionsVariations;
                    if (!empty($default)) {
                        foreach ($default as $bundleId) {
                            $varId = 0; // default product
                            $db->query("INSERT INTO product_has_options VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiii", $pId, $varId, $shopId, $bundleId), true);
                        }
                    }
                    if (!empty($variation)) {
                        foreach ($variation as $vo) {
                            $varId = $vo->varId;
                            foreach ($vo->options as $bundleId) {
                                $db->query("INSERT INTO product_has_options VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiii", $pId, $varId, $shopId, $bundleId), true);
                            }
                        }
                    }

                }
            }
            /////////////////////////////////////////////////////////////////
            // PREPARATION TIME
            /////////////////////////////////////////////////////////////////
            if (isset($product->PreparationTime->preparationTime) && isset($product->PreparationTime->typePreparationTime)) {
                $varId = 0;// default product
                $db->query("INSERT INTO product_has_time VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiis", $pId, $varId, $product->PreparationTime->preparationTime, $product->PreparationTime->typePreparationTime), true);
                if ($has_variations) {
                    if (isset($product->PreparationTime->variations)) {
                        foreach ($product->PreparationTime->variations as $v) {
                            $db->query("INSERT INTO product_has_time VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiis", $pId, $v->variationId, $v->preparationTime, $v->typePreparationTime), true);
                        }
                    } else {
                        die();
                    }
                }
            } else {
                die();
            }


            /////////////////////////////////////////////////////////////////
            // PRICES & INVENTORY
            /////////////////////////////////////////////////////////////////
            foreach ($product->PricesAndInventory as $shop) {

                $prices = $shop->prices;
                $DefaultInventory = $shop->inventory;

                // prices
                foreach ($prices as $shopP) {
                    $shopId = $shopP->shopId;
                    //default product
                    $varId = 0;
                    if ($shopP->isDefault && empty($shopP->inStorePrice) && empty($shopP->deliveryPrice)) {
                        die();
                    } else {
                        $db->query("INSERT INTO product_has_price VALUES (0,?,?,?,?,?,?,'',?,?,?,'',?,?,NOW(),NOW(),'active',0)", array("iiiiiddsdds", $pId, $varId, $shopP->currencyId, intval($shopP->isDefault), $shopId, $shopP->inStorePrice, $shopP->inStoreTax, $shopP->inStoreTaxType, $shopP->deliveryPrice, $shopP->deliveryTax, $shopP->deliveryTaxType), true);
                    }


                    //variations
                    if (!empty($p->variations)) {
                        foreach ($p->variations as $v) {
                            $varId = $v->varId;

                            //prices variations
                            foreach ($v->prices as $shopP) {
                                if ($shopP->IsDefault == true && empty($shopP->inStorePrice) && empty($shopP->deliveryPrice)) {
                                    die();
                                } else {
                                    $db->query("INSERT INTO product_has_price VALUES (0,?,?,?,?,?,?,'',?,?,?,'',?,?,NOW(),NOW(),'active',0)", array("iiIiiddsdds", $pId, $varId, $shopP->currencyId, intval($shopP->isDefault), $shopId, $shopP->inStorePrice, $shopP->inStoreTax, $shopP->inStoreTaxType, $shopP->deliveryPrice, $shopP->deliveryTax, $shopP->deliveryTaxType), true);
                                }
                            }

                            // inventory variations
                            if ($i->type == "period") {
                                $db->query("INSERT INTO product_has_inventory VALUES(0,?,?,?,?,?,?,?,?,?,NOW(),NOW(),'','active')", array("iiiisisi", $pId, $varId, $shopId, $i->amount, $i->type, $i->periodAmount, $i->period, $i->addToPrevious), true);
                            }
                            if ($i->type == "permanent") {
                                $db->query("INSERT INTO product_has_inventory VALUES(0,?,?,0,?,?,?,?,?,?,NOW(),NOW(),'','active')", array("iiisisi", $pId, $varId, $shopId, $i->amount, $i->type, null, null, null), true);

                            }
                        }
                    }

                }
                // inventory default
                if ($DefaultInventory) {
                    $i = $DefaultInventory;
                    $varId = 0;
                    if ($i->type == "period") {
                        $db->query("INSERT INTO product_has_inventory VALUES(0,?,?,?,?,?,?,?,?,NOW(),NOW(),'','active')", array("iiiisisi", $pId, $varId, $shopId, $i->amount, $i->type, $i->periodAmount, $i->period, $i->addToPrevious), true);
                    }
                    if ($i->type == "permanent") {
                        $db->query("INSERT INTO product_has_inventory VALUES(0,?,?,?,?,?,NULL,NULL,NULL,NOW(),NOW(),'','active')", array("iiiis", $pId, $varId, $shopId, $i->amount, $i->type), true);
                    }
                }

            }
            /////////////////////////////////////////////////////////////////
            // ORDER OPTIONS
            /////////////////////////////////////////////////////////////////
            if ($has_orderOptions) {
                foreach ($product->OrderOptions as $o) {
                    $shopId = $o->shopId;
                    $varId = 0;
                    $db->query("INSERT INTO product_has_orderOptions VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $varId, $shopId, $o->onlyCredit, $o->onlyCash), true);
                    if (!empty($o->variations)) {
                        foreach ($o->variations as $v) {
                            $db->query("INSERT INTO product_has_orderOptions VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $v->varId, $shopId, $v->onlyCredit, $v->onlyCash), true);
                        }
                    }
                }
            }

            /////////////////////////////////////////////////////////////////
            // EXPIRY DATE
            /////////////////////////////////////////////////////////////////
            if ($has_expiry) {
                foreach ($product->ExpiryDates as $e) {
                    if (!empty($e->inShops) && !empty($e->expiryDate) && !empty($e->expiryAmount)) {
                        $db->query("INSERT INTO product_has_expiry VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiisi", $pId, $e->forVariations, $e->inShops, date("Y-m-d", strtotime($e->expiryDate)), $e->expiryAmount), true);
                    }
                }
            }

            /////////////////////////////////////////////////////////////////
            // PRICE CONDITIONS
            /////////////////////////////////////////////////////////////////
            if ($has_conditions) {
                foreach ($product->PriceConditions as $cId) {
                    if (!empty($cId)) {
                        $db->query("INSERT INTO product_has_conditions VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $cId), true);
                    }
                }
            }

            /////////////////////////////////////////////////////////////////
            // RESTRICTIONS
            /////////////////////////////////////////////////////////////////
            if ($has_restrictions) {
                if (!empty($product->Restrictions)) {
                    foreach ($product->Restrictions as $r) {
                        $db->query("INSERT INTO product_has_restrictions VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $r), true);
                    }
                }
            }

            if ($has_equipment) {
                if (!empty($product->RestrictionEquipment)) {
                    foreach ($product->RestrictionEquipment as $e) {
                        if (!empty($e)) {
                            $db->query("INSERT INTO product_has_equipment VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $e), true);
                        }
                    }
                }
            }
            if ($exclude_vehicles) {
                $exclude = array_diff(array_column($vehicles, "id"), $product->RestrictionVehicles);
                foreach ($exclude as $e) {
                    $db->query("INSERT INTO product_exclude_vehicles VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $e), true);
                }
            }
            /////////////////////////////////////////////////////////////////
            // RESTRICTIONS
            /////////////////////////////////////////////////////////////////
            if ($has_crossselling) {
                foreach ($product->CrossSelling as $cs) {
                    $db->query("INSERT INTO product_has_crossselling VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiii", $pId, $cs->product, $cs->variation, $cs->shop), true);
                }
            }
            /////////////////////////////////////////////////////////////////
            // PRODUCT IMAGES
            /////////////////////////////////////////////////////////////////
            if ($has_img) {
                foreach ($product->Images as $i) {
                    $varId = $i->variation;
                    foreach ($i->images as $img) {
                        $db->query("INSERT INTO product_has_images VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iis", $pId, $varId, $img), true);
                    }
                }
            }

            // activate product
            $db->query("UPDATE products SET status='active' WHERE id=?", array("i", $pId), true);

        }
        header("Location:/merchant/products");
    }

    public function updateProduct()
    {
        $product = json_decode($_POST["product"]);
        if (empty($product)) {
            header("Location:/merchant/products");
            die();
        }

        $db = $this->Core->getDB();

        $has_options = $product->Options ? 1 : 0;
        $has_orderOptions = $product->OrderOptions ? 1 : 0;
        $has_conditions = !empty($product->PriceConditions) ? 1 : 0;
        $has_properties = !empty($product->BasicInformation->typeInformation->properties) ? 1 : 0;
        $has_allergic = $product->BasicInformation->typeInformation->noAllergies ? 1 : 0;

        $vehicles = $db->query("SELECT id FROM vehicles WHERE 1=?", array("s", 1), false);
        $nVehicles = count($vehicles[0]);

        $exclude_vehicles = $nVehicles != count($product->RestrictionVehicles) ? 1 : 0;
        $has_restrictions = !empty($product->Restrictions) ? 1 : 0;
        $has_equipment = !empty($product->RestrictionEquipment) ? 1 : 0;
        $has_img = !empty($product->Images) ? 1 : 0;
        $has_transport = $product->Transportation ? 1 : 0;
        $has_variations = !empty($product->Variations) ? 1 : 0;
        $has_expiry = !empty($product->ExpiryDates) ? 1 : 0;
        $has_crossselling = !empty($product->CrossSelling) ? 1 : 0;

        $sql = <<<SQL
            UPDATE
                products
            SET
                type_id = ?,
                has_options = ?,
                has_properties = ?,
                has_allergic = ?,
                has_conditions = ?,
                has_restrictions = ?,
                has_img = ?,
                has_transport = ?,
                has_variations = ?,
                has_orderOptions = ?,
                has_expiry = ?,
                has_equipment = ?,
                exclude_vehicles = ?,
                has_crossselling = ?
            WHERE
                id = ?
        SQL;


        $db->query($sql, array("iiiiiiiiiiiiiii",
            $product->BasicInformation->typeInformation->type,
            $has_options,
            $has_properties,
            $has_allergic,
            $has_conditions,
            $has_restrictions,
            $has_img,
            $has_transport,
            $has_variations,
            $has_orderOptions,
            $has_expiry,
            $has_equipment,
            $exclude_vehicles,
            $has_crossselling,
            $product->id
        ), true);

        $pId = $product->id;
        foreach ($product->BasicInformation->descriptions as $d) {
            $sql = <<<SQL
                DELETE FROM productDescription WHERE title = ? AND lang_id = ? AND product_id = ?
            SQL;
            $db->query($sql, array("sii", $d->name, $d->lang_id, $product->id), true);
            $db->query("INSERT INTO productDescription VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iissi", $product->id, $d->lang_id, $d->name, $d->description, intval($d->isDefault)), true);
        }

        //productAdditionalInfo
        if (isset($product->BasicInformation->typeInformation) && (in_array($product->BasicInformation->typeInformation->type, [1, 2, 3]))) {

            //allergies
            if ($has_allergic) {
                foreach ($product->BasicInformation->typeInformation->allergies as $id) {
                    $sql = <<<SQL
                        DELETE FROM product_has_allergies WHERE product_id = ? AND allergy_id = ?
                    SQL;
                    $db->query($sql, array("ii", $pId, $id), true);
                    $db->query("INSERT INTO product_has_allergies VALUES (0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $id), true);
                }
            }

            //propeties
            if ($has_properties) {
                foreach ($product->BasicInformation->typeInformation->properties as $proId) {
                    $sql = <<<SQL
                        DELETE FROM product_has_properties WHERE product_id = ? AND property_id = ?
                    SQL;
                    $db->query($sql, array("ii", $pId, $proId), true);
                    $db->query("INSERT INTO product_has_properties VALUES (0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $proId), true);
                }
            }

            $addInfo = $product->BasicInformation->typeInformation;

            //clean object
            unset($addInfo->properties);
            unset($addInfo->noAllergies);
            unset($addInfo->allergies);
            $object = serialize($addInfo);
            $db->query("UPDATE productAdditionalInfo SET object = ?, updated = NOW() WHERE product_id = ?", array("si", $object, $pId), true);
        }

        /////////////////////////////////////////////////////////////////
        // TRANSPORTATION
        /////////////////////////////////////////////////////////////////
        if ($has_transport) {
            // Save shop and variations with transport
            foreach ($product->Transportation->transportationShops as $tranShop) {
                $shopId = $tranShop->shopId;
                foreach ($tranShop->variations as $v) {
                    // default product has v->varId = 0
                    $sql = <<<SQL
                        DELETE FROM product_has_shop_transport WHERE product_id = ? AND variation_id = ? AND shop_id = ?
                    SQL;
                    $db->query($sql, array("iii", $pId, $v->varId, $shopId), true);
                    $db->query("INSERT INTO product_has_shop_transport VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $shopId, $v->varId, intval($v->hasTransportation), intval($v->hasTransportOnly)), true);
                }
            }
            // save weight and dimensions
            foreach ($product->Transportation->dimensions as $d) {
                // default product has varId = 0
                $sql = <<<SQL
                    DELETE FROM productPhysicalInfo WHERE product_id = ? AND variation_id = ?
                SQL;
                $db->query($sql, array("ii", $pId, $d->varId), true);
                $db->query("INSERT INTO productPhysicalInfo VALUES(0,?,?,?,?,?,?,?,?,NOW(),NOW(),'active')", array("iidsddds", $pId, $d->varId, $d->weight, $d->weightSystem, $d->width, $d->height, $d->depth, $d->distanceSystem), true);
            }
        }

        /////////////////////////////////////////////////////////////////
        // ORDER OPTIONS
        /////////////////////////////////////////////////////////////////
        if ($has_orderOptions) {
            foreach ($product->OrderOptions as $o) {
                $shopId = $o->shopId;
                $varId = 0;
                $sql = <<<SQL
                    DELETE FROM product_has_orderOptions WHERE product_id = ? AND variation_id = ? AND shop_id = ?
                SQL;
                $db->query($sql, array("iii", $pId, $varId, $shopId), true);
                $db->query("INSERT INTO product_has_orderOptions VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $varId, $shopId, $o->onlyCredit, $o->onlyCash), true);
                if (!empty($o->variations)) {
                    foreach ($o->variations as $v) {
                        $sql = <<<SQL
                            DELETE FROM product_has_orderOptions WHERE product_id = ? AND variation_id = ? AND shop_id = ?
                        SQL;
                        $db->query($sql, array("iii", $pId, $v->varId, $shopId), true);
                        $db->query("INSERT INTO product_has_orderOptions VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiii", $pId, $v->varId, $shopId, $v->onlyCredit, $v->onlyCash), true);
                    }
                }
            }
        }

        /////////////////////////////////////////////////////////////////
        // EXPIRY DATE
        /////////////////////////////////////////////////////////////////
        if ($has_expiry) {
            foreach ($product->ExpiryDates as $e) {
                if (!empty($e->inShops) && !empty($e->expiryDate) && !empty($e->expiryAmount)) {
                    $sql = <<<SQL
                        DELETE FROM product_has_expiry WHERE product_id = ? AND variation_id = ? AND shop_id = ?
                    SQL;
                    $db->query($sql, array("iii", $pId, $e->forVariations, $e->inShops), true);
                    $db->query("INSERT INTO product_has_expiry VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iiisi", $pId, $e->forVariations, $e->inShops, date("Y-m-d", strtotime($e->expiryDate)), $e->expiryAmount), true);
                }
            }
        }

        /////////////////////////////////////////////////////////////////
        // PRICE CONDITIONS
        /////////////////////////////////////////////////////////////////
        if ($has_conditions) {
            foreach ($product->PriceConditions as $cId) {
                if (!empty($cId)) {
                    $sql = <<<SQL
                        DELETE FROM product_has_conditions WHERE product_id = ? AND condition_id = ?
                    SQL;
                    $db->query($sql, array("ii", $pId, $cId), true);
                    $db->query("INSERT INTO product_has_conditions VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $cId), true);
                }
            }
        }
        /////////////////////////////////////////////////////////////////
        // RESTRICTIONS
        /////////////////////////////////////////////////////////////////
        if ($has_restrictions) {
            if (!empty($product->Restrictions)) {
                foreach ($product->Restrictions as $r) {
                    $sql = <<<SQL
                        DELETE FROM product_has_restrictions WHERE product_id = ? AND restrition_id = ?
                    SQL;
                    $db->query($sql, array("ii", $pId, $r), true);
                    $db->query("INSERT INTO product_has_restrictions VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $r), true);
                }
            }
        }

        if ($has_equipment) {
            if (!empty($product->RestrictionEquipment)) {
                foreach ($product->RestrictionEquipment as $e) {
                    if (!empty($e)) {
                        $sql = <<<SQL
                            DELETE FROM product_has_equipment WHERE product_id = ? AND equipment_id = ?
                        SQL;
                        $db->query($sql, array("ii", $pId, $e), true);
                        $db->query("INSERT INTO product_has_equipment VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $e), true);
                    }
                }
            }
        }
        if ($exclude_vehicles) {
            $exclude = array_diff(array_column($vehicles, "id"), $product->RestrictionVehicles);
            foreach ($exclude as $e) {
                $sql = <<<SQL
                    DELETE FROM product_exclude_vehicles WHERE product_id = ? AND vehicle_id = ?
                SQL;
                $db->query($sql, array("ii", $pId, $e), true);
                $db->query("INSERT INTO product_exclude_vehicles VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $pId, $e), true);
            }
        }

        /////////////////////////////////////////////////////////////////
        // RESTRICTIONS
        /////////////////////////////////////////////////////////////////
        if ($has_crossselling) {
            foreach ($product->CrossSelling as $cs) {
                $sql = <<<SQL
                    DELETE FROM product_has_crossselling WHERE product_id = ? AND variation_id = ?
                SQL;
                $db->query($sql, array("ii", $pId, $cs->variation), true);
                $db->query("INSERT INTO product_has_crossselling VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("iiii", $pId, $cs->product, $cs->variation, $cs->shop), true);
            }
        }

        /////////////////////////////////////////////////////////////////
        // PRODUCT IMAGES
        /////////////////////////////////////////////////////////////////
        if ($has_img) {
            foreach ($product->Images as $i) {
                $varId = $i->variation;
                foreach ($i->images as $img) {
                    $sql = <<<SQL
                        DELETE FROM product_has_images WHERE product_id = ? AND variation_id = ?
                    SQL;
                    $db->query($sql, array("ii", $pId, $varId), true);
                    $db->query("INSERT INTO product_has_images VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iis", $pId, $varId, $img), true);
                }
            }
        }

        header("Location:/merchant/products");
    }


    public function createProductRestriction()
    {
        if ($_POST) {
            $function = "createProductRestriction" . $_POST["type"];
            $objectRaw = $this->$function();
            $object = serialize($objectRaw);

            $db = $this->Core->getDB();
            $db->query("INSERT INTO productRestrictions VALUES (0,?,?,?,NOW(),NOW(),'active')", array("iss", $_SESSION["merchant"]["merchantId"], $_POST["type"], $object), true);
        } else {
            echo $this->Core->Translator->translate("Something went wrong.");
        }
    }

    public function createProductRestrictionAge()
    {
        $objectRaw = [
            "shops" => $_POST["shops"],
            "variations" => $_POST["variations"],
            "age" => $_POST["age"],
        ];
        return $objectRaw;
    }

    public function createProductRestrictionDistance()
    {
        $objectRaw = [
            "shops" => $_POST["shops"],
            "variations" => $_POST["variations"],
            "distanceFrom" => $_POST["distanceFrom"],
            "distanceUntil" => $_POST["distanceUntil"],
            "distanceSystem" => $_POST["distanceSystem"],
            "isTimeSensitive" => $_POST["isTimeSensitive"],
            "weekdays" => $_POST["weekdays"],
            "timeFrom" => $_POST["timeFrom"],
            "timeUntil" => $_POST["timeUntil"],
            "isWeekly" => $_POST["isWeekly"],
            "dateFrom" => $_POST["dateFrom"],
            "dateUntil" => $_POST["dateUntil"]
        ];
        return $objectRaw;
    }

    public function createProductRestrictionTime()
    {
        $objectRaw = [
            "shops" => $_POST["shops"],
            "variations" => $_POST["variations"],
            "action" => $_POST["action"],
            "timeFrom" => $_POST["timeFrom"],
            "timeUntil" => $_POST["timeUntil"],
            "hasPeriod" => $_POST["hasPeriod"],
            "weekdays" => $_POST["weekdays"]
        ];

        if ($_POST["hasPeriod"] == true) {
            $objectRaw["dateFrom"] = $_POST["dateFrom"];
            $objectRaw["dateUntil"] = $_POST["dateUntil"];
        }
        return $objectRaw;

    }

    public function createProductRestrictionDeliveryTime()
    {
        $objectRaw = [
            "shops" => $_POST["shops"],
            "variations" => $_POST["variations"],
            "maxDays" => $_POST["maxDays"],
            "maxHours" => $_POST["maxHours"],
            "maxMinutes" => $_POST["maxMinutes"],
            "hasTimePeriod" => $_POST["hasTimePeriod"],
            "hasDatePeriod" => $_POST["hasDatePeriod"],
        ];
        if ($_POST["hasTimePeriod"] == true) {
            $objectRaw["weekdays"] = $_POST["weekdays"];
            $objectRaw["timeFrom"] = $_POST["timeFrom"];
            $objectRaw["timeUntil"] = $_POST["timeUntil"];
        }
        if ($_POST["hasDatePeriod"] == true) {
            $objectRaw["dateFrom"] = $_POST["dateFrom"];
            $objectRaw["dateUntil"] = $_POST["dateUntil"];
        }
        return $objectRaw;

    }

    public function createProductRestrictionTransportation()
    {
        $objectRaw = [
            "shops" => $_POST["shops"],
            "variations" => $_POST["variations"],
            "cargoType" => $_POST["cargoType"],
            "tempFrom" => $_POST["tempFrom"],
            "tempUntil" => $_POST["tempUntil"],
            "scala" => $_POST["scala"]
        ];
        return $objectRaw;

    }

    public function getProductType()
    {
        $db = $this->Core->getDB();
        $result = $db->query("SELECT * FROM productType WHERE status=?", array("s", "active"), false);
        return $result;
    }

    public function getAllPriceConditions()
    {
        $db = $this->Core->getDB();
        $con = $db->query("SELECT * FROM priceConditions WHERE status!=? AND merchant_id=? ORDER BY id DESC", array("si", "deleted", $_SESSION["merchant"]["merchantId"]), false);
        return $con;
    }

    public function createConditionExpiration()
    {
        if ($_POST) {
            $objectRaw = [
                "shops" => $_POST["shops"],
                "variations" => $_POST["variations"],
                "prices" => $_POST["prices"],
                "days" => $_POST["days"],
            ];
        }
        $object = serialize($objectRaw);
        $db = $this->Core->getDB();
        $db->query("INSERT INTO priceConditions VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iss", $_SESSION["merchant"]["merchantId"], "Expiration", "$object"), true);
        return $db->insert_id;
    }

    public function createConditionInventory()
    {
        if ($_POST) {
            $objectRaw = [
                "shops" => $_POST["shops"],
                "variations" => $_POST["variations"],
                "action" => $_POST["action"],
                "inventoryAmount" => $_POST["inventoryAmount"],
                "operation" => $_POST["operation"],
                "prices" => $_POST["prices"],
                "isTimeSensitive" => $_POST["isTimeSensitive"],
                "isWeekly" => $_POST["isWeekly"],
                "dateFrom" => $_POST["dateFrom"],
                "dateUntil" => $_POST["dateUntil"],
                "timeFrom" => $_POST["timeFrom"],
                "timeUntil" => $_POST["timeUntil"],
                "weekdays" => $_POST["weekdays"]
            ];
        }
        $object = serialize($objectRaw);
        $db = $this->Core->getDB();
        $db->query("INSERT INTO priceConditions VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iss", $_SESSION["merchant"]["merchantId"], "Inventory", "$object"), true);
        return $db->insert_id;
    }

    public function createConditionDistance()
    {
        if ($_POST) {
            $objectRaw = [
                "shops" => $_POST["shops"],
                "variations" => $_POST["variations"],
                "distanceFrom" => $_POST["distanceFrom"],
                "distanceUntil" => $_POST["distanceUntil"],
                "distanceSystem" => $_POST["distanceSystem"],
                "isTimeSensitive" => $_POST["isTimeSensitive"],
                "weekdays" => $_POST["weekdays"],
                "hourFrom" => $_POST["hourFrom"],
                "hourUntil" => $_POST["hourUntil"],
                "isWeekly" => $_POST["isWeekly"],
                "dateFrom" => $_POST["dateFrom"],
                "dateUntil" => $_POST["dateUntil"],
                "operation" => $_POST["operation"],
                "prices" => $_POST["prices"]
            ];

            $object = serialize($objectRaw);
            $db = $this->Core->getDB();
            $db->query("INSERT INTO priceConditions VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iss", $_SESSION["merchant"]["merchantId"], "Distance", "$object"), true);
            return $db->insert_id;
        }
    }

    public function createConditionTime()
    {
        if ($_POST) {
            $objectRaw = [
                "shops" => $_POST["shops"],
                "days" => $_POST["days"],
                "from" => $_POST["from"],
                "until" => $_POST["until"],
                "dateFrom" => $_POST["dateFrom"],
                "dateUntil" => $_POST["dateUntil"],
                "variations" => $_POST["variations"],
                "prices" => $_POST["prices"],
                "operation" => $_POST["operation"]
            ];
            $object = serialize($objectRaw);
            $db = $this->Core->getDB();
            $db->query("INSERT INTO priceConditions VALUES(0,?,?,?,NOW(),NOW(),'active')", array("iss", $_SESSION["merchant"]["merchantId"], "Time", "$object"), true);
            return $db->insert_id;
        }
    }

    public function getFoodAllergies()
    {
        $db = $this->Core->getDB();
        $result = $db->query("SELECT * FROM foodAllergies WHERE status=?", array("s", "active"), false);
        return $result;
    }

    public function searchProductCategories($shop_id = 0, $name = null)
    {
        $db = $this->Core->getDB();
        $c;
        if ($shop_id == 0 && !empty($name)) {
            $results = $db->query("SELECT * FROM productCategoryDescription WHERE title LIKE ? AND status='active'", array("s", "%" . $name . "%"), false);

            foreach ($results as $r) {
                $result = $db->query("SELECT * FROM productCategory WHERE id=? AND merchant_id=? AND status!='deleted'", array("ii", $r["productCategory_id"], $_SESSION["merchant"]["merchantId"]), false);
                $c[] = $result[0];
            }

        }

        if (!empty($shop_id) && !empty($name)) {
            $results = $db->query("SELECT * FROM shop_has_productCategory WHERE shop_id=? AND status!='deleted'", array("i", $shop_id), false);

            foreach ($results as $r) {
                $description = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND title LIKE ? AND status!='deleted'", array("is", $r["productCategory_id"], "%" . $name . "%"), false);
                foreach ($description as $d) {
                    $result = $db->query("SELECT * FROM productCategory WHERE id=? AND merchant_id=? AND status!='deleted'", array("ii", $d["productCategory_id"], $_SESSION["merchant"]["merchantId"]), false);
                    $c[] = $result[0];
                }
            }
        }

        if (!empty($shop_id) && empty($name)) {
            $results = $db->query("SELECT * FROM shop_has_productCategory WHERE shop_id=? AND status!='deleted'", array("i", $shop_id), false);
            foreach ($results as $r) {
                $result = $db->query("SELECT * FROM productCategory WHERE id=? AND merchant_id=? AND status!='deleted'", array("ii", $r["productCategory_id"], $_SESSION["merchant"]["merchantId"]), false);
                $c[] = $result[0];
            }
        }

        if ($shop_id == 0 && empty($name)) {
            $c = $db->query("SELECT * FROM productCategory WHERE merchant_id=? AND status='active'", array("i", $_SESSION["merchant"]["merchantId"]), false);
        }

        return $this->getProductCategoriesData($c);

    }

    public function getProductCategories()
    {
        $db = $this->Core->getDB();
        $c = $db->query("SELECT * FROM productCategory WHERE merchant_id=? AND status='active'", array("i", $_SESSION["merchant"]["merchantId"]), false);
        if (!empty($c)) {
            return $this->getProductCategoriesData($c);
        }

    }

    public function getProductCategoriesData($c)
    {
        $db = $this->Core->getDB();
        $allCategories = array();
        foreach ($c as $category) {
            $allCategories[$category["id"]]["info"] = $category;

            $descriptions = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND status='active'", array("i", $category["id"]), false);

            $allCategories[$category["id"]]["description"] = $descriptions;

            $allCategories[$category["id"]]["inShops"] = $this->getShopsWithCategory($category["id"]);

            if ($category["hasRestrictions"] == 1) {
                $restrictions = $db->query("SELECT * FROM productCategoryHasRestrictions WHERE productCategory_id=? AND status='active'", array("i", $category["id"]), false);

                foreach ($restrictions as $r) {
                    $allCategories[$category["id"]]["restrictions"][$r["restrictionType"]][] = $this->getRestrictionById($r["restriction_id"], $r["restrictionType"]);
                }
            }

        }
        return $allCategories;
    }

    public function getShopsWithCategory($cid)
    {
        $db = $this->Core->getDB();
        $catHasShop = $db->query("SELECT * FROM shop_has_productCategory WHERE productCategory_id=?", array("i", $cid), false);
        $shops = array();
        foreach ($catHasShop as $cHS) {
            $info = $db->query("SELECT * FROM shops WHERE id=?", array("i", $cHS["shop_id"]), false);
            $address = $db->query("SELECT googleString FROM address WHERE shop_id=? AND status='active'", array("i", $cHS["shop_id"]), false);
            $data = array(["info" => $info[0], "address" => $address[0]]);
            $shops[] = $data;
        }
        return $shops;

    }

    public function getShopHasCategories($shop_id)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM shop_has_productCategory WHERE shop_id=? AND status='active'", array("i", $shop_id), false);
    }

    public function addNewProductCategory()
    {
        $db = $this->Core->getDB();

        $rAge = $_POST["restrictionAge"];
        $Distance = $_POST["restrictionDistance"];
        $rHours = $_POST["restrictionHours"];
        $rDate = $_POST["restrictionDate"];
        $rDeliveryT = $_POST["restrictionDeliveryTime"];
        $vehicles = $_POST["vehicle"];
        $rSize = $_POST["restrictionSize"];
        $rTrans = $_POST["restrictionTrans"];
        $equipment = $_POST["equipment"];
        $inShops = $_POST["shop"];

        // check if there are vehicles excluded
        $allVehicles = $db->query("SELECT id FROM vehicles WHERE status=?", array("s", "active"), false);
        if (count($allVehicles) == count($vehicles)) {
            $rVehicles = "";
        } else {
            $rVehicles = 1;
        }

        // check if there are restrictions
        if (empty($rVehicles) && empty($rAge) && empty($Distance) && empty($rHours) && empty($rDate) && empty($rDeliveryT) && empty($rSize) && empty($rTrans) && empty($equipment)) {
            $hasRestrictions = 0;
        } else {
            $hasRestrictions = 1;
        }

        // create category node
        $db->query("INSERT INTO productCategory VALUES(0,?,NOW(),NOW(),?,'active')", array("ii", $_SESSION["merchant"]["merchantId"], $hasRestrictions), true);
        $categoryId = $db->insert_id;

        if (!empty($rAge)) {
            $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Age',?,'active')", array("ii", $categoryId, $rAge), true);
        }

        if (!empty($Distance)) {
            foreach ($Distance as $rD) {
                $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Distance',?,'active')", array("ii", $categoryId, $rD), true);
            }
        }

        if (!empty($rHours)) {
            foreach ($rHours as $rH) {
                $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Hours',?,'active')", array("ii", $categoryId, $rH), true);
            }
        }

        if (!empty($rDate)) {
            foreach ($rDate as $rDa) {
                $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Date',?,'active')", array("ii", $categoryId, $rDa), true);
            }
        }

        if (!empty($rDeliveryT)) {
            $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'DeliveryTime',?,'active')", array("ii", $categoryId, $rDeliveryT), true);
        }

        if (!empty($rSize)) {
            $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Size',?,'active')", array("ii", $categoryId, $rSize), true);
        }

        if (!empty($rTrans)) {
            $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Transport',?,'active')", array("ii", $categoryId, $rTrans), true);
        }

        if (!empty($equipment)) {
            foreach ($equipment as $eq) {
                $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Equipment',?,'active')", array("ii", $categoryId, $eq), true);
            }
        }

        if (!empty($rVehicles)) {

            for ($i = 0; $i < count($allVehicles); $i++) {
                $id = $allVehicles[$i]["id"];
                if ($vehicles[$id]) {
                    // dont exclude vehicle
                } else {
                    $db->query("INSERT INTO restriction_Vehicle VALUES(0,?,'active')", array("i", $id), true);
                    $key = $db->insert_id;
                    $db->query("INSERT INTO productCategoryHasRestrictions VALUES(0,?,'Vehicles',?,'active')", array("ii", $categoryId, $key), true);
                }
            }

        }

        foreach ($inShops as $shopId) {
            $db->query("INSERT INTO shop_has_productCategory VALUES (0,?,?,NOW(),NOW(),'active')", array("ii", $categoryId, $shopId), true);
        }

        // INSERT INFORMATION
        foreach ($this->Core->Translator->getLanguages() as $lang) {
            if (!empty($_POST["categoryName_" . $lang["code"]])) {
                if ($_POST["defaultLang"] == $lang["code"]) {
                    $default = 1;
                } else {
                    $default = 0;
                }
                $db->query("INSERT INTO productCategoryDescription VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iissi", $categoryId, $lang["id"], $_POST["categoryName_" . $lang["code"]], $_POST["categoryDescription_" . $lang["code"]], $default), true);
            }
        }

        $data["Core"] = $this->Core;
        $data["productCategories"] = $this->getProductCategories();

        return $this->Core->FrontController->partialRender("product-category-list.php", $data);
    }


    public function getRestrictionById($rId, $rType)
    {
        $db = $this->Core->getDB();
        $result = $db->query("SELECT * FROM restriction_$rType WHERE id=? AND status='active'", array("i", $rId), false);
        return $result[0];
    }

    public function getRestrictionByType($type)
    {
        $db = $this->Core->getDB();
        $r = $db->query("SELECT * FROM restriction_$type WHERE merchant_id=? AND status='active'", array("i", $_SESSION["merchant"]["merchantId"]), false);
        return $r[0];
    }

    public function createProductOption()
    {

        $prices = json_decode($_POST["prices"], true);
        $infos = json_decode($_POST["info"], true);
        $img_path = $_POST["image"];
        $bundleId = $_POST["bundleId"];
        $allergies = $_POST["allergies"];

        if (!empty($prices) && !empty($infos)) {
            $db = $this->Core->getDB();

            if ($allergies != 0) {
                $hasAllergies = 1;
            } else {
                $hasAllergies = 0;
            }

            $db->query("INSERT INTO productOption VALUES (0,?,?,NOW(),NOW(),'active',?)", array("iis", $_SESSION["merchant"]["merchantId"], $hasAllergies, $img_path), true);
            $optionId = $db->insert_id;

            if ($hasAllergies == 1) {
                foreach ($allergies as $a) {
                    $db->query("INSERT INTO productOption_has_allergies VALUES(0,?,?,'active')", array("ii", $optionId, $a), true);
                }
            }

            foreach ($infos as $i) {
                $db->query("INSERT INTO productOptionDescription VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iisis", $optionId, $i["lang_id"], $i["title"], $i["isdefault"], $i["description"]), true);
            }

            foreach ($prices as $p) {
                $db->query("INSERT INTO productOptionPrice VALUES(0,?,?,?,?,NOW(),NOW(),'active')", array("idii", $optionId, $p["price"], $p["currency"], $p["isDefaultCurrency"]), true);
            }

            $db->query("INSERT INTO productOption_has_pob VALUES(0,?,?,NOW(),NOW(),'active')", array("ii", $bundleId, $optionId), true);

            $bundle = $this->getOptionBundleById($bundleId);

            return $bundle[$bundleId];
        }

    }

    public function createOptionBundle()
    {
        $info = json_decode($_POST["info"], true);
        $conditions = json_decode($_POST["conditions"], true);

        if (!empty($info)) {
            $db = $this->Core->getDB();

            $db->query("INSERT INTO productOptionBundle VALUES(0,?,?,?,?,?,?,NOW(),NOW(),'active')", array("iiiiii", $_SESSION["merchant"]["merchantId"], $conditions["isRequired"], $conditions["requiredMin"], $conditions["requiredMax"], $conditions["hasAmount"], $conditions["max"]), true);

            $bundle_id = $db->insert_id;

            foreach ($info as $i) {
                $db->query("INSERT INTO productOptionBundleDescription VALUES(0,?,?,?,?,?,NOW(),NOW(),'active')", array("iissi", $bundle_id, $i["lang_id"], $i["title"], $i["description"], $i["isDefault"]), true);
            }
            return $this->getAllOptions();
        }
    }

    public function getAllOptions()
    {
        $db = $this->Core->getDB();

        $optionsBundle = $db->query("SELECT * FROM productOptionBundle WHERE merchant_id=? AND status='active'", array("i", $_SESSION["merchant"]["merchantId"]), false);

        $Bundles = $this->getOptionsBundleData($optionsBundle);

        return $Bundles;
    }

    public function getOptionBundleById($id)
    {
        $db = $this->Core->getDB();

        $r = $db->query("SELECT * FROM productOptionBundle WHERE id=?", array("i", $id), false);

        return $this->getOptionsBundleData($r);
    }

    public function getOptionsBundleData($bundles)
    {
        $db = $this->Core->getDB();
        $AllBundles = array();
        foreach ($bundles as $bundle) {

            $info = $db->query("SELECT * FROM productOptionBundleDescription WHERE pob_id=? AND `default`=1 AND status='active'", array("i", $bundle["id"]), false);
            $AllBundles[$bundle["id"]]["info"] = $bundle;
            $AllBundles[$bundle["id"]]["descriptions"] = $info[0];

            if ($BundleHasOptions = $db->query("SELECT * FROM productOption_has_pob WHERE pob_id=? AND status='active'", array("i", $bundle["id"]), false)) {
                $AllBundles[$bundle["id"]]["hasOptions"] = $this->getProductOptionsByIds($BundleHasOptions);
            }
        }
        return $AllBundles;
    }

    public function getProductOptionsByIds($options): array
    {
        $db = $this->Core->getDB();
        $AllOptions = array();
        foreach ($options as $o) {
            $getOptions = $db->query("SELECT * FROM productOption WHERE id=? AND status='active'", array("i", $o["productOption_id"]), false);
            if ($getOptions) {
                $getData = $this->getProductOptionDataById($getOptions[0]["id"]);
                $getPrices = $this->getProductOptionPricesById($getOptions[0]["id"]);

                $AllOptions[$getOptions[0]["id"]]["info"] = $getOptions[0];
                $AllOptions[$getOptions[0]["id"]]["description"] = $getData;
                $AllOptions[$getOptions[0]["id"]]["prices"] = $getPrices;
            }
        }
        return $AllOptions;
    }

    public function getProductOptionDataById($id)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM productOptionDescription WHERE productOption_id=? AND status='active'", array("i", $id), false);
    }

    public function getProductOptionPricesById($id)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM productOptionPrice WHERE productOption_id=? AND status='active'", array("i", $id), false);
    }

    public function getAllCurrencies()
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM currency WHERE status='active' ORDER BY ? ASC", array("s", "name"), false);
    }

    public function getAllProductRestriction()
    {
        $db = $this->Core->getDB();
        $mId = $_SESSION["merchant"]["merchantId"];

        return $db->query("SELECT * FROM productRestrictions WHERE merchant_id=? AND status='active' ORDER BY id DESC", array("i", $mId), false);
    }

    public function getVehicles()
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM vehicles WHERE 1=?", array("i", 1), false);
    }

    public function getEquipment()
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM restriction_Equipment WHERE status=?", array("s", "active"), false);
    }

    public function getAllRestrictions()
    {
        // this is for category restrictions!!
        $db = $this->Core->getDB();
        $mId = $_SESSION["merchant"]["merchantId"];
        $restrictions = array();

        $restrictions["Age"] = $db->query("SELECT * FROM restriction_Age WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["Date"] = $db->query("SELECT * FROM restriction_Date WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["Distance"] = $db->query("SELECT * FROM restriction_Distance WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["Equipment"] = $db->query("SELECT * FROM restriction_Equipment WHERE status=?", array("s", "active"), false);

        $restrictions["Hours"] = $db->query("SELECT * FROM restriction_Hours WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["DeliveryTime"] = $db->query("SELECT * FROM restriction_DeliveryTime WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["Transportation"] = $db->query("SELECT * FROM restriction_Transportation WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        $restrictions["Vehicle"] = $db->query("SELECT * FROM vehicles WHERE 1=?", array("i", 1), false);

        $restrictions["Size"] = $db->query("SELECT * FROM restriction_Size WHERE merchant_id=? AND status='active'", array("i", $mId), false);

        return $restrictions;
    }

    public function createRestrictionAge()
    {
        $age = $_POST["Age"];
        $db = $this->Core->getDB();
        return $db->query("INSERT INTO restriction_Age VALUES(0,?,?,'active')", array("ii", $_SESSION["merchant"]["merchantId"], $age), true);
    }

    public function createRestrictionDistance()
    {
        $db = $this->Core->getDB();

        if ($_POST["min"] > $_POST["max"]) {
            return false;
        }
        return $db->query("INSERT INTO restriction_Distance VALUES(0,?,?,?,?,'active')", array("iiis", $_SESSION["merchant"]["merchantId"], $_POST["min"], $_POST["max"], $_POST["sys"]), true);
    }

    public function createRestrictionHours()
    {
        $db = $this->Core->getDB();

        if ($_POST["from"] > $_POST["until"]) {
            return false;
        }

        return $db->query("INSERT INTO restriction_Hours VALUES(0,?,?,?,?,?,'active')", array("issss", $_SESSION["merchant"]["merchantId"], $_POST["day"], date("H:i:s", strtotime($_POST["from"])), date("H:i:s", strtotime($_POST["until"])), $_POST["action"]), true);
    }

    public function createRestrictionDate()
    {
        $db = $this->Core->getDB();
        if ($_POST["from"] > $_POST["until"]) {
            return false;
        }
        return $db->query("INSERT INTO restriction_Date VALUES (0,?,?,?,?,'active')", array("isss", $_SESSION["merchant"]["merchantId"], date("Y-m-d", strtotime($_POST["from"])), date("Y-m-d", strtotime($_POST["until"])), $_POST["action"]), true);
    }

    public function createRestrictionDeliveryTime(): bool
    {
        $db = $this->Core->getDB();
        $days = $_POST["days"];
        $hours = $_POST["hours"];
        $minutes = $_POST["minutes"];
        if (!empty($days) || !empty($hours) || !empty($minutes)) {
            if (empty($days)) {
                $days = 0;
            }
            if (empty($hours)) {
                $hours = 0;
            }
            if (empty($minutes)) {
                $minutes = 0;
            }
            return $db->query("INSERT INTO restriction_DeliveryTime VALUES (0,?,?,?,?,'active')", array("iiii", $_SESSION["merchant"]["merchantId"], $days, $hours, $minutes), true);
        }
        return false;
    }

    public function createRestrictionTransportation(): bool
    {
        $db = $this->Core->getDB();

        $from = $_POST["from"];
        $until = $_POST["until"];
        $scale = $_POST["scale"];
        $cargo = $_POST["cargo"];

        if (!empty($from) && !empty($until) && !empty($scale) && !empty($cargo)) {
            return $db->query("INSERT INTO restriction_Transportation VALUES(0,?,?,?,?,?,'active')", array("iddss", $_SESSION["merchant"]["merchantId"], $from, $until, $scale, $cargo), true);
        }
        return false;
    }

    public function createRestrictionSize(): bool
    {
        $x = $_POST["x"];
        $y = $_POST["y"];
        $z = $_POST["z"];
        $sys = $_POST["sys"];

        if (!empty($x) && !empty($y) && !empty($z)) {
            $db = $this->Core->getDB();
            return $db->query("INSERT INTO restriction_Size VALUES (0,?,?,?,?,?,'active')", array("iddds", $_SESSION["merchant"]["merchantId"], $x, $y, $z, $sys), true);
        }
        return false;

    }

    public function addVariation()
    {
        $db = $this->Core->getDB();

        $variations = json_decode($_POST["variations"]);
        $db->query("INSERT INTO productVariation VALUES (0,?,'active')", array("i", $_SESSION["merchant"]["merchantId"]), true);
        $variation_id = $db->insert_id;

        foreach ($variations as $v) {
            $db->query("INSERT INTO productVariationDescription VALUES (0,?,?,?,?,?,NOW(),NOW(),'active')", array("issii", $variation_id, $v->title, $v->description, $v->langId, $v->isDefault), true);
        }

        return $this->getAllVariations();
    }

    function getVariationById($vId): array
    {
        $db = $this->Core->getDB();

        $v = $db->query("SELECT * FROM productVariation WHERE id=? LIMIT 1", array("i", $vId), false);

        $variation["info"] = $v[0];

        $variation["descriptions"] = $db->query("SELECT * FROM productVariationDescription WHERE pv_id=? AND status='active'", array("i", $vId), false);

        return $variation;
    }

    public function getAllVariations()
    {
        $db = $this->Core->getDB();

        $results = $db->query("SELECT * FROM productVariation WHERE merchant_id=? AND status!='deleted' ORDER BY id DESC", array("i", $_SESSION["merchant"]["merchantId"]), false);

        return $this->getVariationsData($results);
    }

    public function getVariationsByIds($ids = []): array
    {
        if (empty($ids)) {
            return [];
        }
        $db = $this->Core->getDB();
        $result = array_map(function ($id) use ($db) {
            $response = $db->query("SELECT * FROM productVariation WHERE id=?", array("i", $id), false);
            return $response[0];
        }, $ids);
        return $this->getVariationsData($result);
    }

    public function getVariationsData($data): array
    {
        $db = $this->Core->getDB();
        return array_map(function ($item) use ($db) {
            $descriptions = $db->query("SELECT * FROM productVariationDescription WHERE pv_id=? AND status='active'", array("i", $sv["id"]), false);
            return [
                $sv["id"] => [
                    "info" => $sv,
                    "descriptions" => $descriptions
                ]
            ];
        }, $data);
    }

    public function getAllProperties(): array
    {
        $db = $this->Core->getDB();

        return $db->query("SELECT * FROM productProperties WHERE status=? ORDER BY name DESC", array("s", "active"), false);
    }

    public function getProductPhysicalInfo($pid)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT * FROM productPhysicalInfo WHERE status=? AND variation_id = 0 AND product_id = ?", array("si", "active", $pid), false)[0];
    }

    public function getAllShopTransportByProductId($pid)
    {
        $db = $this->Core->getDB();
        return $db->query("SELECT shop_id FROM product_has_shop_transport WHERE status=? AND variation_id = 0 AND product_id = ?", array("si", "active", $pid), false);
    }

}