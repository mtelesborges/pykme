<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Transportation"])){
    foreach ($data["restrictions"]["Transportation"] as $rTrans){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col m2 s4">
            <div class="col s12">
                <input type="checkbox" name="restrictionTrans[]" value="<?php echo $rTrans["id"]?>" id="restrictionTrans_<?php echo $rTrans["id"]?>"/>
                <label for="restrictionTrans_<?php echo $rTrans["id"]?>"></label>
            </div>
        </div>
        <div class="col m10 s8">
            <div class="col m4 s12">
                <p><?php echo $Core->Translator->translate("From");?><br/>
                    <b><?php echo $rTrans["temperatureFrom"];?> <?php echo $rTrans["tempSystem"];?></b>
                </p>
            </div>
            <div class="col m4 s12">
                <p><?php echo $Core->Translator->translate("Until");?><br/>
                    <b><?php echo $rTrans["temperatureUntil"];?> <?php echo $rTrans["tempSystem"];?></b>
                </p>
            </div>
            <div class="col m4 s12">
               <p><?php echo $Core->Translator->translate("Cargo Type");?><br/>
                   <b><?php echo $Core->Translator->translate($rTrans["cargoType"]);?></b>
                </p> 
            </div>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first transportation restriction:");
}
?>
<ul>
