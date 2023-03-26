<?php
$Core = $data["Core"];?>
<div class="col s12">
		<div class="col s6 input-field">
			<input id="preparationTime" type="number" class="validate" min="0"/>
			<label><?php echo $Core->Translator->translate("Time amount");?></label>
		</div>
		<div class="col s6 input-field">
			<select id="typePreparationTime">
				<option value="i"><?php echo $Core->Translator->translate("Minutes");?></option>
				<option value="h"><?php echo $Core->Translator->translate("Hours");?></option>
				<option value="d"><?php echo $Core->Translator->translate("Days");?></option>
				<option value="w"><?php echo $Core->Translator->translate("Weeks");?></option>
			</select>
		</div>
	</div>
<?php
if(!empty($data["selectedVariations"])){
?>
<div class="col s12">
	<div class="col s12">
		<h6><?php echo $Core->Translator->translate("Variations:");?></h6>
	</div>
	<?php
	foreach($data["selectedVariations"] as $v){
	?>
	<div class="col s12">
		<div class="col s12">
			<p><?php echo $v["description"]["title"];?><span class="grey-text"> <?php echo $v["description"]["description"];?></span></p>
		</div>
		<div class="col s6 input-field">
			<input id="preparationTimeVariation_<?php echo $v["info"]["id"];?>" type="number" class="validate" min="0"/>
			<label><?php echo $Core->Translator->translate("Time");?></label>
		</div>
		<div class="col s6 input-field">
			<select id="typePreparationTimeVariation_<?php echo $v["info"]["id"];?>">
				<option value="i"><?php echo $Core->Translator->translate("Minutes");?></option>
				<option value="h"><?php echo $Core->Translator->translate("Hours");?></option>
				<option value="d"><?php echo $Core->Translator->translate("Days");?></option>
				<option value="w"><?php echo $Core->Translator->translate("Weeks");?></option>
			</select>
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php
}
?>
<script>
$('select').material_select();
</script>