<?php
$Core = $data["Core"];
?>

<div class="col s12 input-field">
    <select id="priceConditionExpirationShops" multiple>
        <?php
        foreach($data["inShops"] as $shop){
        ?>
        <option selected value="<?php echo $shop["id"]?>"  data-icon="<?php echo $shop["logo"]?>" class="circle left"><?php echo $shop["name"];?><br/><br/><span class="grey-text">(<?php echo $shop["address"]["googleString"];?>)</span></option>   
        <?php
        }
        ?>
    </select>
    <label><?php echo $Core->Translator->translate("Availabe in shops:");?></label>
</div>
<?php
if($data["activeVariations"]){
?>
<div class="col s12 input-field">
   <select id="priceConditionExpirationVariations" multiple>
       <option value="default" selected><?php echo $Core->Translator->translate("Default Product");?></option>
       <?php

       foreach($data["activeVariations"] as $variation){
       ?>
       <option value="<?php echo $variation["info"]["id"]?>"><?php foreach($variation["descriptions"] as $d){
           if($d["default"] == 1){
               echo $d["title"];
           }
       }?></option>
       <?php
       }
       ?>
   </select>
    <label><?php echo $Core->Translator->translate("Product Variation")?></label>
</div>
<?php 
}
?>
<div class="col s12 input-field">
   <input type="number" class="validate" id="priceConditionExpirationAmount"/>
   <label><?php echo $Core->Translator->translate("Days before expiration");?></label>
</div>
<div class="col s12 input-field">
    <select onchange="changeExpirationConditionCurrency($(this))">
        <option selected disabled><?php echo $Core->Translator->translate("Select currency")?></option>
    <?php
    foreach($Core->currencies as $currency){
    ?>
        <option value="<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"]?>)</option>
    <?php
    }
    ?>
    </select>
    <label><?php echo $Core->Translator->translate("Currency");?></label>
</div>
<div class="col s12">
    <?php
    foreach($Core->currencies as $currency){
    ?>
    <div class="input-field ishidden expirationConditionPriceContainer" id="expirationConditionPrice_<?php echo $currency["id"]?>">
        <input class="expirationConditionPrice validate" type="number" step="any" data-currency="<?php echo $currency["id"]?>" min="0"/>
        <label><?php echo $Core->Translator->translate("Amount in");?> <?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"];?>)</label>
        <p class="grey-text"><?php echo $Core->Translator->translate("Amount to <b>substract</b> from normal price, not the actual price!");?></p>
    </div>
    <?php
    }
    ?>
</div>
<div class="col s12 input-field">
    <button type="button" class="btn-flat" onclick="createExpirationCondition()"><?php echo $Core->Translator->translate("Create Expiration Condition");?></button>
</div>






<?php
if($data["priceConditions"]){
?>
<div class="col s12">
        <h6 class="grey-text"><?php echo $Core->Translator->translate("Select Expiration Conditions");?></h6>
        <ul class="collection">
            <?php
            $hasCondition = false;
            foreach($data["priceConditions"] as $condition){ 
                if($condition["type"] == "Expiration"){
                    $hasCondition = true;
                    $object = unserialize($condition["object"]);
                ?>
            <li class="collection-item valign-wrapper">
                <div class="col s2">
                    
                    <input name="priceConditions[]" type="checkbox" id="priceConditionById_<?php echo $condition["id"]?>" value="<?php echo $condition["id"]?>"/>
                    <label for="priceConditionById_<?php echo $condition["id"]?>"><span></span>
                    </label>
                </div>
                <div class="col s10">
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe in shops:")?></p>
                        <?php foreach($object["shops"] as $shop_id){
                        ?>
                            <div class="chip">
                                <img src="<?php echo $data["allShops"][$shop_id]["logo"]?>" alt="logo <?php echo $data["allShops"][$shop_id]["name"]?>">
                                <?php echo $data["allShops"][$shop_id]["name"]?> (<?php echo $data["allShops"][$shop_id]["address"]["googleString"]?>)
                            </div>
                            <br/>
                        <?php   
                        }?>
                    </div>
                    <div class="col s12">
                        <p><?php echo $Core->Translator->translate("Availabe for variations:")?></p>
                        <p class="grey-text">
                            <?php
                            
                            foreach($object["variations"] as $variation){
                                if($variation == "default"){
                                    $variation = $Core->Translator->translate("default");
                                }
                                echo "( ".$variation." )  ";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s12 m6">
                        <p><?php echo $Core->Translator->translate("Days before expiration"); ?></p>
                        <p class="grey-text"><?php echo $object["days"];?></p>
                    </div>
                    <div class="col s12 m6">
                       <p><?php echo $Core->Translator->translate("Subtract amount"); ?></p>
                        <?php
                        foreach($object["prices"] as $price){
                        ?>
                        <p class="grey-text"><?php echo "- ".$price["price"];?> (<?php $cur = array_search($price["currency_id"],array_column($Core->currencies,"id"));echo $Core->currencies[$cur]["code"];?>)</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </li>
            <?php }}?>
            <?php
            if($hasCondition == false){?>
            <p><?php echo $Core->Translator->translate("Please create a inventory condition.");?></p>
            <?php
            }
            ?>
        </ul>
<?php } ?>