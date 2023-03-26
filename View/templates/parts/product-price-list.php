 <?php
 $Core = $data["Core"];
 if(!empty($data["productIsInShops"])){
 if(count($data["productIsInShops"]) > 1){ ?>
<div class="productGreyBox">
    <h6><?php echo $Core->Translator->translate("Set to all shops");?></h6>
        <div class="col s12">
                    <div class="col s12 input-field">
                        <select id="globalCurrencySelect">
                        <option selected disabled><?php echo $Core->Translator->translate("Select Currency");?></option>
                        <?php
                        foreach($data["currency"] as $currency){?>
                            <option value="<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate($currency["name"])?> (<?php echo $currency["code"]?>)</option>
                        <?php   
                        }
                        ?>
                        </select>
                        <label><?php echo $Core->Translator->translate("Currency");?></label>
                    </div>
                    <?php
                    foreach($data["currency"] as $currency){
                    ?>
                    <div id="globalPriceCurrency_<?php echo $currency["id"]?>" class="ishidden globalPriceContainer">
                        <div class="col m4 s12 input-field">
                            <input type="number" step="any" id="globalInStorePrice_<?php echo $currency["id"]?>" class="globalInStorePrice" data-currency="<?php echo $currency["id"]?>"/>
                            <label><?php echo $Core->Translator->translate("In Store Price");?> (<?php echo $currency["code"]?>)</label>
                        </div>
                        <div class="col m4 s12 input-field">
                            <input type="number" step="any" id="globalInStoreTax_<?php echo $currency["id"]?>"/>
                            <label><?php echo $Core->Translator->translate("In Store Tax."); ?></label>
                        </div>
                        <div class="col m4 s12 input-field">
                            <select id="globalInStoreTaxType_<?php echo $currency["id"]?>" class="selectPriceTax">
                                <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                            </select>
                            <label><?php echo $Core->Translator->translate("Tax in store price is:");?></label>
                        </div>

                        <div class="col m4 s12 input-field">
                            <input type="number" step="any" id="globalDeliveryPrice_<?php echo $currency["id"]?>"/>
                            <label><?php echo $Core->Translator->translate("Delivery Price");?> (<?php echo $currency["code"]?>)</label>
                        </div>
                        <div class="col m4 s12 input-field">
                            <input type="number" step="any" id="globalDeliveryTax_<?php echo $currency["id"]?>"/>
                            <label><?php echo $Core->Translator->translate("Delivery Tax."); ?></label>
                        </div>
                        <div class="col m4 s12 input-field">
                            <select id="globalDeliveryTaxType_<?php echo $currency["id"]?>" class="selectPriceTax">
                                <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                            </select>
                            <label><?php echo $Core->Translator->translate("Tax in delivery price is:");?></label>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="col s12 input-field">
                        <input type="number" id="globalInventory" class="validate"/>
                        <label><?php echo $Core->Translator->translate("Inventory amount");?></label>
                        <span class="grey-text"><?php echo $Core->Translator->translate("Leave blank if no inventory needed");?></span>
                    </div>
                    <div class="col s12 input-field">
                        <select id="globalInventoryPeriod" onchange="checkGlobalInventoryPeriod($(this))">
                            <option value="permanent"><?php echo $Core->Translator->translate("Permanent");?></option>
                            <option value="period"><?php echo $Core->Translator->translate("Time period");?></option>
                        </select>
                        <label><?php echo $Core->Translator->translate("Inventory type");?></label>
                    </div>
                    <div class="ishidden" id="globalInventoryHasTimePeriodContainer">
                        <div class="col s12 m6 input-field">
                            <input id="globalInventoryTimeAmount" type="number" class="validate"/>
                            <label><?php echo $Core->Translator->translate("Period amount");?></label>
                        </div>
                        <div class="col s12 m6 input-field">
                            <select id="globalInventoryTimeAmountPeriod">
                                <option value="h"><?php echo $Core->Translator->translate("Hour(s)");?></option>
                                <option value="d"><?php echo $Core->Translator->translate("Day(s)");?></option>
                            </select>
                            <label><?php echo $Core->Translator->translate("Period");?></label>
                        </div>
                        <div class="col s12">
                            <input type="checkbox" id="globalAddInventoryToPrevious"/>
                            <label for="globalAddInventoryToPrevious"><?php echo $Core->Translator->translate("After period ends, add new inventory to remaining inventory.");?></label>
                            <span class="grey-text"><?php echo $Core->Translator->translate("If not checked old inventory will be replaced after new period starts.")?></span>
                        </div>
                    </div>
                
                    <?php
                    if(!empty($data["selectedVariations"])){
                    ?>
                            <div class="col s12">
                                <h6 class="grey-text"><?php echo $Core->Translator->translate("Variations");?></h6>
                                <ul class="collapsible">
                                <?php 
                                foreach($data["selectedVariations"] as $v){
                                ?>
                                    <li>
                                        <div class="collapsible-header">
                                             <h6><?php echo $v["description"]["title"]?></h6>
                                        </div>
                                        <div class="collapsible-body">
                                            <div style="display:grid">
                                                <div class="col s12 input-field">
                                                    <select id="globalCurrencySelectVariation_<?php echo $v["info"]["id"]?>" onchange="getVariationPriceCurrency($(this),<?php echo $v["info"]["id"]?>)">
                                                        <option selected disabled><?php echo $Core->Translator->translate("Select Currency");?></option>
                                                        <?php
                                                        foreach($data["currency"] as $currency){?>
                                                         <option value="<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate($currency["name"])?> (<?php echo $currency["code"]?>)</option>
                                                         <?php   
                                                        }
                                                        ?>
                                                    </select>
                                                    <label><?php echo $Core->Translator->translate("Currency");?></label>
                                                </div>
                                                <?php
                                                   foreach($data["currency"] as $currency){
                                                ?>
                                                <div class="col s12 ishidden containerPriceVariation" id="containerPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>">
                                                    <div class="col m4 s12 input-field">
                                                        <input type="number" step="any" id="globalInStorePriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"/>
                                                        <label><?php echo $Core->Translator->translate("In Store Price");?> (<?php echo $currency["code"];?>)</label>
                                                    </div>
                                                    <div class="col m4 s12 input-field">
                                                        <input type="number" step="any" id="globalInStoreTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"/>
                                                        <label><?php echo $Core->Translator->translate("In Store Tax."); ?></label>
                                                    </div>
                                                    <div class="col m4 s12 input-field">
                                                        <select id="globalInStoreTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" class="selectPriceTax">
                                                            <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                                            <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Tax in store price is:");?></label>
                                                    </div>

                                                    <div class="col m4 s12 input-field">
                                                        <input type="number" step="any" id="globalDeliveryPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"/>
                                                        <label><?php echo $Core->Translator->translate("Delivery Price");?> (<?php echo $currency["code"];?>)</label>
                                                    </div>
                                                    <div class="col m4 s12 input-field">
                                                        <input type="number" step="any" id="globalDeliveryTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"/>
                                                        <label><?php echo $Core->Translator->translate("Delivery Tax."); ?></label>
                                                    </div>
                                                    <div class="col m4 s12 input-field">
                                                        <select id="globalDeliveryTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" class="selectPriceTax">
                                                            <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                                            <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Tax in delivery price is:");?></label>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <div class="col s12 input-field">
                                                    <input type="number" id="globalInventoryVariation_<?php echo $v["info"]["id"]?>" class="validate"/>
                                                    <label><?php echo $Core->Translator->translate("Inventory amount");?></label>
                                                    <span class="grey-text"><?php echo $Core->Translator->translate("Leave blank if no inventory needed");?></span>
                                                </div>
                                                <div class="col s12 input-field">
                                                    <select id="globalInventoryPeriodVariation_<?php echo $v["info"]["id"]?>" onchange="checkGlobalInventoryPeriod($(this),<?php echo $v["info"]["id"]?>)">
                                                        <option value="permanent"><?php echo $Core->Translator->translate("Permanent");?></option>
                                                        <option value="period"><?php echo $Core->Translator->translate("Time period");?></option>
                                                    </select>
                                                    <label><?php echo $Core->Translator->translate("Inventory type");?></label>
                                                </div>
                                                <div class="ishidden" id="globalInventoryHasTimePeriodContainer<?php echo $v["info"]["id"]?>">
                                                    <div class="col s12 m6 input-field">
                                                        <input id="globalInventoryTimeAmountVariation_<?php echo $v["info"]["id"]?>" type="number" class="validate"/>
                                                        <label><?php echo $Core->Translator->translate("Period amount");?></label>
                                                    </div>
                                                    <div class="col s12 m6 input-field">
                                                        <select id="globalInventoryTimeAmountPeriodVariation_<?php echo $v["info"]["id"]?>">
                                                            <option value="h"><?php echo $Core->Translator->translate("Hour(s)");?></option>
                                                            <option value="d"><?php echo $Core->Translator->translate("Day(s)");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Period");?></label>
                                                    </div>
                                                    <div class="col s12">
                                                        <input type="checkbox" id="globalAddInventoryToPreviousVariation_<?php echo $v["info"]["id"]?>"/>
                                                        <label for="globalAddInventoryToPreviousVariation_<?php echo $v["info"]["id"]?>"><?php echo $Core->Translator->translate("After period ends, add new inventory to remaining inventory.");?></label>
                                                        <span class="grey-text"><?php echo $Core->Translator->translate("If not checked old inventory will be replaced after new period starts.")?></span>
                                                    </div>
                                                </div>
                                       </div>
                                       </div>
                                    </li>
                        <?php
                        }
                        ?>
                        </ul>
                    </div>
                    <?php
                    }
                    ?>
            
                    <div class="col s12">
                        <button type="button" class="btn-flat" onclick="setGlobalPrices()"><?php echo $Core->Translator->translate("Set values to all shops");?></button>
                    </div>
    </div>
</div>
  <h6><?php echo $Core->Translator->translate("Set to each shop");?></h6>
    <?php
 }}
    ?>

<?php
if(!empty($data["productIsInShops"])){?>
<div class="col s12">
<ul class="collapsible" data-collapsible="expandable">
 <?php
    foreach ($data["productIsInShops"] as $shop){?>
    <input id="shopPriceInventoryInfo_<?php echo $shop["id"]?>" type="hidden" data-shop-name="<?php echo $shop["name"];?> (<?php echo $shop["address"]["googleString"];?>)" data-currency-id="<?php echo $shop["currency"]["id"]?>" data-currency-name="<?php echo $Core->Translator->translate($shop["currency"]["name"])?>"/>
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
                <h6><?php echo $Core->Translator->translate("Prices");?></h6>
                <div class="col s12 input-field">
                    <select id="selectCurrencyShop_<?php echo $shop["id"]?>" onchange="selectCurrencyShop($(this),<?php echo $shop["id"]?>)">
                        <?php
                        foreach($data["currency"] as $currency){
                        ?>
                        <option value="<?php echo $currency["id"]?>" <?php if($currency["id"] == $shop["currency"]["id"]){ echo "selected";}?>><?php echo $Core->Translator->translate($currency["name"])?> (<?php echo $currency["code"];?>)</option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <?php
                foreach($data["currency"] as $currency){
                ?>
                <div id="containerPriceShop_<?php echo $shop["id"]?>_currency_<?php echo $currency["id"]?>" class="containerShopPrice <?php if($shop["currency"]["id"] != $currency["id"]){ echo "ishidden";} ?>">
                        <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_0 inStore_<?php echo $shop["id"]?>">
                            <input type="number" step="any" class="form_StorePrice_currency_<?php echo $currency["id"]?> StorePrice_<?php echo $shop["id"]?> validate" data-currency-id="<?php echo $currency["id"]?>" />
                            <label class="labelStorePrice_currency_<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate("In Store Price");?> (<?php echo $currency["code"]?>)</label>
                        </div>
                        <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_0 inStore_<?php echo $shop["id"]?>">
                            <input type="number" step="any" class="form_StoreTax_currency_<?php echo $currency["id"]?> validate" id="inStoreTax_<?php echo $shop["id"]?>_<?php echo $currency["id"]?>"/>
                            <label class="labelStoreTax_currency_<?php echo $currency["id"]?>" for="StoreTax_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("In Store Tax. (%)"); ?></label>
                        </div>
                        <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_0 inStore_<?php echo $shop["id"]?>">
                            <select class="form_StoreTaxType_currency_<?php echo $currency["id"]?> selectPriceTax StoreTaxType_<?php echo $shop["id"]?>" data-currency-id="<?php echo $currency["id"]?>" id="storeTaxType_<?php echo $shop["id"]?>_<?php echo $currency["id"]?>">
                                <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                            </select>
                            <label><?php echo $Core->Translator->translate("Tax in store price is:");?></label>
                        </div>

                        <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_0 onDelivery_<?php echo $shop["id"]?>">
                            <input type="number" step="any" class="form_DeliveryPrice_currency_<?php echo $currency["id"]?> DeliveryPrice_<?php echo $shop["id"]?> validate" id="deliveryPrice_<?php echo $shop["id"]?>_<?php echo $currency["id"]?>"/>
                            <label class="labelform_DeliveryPrice_currency_<?php echo $currency["id"]?>" for="DeliveryPrice_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("Delivery Price");?> (<?php echo $currency["code"]?>)</label>
                        </div>
                        <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_0 onDelivery_<?php echo $shop["id"]?>">
                            <input type="number" step="any" class="form_DeliveryTax_currency_<?php echo $currency["id"]?> validate" id="deliveryTax_<?php echo $shop["id"]?>_<?php echo $currency["id"]?>"/>
                            <label class="labelform_DeliveryTax_currency_<?php echo $currency["id"]?>" for="DeliveryTax_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("Delivery Tax."); ?></label>
                        </div>
                        <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_0 onDelivery_<?php echo $shop["id"]?>">
                            <select class="DeliveryTaxType_currency_<?php echo $currency["id"]?> selectPriceTax form_DeliveryTaxType" id="deliveryTaxType_<?php echo $shop["id"]?>_<?php echo $currency["id"]?>">
                                <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                            </select>
                            <label><?php echo $Core->Translator->translate("Tax in delivery price is:");?></label>
                        </div>
                </div>
                <?php }?>
                <h6><?php echo $Core->Translator->translate("Inventory");?></h6>
                <div class="col s12 input-field">
                    <input type="number" id="inventoryShop_<?php echo $shop["id"]?>" class="validate inventoryShop" min="1"/>
                    <label for="inventoryShop_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("Inventory amount");?></label>
                    <span class="grey-text"><?php echo $Core->Translator->translate("Leave blank if no inventory needed");?></span>
                </div>
                <div class="col s12 input-field">
                    <select id="inventoryPeriodShop_<?php echo $shop["id"]?>" class="inventoryPeriodShop" onchange="checkShopInventoryPeriod($(this),<?php echo $shop["id"]?>)">
                        <option value="permanent"><?php echo $Core->Translator->translate("Permanent");?></option>
                        <option value="period"><?php echo $Core->Translator->translate("Time period");?></option>
                    </select>
                    <label><?php echo $Core->Translator->translate("Inventory type");?></label>
                </div>
                <div class="ishidden shopInventoryHasTimePeriodContainer" id="shopInventoryHasTimePeriodContainer<?php echo $shop["id"]?>">
                    <div class="col s12 m6 input-field">
                        <input id="inventoryTimeAmountShop_<?php echo $shop["id"]?>" type="number" class="validate inventoryTimeAmountShop"/>
                        <label for="inventoryTimeAmountShop_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("Period amount");?></label>
                    </div>
                    <div class="col s12 m6 input-field">
                        <select id="inventoryTimeAmountPeriodShop_<?php echo $shop["id"]?>" class="inventoryTimeAmountPeriodShop">
                            <option value="h"><?php echo $Core->Translator->translate("Hour(s)");?></option>
                            <option value="d"><?php echo $Core->Translator->translate("Day(s)");?></option>
                        </select>
                        <label><?php echo $Core->Translator->translate("Period");?></label>
                    </div>
                    <div class="col s12">
                        <input type="checkbox" id="addInventoryToPreviousShop_<?php echo $shop["id"]?>" class="addInventoryToPreviousShop"/>
                        <label for="addInventoryToPreviousShop_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("After period ends, add new inventory to remaining inventory.");?></label>
                        <span class="grey-text"><?php echo $Core->Translator->translate("If not checked old inventory will be replaced after new period starts.")?></span>
                    </div>
                </div>
              
              
              
              
              
              
              
              
              
              
                    <?php
                    if(!empty($data["selectedVariations"])){
                    ?>
                            <div class="col s12">
                                <h6 class="grey-text"><?php echo $Core->Translator->translate("Variations");?></h6>
                                <ul class="collapsible">
                                <?php 
                                $varInShop = json_decode($data["variationInShops"],true);
                                $thisShopHasNoVariation = true;
                                foreach($data["selectedVariations"] as $v){
                                    $key = in_array($v["id"], array_column($varInShop, "varId"));
                                        if(in_array($shop["id"],$varInShop[$key]["varInShops"])){
                                            $thisShopHasNoVariation = false;
                                ?>
                                    <li>
                                        <div class="collapsible-header">
                                             <h6><?php echo $v["description"]["title"]?></h6>
                                        </div>
                                        <div class="collapsible-body">
                                            <div style="display:grid">
                                                <div class="col s12 input-field">
                                                    <select id="CurrencySelectVariation_<?php echo $v["info"]["id"]?>_shop_<?php echo $shop["id"]?>" onchange="shopVariationPriceCurrency($(this),<?php echo $v["info"]["id"]?>,<?php echo $shop["id"]?>)">
                                                        <option selected disabled><?php echo $Core->Translator->translate("Select Currency");?></option>
                                                        <?php
                                                        foreach($data["currency"] as $currency){?>
                                                            <option value="<?php echo $currency["id"];?>" <?php if($currency["id"] == $shop["currency"]["id"]){ echo "selected";}?> ><?php echo $Core->Translator->translate($currency["name"])?> (<?php echo $currency["code"]?>)</option>
                                                        <?php   
                                                        }
                                                        ?>
                                                    </select>
                                                    <label><?php echo $Core->Translator->translate("Currency");?></label>
                                                </div>
                                                <?php
                                                   foreach($data["currency"] as $currency){
                                                ?>
                                                <div class="<?php if($currency["id"] != $shop["currency"]["id"]){ echo "ishidden";}?> containerShopPriceVariation" id="containerPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>">
                                                    <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> inStore_<?php echo $shop["id"]?>">
                                                        <input type="number" step="any" class="InStorePriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?> InStorePriceVariation_<?php echo $v["info"]["id"]?>_shop_<?php echo $shop["id"]?>" id="InStorePriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>" data-currency-id="<?php echo $currency["id"];?>"/>
                                                        <label class="LabelInStorePriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate("In Store Price");?> (<?php echo $currency["code"];?>)</label>
                                                    </div>
                                                    <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> inStore_<?php echo $shop["id"]?>">
                                                        <input type="number" step="any" class="InStoreTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" id="InStoreTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>"/>
                                                        <label class="LabelInStoreTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate("In Store Tax."); ?></label>
                                                    </div>
                                                    <div class="col m4 s12 input-field checkForDelivery inStore_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> inStore_<?php echo $shop["id"]?>">
                                                        <select  class="InStoreTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" id="InStoreTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>" class="selectPriceTax">
                                                            <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                                            <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Tax in store price is:");?></label>
                                                    </div>
                                                    <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> onDelivery_<?php echo $shop["id"]?>">
                                                        <input type="number" step="any" class="DeliveryPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?> " id="DeliveryPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>"/>
                                                        <label class="LabelDeliveryPriceVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate("Delivery Price");?> (<?php echo $currency["code"];?>)</label>
                                                    </div>
                                                    <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> onDelivery_<?php echo $shop["id"]?>">
                                                        <input type="number" step="any" class="DeliveryTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" id="DeliveryTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>"/>
                                                        <label class="LabelDeliveryTaxVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>"><?php echo $Core->Translator->translate("Delivery Tax."); ?></label>
                                                    </div>
                                                    <div class="col m4 s12 input-field onlyPickup onDelivery_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?> onDelivery_<?php echo $shop["id"]?>">
                                                        <select class="DeliveryTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>" id="DeliveryTaxTypeVariation_<?php echo $v["info"]["id"]?>_currency_<?php echo $currency["id"];?>_shop_<?php echo $shop["id"]?>" class="selectPriceTax">
                                                            <option value="inclusive"><?php echo $Core->Translator->translate("Inclusive");?></option>
                                                            <option value="exclusive"><?php echo $Core->Translator->translate("Exclusive");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Tax in delivery price is:");?></label>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                
                                                <h6><?php echo $Core->Translator->translate("Inventory");?></h6>
                                                <div class="col s12 input-field">
                                                    <input type="number" id="inventoryShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>" class="validate inventoryShopVariation_<?php echo $v["info"]["id"]?>"/>
                                                    <label for="inventoryShop_<?php echo $shop["id"]?>"><?php echo $Core->Translator->translate("Inventory amount");?></label>
                                                </div>
                                                <div class="col s12 input-field">
                                                    <select id="inventoryPeriodShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>" class="inventoryPeriodShopVariation_<?php echo $v["info"]["id"]?>" onchange="checkShopInventoryPeriodVariation($(this),<?php echo $shop["id"]?>,<?php echo $v["info"]["id"]?>)">
                                                        <option value="permanent"><?php echo $Core->Translator->translate("Permanent");?></option>
                                                        <option value="period"><?php echo $Core->Translator->translate("Time period");?></option>
                                                    </select>
                                                    <label><?php echo $Core->Translator->translate("Inventory type");?></label>
                                                </div>
                                                <div class="ishidden shopInventoryHasTimePeriodContainer_variation_<?php echo $v["info"]["id"]?>" id="shopInventoryHasTimePeriodContainer<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>">
                                                    <div class="col s12 m6 input-field">
                                                        <input id="inventoryTimeAmountShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>" type="number" class="validate inventoryTimeAmountShopVariation_<?php echo $v["info"]["id"]?>"/>
                                                        <label for="inventoryTimeAmountShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>"><?php echo $Core->Translator->translate("Period amount");?></label>
                                                    </div>
                                                    <div class="col s12 m6 input-field">
                                                        <select id="inventoryTimeAmountPeriodShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>" class="inventoryTimeAmountPeriodShopVariation_<?php echo $v["info"]["id"]?>">
                                                            <option value="h"><?php echo $Core->Translator->translate("Hour(s)");?></option>
                                                            <option value="d"><?php echo $Core->Translator->translate("Day(s)");?></option>
                                                        </select>
                                                        <label><?php echo $Core->Translator->translate("Period");?></label>
                                                    </div>
                                                    <div class="col s12">
                                                        <input type="checkbox" id="addInventoryToPreviousShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>" class="addInventoryToPreviousShopVariation_<?php echo $v["info"]["id"]?>"/>
                                                        <label for="addInventoryToPreviousShop_<?php echo $shop["id"]?>_variation_<?php echo $v["info"]["id"]?>"><?php echo $Core->Translator->translate("After period ends, add new inventory to remaining inventory.");?></label>
                                                        <span class="grey-text"><?php echo $Core->Translator->translate("If not checked old inventory will be replaced after new period starts.")?></span>
                                                    </div>
                                                </div>   
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
    <?php
    }
    ?>
</ul>
</div>
<?php }else{?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product can have diffrent prices at diffrent shops. Please select at least one category so we know in wich shops the product will be available.");?>
        </p>
    </li>
</ul>
<?php } ?>

<script>
function shopVariationPriceCurrency(input,variationId,shopId){
    $(".containerShopPriceVariation").hide();
    var currencyId = input.val();
    $("#containerPriceVariation_"+variationId+"_currency_"+currencyId+"_shop_"+shopId).fadeIn();
}   
function selectCurrencyShop(input,shopId){
    $(".containerShopPrice").fadeOut();
    var currencyId = input.val()
    $("#containerPriceShop_"+shopId+"_currency_"+currencyId).fadeIn();
}
function getVariationPriceCurrency(input,variationId){
    var currencyId = input.val();
    $(".containerPriceVariation").hide();
    $("#containerPriceVariation_"+variationId+"_currency_"+currencyId).fadeIn();
}    
function setGlobalPrices(){
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    
    //inventory
    var defaultInventory        = $("#globalInventory").val();
    var defaultInventoryPeriod  = $("#globalInventoryPeriod").val();

    if(defaultInventoryPeriod == "period"){
        $(".shopInventoryHasTimePeriodContainer").show();
        var defaultInventoryTimeAmount      = $("#globalInventoryTimeAmount").val();
        var globalInventoryTimeAmountPeriod = $("#globalInventoryTimeAmountPeriod").val();
        var globalAddInventoryToPrevious    = $("#globalAddInventoryToPrevious");

        if(defaultInventoryTimeAmount == ""){
            alert("<?php echo $Core->Translator->translate("Please declare inventory 'period amount'");?>");
        }else{
            $(".inventoryTimeAmountShop").val(defaultInventoryTimeAmount);
            $(".inventoryTimeAmountPeriodShop").val(globalInventoryTimeAmountPeriod);
            if(globalAddInventoryToPrevious.prop("checked")){
                $(".addInventoryToPreviousShop").prop("checked",true);
            }
        }
    }
    $(".inventoryShop").val(defaultInventory);
    $(".inventoryPeriodShop").val(defaultInventoryPeriod);
    
    variationIds.forEach(function(variationId){
        var inventory               = $("#globalInventoryVariation_"+variationId).val();
        var inventoryPeriod         = $("#globalInventoryPeriodVariation_"+variationId).val();
        
        $(".inventoryShopVariation_"+variationId).val(inventory);
        $(".inventoryPeriodShopVariation_"+variationId).val(inventoryPeriod);
        
        if(inventoryPeriod == "period"){
            $(".shopInventoryHasTimePeriodContainer_variation_"+variationId).show();
            var inventoryPeriodAmount   = $("#globalInventoryTimeAmountVariation_"+variationId).val();
            var inventoryPeriodType     = $("#globalInventoryTimeAmountPeriodVariation_"+variationId).val();
            var inventoryToPrevious     = $("#globalAddInventoryToPreviousVariation_"+variationId);
            
            if(inventoryPeriodAmount == ""){
               alert("<?php echo $Core->Translator->translate("Please declare inventory 'period amount' for variation");?>"); 
            }else{
                $(".inventoryTimeAmountShopVariation_"+variationId).val(inventoryPeriodAmount);
                $(".inventoryTimeAmountPeriodShopVariation_"+variationId).val(inventoryPeriodType);
            }
            
            if(inventoryToPrevious.prop("checked")){
                $(".addInventoryToPreviousShopVariation_"+variationId).prop("checked",true);
            }
        }
        
    });
    
    $(".globalInStorePrice").map(function(){
        
        //Prices
        var currencyId = $(this).attr("data-currency");
        
        var inStorePrice    = $(this).val();
        var inStoreTax      = $("#globalInStoreTax_"+currencyId).val();
        var inStoreTaxType  = $("#globalInStoreTaxType_"+currencyId).val();
        var deliveryPrice   = $("#globalDeliveryPrice_"+currencyId).val();
        var deliveryTax     = $("#globalDeliveryTax_"+currencyId).val();
        var deliveryTaxType = $("#globalDeliveryTaxType_"+currencyId).val();
        
        $(".form_StorePrice_currency_"+currencyId).val(inStorePrice);
        
        $(".form_StoreTax_currency_"+currencyId).val(inStoreTax);
        
        $(".form_StoreTaxType_currency_"+currencyId).val(inStoreTaxType);
        
        $(".form_DeliveryPrice_currency_"+currencyId).val(deliveryPrice);
        
        $(".form_DeliveryTax_currency_"+currencyId).val(deliveryTax);
        
        $(".DeliveryTaxType_currency_"+currencyId).val(deliveryTaxType);
        
        
        variationIds.forEach(function(variationId){
            var val1 = $("#globalInStorePriceVariation_"+variationId+"_currency_"+currencyId).val();
            if(val1 != ""){
                $(".InStorePriceVariation_"+variationId+"_currency_"+currencyId).val(val1);
            }
            
            var val2 = $("#globalInStoreTaxVariation_"+variationId+"_currency_"+currencyId).val();
            if(val2 != ""){
                $(".InStoreTaxVariation_"+variationId+"_currency_"+currencyId).val(val2);
                $(".InStoreTaxVariation_"+variationId+"_currency_"+currencyId).addClass("active");
            }
            
            var val3 = $("#globalInStoreTaxTypeVariation_"+variationId+"_currency_"+currencyId).val();
            if(val3 != ""){
                $(".InStoreTaxTypeVariation_"+variationId+"_currency_"+currencyId).val(val3);
            }
            
            var val4 = $("#globalDeliveryPriceVariation_"+variationId+"_currency_"+currencyId).val();
            if(val4 != ""){
                $(".DeliveryPriceVariation_"+variationId+"_currency_"+currencyId).val(val4);
            }
            
            var val5 = $("#globalDeliveryTaxVariation_"+variationId+"_currency_"+currencyId).val();
            if(val5 != ""){
                $(".DeliveryTaxVariation_"+variationId+"_currency_"+currencyId).val(val5);
            }
            
            var val6 = $("#globalDeliveryTaxTypeVariation_"+variationId+"_currency_"+currencyId).val();
            if(val6 != ""){
                $(".DeliveryTaxTypeVariation_"+variationId+"_currency_"+currencyId).val(val6);
            }
        });
         
        
    }); 
    
    Materialize.updateTextFields();
    $('select').material_select();
}

$("#globalCurrencySelect").change(function(){
    $(".globalPriceContainer").fadeOut();
    var currencyId = $(this).val();
    $("#globalPriceCurrency_"+currencyId).fadeIn();
});


</script>
