<?php
$Core = $data[0]["Core"];
?>
<form action="/merchant/editshop" method="post">
	<input name="edit" value="Membership" type="hidden"/>
	<input name="shopId" value="<?php echo $data[0]["shop_id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Membership Information");?></h5>
    <p>
        
        <input name="membership" type="radio" id="m_normal" value="1" <?php if($data[0]["membership_id"] == 1){?>checked="checked"<?php }?>/>
        <label for="m_normal"><span><?php echo $Core->Translator->translate("Normal (100% Free)");?></span>
        </label>
    </p>
    <p>
        
        <input name="membership" type="radio" id="m_professional" value="2" <?php if($data[0]["membership_id"] == 2){?>checked="checked"<?php }?>/>
        <label for="m_professional"><span><?php echo $Core->Translator->translate("Professional (&euro; 9.90/m on yearly payment)");?></span>
        </label>
    </p>
	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Membership")?></button>
	</div>
</form>