<?php
$Core = $data["Core"];

if(!empty($data["inShops"])){?>
<div class="col s12">
<ul class="collapsible" data-collapsible="accordion">
   <?php
    foreach ($data["inShops"] as $shop){?>
    <li>
        <div class="collapsible-header">
            <ul class="collection">
              <li class="collection-item avatar">
                  <img src="<?php echo $shop["logo"];?>" alt="" class="circle">
                  <h5 class="card-title shopTitleCard"><?php echo $shop["name"];?></h5>
                  <p><?php echo $shop["address"]["googleString"];?></p>
              </li>
            </ul>
        </div>
        <div class="collapsible-body">
            <div style="display:grid">
                    <?php if($shop["membership"]["membership_id"] == 1){?>
                    <p><?php echo $Core->Translator->translate("WARNING: This options are only available for professional membership.")?></p>
                    <?php } ?>
                <p id="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_default_container">
                    
                    <input type="checkbox" id="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_default" <?php if($shop["membership"]["membership_id"] == 1){ echo "disabled";} ?> onclick="checkOrderOptionCredit($(this),'<?php echo $shop["id"]?>','default')"/>
                    <label for="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_default"><span><?php echo $Core->Translator->translate("Order only possible throw online payment (digital)");?></span>
                    </label><br/>
                </p>
                <p id="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_default_container">
                    
                    <input type="checkbox" id="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_default" <?php if($shop["membership"]["membership_id"] == 1){ echo "disabled";} ?> onclick="checkOrderOptionCash($(this),'<?php echo $shop["id"]?>','default')"/>
                    <label for="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_default"><span><?php echo $Core->Translator->translate("Order only possible throw cash payment (physical)");?></span>
                    </label><br/>
                </p>
                <?php
                    if(!empty($data["activeVariations"]) && $shop["membership"]["membership_id"] != 1){
                    ?>
                            <div class="col s12">
                                <h6 class="grey-text"><?php echo $Core->Translator->translate("Variations");?></h6>
                                <ul class="collapsible">
                                <?php 
                                $varInShop = json_decode($data["variationInShops"],true);
                                $thisShopHasNoVariation = true;
                                foreach($data["activeVariations"] as $v){
                                    $isInshop = false;
                                    $key = array_search($v["info"]["id"],array_column($varInShop, "varId"));

                                       $inShops =  $varInShop[$key]["varInShops"];
                                       foreach($inShops as $inShopId){
                                            if($shop["id"] == $inShopId){
                                                $isInShop = true;
                                            }
                                       }
                                    
                                if($isInShop == true){
                                    $thisShopHasNoVariation = false;
                                ?>
                                    <li>
                                        <div class="collapsible-header">
                                             <h6><?php echo $v["description"]["title"]?></h6>
                                        </div>
                                        <div class="collapsible-body">
                                            <div style="display:grid">
                                                 <p id="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>_container">
                                                    
                                                    <input type="checkbox" id="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>" <?php if($shop["membership"]["membership_id"] == 1){ echo "disabled";} ?> onclick="checkOrderOptionCredit($(this),'<?php echo $shop["id"]?>','<?php echo $v["info"]["id"];?>')"/>
                                                    <label for="orderOption_onlyCredit_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>"><span><?php echo $Core->Translator->translate("Order only possible throw online payment (digital)");?></span>
                                                    </label><br/>
                                                </p>
                                                <p id="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>_container">
                                                    
                                                    <input type="checkbox" id="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>" <?php if($shop["membership"]["membership_id"] == 1){ echo "disabled";} ?> onclick="checkOrderOptionCash($(this),'<?php echo $shop["id"]?>','<?php echo $v["info"]["id"];?>')"/>
                                                    <label for="orderOption_onlyCash_shop_<?php echo $shop["id"];?>_<?php echo $v["info"]["id"];?>"><span><?php echo $Core->Translator->translate("Order only possible throw cash payment (physical)");?></span>
                                                    </label><br/>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                }}
                                ?>
                                </ul>
                            </div>
                    <?php
                    }
                    if( $thisShopHasNoVariation == true){
                    ?>
                    <div class="col s12">
                        <p class="grey-text"><?php echo $Core->Translator->translate("Variations of this product were disabled for this shop under 'Product Variations'");?></p>
                    </div>
                    <?php
                    }
                    ?>
            </div>
        </div>
    </li>
    <?php } ?>
</ul>
</div>
<?php   
}else{
?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product can have diffrent order options at diffrent shops. Please select at least one category so we know in which shops the product will be available.");?>
        </p>
    </li>
</ul>
<?php
}

