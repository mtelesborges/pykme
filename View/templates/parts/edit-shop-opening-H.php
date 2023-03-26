<?php
$Core = $data["Core"];
$weekday = $Core->weekday;
?>
<form action="/merchant/editshop" method="post" id="formOpeningHours">
	<input name="edit" value="Opening_H" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop_id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Opening Hours");?></h5>
	<div class="row">
	
	<p>
        
            <input type="checkbox" name="sameDeliveryHours" id="sameDeliveryHours" <?php if(!empty($data["sameDelivery"])){ echo "checked";}?>/>
        <label for="sameDeliveryHours">    <span><?php echo $Core->Translator->translate("Delivery Hours same as Opening Hours");?></span>
        </label>
    </p>
    <ul class="collapsible popout" data-collapsible="expandable">
		<?php
		foreach($weekday as $day){
		?>
		<li>
			<!----------->
			<div class="collapsible-header">
				<h6><?php echo $Core->Translator->translate($day);?></h6>
			</div>
			<div class="collapsible-body">
				<span class="hoursBody">
					<div id="<?php echo $day;?>">
						<?php 
                                                
						if(!empty($data["hours"]) && in_array($day,array_column($data["hours"],"day"))){
                                                    foreach($data["hours"] as $hour){
                                                            if($hour["day"] == $day){
                                                            ?>
                                                            <div id="first<?php echo $day.$hour["id"];?>">
                                                                    <div class="col s1 valign-wrapper"><button onclick="removeHour('first<?php echo $day.$hour["id"];?>')" class="closeBtn" type="button">x</button></div>
                                                                    <div class="input-field col s5">
                                                                            <p><?php echo $Core->Translator->translate("From")?></p>
                                                                            <input type="time" name="<?php echo $day;?>_from[]" class="timeInput" value="<?php echo date("H:i",strtotime($hour["begin"]));?>"/>
                                                                    </div>
                                                                    <div class="input-field col s6">
                                                                            <p><?php echo $Core->Translator->translate("To")?></p>
                                                                            <input type="time" name="<?php echo $day;?>_to[]" class="timeInput" value="<?php echo date("H:i",strtotime($hour["end"]));?>"/>
                                                                    </div>
                                                            </div>
                                                            <?php
                                                            }
                                                    }
						}else{
						?>
						<div id="first<?php echo $day?>">
							<div class="col s1 valign-wrapper"><button onclick="removeHour('first<?php echo $day;?>')" class="closeBtn" type="button">x</button></div>
							<div class="input-field col s5">
								<p><?php echo $Core->Translator->translate("From")?></p>
								<input type="time" name="<?php echo $day;?>_from[]" class="timeInput"/>
							</div>
							<div class="input-field col s6">
								<p><?php echo $Core->Translator->translate("To")?></p>
								<input type="time" name="<?php echo $day;?>_to[]" class="timeInput"/>
							</div>
						</div>
						<?php }?>
					</div>
					<div class="input-field col s12">
						<button class="btn-flat" onClick="addHours('<?php echo $day;?>')" type="button"><i class="material-icons left">add_circle</i><?php echo $Core->Translator->translate("More Hours");?></button>
					</div>
				</span>
			</div>
			<!----------->
		</li>
		<?php
		}
		?>
	</ul>
	<div class="input-field col s12">
		<button name="submit" type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Hours")?></button>
	</div>
	</div>
</form>
<script>
$('.collapsible').collapsible();
function addHours(day){
	var random = Math.floor(Math.random() * Math.floor(9999999999999999999));
	var input = '<div id="'+random+'"><div class="col s1"><button onclick="removeHour('+random+')"  class="closeBtn" type="button">x</button></div>'+
				'<div class="input-field col s5">'+
						'<p><?php echo $Core->Translator->translate("From")?></p>'+
						'<input type="time" name="'+day+'_from[]"/>'+
					'</div>'+
					'<div class="input-field col s6">'+
						'<p><?php echo $Core->Translator->translate("To")?></p>'+
						'<input type="time" name="'+day+'_to[]"/>'+
					'</div></div>';
	var el = document.getElementById(day);
	el.insertAdjacentHTML("beforeend",input);
}
function removeHour(id){
$("#"+id).remove();
}
</script>
<script>
$.validator.addMethod("time24", function(value, element) {
	if (value == "") return true;
    if (!/^\d{2}:\d{2}$/.test(value)) return false;
    var parts = value.split(':');
    if (parts[0] > 23 || parts[1] > 59) return false;
    return true;
});
var validator = $("#formOpeningHours").validate({
	    ignore: [],
        rules: {
            
        },
        //For custom messages
        messages: {
			
		  
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
$('.timeInput').each(function() {
    $(this).rules('add', {
		time24:true,
        messages: {
            time24:  "<?php echo $Core->Translator->translate("Please enter a valid time, between 00:00 and 23:59");?>"
        }
    });
});	
</script>
<style>
	.collapsible.popout > li.active{
		box-shadow: none;
		-webkit-box-shadow:none;
		border:1px solid #ccc;
	}
</style>