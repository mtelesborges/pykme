<?php
$Core = $view["Core"];
if(isset($_POST['merchantId'])){
	$merchantId = $_POST['merchantId'];
}else{	
	if(isset($Core->FrontController->Router->Parameters)){
		$merchantId = $Core->FrontController->Router->Parameters;
	}else{
		$merchantId = $view["Message"][0]["id"];
	}
}
?>
<div class="main">
    <div class="container">
        <h2 style="margin-top: 10vh"><?php echo $Core->Translator->translate("Verify");?></h2>
	<p><?php echo $Core->Translator->translate("We sent you an SMS with your verification code"); ?></p>
	<form action="/merchant/verify" method="post" id="merchantVerifyForm">
		<div class="row">
			
			<div class="input-field col s6">
				<input name="code" type="text" id="merchantCode"/>
				<label for="code"><?php echo $Core->Translator->translate("SMS Code"); ?></label>
			</div>
				
				<input name="merchantId" type="hidden" value="<?php echo $merchantId;?>"/>
			
			<div class="input-field col s6">
              	<input type="submit" value="<?php echo $Core->Translator->translate("Submit"); ?>" class="btn" value="submit">
			</div>
		</div>
	</form>
	<form action="/merchant/verifyAgain" method="post">
		<input type="hidden" name="merchantId" value="<?php echo $merchantId;?>"/>
		<input type="submit" class="btn" value="<?php echo $Core->Translator->translate("Send SMS again");?>"/>
	</form>
    </div>
</div>

<script>
	
 $("#merchantVerifyForm").validate({
        rules: {
            code: {
                required: true,
				minlength:6,
				maxlength:6,
				numbers: true
            }
        },
        //For custom messages
        messages: {
            code:{
                required: "<?php echo $Core->Translator->translate('Code most be 6 numbers');?>",
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