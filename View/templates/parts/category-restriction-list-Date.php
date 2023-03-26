<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Date"])){
    foreach ($data["restrictions"]["Date"] as $rDate){
    ?>
    <li class="valign-wrapper rDistanceList">
        <div class="col m2 s4">
            <div class="col s12">
                <input type="checkbox" name="restrictionDate[]" value="<?php echo $rDate["id"]?>" id="restrictionDate_<?php echo $rDate["id"]?>"/>
                <label for="restrictionDate_<?php echo $rDate["id"]?>"></label>
            </div>
        </div>
        <div class="col m10 s8">
            <div class="col m4 s12">
                <p><?php echo $Core->Translator->translate("From");?><br/>
                    <b><?php echo date("d-m-Y",strtotime($rDate["begin"]));?></b>
                </p>
            </div>
            <div class="col m4 s12">
               <p><?php echo $Core->Translator->translate("Until");?><br/>
                    <b><?php echo date("d-m-Y",strtotime($rDate["end"]));?></b>
                </p> 
            </div>
            <div class="col m4 s12">
                <p><?php echo $Core->Translator->translate("Action");?><br/>
                   <b class="<?php if($rDate["action"] == "available"){echo "green-text";}if($rDate["action"] == "unavailable"){echo "red-text";}?>"><?php echo $rDate["action"];?></b>
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
