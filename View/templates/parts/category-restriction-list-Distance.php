<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Distance"])){
    foreach ($data["restrictions"]["Distance"] as $rDistance){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col s2">
            <div class="col s12">
                <input name="restrictionDistance[]" value="<?php echo $rDistance["id"]?>" type="checkbox" id="restrictionDistance_<?php echo $rDistance["id"]?>"/>
                <label for="restrictionDistance_<?php echo $rDistance["id"]?>"></label>
            </div>
        </div>
        <div class="col s10">
            <div class="col m6 s12">
                <span><?php echo $Core->Translator->translate("Min. Distance");?>:<br/>
                    <b><?php echo $rDistance["min"];?> <?php echo $rDistance["distanceSystem"];?></b>
                </span>
            </div>
            <div class="col m6 s12">
                <span><?php echo $Core->Translator->translate("Max. Distance");?>:<br/>
                    <b><?php echo $rDistance["max"];?> <?php echo $rDistance["distanceSystem"];?></b>
                </span>
            </div>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first distance restriction:");
}
?>
<ul>
