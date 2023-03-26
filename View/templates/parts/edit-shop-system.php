<?php
$Core = $data["Core"];
?>
<div class="row">
<form action="/merchant/editshop" method="post">
	<input name="edit" value="System" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("System Information");?></h5>
    <div class="col s12 input-field">
        <select name="distanceSystem">
            <option value="km" <?php if($data["shop"]["distanceSystem"] == "km"){ echo "selected";}?>><?php echo $Core->Translator->translate("Kilometer");?></option>
            <option value="mi" <?php if($data["shop"]["distanceSystem"] == "mi"){ echo "selected";}?>><?php echo $Core->Translator->translate("Miles");?></option>
        </select>
        <label><?php echo $Core->Translator->translate("Distance in");?></label>
    </div>
    <div class="col s12 input-field">
        <select name="currency">
            <?php
            foreach($data["allCurrency"] as $currency){
            ?>
            <option value="<?php echo $currency["id"]?>" <?php if($currency["id"] == $data["shop"]["currency_id"]){?>selected<?php }?>><?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"]?>) <?php echo $currency["symbol"]?></option>
            <?php
            }
            ?>
        </select>
        <label><?php echo $Core->Translator->translate("Currency");?></label>
    </div>
	<div class="col s12">
	<button type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update System Information");?></button>
	</div>
</form>
</div>
<script>
$('select').material_select();
</script>