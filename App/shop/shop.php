<?php

class shop
{
    public Core $Core;

    public function __construct($Core)
    {
        $this->Core = $Core;
    }

    public function VIEW_show($shopId)
    {
        $params = $this->getRequestParams();
        $data["Shop"] = $this->getShopById($shopId, $params);
        $data["params"] = $params;
        if (!empty($data["Shop"])) {
            $this->RenderShopShow($data);
            return;
        }
        header("Location: /");
    }

    private function getRequestParams()
    {
        $params = [];
        foreach ($_GET as $key => $value) {
            $params[$key] = $value;
        }
        return $params;
    }

    public function VIEW_getProducts($shopId)
    {
        $db = $this->Core->getDB();

        $hasCategories = $db->query("SELECT productCategory_id FROM shop_has_productCategory WHERE shop_id=? AND status='active'", array("i", $shopId), false);

        foreach ($hasCategories as $hc) {

            $category = $db->query("SELECT * FROM productCategory WHERE id=? AND status='active'", array("i", $hc["productCategory_id"]), false);
            $category["info"] = $category[0];

            // get restrictions
            if ($category[0]["hasRestrictions"] == 1) {
                $hasRestrictions = $db->query("SELECT * FROM productCategoryHasRestrictions WHERE productCategory_id=? AND status='active'", array("i", $hc["productCategory_id"]), false);
                foreach ($hasRestrictions as $hr) {
                    $type = $hr["restrictionType"];
                    $restriction = $db->query("SELECT * FROM restriction_$type WHERE id=? AND status='active'", array("i", $hr["restriction_id"]), false);
                    $obj = [
                        "type" => $type,
                        "restriction" => $restriction[0]
                    ];

                    $category["restrictions"][] = $obj;
                }
            }

            // get description
            $getDes = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND lang_id=? AND status='active'", array("ii", $hc["productCategory_id"], $this->Core->Translator->langId), false);
            if (empty($getDes)) {
                $getDes = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND `default`=1 AND status='active'", array("i", $hc["productCategory_id"]), false);
            }
            $category["description"] = $getDes[0];


            // get products
            require_once("App/merchant/helpers/manageProducts.php");
            $p = new manageProducts($this->Core);

            $category["products"] = $p->getProductsByCategory($shopId, $hc["productCategory_id"]);

            if ($_POST["search"]) {
                foreach ($category["products"] as $key => $product) {

                    if (str_contains(strtolower($product["default"]["Description"]["title"]), strtolower($_POST["search"]))) {
                        // do nothing
                    } else {
                        unset($category["products"][$key]);
                    }
                }
            }

            if ($_POST["searchProperties"]) {
                foreach ($category["products"] as $key => $product) {
                    $hasPropertie = false;
                    foreach ($product["default"]["Properties"] as $prop) {
                        foreach ($_POST["searchProperties"] as $searchProperties) {
                            if ($prop["name"] == $searchProperties) {
                                $hasPropertie = true;
                            }
                        }
                    }
                    if (!$hasPropertie) {
                        unset($category["products"][$key]);
                    }
                }
            }

            if ($_POST["excludeAllergies"]) {
                foreach ($category["products"] as $key => $product) {
                    $hasAllergy = false;
                    foreach ($product["default"]["Allergies"] as $allergies) {
                        foreach ($_POST["excludeAllergies"] as $allergy) {
                            if ($allergies["name"] == $allergy) {
                                $hasAllergy = true;
                            }
                        }
                    }
                    if ($hasAllergy) {
                        unset($category["products"][$key]);
                    }
                }
            }

            $categories[] = $category;
        }
        $shop["Categories"] = $categories;

        $data = array();
        $data["Shop"] = $shop;
        $data["Core"] = $this->Core;


        $this->Core->FrontController->partialRender("product-loop.php", $data);
    }

    /*
     * HELPER FUNCTIONS
     */
    public function getShopById($shopId, $params = [])
    {


        $db = $this->Core->getDB();

        $info = $db->query("SELECT * FROM shops WHERE id=? AND status='on' LIMIT 1", array("i", $shopId), false);

        $shop["info"] = $info[0];

        $membership = $db->query("SELECT membership_id FROM shop_has_membership WHERE shop_id=? AND status='active'", array("i", $shopId), false);
        $shop["membership"] = $membership[0];

        $address = $db->query("SELECT * FROM address WHERE shop_id=? AND status='active' ORDER BY id DESC LIMIT 1", array("i", $shopId), false);
        $shop["address"] = $address[0];

        $hasCategories = $db->query("SELECT productCategory_id FROM shop_has_productCategory WHERE shop_id=? AND status='active'", array("i", $shopId), false);

        foreach ($hasCategories as $hc) {

            $category = $db->query("SELECT * FROM productCategory WHERE id=? AND status='active'", array("i", $hc["productCategory_id"]), false);
            $category["info"] = $category[0];

            // get restrictions
            if ($category[0]["hasRestrictions"] == 1) {
                $hasRestrictions = $db->query("SELECT * FROM productCategoryHasRestrictions WHERE productCategory_id=? AND status='active'", array("i", $hc["productCategory_id"]), false);
                foreach ($hasRestrictions as $hr) {
                    $type = $hr["restrictionType"];
                    $restriction = $db->query("SELECT * FROM restriction_$type WHERE id=? AND status='active'", array("i", $hr["restriction_id"]), false);
                    $obj = [
                        "type" => $type,
                        "restriction" => $restriction[0]
                    ];

                    $category["restrictions"][] = $obj;
                }
            }

            // get description
            $getDes = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND lang_id=? AND status='active'", array("ii", $hc["productCategory_id"], $this->Core->Translator->langId), false);
            if (empty($getDes)) {
                $getDes = $db->query("SELECT * FROM productCategoryDescription WHERE productCategory_id=? AND `default`=1 AND status='active'", array("i", $hc["productCategory_id"]), false);
            }
            $category["description"] = $getDes[0];

            // get products
            require_once("App/merchant/helpers/manageProducts.php");
            $p = new manageProducts($this->Core);

            $category["products"] = $p->getProductsByCategory($shopId, $hc["productCategory_id"], $params["searchProperties"] ?? [], $params["excludeAllergies"] ?? []);
            $category["products2"] = $p->getProductsByCategory($shopId, $hc["productCategory_id"]);

            $categories[] = $category;
        }
        $shop["Categories"] = $categories;

        return $shop;
    }

    /*
     * VIEW FUNCTIONS
     */

    public function RenderShopShow($data)
    {
        $view = [
            "File" => "shop-show.php",
            "PageTitle" => $data["Shop"]["info"]["name"],
            "PageDescription" => $this->Core->Translator->translate("Order online from") . " " . $data["Shop"]["info"]["name"],
            "Design" => "Default",
            "CSS" => array("intlTelInput.min.css"),
            "JS" => array("jquery.validate.min.js", "additional-methods.min.js", "intlTelInput.min.js", "shopShow.js"),
            "Keywords" => array(
                $this->Core->Translator->translate("Delivery"),
                $this->Core->Translator->translate("Order online from") . " " . $data["Shop"]["info"]["name"],
                $data["Shop"]["info"]["name"] . " " . $this->Core->Translator->translate("Delivery"),
            ),
            "Data" => $data,
            "Core" => $this->Core,
            "Message" => null
        ];
        $this->Core->FrontController->render($view);
    }
}
