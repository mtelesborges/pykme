<?php
$Core = $data["Core"];
?>
<div class="col s12 input-field">
    <select id="productRestrictionTime_shops" multiple>
        <?php
        foreach($data["inShops"] as $shop){
        ?>
        <option selected value="<?php echo $shop["id"]?>"  data-icon="<?php echo $shop["logo"]?>" class="circle left"><?php echo $shop["name"];?><br/><br/><span class="grey-text">(<?php echo $shop["address"]["googleString"];?>)</span></option>   
        <?php
        }
        ?>   
    </select>
</div>
<div class="col s12 input-field">
   <select id="productRestrictionTime_variations" multiple>
       <option value="global" selected><?php echo $Core->Translator->translate("All Variations");?></option>
       <option value="default" ><?php echo $Core->Translator->translate("Default Variation");?></option>
        <?php
        foreach($data["activeVariations"] as $variation){
        ?>
            <option value="<?php echo $variation["info"]["id"]?>">
            <?php foreach($variation["descriptions"] as $d){
                if($d["default"] == 1){
                   echo $d["title"];
                }
            }?>
            </option>
        <?php
        }
        ?>
   </select>
    <label><?php echo $Core->Translator->translate("Product Variation")?></label>
</div>
<div class="col m3 s12 input-field">
   <select id="productRestrictionTime_weekdays" multiple>
       <option selected disabled><?php echo $Core->Translator->translate("Select weekdays");?></option>
       <?php
       foreach ($Core->weekday as $day){
       ?>
       <option value="<?php echo $day;?>"><?php echo $Core->Translator->translate($day);?></option>
       <?php
       }
       ?>
   </select>
</div>
<div class="col m3 s12 input-field">
    <input type="time" id="productRestrictionTime_from" class="timepicker"/>
    <label for="productRestrictionTime_from" class="active"><?php echo $Core->Translator->translate("From hour");?></label>
</div>
<div class="col m3 s12 input-field">
    <input type="time" id="productRestrictionTime_until" class="timepicker"/>
    <label for="productRestrictionTime_until" class="active"><?php echo $Core->Translator->translate("Until hour");?></label>
</div>
<div class="col m3 s12 input-field">
    <select id="productRestrictionTime_action">
        <option value="available"><?php echo $Core->Translator->translate("available");?></option>
        <option value="unavailable"><?php echo $Core->Translator->translate("unavailable & visible");?></option>
        <option value="invisible"><?php echo $Core->Translator->translate("invisible");?></option>
    </select>
    <label><?php echo $Core->Translator->translate("Action");?></label>
</div>
<div class="col s12">
    <p>
        <input id="productRestrictionTimeHasPeriod" type="checkbox" onclick="productRestrictionTimeHasAPeriod($(this))"/>
        <label for="productRestrictionTimeHasPeriod"><?php echo $Core->Translator->translate("Hour restriction has a date period");?></label>
    </p>
</div>
<div class="ishidden" id="productRestrictionTimePeriod">
        <div class="col s12 m6 input-field">
            <input id="productRestrictionTimePeriodDateFrom" type="text" class="datepicker"/>
            <label for="productRestrictionHourPeriodDateFrom"><?php echo $Core->Translator->translate("From date");?></label>
        </div>
        <div class="col s12 m6 input-field">
            <input id="productRestrictionTimePeriodDateUntil" type="text" class="datepicker"/>
            <label for="productRestrictionHourPeriodDateUntil"><?php echo $Core->Translator->translate("Until date");?></label>
        </div>
</div>
<div class="col s12 input-field">
    <button type="button" class="btn-flat" onclick="createProductRestrictionTime()"><?php echo $Core->Translator->translate("Create Time Restriction");?></button>
</div>





<?php
if($data["productRestriction"]){?>
<div class="col s12">
    <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Time Restriction");?></h6>
    <ul class="collection">
        <?php
        $hasRestriction = false;
        foreach($data["productRestriction"] as $restriction){
            if($restriction["type"] == "Time"){
                $object =  unserialize($restriction["object"]);
                $hasRestriction = true;
            ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                    
                    <input id="productRestriction_<?php echo $restriction["id"]?>" name="productRestriction[]" type="checkbox" value="<?php echo $restriction["id"]?>"/>
                    <label for="productRestriction_<?php echo $restriction["id"]?>"><span></span>
                    </label>
                </div>
                <div class="col s10">
                    
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe in shops:")?></p>
                        <?php
                        foreach($object["shops"] as $shop_id){
                        ?>
                            <div class="chip">
                                <img src="<?php echo $data["allShops"][$shop_id]["logo"]?>" alt="Logo <?php echo $data["allShops"][$shop_id]["name"]?>">
                                <?php echo $data["allShops"][$shop_id]["name"]?> (<?php echo $data["allShops"][$shop_id]["address"]["googleString"]?>)
                            </div>
                            <br/>
                        <?php
                        }
                        ?>
                    </div>                   
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe for variations:")?></p>
                        <p class="grey-text">
                            <?php
                            foreach($object["variations"] as $variation){
                                if($variation == "default" || $variation == "global"){
                                    if($variation == "default"){
                                        $variation = $Core->Translator->translate("default");
                                    }
                                    if($variation == "global"){
                                        $variation = $Core->Translator->translate("All variations");
                                    }
                                    echo "(".$variation.") ";
                                }else{
                                    foreach($data["AllVariations"][$variation]["descriptions"] as $var){
                                        if($var["default"] == true){
                                            echo "(".$var["title"].") ";
                                        }
                                    }
                                }
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Action:");?></p>
                        <p class="grey-text">
                            <?php 
                            if($object["action"] == "invisible"){
                                $action = "Invisible";
                            }
                            if($object["action"] == "unavailable"){
                                $action = "Unavailable but visible";
                            }
                            if($object["action"] == "available"){
                                $action = "Available";
                            }
                            echo $Core->Translator->translate($action);
                            ?>
                        </p>
                    </div>
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Weekdays:");?></p>
                        <p class="grey-text"><?php foreach($object["weekdays"] as $day){ echo "(".$Core->Translator->translate($day).") ";}?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("From hour:");?></p>
                        <p class="grey-text"><?php echo $object["timeFrom"]?> <?php echo $object["distanceSystem"]?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Until hour:");?></p>
                        <p class="grey-text"><?php echo $object["timeUntil"]?> <?php echo $object["distanceSystem"]?></p>
                    </div>
                    <?php
                    if($object["hasPeriod"] == "true"){
                    ?>
                        <div class="col s6">
                            <p><?php echo $Core->Translator->translate("From date:");?></p>
                            <p class="grey-text"><?php echo $object["dateFrom"]?></p>
                        </div>
                        <div class="col s6">
                            <p><?php echo $Core->Translator->translate("Until date:");?></p>
                            <p class="grey-text"><?php echo $object["dateUntil"]?></p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </li>
            <?php
            }
        }
        if($hasRestriction == false){
            echo $Core->Translator->translate("Please create a time restriction");
        }
        ?>
    </ul>
    </div>
<?php
}
?>

<script>
    function productRestrictionDistanceHasAPeriod(input){
        if(input.prop("checked")){
            $("#productRestrictionDistancePeriod").fadeIn();
        }else{
            $("#productRestrictionDistancePeriod").hide();
        }
    }
    function productRestrictionDistancePeriodCheckWeekly(input){
        if(input.prop("checked")){
            $("#productRestrictionDistancePeriodIsNotWeekly").hide();
        }else{
            $("#productRestrictionDistancePeriodIsNotWeekly").fadeIn();
        }
    }
</script>