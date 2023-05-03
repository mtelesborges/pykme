<?php
$Core = $data["Core"];
 if(!empty($data["inShops"])){
?>
<div class="col s12">
    <p>
        <input type="checkbox" name="noTransportation" id="noTransportation" onclick="checkTransportation($(this))"/>
        <label for="noTransportation"><?php echo $Core->Translator->translate("Sold only at the physical store. (No delivery)")?></label>
    </p>
    <br/><br/>
</div>
<div id="checkTransportation">
    <div class="col m6 s12 input-field">
       <input id="weight" type="number" step="any" class="validate" value="<?php echo $data["productPhysicalInfo"]["weight"];?>"/>
       <label><?php echo $Core->Translator->translate("Weight");?></label>
    </div>
    <div class="col m6 s12 input-field">
        <select id="weightSystem">
            <option value="kg" <?php if($data["productPhysicalInfo"]["weightSystem"] == "kg") echo "selected";?>><?php echo $Core->Translator->translate("Kilograms");?></option>
            <option value="g" <?php if($data["productPhysicalInfo"]["weightSystem"] == "g") echo "selected";?>><?php echo $Core->Translator->translate("Grams");?></option>
            <option value="lb" <?php if($data["productPhysicalInfo"]["weightSystem"] == "lb") echo "selected";?>><?php echo $Core->Translator->translate("Pounds");?></option>
            <option value="oz" <?php if($data["productPhysicalInfo"]["weightSystem"] == "oz") echo "selected";?>><?php echo $Core->Translator->translate("Ounce");?></option>
        </select>
        <label><?php echo $Core->Translator->translate("Weight Measurement");?></label>
    </div>
    <div class="col s12 m3 input-field">
       <input id="width" type="number" step="any" class="validate" value="<?php echo $data["productPhysicalInfo"]["width"];?>"/>
       <label><?php echo $Core->Translator->translate("Width");?></label>
    </div>
    <div class="col s12 m3 input-field">
       <input id="height" type="number" step="any" class="validate" value="<?php echo $data["productPhysicalInfo"]["height"];?>"/>
       <label><?php echo $Core->Translator->translate("Height");?></label>
    </div>
    <div class="col s12 m3 input-field">
       <input id="depth" type="number" step="any" class="validate" value="<?php echo $data["productPhysicalInfo"]["depth"];?>"/>
       <label><?php echo $Core->Translator->translate("Depth");?></label>
    </div>
    <div class="col s12 m3 input-field">
        <select id="distanceSystem">
            <option value="cm" <?php if($data["productPhysicalInfo"]["distanceSystem"] == "cm") echo "selected";?>><?php echo $Core->Translator->translate("Centimeters");?></option>
            <option value="m" <?php if($data["productPhysicalInfo"]["distanceSystem"] == "m") echo "selected";?>><?php echo $Core->Translator->translate("Meters");?></option>
            <option value="in" <?php if($data["productPhysicalInfo"]["distanceSystem"] == "in") echo "selected";?>><?php echo $Core->Translator->translate("Inches");?></option>
            <option value="ft" <?php if($data["productPhysicalInfo"]["distanceSystem"] == "ft") echo "selected";?>><?php echo $Core->Translator->translate("Feet");?></option>
        </select>
        <label><?php echo $Core->Translator->translate("Length Measurement");?></label>
    </div>
    <?php if($data["activeVariations"]){?>
    <div class="col s12">
        <h6><?php echo $Core->Translator->translate("Variations:"); ?></h6>
        <ul class="collapsible">
            <?php
            foreach($data["activeVariations"] as $v){
            ?>
            <li class="collection-item">
                <?php
                foreach($v["descriptions"] as $d){
                if($d["default"] == 1){
                ?>
                <div class="collapsible-header"><h6><?php echo $d["title"]?></h6></div>
                <div class="collapsible-body">
                    <div style="display:grid">
                        <div class="row">
                            <div class="col m6 s12 input-field">
                               <input id="weight_<?php echo $v["info"]["id"]?>" type="number" step="any" class="validate"/>
                               <label><?php echo $Core->Translator->translate("Weight");?></label>
                            </div>
                            <div class="col m6 s12 input-field">
                                <select id="weightSystem_<?php echo $v["info"]["id"]?>">
                                    <option value="kg"><?php echo $Core->Translator->translate("Kilograms");?></option>
                                    <option value="g"><?php echo $Core->Translator->translate("Grams");?></option>
                                    <option value="lb"><?php echo $Core->Translator->translate("Pounds");?></option>
                                    <option value="oz"><?php echo $Core->Translator->translate("Ounce");?></option>
                                </select>
                                <label><?php echo $Core->Translator->translate("Weight Measurement");?></label>
                            </div>
                            <div class="col s12 m3 input-field">
                               <input id="width_<?php echo $v["info"]["id"]?>" type="number" step="any" class="validate"/>
                               <label><?php echo $Core->Translator->translate("Width");?></label>
                            </div>
                            <div class="col s12 m3 input-field">
                               <input id="height_<?php echo $v["info"]["id"]?>" type="number" step="any" class="validate"/>
                               <label><?php echo $Core->Translator->translate("Height");?></label>
                            </div>
                            <div class="col s12 m3 input-field">
                               <input id="depth_<?php echo $v["info"]["id"]?>" type="number" step="any" class="validate"/>
                               <label><?php echo $Core->Translator->translate("Depth");?></label>
                            </div>
                            <div class="col s12 m3 input-field">
                                <select id="distanceSystem_<?php echo $v["info"]["id"]?>">
                                    <option value="cm"><?php echo $Core->Translator->translate("Centimeters");?></option>
                                    <option value="m"><?php echo $Core->Translator->translate("Meters");?></option>
                                    <option value="in"><?php echo $Core->Translator->translate("Inches");?></option>
                                    <option value="ft"><?php echo $Core->Translator->translate("Feet");?></option>
                                </select>
                                <label><?php echo $Core->Translator->translate("Length Measurement");?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }}
                ?>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <?php } ?>
    <div class="col s12">
        <h6><?php echo $Core->Translator->translate("Transportation options:"); ?></h6>
        <ul class="collection">
            <?php
            foreach ($data["inShops"] as $shop){?>
            <li class="collection-item valign-wrapper">
                <div class="col s1">
                    <input type="checkbox" id="transportationShop_<?php echo $shop["id"]?>" value="<?php echo $shop["id"]?>" class="shopHasTransportation" checked="true" onclick="checkTransportOptionShop($(this))"/>
                    <label for="transportationShop_<?php echo $shop["id"]?>"><span></span></label>
                </div>
                <div class="col s11">
                    <div class="col s12">
                        <ul class="collection" style="padding:0;margin:0;border:none;">
                            <li class="collection-item avatar" style="background:none;border-color:#fff;">
                                    <img src="<?php echo $shop["logo"]?>" alt="" class="circle">
                                    <span class="title"><?php echo $shop["name"];?></span>
                                    <p><?php echo $shop["address"]["googleString"]?></p>
                                    <div class="ishidden" id="transportOptionInformationShop_<?php echo $shop["id"]?>">
                                        <p class="grey-text"><?php echo $Core->Translator->translate("Sold only at the physical store. (No delivery)")?></p>
                                    </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12">
                        <ul class="collapsible category-ul" data-collapsible="accordion" id="transportOptionsShop_<?php echo $shop["id"]?>">
                            <li>
                              <div class="collapsible-header category-li grey-text"><i class="material-icons">tune</i><?php echo $Core->Translator->translate("Options");?><br><br></div>
                              <div class="collapsible-body category-li">
                                    <div style="display:grid">
                                       <ul class="collection">
                                            <li class="collection-item" style="background:none;border-color:#fff;">
                                                <div class="col s12 m4">
                                                    <span class="title"><?php echo $Core->Translator->translate("Default Product")?></span>
                                                </div>
                                                <div class="col s6 m4">
                                                    <!-- <?php if(!empty(array_filter($data["WholeProcuct"]["Transport"] ?? [], fn($item) => $item["variation_id"] == 0 && $item["hasTransportation"] == 1))) echo "checked"; ?> -->
                                                    <input type="checkbox" id="transportationVariationShop_<?php echo $shop["id"]?>_0" data-variation-id="0" class="transportationShop_<?php echo $shop["id"]?>" value="0" checked onclick="checkTransportationAndStore($(this),'<?php echo $shop["id"]?>')"/>
                                                    <label for="transportationVariationShop_<?php echo $shop["id"]?>_0"><span><?php echo $Core->Translator->translate("Delivery + In Store"); ?></span></label>
                                                </div>
                                                <div class="col s6 m4">
                                                    <input type="checkbox" id="transportationOnlyVariationShop_<?php echo $shop["id"]?>_0"  data-variation-id="0" class="transportationOnlyShop_<?php echo $shop["id"]?>" value="0" onclick="checkTransportationOnly($(this),'<?php echo $shop["id"]?>')"/>
                                                    <label for="transportationOnlyVariationShop_<?php echo $shop["id"]?>_0"><span><?php echo $Core->Translator->translate("Delivery Only"); ?></span>
                                                    </label>
                                                </div>
                                            </li>
                                        <?php
                                        $varInShop = json_decode($data["variationInShops"],true);
                                        foreach($data["activeVariations"] as $v){
                                            $key = array_search($v["info"]["id"], array_column($varInShop, "varId"));
                                            if(in_array($shop["id"],$varInShop[$key]["varInShops"])){
                                        ?>
                                            <li class="collection-item transportVariation_<?php echo $v["info"]["id"]?>" style="background:none;border-color:#fff;">
                                                <div class="col s12 m4">
                                                    <?php
                                                    foreach($v["descriptions"] as $d){
                                                    if($d["default"] == 1){
                                                    ?>
                                                    <span class="title"><?php echo $d["title"]?></span>
                                                    <span class="grey-text"><?php echo $d["description"]?></span>
                                                    <?php
                                                    }}
                                                    ?>
                                                </div>
                                                <div class="col s12 m4">
                                                    <input type="checkbox" id="transportationVariationShop_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?>"  data-variation-id="<?php echo $v["info"]["id"]?>" class="transportationShop_<?php echo $shop["id"]?> transportVariationInput_<?php echo $v["info"]["id"]?>" value="<?php echo $v["info"]["id"]?>" checked="true" onclick="checkTransportationAndStore($(this),'<?php echo $shop["id"]?>')"/>
                                                    <label for="transportationVariationShop_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?>"><span><?php echo $Core->Translator->translate("Delivery + In Store"); ?></span></label>
                                                </div>
                                                <div class="col s12 m4">
                                                    <input type="checkbox" id="transportationOnlyVariationShop_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?>"  data-variation-id="<?php echo $v["info"]["id"]?>" class="transportationOnlyShop_<?php echo $shop["id"]?> transportVariationInput_<?php echo $v["info"]["id"]?>" value="<?php echo $v["info"]["id"]?>"  onclick="checkTransportationOnly($(this),'<?php echo $shop["id"]?>')"/>
                                                    <label for="transportationOnlyVariationShop_<?php echo $shop["id"]?>_<?php echo $v["info"]["id"]?>"><span><?php echo $Core->Translator->translate("Delivery Only"); ?></span></label>
                                                </div>
                                            </li>
                                        <?php
                                            }
                                        }
                                       ?>
                                       </ul>
                                    </div>
                                  </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </li>
            <?php }?>
        </ul>
    </div>
</div>
<?php
}else{
?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product needs a category in order to show up on your shop.");?><br/>
        </p>
    </li>
</ul>
<?php } ?>
