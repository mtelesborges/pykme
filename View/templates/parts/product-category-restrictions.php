<?php
$Core = $data["Core"];
?>
<h5 class="h5divider"><?php echo $Core->Translator->translate("Restrictions");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5>
<p class="grey-text lighten-1"><?php echo $Core->Translator->translate("Restrictions will be applied to all products under this category. You can limit the access of your category to users and drivers. Make it only available for them when its worth showing. Restrictions can be used across shops in your account and applied to products and categories.");?></p>
<div class="col s12">
  <ul class="collapsible" data-collapsible="expandable">
    <li>
        <div class="collapsible-header"><i class="material-icons">child_friendly</i><?php echo $Core->Translator->translate("Age (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the category or product only available for a user with an age over the selected one.");?></p>
            <div id="load_restriction_Age">
                <?php $Core->FrontController->partialRender("category-restriction-list-Age.php",$data);?>
            </div>

            <div id="rAge" class="formRestriction">
                <div class="col m9 s12 input-field">
                    <input id="Age" type="number" />
                    <label for="Age"><?php echo $Core->Translator->translate("Age in years");?></label>
                </div>
                <div class="col m3 s12">
                    <button class="btn-flat restrictionCreateButton" onclick="createAge()" type="button"><?php echo $Core->Translator->translate("create");?></button>
                </div>
            </div>
            
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">map</i><?php echo $Core->Translator->translate("Distance (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
             <p  class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the category or product only available for user between the min. and max distance.");?></p>
            <div id="load_restriction_Distance">
                <?php $Core->FrontController->partialRender("category-restriction-list-Distance.php",$data);?>
            </div>
            
            <div id="rDistance" class="formRestriction">
                <div class="col m3 s12 input-field">
                    <input id="minDistanceRestriction" type="text"/>
                    <label for="minDistance"><?php echo $Core->Translator->translate("Min. Distance");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <input id="maxDistanceRestriction" type="text"/>
                    <label for="maxDistance"><?php echo $Core->Translator->translate("Max. Distance");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <select id="distanceSystemRestriction">
                        <option value="km"><?php echo $Core->Translator->translate("Kilometer");?></option>
                        <option value="mi"><?php echo $Core->Translator->translate("Miles");?></option>
                    </select>
                    <label for="minDistance"><?php echo $Core->Translator->translate("Distance in:");?></label>
                </div>
                <div class="col m3 s12">
                    <button type="button" class="btn-flat restrictionCreateButton" onclick="createDistance()"><?php echo $Core->Translator->translate("create");?></button>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">access_time</i><?php echo $Core->Translator->translate("Hours (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("This restriction will make the category or product only available or unavailable for a user at a specific time of the week. If you select availabe, product or category will be only available between the time selected and unavailble otherwise. If you select unavailable the product or category will be only between selected time unavailable and available otherwise. Please don't mix available and unavailable for the same product or category it can lead to problems on display.");?></p>
            <div id="load_restriction_Hours">
                <?php $Core->FrontController->partialRender("category-restriction-list-Hours.php",$data);?>
            </div>
            <div id="rHours" class="formRestriction">
                <div class="col m3 s12 input-field">
                   <select id="rHoursDay">
                       <?php
                       foreach ($Core->weekday as $day){
                       ?>
                       <option value="<?php echo $day;?>"><?php echo $Core->Translator->translate($day);?></option>
                       <?php
                       }
                       ?>
                   </select>
                </div>
                <div class="col m2 s12 input-field">
                    <input type="time" id="hoursFrom"/>
                    <label for="hoursFrom" class="active"><?php echo $Core->Translator->translate("From");?></label>
                </div>
                <div class="col m2 s12 input-field">
                    <input type="time" id="hoursUntil"/>
                    <label for="hoursFrom" class="active"><?php echo $Core->Translator->translate("Until");?></label>
                </div>
                <div class="col m2 s12 input-field">
                    <select id="rHoursAction">
                        <option value="available"><?php echo $Core->Translator->translate("Availabe");?></option>
                        <option value="unavailable"><?php echo $Core->Translator->translate("Unavailable");?></option>
                    </select>
                </div>
                <div class="col m3 s12">
                    <button type="button" class="btn-flat restrictionCreateButton" onclick="createHours()"><?php echo $Core->Translator->translate("Create");?></button>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">today</i><?php echo $Core->Translator->translate("Date (Users)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text"><?php echo $Core->Translator->translate("This restriction will make the catergory or product only available or unavailable on an specify date. If you select available, product or category will be only available between the dates selected and unavailable otherwise. If you select unavailable the product or category will be only between selected dates unavailable and available otherwise. Please don't mix available and unavailable for the same product or category it can lead to problems on display.");?></p>
            <div id="load_restriction_Date">
                <?php $Core->FrontController->partialRender("category-restriction-list-Date.php",$data);?>
            </div>
            
            <div id="rDate" class="formRestriction">
                <div class="col m3 s12 input-field">
                    <input type="text" id="rDateFrom" class="datepicker"/>
                    <label for="rDateFrom" class="active"><?php echo $Core->Translator->translate("From");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <input type="text" id="rDateUntil" class="datepicker"/>
                    <label for="rDateUntil" class="active"><?php echo $Core->Translator->translate("Until");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <select id="rDateAction">
                        <option value="available"><?php echo $Core->Translator->translate("available");?></option>
                        <option value="unavailable"><?php echo $Core->Translator->translate("unavailable");?></option>
                    </select>
                </div>
                <div class="col m3 s12">
                    <button type="button" class="btn-flat restrictionCreateButton" onclick="createDate()"><?php echo $Core->Translator->translate("Create");?></button>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">access_alarm</i><?php echo $Core->Translator->translate("Max. Delivery Time (Users & Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text"><?php echo $Core->Translator->translate("If the time of delivery can't be over a certain time, you can restrict it to drivers and users. Pykme will calculate the delivery time and only show it to drivers there capable of delivering it and for users in range.");?></p>
            <div id="load_restriction_DeliveryTime">
                <?php $Core->FrontController->partialRender("category-restriction-list-DeliveryTime.php",$data);?>
            </div>
            <div id="rDeliveyTime" class="formRestriction">
                <div class="col m3 s12 input-field">
                    <input type="number" id="rDeliveryTimeDays"/>
                    <label for="rDeliveryTimeDays"><?php echo $Core->Translator->translate("Max. Days");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <input type="number" id="rDeliveryTimeHours"/>
                    <label for="rDeliveryTimeHours"><?php echo $Core->Translator->translate("Max. Hours");?></label>
                </div>
                <div class="col m3 s12 input-field">
                    <input type="number" id="rDeliveryTimeMinutes"/>
                    <label for="rDeliveryTimeMinutes"><?php echo $Core->Translator->translate("Max. Minutes");?></label>
                </div>
                <div class="col m3 s12">
                    <button type="button" class="btn-flat restrictionCreateButton" onclick="createDeliveryTime()"><?php echo $Core->Translator->translate("Create");?></button>
                </div>
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
                foreach($data["restrictions"]["Vehicle"] as $vehicle){
                ?>
                    <li class="valign-wrapper rDistanceList">
                        <div class="col m4 s12">
                            <div class="switch">
                                
                                    <?php echo $Core->Translator->translate("Exclude");?>
                                    <input type="checkbox" checked name="vehicle[]" value="<?php echo $vehicle["id"]?>">
                                    <label><span class="lever"></span>
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
        <div class="collapsible-header"><i class="material-icons">square_foot</i><?php echo $Core->Translator->translate("Min. Space Sizes (Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("Do your cargo require unusual space? Here you can declare the min. space requirements.");?></p>  
            <div id="load_restriction_Size">
                <?php $Core->FrontController->partialRender("category-restriction-list-Size.php",$data);?>
            </div>
            
            <div id="rSize">
                <div class="col m2 s12 input-field">
                    <input id="xSize" type="number" step="any"/>
                    <label>X</label>
                </div>
                <div class="col m2 s12 input-field">
                    <input id="ySize" type="number" step="any"/>
                    <label>Y</label>
                </div>
                <div class="col m2 s12 input-field">
                    <input id="zSize" type="number" step="any"/>
                    <label>Z</label>
                </div>
                <div class="col m2 s12 input-field">
                    <select id="sysSize">               
                        <option value="cm"><?php echo $Core->Translator->translate("cm");?></option>
                        <option value="m"><?php echo $Core->Translator->translate("meters");?></option>
                        <option value="ft"><?php echo $Core->Translator->translate("feet");?></option>
                        <option value="yd"><?php echo $Core->Translator->translate("yards");?></option>
                    </select>
                </div>
                <div class="col m3 s12">
                     <button type="button" class="btn-flat restrictionCreateButton" onclick="createSize()"><?php echo $Core->Translator->translate("Create");?></button>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">thermostat</i><?php echo $Core->Translator->translate("Transportation (Drivers)");?></div>
        <div class="collapsible-body collapsible-body-restictions">
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("Is your cargo sensitive? Here you can declare the type of needs your cargo has.");?></p>
            <div id="load_restriction_Transportation">
                <?php $Core->FrontController->partialRender("category-restriction-list-Transportation.php",$data);?>
            </div>
            
            <div id="rTransportation">
                <div class="col m2 s12 input-field">
                    <input id="rTempFrom" type="number" class="validate" step="any"/>
                    <label><?php echo $Core->Translator->translate("Min. Temp.");?></label>
                </div>
                <div class="col m2 s12 input-field">
                    <input id="rTempUntil" type="number" class="validate" step="any"/>
                    <label><?php echo $Core->Translator->translate("Max. Temp.");?></label>
                </div>
                <div class="col m2 s12 input-field">
                    <select id="tempScala">
                        <option value="°C">°C</option>
                        <option value="°F">°F</option>
                        <option value="K">K</option>
                    </select>
                </div>
                <div class="col m3 s12 input-field">
                    <select id="cargoType">
                        <option value="refrigerated"><?php echo $Core->Translator->translate("Refrigirated");?></option>
                        <option value="heated"><?php echo $Core->Translator->translate("Heated");?></option>
                        <option value="fragil"><?php echo $Core->Translator->translate("Fragil");?></option>
                        <option value="alive"><?php echo $Core->Translator->translate("Alive");?></option>
                        <option value="dangerous"><?php echo $Core->Translator->translate("Dangerous");?></option>
                    </select>
                </div>
                <div class="col m3 s12">
                     <button type="button" class="btn-flat restrictionCreateButton" onclick="createTransportation()"><?php echo $Core->Translator->translate("Create");?></button>
                </div>
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
                foreach($data["restrictions"]["Equipment"] as $equipment){
                ?>
                <li>
                    <input type="checkbox" value="<?php echo $equipment["id"]?>" name="equipment[]" id="equipment_<?php echo $equipment["id"]?>"/>
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

<script>
function createAge(){
    
    var age = $("#Age").val();
    var data = {
        type: "Age",
        Age: age
    };
    
    if(age != ""){
        sendAjax(data,'Age');
        $("#Age").val("");
    }else{
        alert("<?php echo $Core->Translator->translate("Please fill all fields");?>");
    }
    
    
}

function createDistance(){
    var min = $("#minDistanceRestriction").val();
    var max = $("#maxDistanceRestriction").val();
    var sys = $("#distanceSystemRestriction").val();
    
    var data = {
        min:min,
        max:max,
        sys:sys,
        type:"Distance"
    };
    
    if(min != "" || max != "" || sys != ""){
        sendAjax(data,"Distance");
        $("#minDistanceRestriction").val("");
        $("#maxDistanceRestriction").val("");
    }else{
        alert("<?php echo $Core->Translator->translate("Please fill all fields");?>");
    }
}

function createHours(){
    var from    = $("#hoursFrom");
    var until   = $("#hoursUntil");
    var day     = $("#rHoursDay");
    var action  = $("#rHoursAction");
    
    var data = {
        type: "Hours",
        from: from.val(),
        until: until.val(),
        action: action.val(),
        day: day.val()
    };
    
    if(from.val() == "" || until.val() == ""){
        alert("<?php echo $Core->Translator->translate("Please fill all fields");?>");
    }else{
        sendAjax(data,"Hours");
        from.val("");
        until.val("");
    }
}
function createDate(){
    var from    = $("#rDateFrom");
    var until   = $("#rDateUntil");
    var action  = $("#rDateAction");
    
    var data = {
        type: "Date",
        from: from.val(),
        until: until.val(),
        action: action.val()
    }
    
    if(from.val() == "" || until.val() == ""){
        alert("<?php echo $Core->Translator->translate("Please fill all fields");?>");
    }else{
        sendAjax(data,"Date");
        from.val("");
        until.val("");
    }
}

function createDeliveryTime(){
    var days    = $("#rDeliveryTimeDays");
    var hours   = $("#rDeliveryTimeHours");
    var minutes = $("#rDeliveryTimeMinutes");
    
    var data = {
        type:"DeliveryTime",
        days:days.val(),
        hours:hours.val(),
        minutes:minutes.val()
    };
    
    if(days.val() == "" && hours.val() == "" && minutes.val() == ""){
        alert("<?php echo $Core->Translator->translate("Please fill at least one field");?>");
    }else{
        sendAjax(data,"DeliveryTime");
        days.val("");
        hours.val("");
        minutes.val("");
    }
}

function createTransportation(){
    var tFrom   = $("#rTempFrom");
    var tUntil  = $("#rTempUntil");
    var scale   = $("#tempScala");
    var cType   = $("#cargoType");
    
    var data = {
        type: "Transportation",
        from: tFrom.val(),
        until: tUntil.val(),
        scale: scale.val(),
        cargo: cType.val()
    };
    
    sendAjax(data,"Transportation");
    tFrom.val("");
    tUntil.val("");
}

function createSize(){
    var x = $("#xSize");
    var y = $("#ySize");
    var z = $("#zSize");
    var sys = $("#sysSize");
    
    var data = {
        type:"Size",
        x:x.val(),
        y:y.val(),
        z:z.val(),
        sys:sys.val()
    };
    
    if(x.val() == "" || y.val() =="" || z.val()==""){
        alert("<?php echo $Core->Translator->translate("Please fill all fields");?>");
    }else{
        sendAjax(data,"Size");
        x.val("");
        y.val("");
        z.val("");
    }
}
    
function sendAjax(data,type){
    $.ajax({
        url: "/merchant/createRestriction",
        data: data,
        type: 'POST',
        success: function (data) {
            $("#load_restriction_"+type).html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
    });
}

  
</script>