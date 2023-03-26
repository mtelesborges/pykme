<?php
$Core = $view["Core"];
?>
<div class="row">
    <div class="container valign-wrapper" style="height:80vh">
        <div class="col m6 s12">
            <h2><?php echo $Core->Translator->translate("Merchant Login");?></h2>
            <form action="/merchant/login" method="post" id="merchantLoginForm">
                <div class="input-field col s12">
                        <input name="email" type="email" id="merchantEmail"/>
                        <label for="email"><?php echo $Core->Translator->translate("Email"); ?></label>
                </div>
                <div class="input-field col s12">
                        <input name="pass" type="password" id="merchantPass"/>
                        <label for="pass"><?php echo $Core->Translator->translate("Password"); ?></label>
                </div>
                <div class="input-field col s12">
                    <input type="submit" value="<?php echo $Core->Translator->translate("Login"); ?>" class="btn" value="submit">
                </div>
            </form>
        </div>
        <div class="col m6 s12">
            <img src="/View/img/login.svg" class="introIllustration"/>
        </div>
    </div>
</div>
<script>
	
 $("#merchantLoginForm").validate({
        rules: {
            email: {
                required: true,
				email: true
            },
			pass: {
                required: true,
            }
        },
        //For custom messages
        messages: {
            email:{
                required: "<?php echo $Core->Translator->translate('Email is required');?>",
				email: "<?php echo $Core->Translator->translate('Enter valid Email');?>"
            },
			pass:{
                required: "<?php echo $Core->Translator->translate('Password is required');?>",
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