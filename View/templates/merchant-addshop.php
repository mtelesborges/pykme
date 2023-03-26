<?php
$Core = $view["Core"];
$weekday= $view["Data"]["weekday"];
?>
<link rel="stylesheet" href="/Vendors/upload-and-crop-image/croppie.css"/>
<div class="container">
	
	
	<form action="/merchant/addShop" method="post" id="addShop">
	<div class="row">
	<div class="col m9 s12">
		<h3><?php echo $Core->Translator->translate("Add New Shop"); ?></h3>
		<div id="i_membership" class="section scrollspy">
            <h5 class="h5divider"><?php echo $Core->Translator->translate("Membership Information");?></h5>
                <p>
                    <input name="membership" type="radio" id="m_normal" value="1" checked="checked" onclick="checkMembership()"/>
                    <label for="m_normal"><?php echo $Core->Translator->translate("Normal (100% Free)");?></label>
                </p>
                <p>
                    <input name="membership" type="radio" id="m_professional" value="2" onclick="checkMembership()"/>
                    <label for="m_professional"><?php echo $Core->Translator->translate("Professional (&euro; 9.90/m on yearly payment)");?></label>
                </p>
		</div>
		<div id="i_sysInfo"  class="scrollspy section">
			<h5 class="h5divider"><?php echo $Core->Translator->translate("System Information");?></h5>
			<div class="col s12 input-field">
				<select name="distanceSystem">
					<option value="km" selected><?php echo $Core->Translator->translate("Kilometer");?></option>
					<option value="mi"><?php echo $Core->Translator->translate("Miles");?></option>
				</select>
				<label><?php echo $Core->Translator->translate("Distance in");?></label>
			</div>
			<div class="col s12 input-field">
				<select name="currency">
					<?php
					foreach($view["Data"]["currency"] as $currency){
					?>
					<option value="<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"]?>) <?php echo $currency["symbol"]?></option>
					<?php
					}
					?>
				</select>
				<label><?php echo $Core->Translator->translate("Currency");?></label>
			</div>
		</div>
		<div id="i_basicInfo" class="scrollspy section">
            <h5 class="h5divider"><?php echo $Core->Translator->translate("Basic Information");?></h5>
            
                <div class="input-field col s12">
                    <select name="category" id="shopCategory">
                      <option value="none" disabled selected><?php echo $Core->Translator->translate("Choose Shop Category");?></option>
                        <?php
                        foreach ($view["Data"]["cat"] as $category){
                        ?>
                          <option value="<?php echo $category["id"]?>"><?php echo $Core->Translator->translate($category["name"]); ?></a></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label><?php echo $Core->Translator->translate("Shop Category");?></label>
                </div>
            <?php
                foreach($view["Data"]["cat"] as $category){
                ?>
                <div class="input-field col s12 subcategories" id="category-<?php echo $category["id"]?>" style="display:none">
                
                    <select name="subCategory[]" multiple id="subcategory-<?php echo $category['id']?>">
                      <option value="none" disabled selected><?php echo $Core->Translator->translate("Choose Subcategories");?></option>
                        <?php
                        foreach ($view["Data"]["subCat"] as $subCat){
                            if($subCat["category_id"] == $category["id"]){
                        ?>
                            <option value="<?php echo $subCat["id"]?>"><?php echo $Core->Translator->translate($subCat["name"]); ?></a></option>
                        <?php
                        }};
                        ?>
                    </select>
                    <label><?php echo $Core->Translator->translate("Shop Subcategory");?></label>
               
                </div>
                 <?php
                };
                ?>
		<div class="input-field col s12">
                    <input name="shopName"  type="text"/>
                    <label for="shopName"><?php echo $Core->Translator->translate("Shop Name"); ?></label>
		</div>
		
		<div class="input-field col s12">
                    <input name="shopDescription"  type="text"/>
                    <label for="shopDescription"><?php echo $Core->Translator->translate("Shop Description"); ?></label>
		</div>
            
                <div class="col s12">
                    <div class="file-field input-field">
                      <div class="btn">
                            <span><?php echo $Core->Translator->translate("Logo"); ?></span>
                            <input type="file" name="upload_image" id="upload_image" />
                      </div>
                      <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="path_image" name="path_image">
                           <div id="uploaded_image"></div>
                      </div>
                    </div>
		</div>
		<div class="input-field col s12">
                        <input name="shopAddress"  type="text" id="addressInput"  placeholder="<?php echo $Core->Translator->translate("Enter your Address here"); ?>"/>
			<label for="shopAddress"><?php echo $Core->Translator->translate("Address"); ?></label>
		</div>
                <div id="address" class="col s12" style="display: none">
                    <div class="col s12 m6">
                            <div id="map"></div>
                    </div>
                    <div class="col s12 m6">
                            <div class="input-field">
                                    <input type="text" id="street_number" name="street_number" readonly="readonly"  placeholder=""/>
                                    <label for="street_number"><?php echo $Core->Translator->translate("Street Number"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="route" name="route" readonly="readonly" placeholder=""/>
                                    <label for="route"><?php echo $Core->Translator->translate("Street Name"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="locality" name="neighbourhood" readonly="readonly"  placeholder=""/>
                                    <label for="locality"><?php echo $Core->Translator->translate("Neighbourhood"); ?></label>
                            </div>
							<div class="input-field">
                                    <input type="text" id="administrative_area_level_2" readonly="readonly" name="city"   placeholder=""/>
                                    <label for="administrative_area_level_2"><?php echo $Core->Translator->translate("City"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="administrative_area_level_1"  readonly="readonly" name="state" placeholder=""/>
                                    <label for="administrative_area_level_1"><?php echo $Core->Translator->translate("State"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input  type="text" id="postal_code" name="postal_code" readonly="readonly" placeholder=""/>
                                    <label for="postal_code"><?php echo $Core->Translator->translate("Zip code"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="country" readonly="readonly" name="country" placeholder=""/>
                                    <label for="country"><?php echo $Core->Translator->translate("Country"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="lat" name="lat" readonly="readonly" placeholder=""/>
                                    <label for="lat"><?php echo $Core->Translator->translate("Latitude"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="lng" name="lng" readonly="readonly" placeholder=""/>
                                    <label for="street_number"><?php echo $Core->Translator->translate("Longitude"); ?></label>
                            </div>
							<div class="input-field">
                                    <input type="text" id="timezone" name="timezone" readonly="readonly" placeholder=""/>
                                    <label><?php echo $Core->Translator->translate("Timezone"); ?></label>
                            </div>
							
							<input type="hidden" id="addressObject" name="addressObject"/>
                    </div>
                </div>
		</div>
		<div id="i_opening" class="section scrollspy">
                <h5 class="h5divider"><?php echo $Core->Translator->translate("Opening Hours");?></h5>
				<ul class="collapsible popout" data-collapsible="expandable">
				<?php
				foreach($weekday as $day){
				?>
				<li>
					<!----------->
					<div class="collapsible-header">
						<h6><?php echo $Core->Translator->translate($day);?></h6>
					</div>
					<div class="collapsible-body">
						<span class="hoursBody">
							<div id="<?php echo $day;?>">
								<div id="first<?php echo $day;?>">
									<div class="col s1 valign-wrapper"><button onclick="removeHour('first<?php echo $day;?>')" class="closeBtn" type="button">x</button></div>
									<div class="input-field col s5">
										<p><?php echo $Core->Translator->translate("From")?></p>
										<input type="time" name="<?php echo $day;?>_from[]" class="timeInput"/>
									</div>
									<div class="input-field col s6">
										<p><?php echo $Core->Translator->translate("To")?></p>
										<input type="time" name="<?php echo $day;?>_to[]" class="timeInput"/>
									</div>
								</div>
							</div>
							<div class="input-field col s12">
								<button class="btn" onClick="addHours('<?php echo $day;?>')" type="button"><?php echo $Core->Translator->translate("More Hours");?></button>
							</div>
						</span>
					</div>
					<!----------->
				</li>
				<?php
				}
				?>
			</div>
			
			<div id="i_holidays" class="section scrollspy">
				<h5 class="h5divider"><?php echo $Core->Translator->translate("Holidays & Closed Days");?></h5>
				<div id="holidays">
					<div id="firstHoliday">
						<div class="col s1">
							<button class="closeBtn" type="button" onClick="removeDay('firstHoliday')">x</button>
						</div>
						<div class="input-field col s5">
							<input type="text" class="datepicker" name="holiday_from[]">
							<label><?php echo $Core->Translator->translate("From")?></label>
						</div>
						<div class="input-field col s6">
							<input type="text" class="datepicker" name="holiday_to[]">
							<label><?php echo $Core->Translator->translate("To")?></label>
						</div>
					</div>
				</div>
				<button class="btn" type="button" onClick="addDays('holidays')"><?php echo $Core->Translator->translate("ADD DAYS")?></button>
			</div>
			
			<div id="i_deliveryOptions" class="section scrollspy">
                <h5 class="h5divider"><?php echo $Core->Translator->translate("Delivery Options");?></h5>
				<p>
					<input type="checkbox" name="noDelivery" id="noDelivery" onClick="checkDelivery()"/>
					<label for="noDelivery"><?php echo $Core->Translator->translate("No Delivery only Take-Away");?></label>
				</p>
				<p  class="hasDelivery">
					<input type="checkbox" name="pykmeDelivery" id="pykmeDelivery"/>
					<label for="pykmeDelivery"><?php echo $Core->Translator->translate("Use 3rd party Drivers (19% comission to Drivers)");?></label>
				</p>
				<p  class="hasDelivery">
					<input type="checkbox" name="selfDelivery" id="selfDelivery"/>
					<label for="selfDelivery"><?php echo $Core->Translator->translate("Inhouse Delivery (Own Drivers)");?></label>
				</p>
				<div id="inhouseDeliveryOptions">
					<div class="col s12">
						<h8><?php echo $Core->Translator->translate("Minimum Order by Distance");?></h8>
						<div id="minOrder">
							<div id="firstMinimumOrder" class="col s12">
								<div class="input-field col s1">
									<button class="closeBtn" type="button" onClick="removeDay('firstMinimumOrder')">x</button>
								</div>
								<!-- @TODO fix km/miles and currency -->
								<div class="input-field col s6">
									<input type="text" name="orderDistance[]"/>
									<label><?php echo $Core->Translator->translate("until Distance (not Radius)");?></label>
								</div>
								<div class="input-field col s5">
									<input type="text" name="minPrice[]"/>
									<label><?php echo $Core->Translator->translate("Min. Order Amount");?></label>
								</div>
							</div>
						</div>
						<div class="input-field col s12">
                            <button class="btn" type="button" onClick="addMinimum()"><?php echo $Core->Translator->translate("ADD MINIMUM");?></button>
                        </div>
					</div>
					<div class="col s12">
						<h8><?php echo $Core->Translator->translate("Delivery Rates by Distance");?></h8>
						<div id="inhouseDeliveryO">
							<div id="firstInhouseDeliveryOptions" class="col s12">
								<div class="input-field col s1">
									<button class="closeBtn" type="button" onClick="removeDay('firstInhouseDeliveryOptions')">x</button>
								</div>
								<div class="input-field col s4">
									<input type="text" name="distance[]"/>
									<label><?php echo $Core->Translator->translate("until Distance (not Radius)");?></label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="distancePrice[]"/>
									<label><?php echo $Core->Translator->translate("Price");?></label>
								</div>
								<div class="input-field col s3">
									<input type="number" name="distanceTime[]"/>
									<label><?php echo $Core->Translator->translate("Minutes to Delivery");?></label>
								</div>
							</div>
						</div>
						<div class="input-field col s12">
							<button class="btn" type="button" onClick="addDeliveryRate()"><?php echo $Core->Translator->translate("ADD RATE");?></button>
						</div>
					</div>
				</div>
			</div>
			
			<div id="i_delivery" class="section scrollspy">
                <h5 class="h5divider"><?php echo $Core->Translator->translate("Delivery Hours");?></h5>
				<p id="hasNoDelivery" style="display: none"><?php echo $Core->Translator->translate("No Delivery available, only Take-Away");?></p>
				<div class="hasDelivery">
					<p>
						<input type="checkbox" name="sameDeliveryHours" id="sameDeliveryHours"/>
						<label for="sameDeliveryHours"><?php echo $Core->Translator->translate("Delivery Hours same as Opening Hours");?></label>
					</p>
					<div id="deliveryHours">
						<ul class="collapsible popout" data-collapsible="expandable">
							<?php
							foreach($weekday as $day){
							?>
							<li>
								<div class="collapsible-header">
									<h6><?php echo $Core->Translator->translate($day);?></h6>
								</div>
								<div class="collapsible-body">
									<span class="hoursBody">
										<div id="<?php echo $day;?>Delivery">
											<div id="first<?php echo $day;?>Delivery">
												<div class="input-field col s1"><button onclick="removeHour('first<?php echo $day;?>Delivery')" class="closeBtn" type="button">x</button></div>
												<div class="input-field col s5">
													<p><?php echo $Core->Translator->translate("From")?></p>
													<input type="time" name="<?php echo $day;?>Delivery_from[]"  class="timeInput"/>
												</div>
												<div class="input-field col s6">
													<p><?php echo $Core->Translator->translate("To")?></p>
													<input type="time" name="<?php echo $day;?>Delivery_to[]"  class="timeInput"/>

												</div>
											</div>
										</div>
										<div class="input-field col s12">
											<button class="btn" onClick="addHours('<?php echo $day;?>Delivery')" type="button"><?php echo $Core->Translator->translate("More Hours");?></button>
										</div>
									</span>
								</div>
							</li>
							<?php
							}
							?>	
						</ul>
					</div>
				</div>
			</div>
			<div id="i_notifications" class="section scrollspy">
				<h5 class="h5divider"><?php echo $Core->Translator->translate("Order Notifications");?></h5>
				<div class="input-field col s12">
					<input name="orderByEmail" id="shopEmail" type="email"/>
					<label><?php echo $Core->Translator->translate("Order to Email");?></label>
				</div>
				<div class="input-field col s12">
					<label class="active"><?php echo $Core->Translator->translate("Order to SMS");?></label>
					<input name="orderBySMS" type="text" disabled class="inputProfessional phoneNumber" value="" id="orderBySMS"/>
					<span><?php echo $Core->Translator->translate("$0.20 per SMS");?></span>
					<span class="onlyProfessional"><b><?php echo $Core->Translator->translate("(Only for Professional Membership</b>)");?></b> <span class="switchToProf" onClick="switchToProfessional()"><?php echo $Core->Translator->translate("Switch to Professional")?></span></span>
				</div>
			</div>
			<div id="i_payments"  class="section scrollspy">
				<h5 class="h5divider"><?php echo $Core->Translator->translate("Payments");?></h5>
					<div class="col s12">
						<input name="cash_payment" id="cash_payment" type="checkbox" checked class="CheckPaymentMethod"/>
						<label for="cash_payment"><?php echo $Core->Translator->translate("Cash on Delivery/Take-Away");?></label>
					</div>
					<div class="col s12">
						<input name="credit_online_payment" id="credit_online_payment" type="checkbox" disabled class="inputProfessional CheckPaymentMethod" onClick="showBankDetails()"/>
						<label for="credit_online_payment"><?php echo $Core->Translator->translate("Credit Card Online");?></label>
						<span class="onlyProfessional"><b><?php echo $Core->Translator->translate("(Only for Professional Membership)");?></b> <span class="switchToProf" onClick="switchToProfessional()"><?php echo $Core->Translator->translate("Switch to Professional")?></span></span>
						<div class="col s12" id="bankDetails">
							<div class="input-field col s12">
								<input name="benificiary" type="text" id="benificiary"/>
								<label><?php echo $Core->Translator->translate("Beneficiary Name (Person or Business)");?></label>
							</div>
							<div class="input-field col s12">
								<input name="IBAN" type="text" id="iban"/>
								<label><?php echo $Core->Translator->translate("IBAN");?></label>
							</div>
							<div class="input-field col s12">
								<input name="BIC/SWIFT" type="text" id="bic"/>
								<label><?php echo $Core->Translator->translate("BIC/SWIFT Code");?></label>
							</div>
							<div class="input-field col s12">
								<input name="Clearing" type="text"/>
								<label><?php echo $Core->Translator->translate("Clearing Code or BC");?></label>
							</div>
							<div class="input-field col s12">
								<input name="Bank" type="text"/>
								<label><?php echo $Core->Translator->translate("Bank Name");?></label>
							</div>
							<div class="input-field col s12">
								<textarea name="BankAddress"  class="materialize-textarea"></textarea>
								<label><?php echo $Core->Translator->translate("Bank Address");?></label>
							</div>
						</div>
					</div>
					<div class="col s12">
						<input name="credit_delivery_payment" id="credit_delivery_payment" type="checkbox" class="CheckPaymentMethod"/>
						<label for="credit_delivery_payment"><?php echo $Core->Translator->translate("Credit Card on Delivery/Take-Away (With own POS Terminal)");?></label>
					</div>
			
			</div>
			<div class="col s12">
				<p><?php echo $Core->Translator->translate("By clicking “Register Shop” I represent that I have read, understand, and agree to the <a href='/merchant/agreement' target='_blank'>pykme Agreement</a> and <a href='/merchant/privacypolicy' target='_blank'>Privacy Policy</a>.");?></p>
			</div>
			<div class="col s12">
				<h5 class="h5divider"></h5>
                <button type="submit" class="btn" id="submitShopButton">
					<i class="material-icons right">arrow_forward</i>
					<?php echo $Core->Translator->translate("Register Shop");?>
				</button>
				<p id="displayAllErros"></p>
			</div>
			</form>
            </div>
			<div class="col hide-on-small-only m3 s12">
				<div class="target">
				  <ul class="section table-of-contents" style="max-width: 100%">
					<li><a href="#i_membership"><?php echo $Core->Translator->translate("Membership Information");?></a></li>
					<li><a href="#i_sysInfo"><?php echo $Core->Translator->translate("System Information");?></a></li> 
					<li><a href="#i_basicInfo"><?php echo $Core->Translator->translate("Basic Information");?></a></li>
					<li><a href="#i_opening"><?php echo $Core->Translator->translate("Opening Hours");?></a></li>
					<li><a href="#i_holidays"><?php echo $Core->Translator->translate("Holidays & Closed Days");?></a></li>
					<li><a href="#i_deliveryOptions"><?php echo $Core->Translator->translate("Delivery Options");?></a></li>  
					<li><a href="#i_delivery"><?php echo $Core->Translator->translate("Delivery Hours");?></a></li>
					<li><a href="#i_notifications"><?php echo $Core->Translator->translate("Order Notifications");?></a></li>
					<li><a href="#i_payments"><?php echo $Core->Translator->translate("Payments");?></a></li>
				  </ul>
				</div>
			</div>
			
		</div>
	
        </div>
</div>

 <!-- Modal Structure -->
<div id="uploadimageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><?php echo $Core->Translator->translate("Select Logo")?></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-8 text-center">
                    <div id="image_demo" style="width:350px; margin-top:30px"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="text-align: left;">
                <button class="btn btn-success crop_image left-align"><?php echo $Core->Translator->translate("Select")?></button>
			
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.1.0/croppie.min.js" integrity="sha512-OHrlqGLXjADlnhjsfWQOdm+x45eZp9LFSSYa2qNnSUSm8hsv41R4U5pcwb8RWbePh3phfFlWMHl7Q8QSoPnueg==" crossorigin="anonymous"></script>
<script>
function switchToProfessional(){
	$("#m_normal").prop("checked",false);
	$("#m_professional").prop("checked",true);
	checkMembership();
}
function showBankDetails(){
	var input = document.getElementById("credit_online_payment");
	if(input.checked){
	   	$("#bankDetails").fadeIn();
	 }else{
		 $("#bankDetails").fadeOut();
	 }
}
function checkMembership(){
	var professional = document.getElementById("m_professional");
	if(professional.checked){
		$("#paypal_payment").prop("disabled",false);
	   	$(".onlyProfessional").fadeOut();
		$(".inputProfessional").prop("disabled",false);
		showBankDetails();
	}else{
		$("#paypal_payment").prop("disabled",true);
	  	$(".onlyProfessional").fadeIn();
		$(".inputProfessional").prop("disabled",true);
		$(".inputProfessional").prop("checked",false);
		$(".inputProfessional").val("");
		$("#bankDetails").fadeOut()
	}
}
$('#sameDeliveryHours').click(function() {
	if(this.checked){
		$("#deliveryHours").hide();  
	}else{
		$("#deliveryHours").show(); 
	}
    
});
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
function removeHour(id){
	$("#"+id).remove();
}
function removeDay(id){
	$("#"+id).remove();
}

function addDeliveryRate(){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'" class="col s12">'+
						'<div class="input-field col s1">'+
							'<button class="closeBtn" type="button" onClick="removeDay('+random+')">x</button>'+
						'</div>'+
						'<div class="input-field col s4">'+
							'<input type="text" name="distance[]">'+
							'<label><?php echo $Core->Translator->translate("until Distance (not Radius)")?></label>'+
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
							'<label><?php echo $Core->Translator->translate("until Distance (not Radius)")?></label>'+
						'</div>'+
						'<div class="input-field col s5">'+
							'<input type="text" name="minPrice[]">'+
							'<label><?php echo $Core->Translator->translate("Min. Order Amount")?></label>'+
						'</div>'+
					'</div>';
	var el = document.getElementById("minOrder");
	el.insertAdjacentHTML("beforeend",input);
}
function addDays(){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'">'+
						'<div class="col s1">'+
							'<button class="closeBtn" type="button" onClick="removeDay('+random+')">x</button>'+
						'</div>'+
						'<div class="input-field col s5">'+
							'<input type="text" class="datepicker" name="holiday_from[]">'+
							'<label><?php echo $Core->Translator->translate("From")?></label>'+
						'</div>'+
						'<div class="input-field col s6">'+
							'<input type="text" class="datepicker" name="holiday_to[]">'+
							'<label><?php echo $Core->Translator->translate("To")?></label>'+
						'</div>'+
					'</div>';
	var el = document.getElementById("holidays");
	el.insertAdjacentHTML("beforeend",input);
	
}
function addHours(day){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'"><div class="col s1"><button onclick="removeHour('+random+')"  class="closeBtn" type="button">x</button></div>'+
				'<div class="input-field col s5">'+
						'<p><?php echo $Core->Translator->translate("From")?></p>'+
						'<input type="time" name="'+day+'_from[]"/>'+
					'</div>'+
					'<div class="input-field col s6">'+
						'<p><?php echo $Core->Translator->translate("To")?></p>'+
						'<input type="time" name="'+day+'_to[]"/>'+
					'</div></div>';
	var el = document.getElementById(day);
	el.insertAdjacentHTML("beforeend",input);
}

$(document).ready(function(){
		$('body').on('focus',".datepicker", function(){

		var date = new Date();
		var today1 = '31-12-' + date.getFullYear();
        $(this).pickadate({
		  format: 'dd-mm-yyyy',
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 100, // Creates a dropdown of 15 years to control year,
          today: '<?php echo $Core->Translator->translate("Today"); ?>',
          clear: '<?php echo $Core->Translator->translate("Clear"); ?>',
          close: 'Ok',
          closeOnSelect: false, // Close upon selecting a date,
          container: undefined, // ex. 'body' will append picker to body
		  max: today1
        });
    });
	
	$('.scrollspy').scrollSpy({
		activeClass : "sidebarActive"
	});
	$('.target').pushpin({
      top: 70,
      offset: 0
    });

$("ul.select-dropdown > li > span").on("click",function(){
     var valToSearch = this.innerHTML;
     var options = $("select.initialized ").children();
     
     options.each(function(){
         if(this.innerHTML == valToSearch){
             var value = this.value;
			 $(".subcategories").fadeOut();
             $("#category-"+value).fadeIn();
             setTimeout( function(){
						validator.element("#shopCategory");
			 },500);
         }
     });
	 	setTimeout(function(){
		 			for (let i = 1; i < 12; i++) {
					 validator.element("#subcategory-"+i);
					}
		 },500);
 });

 $('.modal').modal({dismissible: false});
    var $image_crop = $('#image_demo').croppie({
        enforceBoundary: false,
        viewport: {
          width:250,
          height:250,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
    });

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('open');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: { width: 500, height: 500 }
    }).then(function(response){
      $.ajax({
        url:"/Vendors/upload-and-crop-image/upload.php",
        type: "POST",
        data:{
            "image": response,
            "id": <?php echo $_SESSION["merchant"]["merchantId"];?>,
            "userType": "merchant"
        },
        success:function(data)
        {
          $('#uploadimageModal').modal('close');
          $('#path_image').val(data);
          $("#uploaded_image").empty();
          var img = $("<img class='preview_logo'>");
          img.attr("src",data);
          img.appendTo("#uploaded_image");
        }
      });
    })
  });

});  
</script>
<script>
$.validator.addMethod("time24", function(value, element) {
	if (value == "") return true;
    if (!/^\d{2}:\d{2}$/.test(value)) return false;
    var parts = value.split(':');
    if (parts[0] > 23 || parts[1] > 59) return false;
    return true;
});
$.validator.addMethod('checkSubCategory', function(value, element) {

		var cat = document.getElementById("shopCategory").value;

		var input = $("#subcategory-"+cat).val();

		if(input == ''){
		   return false;
		   }else{
			return true;
		   }
});
$.validator.addMethod('fullAddress', function(value, element) {
		var input = document.getElementById("street_number").value;
	
		if(input == ''){
		   return false;
		   }else{
			return true;
		   }
});
$.validator.addMethod('checkCategory', function(value, element) {
		var input = document.getElementById("shopCategory").value;
		if(input == 'none'){
		   return false;
		   }else{
			return true;
		   }
});

$.validator.addMethod('checkBankBenificiary', function(value, element) {
		var input = document.getElementById("credit_online_payment");
		if(input.checked == true){
			var benificiary = document.getElementById("benificiary").value;
			if(benificiary == ""){
			 	return false;  
			 }else{
				 return true;
			 }
		}else{
			return true;
		}
});
$.validator.addMethod('checkBankIBAN', function(value, element) {
		var input = document.getElementById("credit_online_payment");
		if(input.checked == true){
		   	var iban = document.getElementById("iban").value;
			if(iban == ""){
			 	return false;  
			 }else{
				 return true;
			 }
		}else{
			return true;
		}
});
$.validator.addMethod('checkBankBIC', function(value, element) {
		var input = document.getElementById("credit_online_payment");
		if(input.checked == true){
			var bic = document.getElementById("bic").value;
			if(bic == ""){
			 	return false;  
			 }else{
				 return true;
			 }
		}else{
			return true;
		}
});
$.validator.addMethod('checkSMS', function(value, element) {
		var input = document.getElementById("m_professional");
		if(input.checked == true){
			var sms = document.getElementById("orderBySMS").value;
			if(sms == ""){
			 	return false;  
			 }else{
				 return true;
			 }
		}else{
			return true;
		}
});
var validator = $("#addShop").validate({
	    ignore: [],
        rules: {
            shopAddress:{
				fullAddress: true
			},
			shopName:{
				required:true
			},
			category:{
				checkCategory:true
			},
			"subCategory[]":{
				checkCategory:true,
				checkSubCategory:true
			},
			path_image:{
				required:true
			},
			orderByEmail:{
			 	required:true,
				email:true
			},
			orderBySMS:{
				checkSMS:true	
			},
			benificiary:{
				checkBankBenificiary:true
			},
			IBAN:{
				checkBankIBAN:true
			},
			"BIC/SWIFT":{
				checkBankBIC:true
			},
			"distanceTime[]":{
				number:true
			},
			"distance[]":{
				number:true
			},
			"distancePrice[]":{
				number:true
			},
			"orderDistance[]":{
				number:true
			},
			"minPrice[]":{
				number:true
			},
			//@TODO checkboxes disapear when error
			cash_payment:{
				 require_from_group: [1, ".CheckPaymentMethod"]
			},
			credit_online_payment:{
				 require_from_group: [1, ".CheckPaymentMethod"]
			},
			credit_delivery_payment:{
				 require_from_group: [1, ".CheckPaymentMethod"]
			}
			
        },
        //For custom messages
        messages: {
			credit_delivery_payment:{
				 require_from_group:"<?php echo $Core->Translator->translate("Please select a Payment Method");?>"
			},
			credit_online_payment:{
				 require_from_group:"<?php echo $Core->Translator->translate("Please select a Payment Method");?>"
			},
			cash_payment:{
				 require_from_group:"<?php echo $Core->Translator->translate("Please select a Payment Method");?>"
			},
			orderByEmail:{
			 	required:"<?php echo $Core->Translator->translate("Please enter Email");?>",
				email:"<?php echo $Core->Translator->translate("Please enter valid Email");?>"
			},
			path_image:{
				required:"<?php echo $Core->Translator->translate("Please select a Logo");?>"
			},
           shopAddress: {
			   fullAddress:"<?php echo $Core->Translator->translate("Enter Street Number");?>"
		   },
			shopName:{
				required:"<?php echo $Core->Translator->translate("Enter Shop Name");?>"
			},
			category:{
				checkCategory:"<?php echo $Core->Translator->translate("Select Category");?>"
			},
			"subCategory[]":{
				checkSubCategory:"<?php echo $Core->Translator->translate("Select Subcategory");?>"
			},
			benificiary:{
				checkBankBenificiary:"<?php echo $Core->Translator->translate("Benificiary has to be filled in");?>"
			},
			IBAN:{
				checkBankIBAN:"<?php echo $Core->Translator->translate("IBAN has to be filled in");?>"
			},
			"BIC/SWIFT":{
				checkBankBIC:"<?php echo $Core->Translator->translate("BIC/SWIFT has to be filled in");?>"
			},
			orderBySMS:{
				checkSMS:"<?php echo $Core->Translator->translate("Mobile number has to be filled in");?>"	
			},
			"distanceTime[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only minutes")?>"
			},
			"distance[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers")?>"
			},
			"distancePrice[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers")?>"
			},
			"orderDistance[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers")?>"
			},
			"minPrice[]":{
				number:"<?php echo $Core->Translator->translate("Please enter only numbers")?>"
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
$("#submitShopButton").click(function(){
	if ($('#addShop').valid()) {
		

    } else {
		alert("<?php echo $Core->Translator->translate("Please fill all required Fields!");?>");
    }
});

$('.timeInput').each(function() {
    $(this).rules('add', {
		time24:true,
        messages: {
            time24:  "<?php echo $Core->Translator->translate("Please enter valid time format");?>"
        }
    });
});	
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC00WRtBgVw_2E2zJM0EwR9uiyW6uZ03bM&callback=initAutocomplete&libraries=places" async defer></script>
<script>
let placeSearch;
let autocomplete;
const componentForm = {
  street_number: "short_name",
  route: "long_name",
  locality: "long_name",
  administrative_area_level_2 : "long_name",
  administrative_area_level_1: "long_name",
  country: "long_name",
  postal_code: "short_name",
};



function initAutocomplete() {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: <?php echo $Core->Tracker->lat;?>, lng: <?php echo $Core->Tracker->lng;?> },
    zoom: 15,
  });
  // Create the autocomplete object, restricting the search predictions to
  // geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
    document.getElementById("addressInput"),
    { types: ["address"] }
  );
	autocomplete.bindTo("bounds", map);
	 const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");
  infowindow.setContent(infowindowContent);
  const marker = new google.maps.Marker({
    map,
    anchorPoint: new google.maps.Point(0, -29),
  });

  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  autocomplete.setFields(["address_component", "geometry"]);
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", () => {
    infowindow.close();
    marker.setVisible(false);
    const place = autocomplete.getPlace();

    if (!place.geometry) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
      map.setZoom(17);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }
	  console.log(place);
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
	  
	  
  // Get the place details from the autocomplete object.


  for (const component in componentForm) {
    document.getElementById(component).value = "";
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  for (const component of place.address_components) {
    const addressType = component.types[0];

    if (componentForm[addressType]) {
      const val = component[componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
	document.getElementById("lat").value = place.geometry.location.lat();
	document.getElementById("lng").value = place.geometry.location.lng();
	  var timezone;
	  var lat = place.geometry.location.lat();
	  var lng = place.geometry.location.lng();
	
	$.ajax({
	   url:"https://maps.googleapis.com/maps/api/timezone/json?location="+lat+","+lng+"&timestamp="+(Math.round((new Date().getTime())/1000)).toString()+"&key=AIzaSyC00WRtBgVw_2E2zJM0EwR9uiyW6uZ03bM",
		})
		.done(function(response){
		   if(response.timeZoneId != null){
			 document.getElementById("timezone").value = response.timeZoneId;
			   validator.element("#addressInput");
		   }
	});
	document.getElementById("addressObject").value = JSON.stringify(place);
 	$("#address").fadeIn();
 	

  });

}


</script>
<script>
    var input = document.querySelector(".phoneNumber");
    window.intlTelInput(input, {
      // allowDropdown: false,
		customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
    return selectedCountryPlaceholder;
  },
       autoHideDialCode: true,
      // autoPlaceholder: "079 60 79",
      //dropdownContainer:"#teltest",
      // excludeCountries: ["us"],
      formatOnDisplay: true,
      initialCountry: "auto",
	  geoIpLookup: function(callback) {
		$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
		  var countryCode = (resp && resp.country) ? resp.country : "us";
		  callback(countryCode);
		});
	  },
       hiddenInput: "full_number",
      // localizedCountries: { 'de': 'Deutschland' },
      nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      preferredCountries: ['li', 'ch', 'de', 'at'],
       separateDialCode: true,
      utilsScript: "/View/js/utils.js",
    });

	$( window ).resize(function() {
	  	var width = $("#shopEmail").width();
		var padd =	parseInt($("#shopEmail").css("padding-left")) + 6;
		var real_width = width - padd;
		$(".phoneNumber").css( "maxWidth", real_width );
		$(".phoneNumber").css( "width", real_width );
	});
	
	
  </script>

   