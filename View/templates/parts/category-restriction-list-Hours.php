<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Hours"])){
    foreach ($data["restrictions"]["Hours"] as $rHours){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col m2 s4">
            <div class="col s12">
                <input type="checkbox" name="restrictionHours[]" value="<?php echo $rHours["id"]?>" id="restrictionHours_<?php echo $rHours["id"]?>"/>
                <label for="restrictionHours_<?php echo $rHours["id"]?>"></label>
            </div>
        </div>
        <div class="col m10 s8">
            <div class="col m3 s12">
                <p><?php echo $Core->Translator->translate("Day");?><br/>
                    <b><?php echo $Core->Translator->translate($rHours["day"]);?></b>
                </p>
            </div>
            <div class="col m3 s12">
                <p><?php echo $Core->Translator->translate("From");?><br/>
                    <b><?php echo date("H:i",strtotime($rHours["begin"]));?></b>
                </p>
            </div>
            <div class="col m3 s12">
               <p><?php echo $Core->Translator->translate("Until");?><br/>
                    <b><?php echo date("H:i",strtotime($rHours["end"]));?></b>
                </p> 
            </div>
            <div class="col m3 s12">
               <p><?php echo $Core->Translator->translate("Action");?><br/>
                   <b class="<?php if($rHours["action"] == "available"){echo "green-text";}if($rHours["action"] == "unavailable"){echo "red-text";}?>"><?php echo $rHours["action"];?></b>
                </p> 
            </div>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first Hour restriction:");
}
?>
<ul>
