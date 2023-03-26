<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["DeliveryTime"])){
    foreach ($data["restrictions"]["DeliveryTime"] as $rDT){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col m2 s4">
            <div class="col s12">
                <input type="radio" name="restrictionDeliveryTime" value="<?php echo $rDT["id"]?>" id="restrictionDeliveryTime_<?php echo $rDT["id"]?>"/>
                <label for="restrictionDeliveryTime_<?php echo $rDT["id"]?>"></label>
            </div>
        </div>
        <div class="col m10 s8">
            <div class="col m4 s12">
                <p><?php echo $Core->Translator->translate("Days");?><br/>
                    <b><?php echo $rDT["days"];?></b>
                </p>
            </div>
            <div class="col m4 s12">
               <p><?php echo $Core->Translator->translate("Hours");?><br/>
                    <b><?php echo $rDT["hours"];?></b>
                </p> 
            </div>
            <div class="col m4 s12">
               <p><?php echo $Core->Translator->translate("Minutes");?><br/>
                   <?php echo $rDT["minutes"];?></b>
                </p> 
            </div>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first Date restriction:");
}
?>
<ul>
