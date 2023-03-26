<ul>
<?php
$Core = $data["Core"];
if(!empty($data["restrictions"]["Age"])){
    foreach ($data["restrictions"]["Age"] as $rAge){
    ?>
    <li>
        <div class="col s12">
            <p>
            <input type="radio" value="<?php echo $rAge["id"]?>" name="restrictionAge" id="rAge_<?php echo $rAge["id"]?>"/>
            <label for="rAge_<?php echo $rAge["id"]?>" class="black-text">+<?php echo $rAge["age"];?> <?php echo $Core->Translator->translate("years old");?></label>
            </p>
        </div>
    </li>    
    <?php        
    }
}else{
    echo $Core->Translator->translate("Create your first age restriction:");
}
?>
<ul>
