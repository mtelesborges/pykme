<?php
$Core = $data["Core"];
?>
<form action="/merchant/editshop" method="post">
	<input name="edit" value="Payment_Options" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Payments");?></h5>
     <div class="col s12">
						
                                                <input name="cash_payment" id="cash_payment" type="checkbox" <?php if($data["shop"]["cashOnDelivery"]){ echo "checked";}?> class="CheckPaymentMethod"/>
                                                <label for="cash_payment"><span><?php echo $Core->Translator->translate("Cash on Delivery/Take-Away");?></span>
                                                </label>
					</div>
					<div class="col s12">
						<?php if($data["membership"]["membership_id"] == 2){?>
						
                                                <input name="credit_online_payment" id="credit_online_payment" type="checkbox" <?php if($data["shop"]["onlineCreditCard"]){ echo "checked";} ?> class="inputProfessional CheckPaymentMethod" onClick="showBankDetails()"/>
                                                <label for="credit_online_payment"><span><?php echo $Core->Translator->translate("Credit Card Online");?></span>
                                                </label>
						<?php }else{?>
						
						<input name="credit_online_payment" id="credit_online_payment" type="checkbox" disabled class="inputProfessional CheckPaymentMethod" onClick="showBankDetails()"/>
                                                <label for="credit_online_payment"><span><?php echo $Core->Translator->translate("Credit Card Online");?></span>
                                                </label>
						
						<span class="onlyProfessional"><b><?php echo $Core->Translator->translate("(Only for Professional Membership)");?></b> <span class="switchToProf" onClick="editShop('Membership','<?php echo $data["shop"]["id"]?>')"><?php echo $Core->Translator->translate("Switch to Professional")?></span></span>
						<?php } ?>
						
						
						<div class="col s12" id="bankDetails" <?php if($data["shop"]["onlineCreditCard"] == 0){ echo "style='display:none'";}?>>
							<div class="input-field col s12">
								<input name="benificiary" type="text" id="benificiary" value="<?php echo $data["bank"]["benificiary"]?>"/>
								<label class="<?php if($data["bank"]["benificiary"]){ echo "active";}?>"><?php echo $Core->Translator->translate("Beneficiary Name (Person or Business)");?></label>
							</div>
							<div class="input-field col s12">
								<input name="IBAN" type="text" id="iban" value="<?php echo $data["bank"]["iban"]?>"/>
								<label class="<?php if($data["bank"]["iban"]){ echo "active";}?>"><?php echo $Core->Translator->translate("IBAN");?></label>
							</div>
							<div class="input-field col s12">
								<input name="BIC/SWIFT" type="text" id="bic"  value="<?php echo $data["bank"]["bic_swift"]?>"/>
								<label class="<?php if($data["bank"]["bic_swift"]){ echo "active";}?>"><?php echo $Core->Translator->translate("BIC/SWIFT Code");?></label>
							</div>
							<div class="input-field col s12">
								<input name="Clearing" type="text" value="<?php echo $data["bank"]["clearing"]?>"/>
								<label class="<?php if($data["bank"]["clearing"]){ echo "active";}?>"><?php echo $Core->Translator->translate("Clearing Code or BC");?></label>
							</div>
							<div class="input-field col s12">
								<input name="Bank" type="text" value="<?php echo $data["bank"]["bank"]?>"/>
								<label class="<?php if($data["bank"]["bank"]){ echo "active";}?>"><?php echo $Core->Translator->translate("Bank Name");?></label>
							</div>
							<div class="input-field col s12">
								<textarea name="BankAddress"  class="materialize-textarea"><?php echo $data["bank"]["bankAddress"]?></textarea>
								<label class="<?php if($data["bank"]["bankAddress"]){ echo "active";}?>"><?php echo $Core->Translator->translate("Bank Address");?></label>
							</div>
						</div>
					</div>
					<div class="col s12">
						
                                                <input name="credit_delivery_payment" id="credit_delivery_payment" type="checkbox" class="CheckPaymentMethod" <?php if($data["shop"]["creditOnDelivery"]){ echo "checked";}?>/>
                                                <label for="credit_delivery_payment"><span><?php echo $Core->Translator->translate("Credit Card on Delivery/Take-Away (With own POS Terminal)");?></span>
                                                </label>
					</div>   
	
	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Payments")?></button>
	</div>
</form>
<script>
function showBankDetails(){
	var input = document.getElementById("credit_online_payment");
	if(input.checked){
	   	$("#bankDetails").fadeIn();
	 }else{
		 $("#bankDetails").fadeOut();
	 }
}
</script>
