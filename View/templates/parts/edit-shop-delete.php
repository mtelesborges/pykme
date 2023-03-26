<?php
$Core = $data["Core"];
?>
<form action="/merchant/editshop" method="post">
	<input name="edit" value="Delete" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Delete Shop");?></h5>

	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn" style="background: red"><i class="material-icons left">delete</i><?php echo $Core->Translator->translate("Delete Shop")?></button>
	</div>
</form>