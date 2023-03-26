<?php
$Core = $data["Core"];
?>

<div class="col s12 input-field">
    <select id="priceConditionInventoryShops" multiple>
        <?php
        foreach($data["inShops"] as $shop){
        ?>
        <option selected value="<?php echo $shop["id"]?>"  data-icon="<?php echo $shop["logo"]?>" class="circle left"><?php echo $shop["name"];?><br/><br/><span class="grey-text">(<?php echo $shop["address"]["googleString"];?>)</span></option>   
        <?php
        }
        ?>
    </select>
    <label><?php echo $Core->Translator->translate("Availabe in shops:");?></label>
</div>
    <?php
    if($data["activeVariations"]){
    ?>
    <div class="col s12 input-field">
        <select id="priceConditionInventoryVariations" multiple>
            <option value="default" selected><?php echo $Core->Translator->translate("Default Product");?></option>
            <?php

            foreach($data["activeVariations"] as $variation){
            ?>
            <option value="<?php echo $variation["info"]["id"]?>"><?php foreach($variation["descriptions"] as $d){
                if($d["default"] == 1){
                    echo $d["title"];
                }
            }?></option>
            <?php
            }
            ?>
        </select>
        <label><?php echo $Core->Translator->translate("Product Variation")?></label>
    </div>
    <?php 
    }
    ?>
<div class="col s12 m6 input-field">
    <select id="priceConditionInventoryActionType">
        <option value="smaller"><?php echo $Core->Translator->translate("Smaller then");?></option>
        <option value="bigger"><?php echo $Core->Translator->translate("Bigger then");?></option>
    </select>
    <label><?php echo $Core->Translator->translate("Action");?></label>
</div>
<div class="col s12 m6 input-field">
    <input type="number" class="validate" id="priceConditionInventoryAmount"/>
    <label><?php echo $Core->Translator->translate("Inventory amount");?></label>
</div>
<div class="col s12 m6 input-field">
    <select id="priceConditionInventoryOperation">
        <option selected disabled><?php echo $Core->Translator->translate("Select operation");?></option>
        <option value="add"><?php echo $Core->Translator->translate("Addition to price");?></option>
        <option value="sub"><?php echo $Core->Translator->translate("Subtraction from price");?></option>
    </select>
</div>
<div class="col s12 m6 input-field">
    <select onchange="changeInventoryConditionCurrency($(this))">
        <option selected disabled><?php echo $Core->Translator->translate("Select currency")?></option>
    <?php
    foreach($Core->currencies as $currency){
    ?>
        <option value="<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"]?>)</option>
    <?php
    }
    ?>
    </select>
    <label><?php echo $Core->Translator->translate("Currency");?></label>
</div>
<div class="col s12">
    <?php
    foreach($Core->currencies as $currency){
    ?>
    <div class="input-field ishidden inventoryConditionPriceContainer" id="inventoryConditionPrice_<?php echo $currency["id"]?>">
        <input class="inventoryConditionPrice validate" type="number" step="any" data-currency="<?php echo $currency["id"]?>" min="0"/>
        <label><?php echo $Core->Translator->translate("Amount in");?> <?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"];?>)</label>
        <p class="grey-text"><?php echo $Core->Translator->translate("Amount to add or substract from normal price, not the actual price!");?></p>
    </div>
    <?php
    }
    ?>
</div>
<p class="col s12">
    <input type="checkbox" id="priceConditionInventoryIsTimeSensitive" onchange="checkInventoryTimeSensitive($(this))"/>
    <label for="priceConditionInventoryIsTimeSensitive"><?php echo $Core->Translator->translate("Condition is time sensitive");?></option>
</p>
<div id="priceConditionInventoryTimeContent" class="ishidden">
    <p class="col s12">
        <input id="inventoryConditionApplyWeekly" type="checkbox" onclick="checkInventoryConditionWeekly($(this))" checked/>
        <label for="inventoryConditionApplyWeekly"><?php echo $Core->Translator->translate("Apply condition weekly");?></label>
    </p>
    <div class="ishidden" id="inventoryConditionHasPeriod">
        <div class="col s12 m6 input-field">
            <input type="text" class="datepicker" id="inventoryConditionPeriodFrom"/>
            <label for="inventoryConditionPeriodFrom"><?php echo $Core->Translator->translate("From date");?></label>
        </div>
        <div class="col s12 m6 input-field">
            <input type="text" class="datepicker" id="inventoryConditionPeriodUntil"/>
            <label for="inventoryConditionPeriodUntil"><?php echo $Core->Translator->translate("Until date");?></label>
        </div>
    </div>
    <div class="col m4 s12 input-field">
        <select id="inventoryConditionTimeDays" multiple>
            <option selected disabled><?php echo $Core->Translator->translate("Select Weekdays");?></option>
            <?php
            foreach($Core->weekday as $day){
            ?>
                <option value="<?php echo $day?>"><?php echo $Core->Translator->translate($day)?></div>
            <?php
            }
            ?>
        </select>
        <label><?php echo $Core->Translator->translate("Weekdays");?></label>
    </div>
    <div class="col s12 m4 input-field">
        <input id="inventoryConditionTimeFrom" type="text" class="timepicker"/>
        <label for="inventoryConditionTimeFrom"><?php echo $Core->Translator->translate("From")?></label>
    </div>
    <div class="col s12 m4 input-field">
        <input id="inventoryConditionTimeUntil" type="text" class="timepicker"/>
        <label for="inventoryConditionTimeUntil"><?php echo $Core->Translator->translate("Until")?></label>
    </div>

