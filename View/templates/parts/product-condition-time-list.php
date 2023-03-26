<?php
$Core = $data["Core"];
?>
    <div class="col s12 input-field">
        <select id="priceConditionTimeShops" multiple>
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
       <select id="priceConditionTimeVariations" multiple>
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
    <p class="col s12">

        <input id="priceConditionPeriod" type="checkbox" checked onclick="checkePriceConditionPeriod($(this))"/>
        <label for="priceConditionPeriod"><?php echo $Core->Translator->translate("Apply condition every week");?></label>

    </p>
    <div class="ishidden" id="priceConditionPeriodContent">
        <div class="col s12 m6 input-field">
            <input id="priceConditionPeriodFrom" type="text" class="datepicker"/>
            <label><?php echo $Core->Translator->translate("From");?></label>
        </div>
        <div class="col s12 m6 input-field">
            <input id="priceConditionPeriodUntil" type="text" class="datepicker"/>
            <label><?php echo $Core->Translator->translate("Until");?></label>
        </div>
    </div>
    <div class="col m4 s12 input-field">
        <select id="priceConditionTimeDays" multiple>
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
    <div class="col m4 s12 input-field">
        <input type="text" id="priceConditionFrom" class="timepicker"/>
        <label for="priceConditionFrom"><?php echo $Core->Translator->translate("From Hour");?></label>
    </div>
    <div class="col m4 s12 input-field">
        <input type="text" id="priceConditionUntil" class="timepicker"/>
        <label for="priceConditionUntil"><?php echo $Core->Translator->translate("Until Hour");?></label>
    </div>
    
    <div class="col s12 input-field">
        <select id="priceConditionTimeOperation"/>
            <option selected disabled><?php echo $Core->Translator->translate("Choose operation");?></option>
            <option value="add"><?php echo $Core->Translator->translate("Addition to price");?></option>
            <option value="sub"><?php echo $Core->Translator->translate("Subtraction from price");?></option>
        </select>
        <label><?php echo $Core->Translator->translate("Operation");?></label>
    </div>
    <div class="col s6 input-field">
        <select name="priceConditionTimeCurrency[]" onchange="priceConditionCurrency($(this))">
            <option selected disbaled><?php echo $Core->Translator->translate("Select Currency");?></option>
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
    <?php 
    foreach($Core->currencies as $currency){
    ?>  
    <div class="col s6 input-field ishidden priceConditionTimeAmountCurrency" id="priceConditionTimeAmountCurrency_<?php echo $currency["id"]?>">
       <input type="number" step="any" class="priceConditionTimeAmount validate" data-currency="<?php echo $currency["id"]?>"/>
       <label><?php echo $Core->Translator->translate("Amount to normal price");?> (<?php echo $currency["code"]?>)</label>
       <p class="grey-text"><?php echo $Core->Translator->translate("Amount to add or substract from normal price, not the actual price!")?></p>
    </div>
    <?php
    }
    ?>
    <div class="col s12 input-field">
        <button type="button" class="btn-flat" onclick="createTimeCondition()"><?php echo $Core->Translator->translate("Create Time Condition")?></button>
    </div>                  
<?php
if($data["priceConditions"]){
?>
    <div class="col s12">
        <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Time Conditions");?></h6>
        <ul class="collection">
            <?php
            foreach($data["priceConditions"] as $condition){ 
                $hasCondition = false;
                if($condition["type"] == "Time"){
                    $object = unserialize($condition["object"]);
                    $hasCondition = true;
                ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                  
                    <input type="checkbox" value="<?php echo $condition["id"]?>" name="priceConditions[]" id="priceConditionById_<?php echo $condition["id"]?>">
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
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Period:")?></p>
                        <?php
                        if(empty($object["dateFrom"]) && empty($object["dateUntil"])){
                            echo "<p class='grey-text'>".$Core->Translator->translate("Weekly")."</p>";
                        }else{?>
                            <p class="grey-text"><?php echo $object["dateFrom"]?> => <?php echo $object["dateUntil"]?></p> 
                        <?php    
                        }
                        ?>  
                    </div>
                    <div class="col m4 s12">
                        <p><?php echo $Core->Translator->translate("On days:")?></p>
                        <?php
                        foreach($object["days"] as $day){
                        ?>
                        <p class="grey-text"><?php echo $Core->Translator->translate($day)?></p>
                        <?php }?>
                    </div>
                    <div class="col m4 s12">
                        <p><?php echo $Core->Translator->translate("Hours");?></p>
                        <p class="grey-text"><?php echo $Core->Translator->translate("From");?>: <?php echo $object["from"]?></p>
                        <p class="grey-text"><?php echo $Core->Translator->translate("Until");?>: <?php echo $object["until"]?></p>
                    </div>
                    <div class="col m4 s12">
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
                <?php }}
                if($hasCondition == false){
                    echo $Core->Translator->translate("Please create a time condition.");
                }
                ?>
         </ul>
    </div>
<?php
}
?>
