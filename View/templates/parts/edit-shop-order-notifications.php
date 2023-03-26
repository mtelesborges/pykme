<?php
$Core = $data["Core"];
?>
<form action="/merchant/editshop" method="post">
	<input name="edit" value="Orders_Notification" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Orders Notification");?></h5>
        <div class="input-field col s12">
            <input name="orderByEmail" id="shopEmail" type="email" value="<?php echo $data["shop"]["orderByEmail"]?>"/>
            <label class="active"><?php echo $Core->Translator->translate("Order to Email");?></label>
        </div>
        <div class="input-field col s12">
            <label class="active"><?php echo $Core->Translator->translate("Order to SMS");?></label>
			<?php 
			if($data["membership"]["membership_id"] == 2){
			?>
			<input name="orderBySMS" type="text" class="inputProfessional phoneNumber" value="<?php echo $data["shop"]["orderBySMS"]?>" id="orderBySMS"/>
            <span><?php echo $Core->Translator->translate("$0.20 per SMS");?></span>
			<?php }else{?>
			<input name="orderBySMS" type="text" disabled class="inputProfessional phoneNumber" value="" id="orderBySMS"/>
            <span><?php echo $Core->Translator->translate("$0.20 per SMS");?></span>
            <span class="onlyProfessional"><b><?php echo $Core->Translator->translate("(Only for Professional Membership</b>)");?></b><span class="switchToProf" onClick="editShop('Membership','<?php echo $data["shop"]["id"]?>')"><?php echo $Core->Translator->translate("Switch to Professional")?></span></span>
			<?php }?>
        </div>
	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Orders Notification")?></button>
	</div>
</form>
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


	
	
  </script>