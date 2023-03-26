<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Size"])){
    foreach ($data["restrictions"]["Size"] as $rSize){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col s2">
            <div class="col s12">
                <input name="restrictionSize" value="<?php echo $rSize["id"]?>" type="radio" id="restrictionSize_<?php echo $rSize["id"]?>"/>
                <label for="restrictionSize_<?php echo $rSize["id"]?>"></label>
            </div>
        </div>
        <div class="col s10">
            <div class="col m4 s12">
                <span><?php echo $Core->Translator->translate("X");?>:<br/>
                    <b><?php echo $rSize["x"];?> <?php echo $rSize["distanceSystem"];?></b>
                </span>
            </div>
            <div class="col m4 s12">
                <span><?php echo $Core->Translator->translate("Y");?>:<br/>
                    <b><?php echo $rSize["y"];?> <?php echo $rSize["distanceSystem"];?></b>
                </span>
            </div>
             <div class="col m4 s12">
                <span><?php echo $Core->Translator->translate("Z");?>:<br/>
                    <b><?php echo $rSize["z"];?> <?php echo $rSize["distanceSystem"];?></b>
                </span>
            </div>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first size restriction:");
}
?>
<ul>
