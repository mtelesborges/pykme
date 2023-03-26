<?php
$Core = $data["Core"];
?>
<p class="grey-text"><?php echo $Core->Translator->translate("Decide how and when to sell. You can adjust your price for specific circumstances. Do your business run slow on specific days? Make your price lower to attract customers or make it higher on busy times to maximize your income. <b>WARNING: Please notice that the conditions <span style='text-decoration:underline'>accumulate with each other</span> and will be only availabe for declared currencies!</b>");?></p>

<?php
if($data["inShops"]){
 
?>
 <ul class="collapsible" data-collapsible="expandable">
    <li>
        <div class="collapsible-header">
            <i class="material-icons left">schedule</i><?php echo $Core->Translator->translate("Time");?>
        </div>
        <div class="collapsible-body">
        <div style="display:grid">
            <p class="grey-text"><?php echo $Core->Translator->translate("Make you price higher or lower depending on the time.");?></p>
            <div class="col s12">
                <div id="load_priceConditionTime">
                    <?php $Core->FrontController->partialRender("product-condition-time-list.php",$data);?>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header">
            <i class="material-icons left">map</i><?php echo $Core->Translator->translate("Distance");?>
        </div>
        <div class="collapsible-body">
            <div style="display:grid">
                <p class="grey-text"><?php echo $Core->Translator->translate("Make you price higher or lower depending on the delivery distance.");?></p>
                <div class="col s12">
                    <div id="load_priceConditionDistance">
                        <?php $Core->FrontController->partialRender("product-condition-distance-list.php",$data);?>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header">
            <i class="material-icons left">production_quantity_limits</i><?php echo $Core->Translator->translate("Inventory");?>
        </div>
        <div class="collapsible-body">
            <div style="display:grid">
                <p class="grey-text"><?php echo $Core->Translator->translate("Make you price higher or lower depending on the inventory amount. Please note that this will be only applied for shops that has an inventory declared!");?></p>
                <div class="col s12">
                    <div id="load_priceConditionInventory">
                        <?php $Core->FrontController->partialRender("product-condition-inventory-list.php",$data);?>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="collapsible-header">
            <i class="material-icons left">event_busy</i><?php echo $Core->Translator->translate("Expiry Date");?>
        </div>
        <div class="collapsible-body">
            <div style="display:grid">
                <p class="grey-text"><?php echo $Core->Translator->translate("Make you price higher or lower depending on the expiration date. Please note that this will be only applied for products that has an expiration date declared!");?></p>
                <div class="col s12">
                    <div id="load_priceConditionExpiration">
                        <?php $Core->FrontController->partialRender("product-condition-expiration-list.php",$data);?>
                    </div>
                </div>
            </div>
        </div>
    </li>
 </ul>
<?php }else{ ?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Please select at least one category so we know in wich shops the product will be available.");?>
        </p>
    </li>
</ul>
<?php } ?>

