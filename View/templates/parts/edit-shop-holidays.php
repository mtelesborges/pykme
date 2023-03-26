<?php
$Core = $data["Core"];

?>
<div class="row">
<form action="/merchant/editshop" method="post">
	<input name="edit" value="Holidays" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop_id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Holidays & Closed Days");?></h5>
				<div id="holidays">
					<?php
					if(!empty($data["holidays"])){
					foreach($data["holidays"] as $holiday){
					?>
					<div id="firstHoliday-<?php echo $holiday["id"]; ?>">
						<div class="col s1">
							<button class="closeBtn" type="button" onClick="removeDay('firstHoliday-<?php echo $holiday["id"]; ?>')">x</button>
						</div>
						<div class="input-field col s5">
							<input type="text" class="datepicker" name="holiday_from[]" value="<?php echo date("d-m-Y",strtotime($holiday["from"]))?>">
							<label class="active"><?php echo $Core->Translator->translate("From")?></label>
						</div>
						<div class="input-field col s6">
							<input type="text" class="datepicker" name="holiday_to[]" value="<?php echo date("d-m-Y",strtotime($holiday["to"]))?>">
							<label class="active"><?php echo $Core->Translator->translate("To")?></label>
						</div>
					</div>
					<?php }}else{?>
					<div id="firstHoliday">
						<div class="col s1">
							<button class="closeBtn" type="button" onClick="removeDay('firstHoliday')">x</button>
						</div>
						<div class="input-field col s5">
							<input type="text" class="datepicker" name="holiday_from[]">
							<label><?php echo $Core->Translator->translate("From")?></label>
						</div>
						<div class="input-field col s6">
							<input type="text" class="datepicker" name="holiday_to[]">
							<label><?php echo $Core->Translator->translate("To")?></label>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="input-field col s12">
				<button class="btn-flat" type="button" onClick="addDays()"><i class="material-icons left">add_circle</i><?php echo $Core->Translator->translate("ADD DAYS")?></button>
				</div>
	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update holidays and closed days")?></button>
	</div>
	</div>
</form>
</div>
<script>
function addDays(){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'">'+
						'<div class="col s1">'+
							'<button class="closeBtn" type="button" onClick="removeDay('+random+')">x</button>'+
						'</div>'+
						'<div class="input-field col s5">'+
							'<input type="text" class="datepicker" name="holiday_from[]">'+
							'<label><?php echo $Core->Translator->translate("From")?></label>'+
						'</div>'+
						'<div class="input-field col s6">'+
							'<input type="text" class="datepicker" name="holiday_to[]">'+
							'<label><?php echo $Core->Translator->translate("To")?></label>'+
						'</div>'+
					'</div>';
	var el = document.getElementById("holidays");
	el.insertAdjacentHTML("beforeend",input);
	
}
function removeDay(id){
	$("#"+id).remove();
}
	$('body').on('focus',".datepicker", function(){

		var date = new Date();
		var today1 = '31-12-' + date.getFullYear();
        $(this).pickadate({
		  format: 'dd-mm-yyyy',
          selectMonths: true, // Creates a dropdown to control month
          selectYears: 100, // Creates a dropdown of 15 years to control year,
          today: '<?php echo $Core->Translator->translate("Today"); ?>',
          clear: '<?php echo $Core->Translator->translate("Clear"); ?>',
          close: 'Ok',
          closeOnSelect: false, // Close upon selecting a date,
          container: 'body', // ex. 'body' will append picker to body
		  max: today1
        });
    });
</script>
