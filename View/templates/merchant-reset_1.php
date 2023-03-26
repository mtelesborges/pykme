<?php
$Core = $view["Core"];
?>
<div class="main">
	<form action="/merchant/resetpassword" method="post" id="merchantReset_1">
		<div class="row">
			
			<div class="input-field col s6">
				<input name="email" type="email" id="merchantEmail"/>
				<label for="email"><?php echo $Core->Translator->translate("Email"); ?></label>
			</div>

			<div class="input-field col s6">
              	<input type="submit" value="<?php echo $Core->Translator->translate("Submit"); ?>" class="btn" value="submit">
			</div>
		</div>
	</form>
</div>

<script>
	
 $("#merchantReset_1").validate({
        rules: {
            email: {
                required: true,
				email: true
            }
        },
        //For custom messages
        messages: {
            email:{
                required: "<?php echo $Core->Translator->translate('Email is required');?>",
				email: "<?php echo $Core->Translator->translate('Enter valid Email');?>"
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