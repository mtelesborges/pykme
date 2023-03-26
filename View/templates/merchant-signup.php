<?php
$Core = $view["Core"];
?>
<div class="container">
	<h2 style="margin-top: 10vh"><?php echo $Core->Translator->translate("Sell on pykme");?></h2>
	<p><?php echo $Core->Translator->translate("We’ll help you get rid of the greedy online ordering systems.<br/><span><a href='/membership/plans' target='_blank'>Learn about our Memberships</a></span>"); ?></p>
	<form action="/merchant/signup" method="post" id="merchantSignupForm">
		<div class="row">
			
			<div class="input-field col s12  m6">	
            	<input name="name"	
					   type="text" 
					   id="merchantName"
					   <?php if($_POST["name"]){
					   		echo "value='".$_POST["name"]."'";
					    } ?>
				/>
				<label for="name"><?php echo $Core->Translator->translate("Name"); ?></label>
			</div>
			
			<div class="input-field col s12  m6">
              	<input name="fName" 
					   type="text" 
					   id="merchantfName"
					   <?php if($_POST["fName"]){
					   		echo "value='".$_POST["fName"]."'";
					    } ?>
				/>
				<label for="fName"><?php echo $Core->Translator->translate("Surname"); ?></label>
			</div>
			
			<div class="input-field col s12  m6">
              	<input name="birthday"	
					   type="text" 
					   class="datepicker" 
					   id="merchantBirthday"
					   <?php if($_POST["birthday"]){
					   		echo "value='".$_POST["birthday"]."'";
					    } ?>
				/>
				<label for="birthday"><?php echo $Core->Translator->translate("Birthday"); ?></label>
			</div>
			
			<div class="input-field col s12  m6">
              	<input name="email"	
					   type="email" 
					   id="merchantEmail"
					   <?php if($_POST["email"]){
					   		echo "value='".$_POST["email"]."'";
					    } ?>
				/>
				<label for="email"><?php echo $Core->Translator->translate("E-Mail"); ?></label>
			</div>
			
					
			
			
			<div class="input-field col s12">
              	<input name="phone"	
					   type="tel" 
					   id="merchantPhone"
					   autocomplete="off" 
					   <?php if($_POST["phone"]){
					   		echo "value='".$_POST["phone"]."'";
					    } ?>
				/>
				
			</div>
			
			<div class="input-field col s12  m6">
              	<input name="pass_1"  type="password" id="merchantPass1"/>
				<label for="pass_1"><?php echo $Core->Translator->translate("Password"); ?></label>
			</div>
			
			<div class="input-field col s12  m6 ">
              	<input name="pass_2" type="password" id="merchantPass2">
				<label for="pass_2"><?php echo $Core->Translator->translate("Repeat Password"); ?></label>
			</div>
			
			
			<div class="input-field col s12  m6  offset-m6" style="margin-top:5vh">
              	<input type="submit" style="float: right;" value="<?php echo $Core->Translator->translate("Submit"); ?>" class="btn" value="submit">
			</div>

		</div>
	</form>
</div>

<script>
	
 $("#merchantSignupForm").validate({
        rules: {
            name: {
                required: true
            },
			fName: {
                required: true
            },
			birthday: {
                required: true
            },
			email:{
				required: true,
				email: true
			},
			phone:{
				required: true,
				number: true
			},
			pass_1:{
				required: true,
			},
			pass_2:{
				required: true,
				equalTo: "#merchantPass1"
			},
			membership:{
				required: true,
			}
           
        },
        //For custom messages
        messages: {
            name:{
                required: 	"<?php echo $Core->Translator->translate('Enter your Name');?>"
            },
			fName:{
                required: 	"<?php echo $Core->Translator->translate('Enter your Surname');?>"
            },
			birthday:{
                required: 	"<?php echo $Core->Translator->translate('Enter your Birthday');?>"
            },
			email:{
                required: 	"<?php echo $Core->Translator->translate('Enter your Email');?>",
				email: 		"<?php echo $Core->Translator->translate('Enter a valid Email');?>"
            },
			phone:{
                required: 	"<?php echo $Core->Translator->translate('Enter your Cellphone number');?>",
				number: 	"<?php echo $Core->Translator->translate('Enter a valid number (No Spaces!)');?>"
            },
			pass_1:{
				required: 	"<?php echo $Core->Translator->translate('Enter your Password');?>"
			},
			pass_2:{
				required: 	"<?php echo $Core->Translator->translate('Enter your Password');?>",
				equalTo: 	"<?php echo $Core->Translator->translate('Password does not match');?>"
			},
			membership:{
				required: 	"<?php echo $Core->Translator->translate('Choose membership');?>"
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
<script>
    var input = document.querySelector("#merchantPhone");
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
	  	var width = $("#merchantName").width();
		var padd =	parseInt($("#merchantPhone").css("padding-left")) + 6;
		var real_width = width - padd;
		$("#merchantPhone").css( "maxWidth", real_width );
		$("#merchantPhone").css( "width", real_width );
	});
	
 $(document).ready(function(){
 $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 200, // Creates a dropdown of 15 years to control year,
    today: '<?php echo $Core->Translator->translate("Today")?>',
    clear: '<?php echo $Core->Translator->translate("Clear")?>',
    close: 'Ok',
    closeOnSelect: false, // Close upon selecting a date,
    container: undefined, // ex. 'body' will append picker to body
  });
  });	
  </script>