</div>
<div class="col s12 input-field">
    <button type="button" class="btn-flat" onclick="createInventoryCondition()"><?php echo $Core->Translator->translate("Create Inventory Condition")?></button>
</div>


<?php
if($data["priceConditions"]){
?>
<div class="col s12">
        <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Inventory Conditions");?></h6>
        <ul class="collection">
            <?php
            $hasCondition = false;
            foreach($data["priceConditions"] as $condition){ 
                if($condition["type"] == "Inventory"){
                    $hasCondition = true;
                    $object = unserialize($condition["object"]);
                ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                    
                    <input name="priceConditions[]" type="checkbox" id="priceConditionById_<?php echo $condition["id"]?>" value="<?php echo $condition["id"]?>"/>
                    <label for="priceConditionById_<?php echo $condition["id"]?>"><span></span>
                    </label>
                </div>
                <div class="col s10">
                    
                     <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe in shops:")?></p>
                        <?php foreach($object["shops"] as $shop_id){
                        ?>
                            <div class="chip">
                                <img src="<?php echo $data["allShops"][$shop_id]["logo"]?>" alt="logo <?php echo $data["allShops"][$shop_id]["name"]?>">
                                <?php echo $data["allShops"][$shop_id]["name"]?> (<?php echo $data["allShops"][$shop_id]["address"]["googleString"]?>)
                            </div>
                            <br/>
                        <?php   
                        }?>
                    </div>
                     <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe for variations:")?></p>
                        <p class="grey-text">
                            <?php
                            
                            foreach($object["variations"] as $variation){
                                if($variation == "default"){
                                    $variation = $Core->Translator->translate("default");
                                }
                                echo "( ".$variation." )  ";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Action when:")?></p>
                        <p class="grey-text">
                            <?php
                            if($object["action"] == "bigger"){
                                echo $Core->Translator->translate("Bigger then");
                            }
                            if($object["action"] == "smaller"){
                                echo $Core->Translator->translate("Smaller then");
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Inventory Amount");?></p>
                        <p class="grey-text"><?php echo $object["inventoryAmount"] ?></p>
                    </div>
                    
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Operation"); ?></p>
                        <p class="grey-text">
                            <?php
                            if($object["operation"] == "add"){
                                echo $Core->Translator->translate("Addition to price");
                            }
                            if($object["operation"] == "sub"){
                                echo $Core->Translator->translate("Subtration from price");
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Amount");?></p>
                         <?php
                        $action;
                        if($object["operation"] == "add"){
                            $action = "+";
                        }
                        if($object["operation"] == "sub"){
                            $action = "-";
                        }
                        foreach($object["prices"] as $price){
                        ?>
                        <p class="grey-text"><?php echo $action." ".$price["price"];?> (<?php $cur = array_search($price["currency_id"],array_column($Core->currencies,"id"));echo $Core->currencies[$cur]["code"];?>)</p>
                        <?php
                        }
                        ?>
                    </div>
                    
                     <?php
                    if($object["isTimeSensitive"] == "true"){
                    ?>
                        <div class="col s6">
                            <p><?php echo $Core->Translator->translate("Days");?></p>
                            <?php 
                            foreach($object["weekdays"] as $day){
                            ?>
                            <p class="grey-text"><?php echo $Core->Translator->translate($day);?></p>
                            <?php }?>
                        </div>
                        <div class="col s6">
                            <p><?php echo $Core->Translator->translate("Time");?></p>
                            <p class="grey-text"><?php echo $object["timeFrom"]?> => <?php echo $object["timeUntil"]?></p>
                        </div>
                    <?php }?>
                    
                    <div class="col s12 m6">
                        <p><?php echo $Core->Translator->translate("Period");?></p>
                    <?php
                    if($object["isWeekly"] == "true"){ ?>
                        <p class="grey-text"><?php echo $Core->Translator->translate("Always");?></p>
                    <?php
                    }
                    
                    if($object["isWeekly"] == "false"){
                    ?>
                        <p class="grey-text"><?php echo $object["dateFrom"]?> => <?php echo $object["dateUntil"]?></p>
                    <?php
                    }
                    ?>
                    
                </div>
            </li>
            <?php }}?>
            <?php
            if($hasCondition == false){?>
            <p><?php echo $Core->Translator->translate("Please create a inventory condition.");?></p>
            <?php
            }
            ?>
        </ul>
<?php } ?>