<?php
$Core = $data["Core"];
?>
<div class="col s12 input-field">
    <select id="priceConditionDistanceShops" multiple>
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
       <select id="priceConditionDistanceVariations" multiple>
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
<div class="col s12 input-field">
    <select id="distanceConditionOperation">
        <option selected disabled><?php echo $Core->Translator->translate("Choose operation")?></option>
        <option value="add"><?php echo $Core->Translator->translate("Addition to price");?></option>
        <option value="sub"><?php echo $Core->Translator->translate("Subtraction from price");?></option>
    </select>
    <label><?php echo $Core->Translator->translate("Operation");?></label>
</div>
<div class="col s12 input-field">
    <select onchange="changeDistanceConditionCurrency($(this))">
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
    <div class="input-field ishidden distanceConditionPriceContainer" id="distanceConditionPrice_<?php echo $currency["id"]?>">
        <input class="distanceConditionPrice validate" type="number" step="any" data-currency="<?php echo $currency["id"]?>" min="0"/>
        <label><?php echo $Core->Translator->translate("Amount in");?> <?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"];?>)</label>
        <p class="grey-text"><?php echo $Core->Translator->translate("Amount to add or substract from normal price, not the actual price!");?></p>
    </div>
    <?php
    }
    ?>
</div>
<div class="col s12 m4 input-field">
    <input id="priceConditionDistanceFrom" type="number" step="any" class="validate"/>
    <label><?php echo $Core->Translator->translate("Distance from");?></label>
</div>
<div class="col s12 m4 input-field">
    <input id="priceConditionDistanceUntil" type="number" step="any" class="validate"/>
    <label><?php echo $Core->Translator->translate("Distance until");?></label>
</div>
<div class="col s12 m4 input-field">
    <select id="priceConditionDistanceSystem">
        <option value="km"><?php echo $Core->Translator->translate("Kilometers")?></option>
        <option value="mi"><?php echo $Core->Translator->translate("Miles")?></option>
    </select>
</div>
<p class="col s12">
    <input type="checkbox" id="timeSensitiveDistanceCondition" onclick="checkDistanceTimeSensitive($(this))"/>
    <label for="timeSensitiveDistanceCondition"><?php echo $Core->Translator->translate("Condition is time sensitive");?></label>
</p>
<div class="ishidden" id="distanceConditionTimeSensitiveContent">
    <p class="col s12">
        <input id="distanceConditionApplyWeekly" type="checkbox" onclick="checkDistanceConditionWeekly($(this))" checked/>
        <label for="distanceConditionApplyWeekly"><?php echo $Core->Translator->translate("Apply condition weekly");?></label>
    </p>
    <div class="ishidden" id="distanceConditionHasPeriod">
        <div class="col s12 m6 input-field">
            <input type="text" class="datepicker" id="distanceConditionPeriodFrom"/>
            <label for="distanceConditionPeriodFrom"><?php echo $Core->Translator->translate("From date");?></label>
        </div>
        <div class="col s12 m6 input-field">
            <input type="text" class="datepicker" id="distanceConditionPeriodUntil"/>
            <label for="distanceConditionPeriodUntil"><?php echo $Core->Translator->translate("Until date");?></label>
        </div>
    </div>
    <div class="col m4 s12 input-field">
        <select id="distanceConditionTimeDays" multiple>
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
        <input id="distanceConditionTimeFrom" type="text" class="timepicker"/>
        <label for="distanceConditionTimeFrom"><?php echo $Core->Translator->translate("From")?></label>
    </div>
    <div class="col s12 m4 input-field">
        <input id="distanceConditionTimeUntil" type="text" class="timepicker"/>
        <label for="distanceConditionTimeUntil"><?php echo $Core->Translator->translate("Until")?></label>
    </div>
</div>
<div class="col s12 input-field">
    <button type="button" class="btn-flat" onclick="createDistanceCondition()"><?php echo $Core->Translator->translate("Create Distance Condition")?></button>
</div>

<?php
if($data["priceConditions"]){
?>
<div class="col s12">
        <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Distance Conditions");?></h6>
        <ul class="collection">
            <?php
            $hasCondition = false;
            foreach($data["priceConditions"] as $condition){ 
                if($condition["type"] == "Distance"){
                    $hasCondition = true;
                    $object = unserialize($condition["object"]);
                ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                  
                    <input type="checkbox" value="<?php echo $condition["id"]?>" name="priceConditions[]" id="priceConditionById_<?php echo $condition["id"]?>">
                    <label for="priceConditionById_<?php echo $condition["id"]?>"> <span></span>
                    </label>
                </div>
                <div class="col s10">
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe in shops:")?></p>
                        <?php foreach($object["shops"] as $shop_id){
                        ?>
                            <div class="chip">
                                <img src="<?php echo $data["allShops"][$shop_id]["logo"]?>" alt="Logo <?php echo $data["allShops"][$shop_id]["name"]?>">
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
                        <p><?php echo $Core->Translator->translate("From distance:")?></p>
                        <p class="grey-text"><?php echo $object["distanceFrom"]?> <?php echo $object["distanceSystem"]?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Until distance:")?></p>
                        <p class="grey-text"><?php echo $object["distanceUntil"]?> <?php echo $object["distanceSystem"]?></p>
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
                            <p class="grey-text"><?php echo $object["hourFrom"]?> => <?php echo $object["hourUntil"]?></p>
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
                    <div class="col s12 m6">
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
                </div>
            </li>
            <?php }}?>
            <?php
            if($hasCondition == false){?>
            <p><?php echo $Core->Translator->translate("Please create a distance condition.");?></p>
            <?php
            }
            ?>
        </ul>
<?php } ?>