<script>

  function priceConditionCurrency(input){
      var currency_id = input.val();
      $(".priceConditionTimeAmountCurrency").hide();
      $("#priceConditionTimeAmountCurrency_"+currency_id).fadeIn();
  }
  
  function createTimeCondition(){
      var hasPrices = false;
      var prices = $(".priceConditionTimeAmount").map(function(){
          if($(this).val() != ""){
              hasPrices = true;
              var p = {
                  price: $(this).val(),
                  currency_id: $(this).attr("data-currency")
              }
              return p;
          }
      }).get();
      
      if(hasPrices == false){
          alert("<?php echo $Core->Translator->translate("Condition has no amount declared!")?>");
          throw new Error;
      }else{
          var hasVariation = document.getElementById("priceConditionTimeVariations");
          if(hasVariation){
              var variations = $("#priceConditionTimeVariations").val();
          }else{
              var variations = ["default"];
          }
          
          var from = $("#priceConditionFrom").val()
          var until = $("#priceConditionUntil").val();
          
          if(from == "" || until == ""){
              alert("<?php echo $Core->Translator->translate("Time has to be declared!")?>");
              throw new Error;
          }
          
          if(from > until){
             alert("<?php echo $Core->Translator->translate("Time period From can not be after Until")?>");
              throw new Error; 
          }
          
          var weekdays = $("#priceConditionTimeDays").val();
          
          if(weekdays == ""){
              alert("<?php echo $Core->Translator->translate("Please select a weekday");?>");
              throw new Error;
          }
          
          var shops = $("#priceConditionTimeShops").val();
          
          if(shops == ""){
              alert("<?php echo $Core->Translator->translate("Please select at least one shop")?>");
              throw new Error;
          }
          
          var operation = $("#priceConditionTimeOperation").val()
          
          if(operation == ""){
               alert("<?php echo $Core->Translator->translate("Please choose an operation")?>");
              throw new Error;
          }
          
          if($("priceConditionPeriod").prop("checked") == false){
              var periodFrom = $("#priceConditionPeriodFrom").val();
              var periodUntil= $("#priceConditionPeriodUntil").val();
              if(periodFrom == "" || periodUntil == ""){
                  alert("<?php echo $Core->Translator->translate("Please complete the date period")?>");
                  throw new Error;
              }
              
              if(parseDate(periodFrom,"dd-mm-yyyy") > parseDate(periodUntil,"dd-mm-yyyy")){
                  alert(periodFrom);
                   alert(periodUntil);
                  alert("<?php echo $Core->Translator->translate("Date period 'From' can't be after 'Until'")?>");
                  throw new Error; 
              }
          }
          
          var data = {
              shops:shops,
              availableShops: getShops(),
              days:weekdays,
              from:from,
              until:until,
              dateFrom:periodFrom,
              dateUntil:periodUntil,
              variations:variations,
              prices:prices,
              operation:operation,
              condition: "Time"
              
          };
          
          $.ajax({
              url: "/merchant/createPriceCondition",
              data:data,
              type: 'POST',
              success:function(data){
                $("#load_priceConditionTime").html(data);
                $('.collapsible').collapsible();
                $('select').material_select();
              },
              error: function(e){
                console.log(e);
                alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
              }
          });
          
      }
  }
function createDistanceCondition(){
    var shops = $("#priceConditionDistanceShops").val();
    if(shops == ""){
        alert("<?php echo $Core->Translator->translate("Please choose a shop")?>");
        throw new Error;
    }
    
    var from    = $("#priceConditionDistanceFrom").val();
    var until   = $("#priceConditionDistanceUntil").val();
    
    if(from == "" || until == ""){
        alert("<?php echo $Core->Translator->translate("Please declare distance");?>");
        throw new Error;
    }
  
    if(Number(from) > Number(until)){
        alert("<?php echo $Core->Translator->translate("Distance From can't be after Until");?>");
        throw new Error;
    }
    
    var distanceSystem = $("#priceConditionDistanceSystem").val();
    
    var isTimeSensitive = $("#timeSensitiveDistanceCondition").prop("checked");
    
    var isWeekly = true; 
    if(isTimeSensitive){
        if($("#distanceConditionApplyWeekly").prop("checked")){
          isWeekly = true;   
        }else{
          isWeekly = false;   
        }

        if(isWeekly == false){
            var dateFrom    = $("#distanceConditionPeriodFrom").val();
            var dateUntil   = $("#distanceConditionPeriodUntil").val();
            if(dateFrom == "" || dateUntil == ""){
                alert("<?php echo $Core->Translator->translate("Please declare date From an Until");?>");
                throw new Error;
            }
            alert(dateFrom);
            alert(new Date(dateUntil));
            if(parseDate(dateFrom,"dd-mm-yyyy") > parseDate(dateUntil,"dd-mm-yyyy")){
                alert("<?php echo $Core->Translator->translate("Date From can't be further then Until")?>");
                throw new Error;
            }
        }
        
        var weekdays = $("#distanceConditionTimeDays").val();
        var hourFrom = $("#distanceConditionTimeFrom").val();
        var hourUntil= $("#distanceConditionTimeUntil").val();
        
        if(weekdays == ""){
            alert("<?php echo $Core->Translator->translate("Please select weekdays");?>");
            throw new Error;
        }
        
        if(hourFrom == "" || hourUntil ==""){
            alert("<?php echo $Core->Translator->translate("Hours can not be empty")?>");
            throw new Error;
        }
        
        if(hourFrom > hourUntil){
            alert("<?php echo $Core->Translator->translate("Hours from can not be further then until");?>");
            throw new Error;
        }
    }
    
    var operation = $("#distanceConditionOperation").val();
    
    if(operation == ""){
        alert("<?php echo $Core->Translator->translate("Please choose an operation");?>");
        throw new Error;
    }
    var hasPrice = false;
    var prices = $(".distanceConditionPrice").map(function(){
        if($(this).val() != ""){
            hasPrice = true;
            
            var p = {
                price: $(this).val(),
                currency_id: $(this).attr("data-currency")
            }
            return p;
        }
    }).get();
    
    if(hasPrice == false){
        alert("<?php echo $Core->Translator->translate("Please declare amount in at least one currency")?>");
        throw new Error;
    }
    if(document.getElementById("priceConditionDistanceVariations")){
        var variations = $("#priceConditionDistanceVariations").val();
        if(variations == ""){
            alert("<?php echo $Core->Translator->translate("Please select product variation");?>");
            throw new Error;
        }
    }else{
        var variations = ["default"];
    }
   
    var data = {
        shops:shops,
        variations:variations,
        distanceFrom:from,
        distanceUntil:until,
        distanceSystem:distanceSystem,
        isTimeSensitive:isTimeSensitive,
        weekdays:weekdays,
        hourFrom:hourFrom,
        hourUntil:hourUntil,
        isWeekly:isWeekly,
        dateFrom:dateFrom,
        dateUntil:dateUntil,
        operation:operation,
        prices:prices,
        availableShops: getShops(),
        condition: "Distance"
    }
    
    $.ajax({
          url: "/merchant/createPriceCondition",
          data:data,
          type: 'POST',
          success:function(data){
            $("#load_priceConditionDistance").html(data);
            $('.collapsible').collapsible();
           $('select').material_select();
          },
          error: function(e){
            console.log(e);
            alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
          }
    });
}

function createInventoryCondition(){
    var shops = $("#priceConditionInventoryShops").val();

    if(shops == ""){
        alert("<?php echo $Core->Translator->translate("Please choose at least one shop");?>");
        throw new Error;
    }
    
    if(document.getElementById("priceConditionInventoryVariations")){
        var variations = $("#priceConditionInventoryVariations").val();
        if(variations == ""){
            alert("<?php echo $Core->Translator->translate("Please select at least one product variation")?>");
            throw new Error;
        }
    }else{
        var variations = ["default"];
    }
    
    var action = $("#priceConditionInventoryActionType").val();
    
    var inventoryAmount = $("#priceConditionInventoryAmount").val();
    
    if(inventoryAmount == "" || inventoryAmount == 0){
        alert("<?php echo $Core->Translator->translate("Please declare inventory amount");?>");
        throw new Error;
    }
    
    var operation = $("#priceConditionInventoryOperation").val();
    
    if(operation == ""){
        alert("<?php echo $Core->Translator->translate("Please select operation");?>");
        throw new Error;
    }
    
    var hasPrices = false;
    var prices = $(".inventoryConditionPrice").map(function(){
        if($(this).val() != ""){
            hasPrices = true;
            
            var p = {
                price: $(this).val(),
                currency_id: $(this).attr("data-currency")
            }
            
            return p;
        }
    }).get();
    
    if(hasPrices == false){
        alert("<?php echo $Core->Translator->translate("Please declare amount in at least one currency");?>");
        throw new Error;
    }
    
    if($("#priceConditionInventoryIsTimeSensitive").prop("checked")){
        var isTimeSensitive = true;
    }else{
        var isTimeSensitive = false;
    }
    
    var isWeekly = true;
    if(isTimeSensitive){
        if($("#inventoryConditionApplyWeekly").prop("checked")){
            isWeekly = true;
        }else{
            isWeekly = false;
        }
        
        if(isWeekly == false){
            var dateFrom = $("#inventoryConditionPeriodFrom").val();
            var dateUntil= $("#inventoryConditionPeriodUntil").val();
            
            if(parseDate(dateFrom,"dd-mm-yyyy") > parseDate(dateUntil,"dd-mm-yyyy")){
                alert("<?php echo $Core->Translator->translate("Date From can not be after Date Until");?>");
                throw new Error;
            }
            
            if(dateFrom == "" || dateUntil ==""){
               alert("<?php echo $Core->Translator->translate("Please declare both dates From and Until");?>"); 
               throw new Error;
            }
            
        }
        var timeFrom = $("#inventoryConditionTimeFrom").val();
        var timeUntil= $("#inventoryConditionTimeUntil").val();
        
        if(timeFrom == "" || timeUntil == ""){
            alert("<?php echo $Core->Translator->translate("Please declare time From and Until")?>");
            throw new Error;
        }
        
        if(timeFrom > timeUntil){
            alert("<?php echo $Core->Translator->translate("Time From can't be after Until")?>");
            throw new Error;
        }

        var weekdays = $("#inventoryConditionTimeDays").val();
        if(weekdays == ""){
            alert("<?php echo $Core->Translator->translate("Please select weekdays")?>");
            throw new Error;
        }
    }
    
    var data = {
        availableShops: getShops(),
        condition: "Inventory",
        shops:shops,
        variations:variations,
        action:action,
        inventoryAmount:inventoryAmount,
        operation:operation,
        prices:prices,
        isTimeSensitive:isTimeSensitive,
        isWeekly:isWeekly,
        dateFrom:dateFrom,
        dateUntil:dateUntil,
        timeFrom:timeFrom,
        timeUntil:timeUntil,
        weekdays:weekdays
    }
    
     $.ajax({
          url: "/merchant/createPriceCondition",
          data:data,
          type: 'POST',
          success:function(data){
            $("#load_priceConditionInventory").html(data);
            $('.collapsible').collapsible();
            $('select').material_select();
          },
          error: function(e){
            console.log(e);
            alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
          }
    });
    
 
 
}

function createExpirationCondition(){
    var shops = $("#priceConditionExpirationShops").val();
    
    if(shops == ""){
        alert("<?php echo $Core->Translator->translate("Please select at least one shop");?>");
        throw new Error;
    }
    
    if(document.getElementById("priceConditionExpirationVariations")){
        var variations = $("#priceConditionExpirationVariations").val();
        if(variations == ""){
            alert("<?php echo $Core->Translator->translate("Please select at least one product variation")?>");
            throw new Error;
        }
    }else{
        var variations = ["default"];
    }
    
    var days = $("#priceConditionExpirationAmount").val();
    
    if(days == ""){
        alert("<?php echo $Core->Translator->translate("Please declare days before experation");?>");
        throw new Error;
    }
    
    var hasPrices = false;
    var prices = $(".expirationConditionPrice").map(function(){
        if($(this).val() != ""){
            hasPrices = true;
            
            var p = {
                price: $(this).val(),
                currency_id: $(this).attr("data-currency")
            }
            
            return p;
        }
    }).get();
    
    if(hasPrices == false){
        alert("<?php echo $Core->Translator->translate("Please declare amount in at least one currency");?>");
        throw new Error;
    }
    
        var data = {
        availableShops: getShops(),
        condition: "Expiration",
        shops:shops,
        variations:variations,
        prices:prices,
        days:days
    }
    
     $.ajax({
          url: "/merchant/createPriceCondition",
          data:data,
          type: 'POST',
          success:function(data){
            $("#load_priceConditionExpiration").html(data);
            $('.collapsible').collapsible();
            $('select').material_select();
          },
          error: function(e){
            console.log(e);
            alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
          }
    });
    
}

$('select').material_select();
$('.collapsible').collapsible();
</script>