<?php
$Core = $data["Core"];
?>
<div class="col s12 input-field">
    <select id="productRestrictionDistance_shops" multiple>
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
       <select id="productRestrictionDistance_variations" multiple>
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

<div class="col s12 m4 input-field">
    <input id="productRestrictionDistanceFrom" type="number" step="any" min="0"/>
    <label><?php echo $Core->Translator->translate("From distance");?></label>
</div>
<div class="col s12 m4 input-field">
    <input id="productRestrictionDistanceUntil" type="number" step="any" min="0"/>
    <label><?php echo $Core->Translator->translate("Until distance");?></label>
</div>
<div class="col s12 m4 input-field">
    <select id="productRestrictionDistanceSystem">
        <option value="km"><?php echo $Core->Translator->translate("Kilometers");?></option>
        <option value="mi"><?php echo $Core->Translator->translate("Miles");?></option>
    </select>
</div>
<div class="col s12">
    <p>
        <input id="productRestrictionDistanceHasPeriod" type="checkbox" onclick="productRestrictionDistanceHasAPeriod($(this))"/>
        <label for="productRestrictionDistanceHasPeriod"><?php echo $Core->Translator->translate("Distance restriction is time sensitive");?></label>
    </p>
</div>
<div class="ishidden" id="productRestrictionDistancePeriod">
    <div class="col s12">
        <p>
            <input id="productRestrictionDistancePeriodIsWeekly" type="checkbox" checked="checked" onclick="productRestrictionDistancePeriodCheckWeekly($(this))"/>
            <label for="productRestrictionDistancePeriodIsWeekly"><?php echo $Core->Translator->translate("Weekly");?></label>
        </p>
    </div>
    <div class="ishidden" id="productRestrictionDistancePeriodIsNotWeekly">
        <div class="col s12 m6 input-field">
            <input id="productRestrictionDistancePeriodDateFrom" type="text" class="datepicker"/>
            <label for="productRestrictionDistancePeriodDateFrom"><?php echo $Core->Translator->translate("From date");?></label>
        </div>
        <div class="col s12 m6 input-field">
            <input id="productRestrictionDistancePeriodDateUntil" type="text" class="datepicker"/>
            <label for="productRestrictionDistancePeriodDateUntil"><?php echo $Core->Translator->translate("Until date");?></label>
        </div>
    </div>
    <div class="col s12 m4 input-field">
        <select id="productRestrictionDistancePeriodWeekdays" multiple>
            <option disabled selected><?php echo $Core->Translator->translate("Select weekdays");?></option>
            <?php 
            foreach($Core->weekday as $day){?>
                <option value="<?php echo $day;?>"><?php echo $Core->Translator->translate($day);?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col s12 m4 input-field">
        <input id="productRestrictionDistancePeriodTimeFrom" type="text" class="timepicker"/>
        <label><?php echo $Core->Translator->translate("From time")?></label>
    </div>
    <div class="col s12 m4 input-field">
        <input id="productRestrictionDistancePeriodTimeUntil" type="text" class="timepicker"/>
        <label><?php echo $Core->Translator->translate("Until time")?></label>
    </div>
</div>
<div class="col s12 input-field">
    <button type="button" class="btn-flat" onclick="createProductRestrictionDistance()"><?php echo $Core->Translator->translate("Create Distance Restriction");?></button>
</div>





<?php
if($data["productRestriction"]){?>
<div class="col s12">
    <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Distance Restriction");?></h6>
    <ul class="collection">
        <?php
        $hasRestriction = false;
        foreach($data["productRestriction"] as $restriction){
            if($restriction["type"] == "Distance"){
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
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("From distance");?></p>
                        <p class="grey-text"><?php echo $object["distanceFrom"]?> <?php echo $object["distanceSystem"]?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Until distance");?></p>
                        <p class="grey-text"><?php echo $object["distanceUntil"]?> <?php echo $object["distanceSystem"]?></p>
                    </div>
                    
                    <?php
                    if($object["isTimeSensitive"] == "true"){
                    ?>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("From time");?></p>
                        <p class="grey-text"><?php echo $object["timeFrom"]?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("Until time");?></p>
                        <p class="grey-text"><?php echo $object["timeUntil"]?></p>
                    </div>
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Weekdays");?></p>
                        <p class="grey-text"><?php foreach($object["weekdays"] as $day){ echo "(".$Core->Translator->translate($day).") ";}?></p>
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
            echo $Core->Translator->translate("Please create a distance restriction");
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