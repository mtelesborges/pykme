<?php
$Core = $data["Core"];
?>




<div class="col s12 input-field">
    <select id="productRestrictionTransportation_shops" multiple>
        <?php
        foreach($data["inShops"] as $shop){
        ?>
        <option selected value="<?php echo $shop["id"]?>"  data-icon="<?php echo $shop["logo"]?>" class="circle left"><?php echo $shop["name"];?><br/><br/><span class="grey-text">(<?php echo $shop["address"]["googleString"];?>)</span></option>   
        <?php
        }
        ?>   
    </select>
</div>
<div class="col s12 input-field">
   <select id="productRestrictionTransportation_variations" multiple>
        <option value="global" selected><?php echo $Core->Translator->translate("All Variations");?></option>
        <option value="default"><?php echo $Core->Translator->translate("Default Variation");?></option>
        <?php
        foreach($data["activeVariations"] as $variation){
        ?>
            <option value="<?php echo $variation["info"]["id"]?>">
            <?php foreach($variation["descriptions"] as $d){
                if($d["default"] == 1){
                   echo $d["title"];
                }
            }?>
            </option>
        <?php
        }
        ?>
   </select>
    <label><?php echo $Core->Translator->translate("Product Variation")?></label>
</div>
<div class="col m3 s12 input-field">
    <select id="productRestrictionTransportation_cargoType">
        <option value="refrigerated"><?php echo $Core->Translator->translate("Refrigirated");?></option>
        <option value="fragil"><?php echo $Core->Translator->translate("Fragil");?></option>
        <option value="alive"><?php echo $Core->Translator->translate("Alive");?></option>
        <option value="dangerous"><?php echo $Core->Translator->translate("Dangerous");?></option>
    </select>
</div>
<div class="col m3 s12 input-field">
    <input id="productRestrictionTransportation_tempFrom" type="number" class="validate" step="any"/>
    <label><?php echo $Core->Translator->translate("Min. Temp.");?></label>
</div>
<div class="col m3 s12 input-field">
    <input id="productRestrictionTransportation_tempUntil" type="number" class="validate" step="any"/>
    <label><?php echo $Core->Translator->translate("Max. Temp.");?></label>
</div>
<div class="col m3 s12 input-field">
    <select id="productRestrictionTransportation_Scala">
        <option value="°C">°C</option>
        <option value="°F">°F</option>
        <option value="K">K</option>
    </select>
</div>
<div class="col s12 input-field">
     <button type="button" class="btn-flat restrictionCreateButton" onclick="createProductRestrictionTransportation()"><?php echo $Core->Translator->translate("Create Transportation Restriction");?></button>
</div>






<?php
if($data["productRestriction"]){?>
<div class="col s12">
    <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Age Restriction");?></h6>
    <ul class="collection">
        <?php
        $hasRestriction = false;
        foreach($data["productRestriction"] as $restriction){
            if($restriction["type"] == "Transportation"){
                $object =  unserialize($restriction["object"]);
                $hasRestriction = true;
            ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                    
                    <input id="productRestriction_<?php echo $restriction["id"]?>" name="productRestriction[]" type="checkbox" value="<?php echo $restriction["id"]?>"/>
                    <label for="productRestriction_<?php echo $restriction["id"]?>"><span></span>
                    </label>
                </div>
                <div class="col s10">
                    
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe in shops:")?></p>
                        <?php
                        foreach($object["shops"] as $shop_id){
                        ?>
                            <div class="chip">
                                <img src="<?php echo $data["allShops"][$shop_id]["logo"]?>" alt="Logo <?php echo $data["allShops"][$shop_id]["name"]?>">
                                <?php echo $data["allShops"][$shop_id]["name"]?> (<?php echo $data["allShops"][$shop_id]["address"]["googleString"]?>)
                            </div>
                            <br/>
                        <?php
                        }
                        ?>
                    </div>                   
                    <div class="col s12 m6">
                        <p><?php echo $Core->Translator->translate("Availabe for variations:")?></p>
                        <p class="grey-text">
                            <?php
                            foreach($object["variations"] as $variation){
                                if($variation == "default" || $variation == "global"){
                                    if($variation == "default"){
                                        $variation = $Core->Translator->translate("default");
                                    }
                                    if($variation == "global"){
                                        $variation = $Core->Translator->translate("All variations");
                                    }
                                    echo "(".$variation.") ";
                                }else{
                                    foreach($data["AllVariations"][$variation]["descriptions"] as $var){
                                        if($var["default"] == true){
                                            echo "(".$var["title"].") ";
                                        }
                                    }
                                }
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s12 m6">
                        <p><?php echo $Core->Translator->translate("Type")?></p>
                        <p class="grey-text"><?php echo $object["cargoType"]; ?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("min. Temperature");?></p>
                        <p class="grey-text"><?php echo $object["tempFrom"]?> <?php echo $object["scala"]?></p>
                    </div>
                    <div class="col s6">
                        <p><?php echo $Core->Translator->translate("max. Temperature");?></p>
                        <p class="grey-text"><?php echo $object["tempUntil"]?> <?php echo $object["scala"]?></p>
                    </div>
                    
                </div>
            </li>
            <?php
            }
        }
        if($hasRestriction == false){
            echo $Core->Translator->translate("Please create a transportation restriction");
        }
        ?>
    </ul>
    </div>
<?php
}
?>

