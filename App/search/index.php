<?php

class searchShops{
    public $Core;
    function __construct($Core){
        $this->Core = $Core;
    }
    
    function searchNearBy($lat,$lng){
        $db = $this->Core->getDB();
        $shops = $db->query("SELECT id, (3959 * acos(cos(radians(?)) * cos(radians(lat)) * cos( radians(lng) - radians(?)) + sin(radians(?)) * 
sin(radians(lat)))) 
AS distance 
FROM shops WHERE status='on' HAVING distance < 20 ORDER BY id DESC LIMIT 4
",
                array("ddd",$lat,$lng,$lat),false);
        if($shops){
        $ids = array_column($shops,"id");
        include_once("App/merchant/merchant.php");
        $m = new merchant($this->Core);

        return $m->getShopBasicInfoByIds($ids);
        }
    }
}

