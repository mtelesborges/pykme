<?php
$Core = $data["Core"];
if($data["inShops"]){
?>
<p class="grey-text lighten-1"><?php echo $Core->Translator->translate(" You can limit the access of your product to users and drivers. Make it only available for them when its convinient.");?></p>
<div class="col s12">
  <ul class="collapsible" data-collapsible="expandable">
    <li>
        <div class="collapsible-header"><i class="material-icons">child_friendly</i><?php echo $Core->Translator->translate("Age (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the product only available for a user with an age over the selected one.");?></p>
            <div id="load_product_restriction_Age">
                <?php $Core->FrontController->partialRender("product-restriction-list-Age.php",$data);?>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">map</i><?php echo $Core->Translator->translate("Distance (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p  class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the product only visible for user between the min. and max distance.");?></p>
            <div id="load_product_restriction_Distance">
                <?php $Core->FrontController->partialRender("product-restriction-list-Distance.php",$data);?>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">access_time</i><?php echo $Core->Translator->translate("Time (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the product only available, unavailabe but visible or invisible for a user at a specific time of the week. If you select available, product will be only availble between the time selected and unavailable/invisible otherwise. If you select invisible or unavailable the product will be only between selected time unavailable/invisible and visible otherwise. Please don't mix visible and invisible for the same product it can lead to problems on display.");?></p>
            <div id="load_product_restriction_Time">
                <?php $Core->FrontController->partialRender("product-restriction-list-Time.php",$data);?>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">access_alarm</i><?php echo $Core->Translator->translate("Max. Delivery Time (Users & Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text"><?php echo $Core->Translator->translate("If the time of delivery can't be over a certain time, you can restrict it to drivers and users. Pykme will calculate the delivery time and only show it to drivers there capable of delivering it and for users in range.");?></p>
            <div id="load_product_restriction_DeliveryTime">
                <?php $Core->FrontController->partialRender("product-restriction-list-DeliveryTime.php",$data);?>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">directions_bike</i><?php echo $Core->Translator->translate("Vehicle (Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("You can include or exclude vehicles. All vehicles and on foot delivery are included by default.");?></p>
            <div id="rVehicle">
                <ul>
                <?php
                foreach($data["vehiclesRestriction"] as $vehicle){
                ?>
                    <li class="valign-wrapper rDistanceList">
                        <div class="col m4 s12">
                            <div class="switch">
                                <label>
                                    <?php echo $Core->Translator->translate("Exclude");?>
                                    <input type="checkbox" checked name="vehicleProduct[]" value="<?php echo $vehicle["id"]?>">
                                    <span class="lever"></span>
                                    <?php echo $Core->Translator->translate("Include");?>
                                </label>
                            </div>
                        </div>
                        <div class="col m7 s12">
                            <p><?php echo $Core->Translator->translate($vehicle["name"]);?></p>
                        </div>
                    </li>
                <?php
                }
                ?>
                </ul>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">thermostat</i><?php echo $Core->Translator->translate("Transportation (Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("Is your cargo sensitive? Here you can declare the type of needs your cargo has.");?></p>
            <div id="load_product_restriction_Transportation">
                <?php $Core->FrontController->partialRender("product-restriction-list-Transportation.php",$data);?>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">handyman</i><?php echo $Core->Translator->translate("Equipment (Drivers)");?></div>
        <div class="collapsible-body">
            <span><?php echo $Core->Translator->translate("Select equipment needed from drivers for a successfull delivery:");?></span>
            <br/><br/>
            <ul>
                <?php
                foreach($data["equipmentRestriction"] as $equipment){
                ?>
                <li>
                    <input type="checkbox" value="<?php echo $equipment["id"]?>" name="equipmentProduct[]" id="equipment_<?php echo $equipment["id"]?>"/>
                    <label for="equipment_<?php echo $equipment["id"]?>"><?php echo $Core->Translator->translate($equipment["name"]);?></label>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </li>
  </ul>

</div>
<?php
}else{
?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product can have diffrent prices at diffrent shops. Please select at least one category so we know in wich shops the product will be available.");?>
        </p>
    </li>
</ul>
<?php 
}
?>

<script>
function createProductRestictionAge(){
    var shops       = $("#productRestrictionAge_shops").val();
    var variations  = $("#productRestrictionAge_variations").val();
    var age         = $("#productRestrictionAge_amount").val();
    
    if(shops == ""){
        alert("<?php echo $Core->Translator->translate("Please select at least one shop.");?>");
        throw new Error;
    }
    if(document.getElementById("productRestrictionAge_variations")){
        if(variations == ""){
            alert("<?php echo $Core->Translator->translate("Please select at least one variation");?>");
            throw new Error;
        }
    }else{
        variations = ["default"];
    }
    
    if(age == ""){
        alert("<?php echo $Core->Translator->translate("Please declare the age.");?>");
        throw new Error;
    }
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    var data = {
        type: "Age",
        shops:shops,
        variations:variations,
        age:age,
        inShops:shopIds,
        activeVariations:variationIds
    };
    
    createRestriction(data);
}
function createProductRestrictionDistance(){
        var shops = $("#productRestrictionDistance_shops").val();
        if(shops == ""){
            alert("<?php echo $Core->Translator->translate("Please select at least one shop");?>");
            throw new Error;
        }
        
        var variations = $("#productRestrictionDistance_variations").val();
        
        if(document.getElementById("productRestrictionDistance_variations")){
            if(variations == ""){
                alert("<?php echo $Core->Translator->translate("Please select one variation");?>");
                throw new Error;
            }
        }
        
        // if global delete all other variations (keep it clean)
        var global = false;
        variations.map(function(variation){
            if(variation == "global"){
                global = true;
            }
        });
        if(global == true){
            variations = ["global"];
        }
        
        var distanceFrom    = $("#productRestrictionDistanceFrom").val();
        var distanceUntil   = $("#productRestrictionDistanceUntil").val(); 
        
        if(distanceFrom == "" || distanceUntil == ""){
            alert("<?php echo $Core->Translator->translate("Please declare both distances");?>");
            throw new Error;
        }
        if(new Number(distanceFrom) >new Number(distanceUntil)){
            alert("<?php echo $Core->Translator->translate("Distance from can't be greater then until");?>");
            throw new Error;
        }
        
        var distanceSystem = $("#productRestrictionDistanceSystem").val();
        
        var isTimeSensitive;
        if($("#productRestrictionDistanceHasPeriod").prop("checked")){
            isTimeSensitive = true;
            
            var weekdays = $("#productRestrictionDistancePeriodWeekdays").val();
            if(weekdays == ""){
                alert("<?php echo $Core->Translator->translate("Please select weekdays");?>");
                throw new Error;
            }
            
            var timeFrom    = $("#productRestrictionDistancePeriodTimeFrom").val();
            var timeUntil   = $("#productRestrictionDistancePeriodTimeUntil").val();
            if(timeFrom == "" || timeUntil == ""){
                alert("<?php echo $Core->Translator->translate("Please declare both times");?>");
                throw new Error;
            }
            if(timeFrom > timeUntil){
                alert("<?php echo $Core->Translator->translate("Time from can't be after until");?>");
                throw new Error;
            }
            
            var isWeekly;
            if($("#productRestrictionDistancePeriodIsWeekly").prop("checked")){
                isWeekly = true;
            }else{
                isWeekly = false;
                var dateFrom = $("#productRestrictionDistancePeriodDateFrom").val();
                var dateUntil = $("#productRestrictionDistancePeriodDateUntil").val();
                
                if(dateFrom == "" || dateUntil == ""){
                    alert("<?php echo $Core->Translator->translate("Please declare both dates");?>");
                    throw new Error;
                }
                if(parseDate(dateFrom,"dd-mm-yyyy") > parseDate(dateUntil,"dd-mm-yyyy")){
                    alert("<?php echo $Core->Translator->translate("Date from can't be after until");?>");
                    throw new Error;
                }
            }
        }else{
            isTimeSensitive = false;
        }
        
        var shopIds = getShops();
        var variationIds = getVariations(shopIds);
        var data = {
            type:"Distance",
            shops:shops,
            variations:variations,
            distanceFrom:distanceFrom,
            distanceUntil:distanceUntil,
            distanceSystem:distanceSystem,
            isTimeSensitive:isTimeSensitive,
            weekdays:weekdays,
            timeFrom:timeFrom,
            timeUntil:timeUntil,
            isWeekly:isWeekly,
            dateFrom:dateFrom,
            dateUntil:dateUntil,
            inShops:shopIds,
            activeVariations:variationIds
        };
        
        createRestriction(data);
}

function createProductRestrictionTime(){
    var shops = $("#productRestrictionTime_shops").val();
    
    if(shops == ""){
        alert("<?php echo $Core->Translator->translate("Please select at least one shop");?>");
        throw new Error;
    }
    
    var action = $("#productRestrictionTime_action").val();
    
    var variations = $("#productRestrictionTime_variations").val();

    if(variations == ""){
        alert("<?php echo $Core->Translator->translate("Please select one variation");?>");
        throw new Error;
    }  
    // if global delete all other variations (keep it clean)
    var global = false;
    variations.map(function(variation){
        if(variation == "global"){
            global = true;
        }
    });
    if(global == true){
        variations = ["global"];
    }
    
    var timeFrom = $("#productRestrictionTime_from").val();
    var timeUntil = $("#productRestrictionTime_until").val();
    
    if(timeFrom == "" || timeUntil == ""){
        alert("<?php echo $Core->Translator->translate("Please declare hours from and until");?>");
        throw new Error;
    }
    
    if(timeFrom > timeUntil){
        alert("<?php echo $Core->Translator->translate("Hours from can't be after until");?>");
        throw new Error;
    }
    
    var weekdays = $("#productRestrictionTime_weekdays").val();
    if(weekdays == ""){
        alert("<?php echo $Core->Translator->translate("Please select weekdays");?>");
        throw new Error;
    }
    
    var hasPeriod;
    
    if($("#productRestrictionTimeHasPeriod").prop("checked")){
        hasPeriod = true;
    }else{
        hasPeriod = false;
    }
    
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    var data = {
        type:"Time",
        shops:shops,
        action:action,
        variations:variations,
        weekdays:weekdays,
        timeFrom:timeFrom,
        timeUntil:timeUntil,
        inShops:shopIds,
        activeVariations:variationIds,
        hasPeriod:hasPeriod
    }
    
    if(hasPeriod == true){
        var dateFrom = $("#productRestrictionTimePeriodDateFrom").val();
        var dateUntil = $("#productRestrictionTimePeriodDateUntil").val();
        
        if(dateFrom == "" || dateUntil == ""){
            alert("<?php echo $Core->Translator->translate("Please declate both dates");?>");
            throw new Error;
        }
        
        if(parseDate(dateFrom,"dd-mm-yyyy") > parseDate(dateUntil,"dd-mm-yyyy")){
            alert("<?php echo $Core->Translator->translate("Date from can't be after until");?>");
            throw new Error;
        }
        
        data.dateFrom = dateFrom;
        data.dateUntil = dateUntil;
    }
    
    createRestriction(data);
}

function createProductRestrictionDeliveryTime(){
    var shops = $("#productRestrictionDeliveryTime_shops").val();
    if(shops ==""){
        alert("<?php echo $Core->Translator->translate("Please select at least one shop");?>");
        throw new Error;
    }
    
    var variations = $("#productRestrictionDeliveryTime_variations").val();

    if(variations == ""){
        alert("<?php echo $Core->Translator->translate("Please select one variation");?>");
        throw new Error;
    }  
    // if global delete all other variations (keep it clean)
    var global = false;
    variations.map(function(variation){
        if(variation == "global"){
            global = true;
        }
    });
    if(global == true){
        variations = ["global"];
    }
    
    var maxDays      = $("#productRestrictionDeliveryTime_Days").val();
    var maxHours    = $("#productRestrictionDeliveryTime_Hours").val();
    var maxMinutes  = $("#productRestrictionDeliveryTime_Minutes").val();
    
    if(maxDays =="" && maxHours =="" && maxMinutes ==""){
        alert("<?php echo $Core->Translator->translate("Please declare max. delivery time");?>");
        throw new Error;
    }
    
    var hasTimePeriod;
    if($("#productRestrictionDeliveryTimeHasTimePeriod").prop("checked")){
        hasTimePeriod = true;
    }else{
        hasTimePeriod = false;
    }
    
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    var data = {
        type:"DeliveryTime",
        shops:shops,
        variations:variations,
        maxDays:maxDays,
        maxHours:maxHours,
        maxMinutes:maxMinutes,
        hasTimePeriod:hasTimePeriod,
        inShops:shopIds,
        activeVariations:variationIds,
    };
    
    if(hasTimePeriod == true){
        var weekdays    = $("#productRestrictionDeliveryTime_weekdays").val();
        var timeFrom    = $("#productRestrictionDeliveryTime_from").val();
        var timeUntil   = $("#productRestrictionDeliveryTime_until").val();
        
        if(weekdays == ""){
            alert("<?php echo $Core->Translator->translate("Please select weekdays");?>");
            throw new Error;
        }
        
        if(timeFrom =="" || timeUntil ==""){
            alert("<?php echo $Core->Translator->translate("Please declare time from and until");?>");
            throw new Error;
        }
        if(timeFrom > timeUntil){
            alert("<?php echo $Core->Translator->translate("Time from can't be after until");?>");
            throw new Error;
        }
        
        data.weekdays = weekdays;
        data.timeFrom = timeFrom;
        data.timeUntil= timeUntil;
        
    }
    
    var hasDatePeriod;
    if($("#productRestrictionDeliveryTimeHasDatePeriod").prop("checked")){
        hasDatePeriod = true;
    }else{
        hasDatePeriod = false;
    }
    data.hasDatePeriod = hasDatePeriod;
    if(hasDatePeriod == true){
        var dateFrom    = $("#productRestrictionDeliveryTimePeriodDateFrom").val();
        var dateUntil   = $("#productRestrictionDeliveryTimePeriodDateUntil").val();
        if(dateFrom =="" || dateUntil ==""){
            alert("<?php echo $Core->Translator->translate("Please declare both dates");?>");
            throw new Error;
        }
        if(parseDate(dateFrom,"dd-mm-yyyy") > parseDate(dateUntil,"dd-mm-yyyy")){
            alert("<?php echo $Core->Translator->translate("Date from can't be after until");?>");
            throw new Error;
        }
        data.dateFrom = dateFrom;
        data.dateUntil= dateUntil;
    }
    
    createRestriction(data);
    
}

function createProductRestrictionTransportation(){
    var shops = $("#productRestrictionTransportation_shops").val();
     if(shops ==""){
        alert("<?php echo $Core->Translator->translate("Please select at least one shop");?>");
        throw new Error;
    }
    
    var variations = $("#productRestrictionTransportation_variations").val();

    if(variations == ""){
        alert("<?php echo $Core->Translator->translate("Please select one variation");?>");
        throw new Error;
    }  
    // if global delete all other variations (keep it clean)
    var global = false;
    variations.map(function(variation){
        if(variation == "global"){
            global = true;
        }
    });
    if(global == true){
        variations = ["global"];
    }
    
    var type = $("#productRestrictionTransportation_cargoType").val();
    var tempFrom = $("#productRestrictionTransportation_tempFrom").val();
    var tempUntil = $("#productRestrictionTransportation_tempUntil").val();
    
    if(tempFrom == "" || tempUntil == ""){
        alert("<?php echo $Core->Translator->translate("Please declare both max. and min. temperature");?>");
        throw new Error;
    }
    
    if(parseInt(tempFrom) > parseInt(tempUntil)){
        alert("<?php echo $Core->Translator->translate("Temperature min. can't be higher then max.");?>");
        throw new Error;
    }
    
    var scala = $("#productRestrictionTransportation_Scala").val();
    
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    var data = {
        type:"Transportation",
        shops:shops,
        variations:variations,
        cargoType:type,
        tempFrom:tempFrom,
        tempUntil:tempUntil,
        scala:scala,
        inShops:shopIds,
        activeVariations:variationIds
    };
    
    createRestriction(data);
    
}

function createRestriction(data){
    $.ajax({
        url: "/merchant/createProductRestriction",
        data: data,
        type: 'POST',
        success: function (response) {  
            $("#load_product_restriction_"+data.type).html(response);
            $('.collapsible').collapsible();
            $('select').material_select();
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
</script>