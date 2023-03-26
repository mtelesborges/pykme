<?php
$Core = $data["Core"];
?>
<div class="row">
<form action="/merchant/editshop" method="post" id="editDeliveryO">
	<input name="edit" value="Delivery_O" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<div id="i_deliveryOptions" class="section scrollspy">
                <h5><?php echo $Core->Translator->translate("Delivery Options");?></h5>
				<p>
                                    
                                    <input type="checkbox" name="noDelivery" id="noDelivery" onClick="checkDelivery()" <?php if($data["shop"]["noDelivery"] == 1){ echo "checked";}?>/>
                                    <label for="noDelivery"><span><?php echo $Core->Translator->translate("No Delivery only Take-Away");?></span>
                                    </label>
				</p>
				<p  class="hasDelivery">
					
                                        <input type="checkbox" name="pykmeDelivery" id="pykmeDelivery" <?php if($data["shop"]["pykmeDelivery"] == 1){ echo "checked";}?>/>
                                        <label for="pykmeDelivery"><span><?php echo $Core->Translator->translate("Use 3rd party Drivers (19% comission to Drivers)");?></span>
                                        </label>
				</p>
				<p  class="hasDelivery">
					
                                        <input type="checkbox" name="selfDelivery" id="selfDelivery" <?php if($data["shop"]["selfDelivery"] == 1){ echo "checked";}?>/>
                                        <label for="selfDelivery"><span><?php echo $Core->Translator->translate("Inhouse Delivery (Own Drivers)");?></span>
                                        </label>
				</p>
				
				<div id="inhouseDeliveryOptions" <?php if($data["shop"]["selfDelivery"] == 0){ ?>style="display:none"<?php }?>>
					<div class="col s12">
						<h8><?php echo $Core->Translator->translate("Minimum Order by Distance");?></h8>
						<div id="minOrder">
							<?php if($data["minOrder"]){
								foreach($data["minOrder"] as $min){
							?>
							<div id="firstMinimumOrder-<?php echo $min["id"];?>" class="col s12">
								<div class="input-field col s1">
									<button class="closeBtn" type="button" onClick="removeDay('firstMinimumOrder-<?php echo $min["id"];?>')">x</button>
								</div>
								<!-- @TODO fix km/miles and currency -->
								<div class="input-field col s6">
									<input type="text" name="orderDistance[]" value="<?php echo $min["distance"]?>"/>
									<label class="active"><?php echo $Core->Translator->translate("until Distance (not Radius)");?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>
								</div>
								<div class="input-field col s5">
									<input type="text" name="minPrice[]"  value="<?php echo $min["min_order"]?>"/>
									<label class="active"><?php echo $Core->Translator->translate("Min. Order Amount");?> (<?php echo $data["currency"]["code"]?>)</label>
								</div>
							</div>
							<?php }}else{?>
							<div id="firstMinimumOrder" class="col s12">
								<div class="input-field col s1">
									<button class="closeBtn" type="button" onClick="removeDay('firstMinimumOrder')">x</button>
								</div>
								<!-- @TODO fix km/miles and currency -->
								<div class="input-field col s6">
									<input type="text" name="orderDistance[]"/>
									<label><?php echo $Core->Translator->translate("until Distance (not Radius)");?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>
								</div>
								<div class="input-field col s5">
									<input type="text" name="minPrice[]"/>
									<label><?php echo $Core->Translator->translate("Min. Order Amount");?> (<?php echo $data["currency"]["code"]?>)</label>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="input-field col s12">
                            <button class="btn-flat" type="button" onClick="addMinimum()"><?php echo $Core->Translator->translate("ADD MINIMUM");?></button>
                        </div>
					</div>
					<div class="col s12">
						<h8><?php echo $Core->Translator->translate("Delivery Rates by Distance");?></h8>
						<div id="inhouseDeliveryO">
							<?php 
							if($data["rates"]){ 
							foreach($data["rates"] as $rates){
							?>
								<div id="firstInhouseDeliveryOptions-<?php echo $rates["id"]?>" class="col s12">
									<div class="input-field col s1">
										<button class="closeBtn" type="button" onClick="removeDay('firstInhouseDeliveryOptions-<?php echo $rates["id"]?>')">x</button>
									</div>
									<div class="input-field col s4">
										<input type="text" name="distance[]" value="<?php echo $rates["distance"]?>"/>
										<label class="active"><?php echo $Core->Translator->translate("until Distance (not Radius)");?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>
									</div>
									<div class="input-field col s4">
										<input type="text" name="distancePrice[]" value="<?php echo $rates["price"]?>"/>
										<label class="active"><?php echo $Core->Translator->translate("Price");?> (<?php echo $data["currency"]["code"]?>)</label>
									</div>
									<div class="input-field col s3">
										<input type="text" name="distanceTime[]" value="<?php echo $rates["time"]?>"/>
										<label class="active"><?php echo $Core->Translator->translate("Minutes to Delivery");?></label>
									</div>
								</div>
							<?php }}else{ ?>
							<div id="firstInhouseDeliveryOptions" class="col s12">
								<div class="input-field col s1">
									<button class="closeBtn" type="button" onClick="removeDay('firstInhouseDeliveryOptions')">x</button>
								</div>
								<div class="input-field col s4">
									<input type="text" name="distance[]"/>
									<label><?php echo $Core->Translator->translate("until Distance (not Radius)");?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="distancePrice[]"/>
									<label><?php echo $Core->Translator->translate("Price");?> (<?php echo $data["currency"]["code"]?>)</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="distanceTime[]"/>
									<label><?php echo $Core->Translator->translate("Minutes to Delivery");?></label>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="input-field col s12">
							<button class="btn-flat" type="button" onClick="addDeliveryRate()"><?php echo $Core->Translator->translate("ADD RATE");?></button>
						</div>
					</div>
				</div>
			
			</div>
	<div class="col s12">
	<button type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Delivery Options");?></button>
	</div>
</form>
</div>
<script>
checkDelivery();
function addDeliveryRate(){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'" class="col s12">'+
						'<div class="input-field col s1">'+
							'<button class="closeBtn" type="button" onClick="removeDay('+random+')">x</button>'+
						'</div>'+
						'<div class="input-field col s4">'+
							'<input type="text" name="distance[]">'+
							'<label><?php echo $Core->Translator->translate("until Distance (not Radius)")?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>'+
						'</div>'+
						'<div class="input-field col s4">'+
							'<input type="text" name="distancePrice[]">'+
							'<label><?php echo $Core->Translator->translate("Price")?></label>'+
						'</div>'+
						'<div class="input-field col s3">'+
							'<input type="text" name="distanceTime[]">'+
							'<label><?php echo $Core->Translator->translate("Minutes to Delivery")?></label>'+
						'</div>'+
					'</div>';
	var el = document.getElementById("inhouseDeliveryO");
	el.insertAdjacentHTML("beforeend",input);
}
function addMinimum(){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'" class="col s12">'+
						'<div class="input-field col s1">'+
							'<button class="closeBtn" type="button" onClick="removeDay('+random+')">x</button>'+
						'</div>'+
						'<div class="input-field col s6">'+
							'<input type="text" name="orderDistance[]">'+
							'<label><?php echo $Core->Translator->translate("until Distance (not Radius)")?>(<?php echo $data["shop"]["distanceSystem"]?>)</label>'+
						'</div>'+
						'<div class="input-field col s5">'+
							'<input type="text" name="minPrice[]">'+
							'<label><?php echo $Core->Translator->translate("Min. Order Amount")?></label>'+
						'</div>'+
					'</div>';
	var el = document.getElementById("minOrder");
	el.insertAdjacentHTML("beforeend",input);
}
$("#selfDelivery").click(function(){
	var inhouseDelivery = document.getElementById("selfDelivery");
	if(inhouseDelivery.checked == true){
	   $("#inhouseDeliveryOptions").fadeIn();
	 }else{
	  	$("#inhouseDeliveryOptions").fadeOut();
	 }
});

function checkDelivery(){
	var delivery = document.getElementById("noDelivery");
	var inhouseDelivery = document.getElementById("selfDelivery");
	if(delivery.checked == true){
	    $(".hasDelivery").fadeOut();
		$("#inhouseDeliveryOptions").fadeOut();
		$("#hasNoDelivery").fadeIn();
	}else{
		$("#hasNoDelivery").fadeOut();
		$(".hasDelivery").fadeIn();
		if(inhouseDelivery.checked == true){
		  $("#inhouseDeliveryOptions").fadeIn(); 
		}
	}
}

function removeDay(id){
	$("#"+id).remove();
}
var validator = $("#editDeliveryO").validate({
        rules: {
			"orderDistance[]":{
				number:true,
				required:true,
			},
			"minPrice[]":{
				number:true,
				required:true,
			},
			"distance[]":{
				number:true,
				required:true,
			},
			"distancePrice[]":{
				number:true,
				required:true,
			},
			"distanceTime[]":{
				number:true,
				required:true,
			}
        },
        //For custom messages
        messages: {
			"orderDistance[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers");?>",
				required:"<?php echo $Core->Translator->translate("This field is required");?>"
			
			},
			"minPrice[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers");?>",
				required:"<?php echo $Core->Translator->translate("This field is required");?>"
			},
			"distance[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers");?>",
				required:"<?php echo $Core->Translator->translate("This field is required");?>"
			},
			"distancePrice[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers");?>",
				required:"<?php echo $Core->Translator->translate("This field is required");?>"
			},
			"distanceTime[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers");?>",
				required:"<?php echo $Core->Translator->translate("This field is required");?>"
			}
        },
	    errorClass: "invalid form-error",
        errorElement : 'div',
        errorPlacement: function(error, element) {
          var placement = $(element).data('error');
          if (placement) {
            $(placement).append(error)
          } else {
            error.insertAfter(element);
          }
        }
     });
</script>