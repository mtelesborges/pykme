<?php
$Core = $data["Core"];
$Bundle = $data["bundle"];
$languages = $Core->Translator->getLanguages();


?>
<?php
if(!empty($Bundle["hasOptions"])){?>
<ul class="collection">
   <?php
   foreach($Bundle["hasOptions"] as $o){

      $title;
      $price;
      $currency_id;
       foreach($o["description"] as $d){
           if($d["default"] == 1){
               $title = $d["title"];
           }
       }
       foreach($o["prices"] as $p){
           if($p["isdefault"] == 1){
               $price = $p["price"];
               $curreny_id = $p["currency_id"];
           }
       }
   ?>
    <li class="collection-item col m6 s12" style="background:none"><div><?php echo $title;?><a href="#!" class="secondary-content"><?php echo $price;?></a></div></li>
   <?php
   }
   ?>
</ul>
<?php }else{?>
<p class="grey-text"><?php echo $Core->Translator->translate("This bundle has no options and will no be displayed. Please add an option.");?></p>
<?php
}
?>
<ul class="collapsible"  data-collapsible="accordion">
<li>
<div class="collapsible-header">
<h6><i class="material-icons left">
add_circle_outline
</i><?php echo $Core->Translator->translate("Add Option");?></h6>
</div>
<div class="collapsible-body">
<div style="display:grid">
 <div class="col s12 input-field">
        <select onchange="optionLanguage(<?php echo $Bundle["info"]["id"]?>,$(this))">
            <?php 
            foreach($languages as $lang){
                if($lang["code"] == $Core->Translator->lang){
                    $default = true;
                }else{
                    $default = false;
                }
            ?>
            <option value="<?php echo $lang["id"]?>" <?php if($default == true){ echo "selected";}?>><?php echo $Core->Translator->translate($lang["name"]);?></option>
            <?php
            }
            ?>
            
        </select>
</div>
 <?php
    foreach ($languages as $lang){
        if($lang["code"] == $Core->Translator->lang){
            $default = true;
        }else{
            $default = false;
        }
    
    ?>
<div id="optionLanguage_<?php echo $lang["id"]?>_bundle_<?php echo $Bundle["info"]["id"]?>" class="ishidden optionDescriptionContainer" <?php if($default == true){ echo"style='display:block'";}?>>
    <div class="col s12">
        <input id="optionDefaultLang_<?php echo $lang["id"]?>_bundle_<?php echo $Bundle["info"]["id"]?>" type="radio" <?php if($default == true){ echo "checked";}?> name="optionDefaultLang_bundle_<?php echo $Bundle["info"]["id"]?>" value="<?php echo $lang["id"]?>"/>
        <label for="optionDefaultLang_<?php echo $lang["id"]?>_bundle_<?php echo $Bundle["info"]["id"]?>"><?php echo $Core->Translator->translate("Default Language");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this language if selected language by the user is not translated");?>">help_outline</i></label>    
    </div>
    <div class="col s12 input-field">
       <input type="text" class="optionTitle_<?php echo $Bundle["info"]["id"]?>" data-language="<?php echo $lang["id"]?>"/>
       <label><?php echo $Core->Translator->translate("Option Title");?> (<?php echo $lang["name"]?>)</label>
    </div>
    <div class="col s12 input-field">
        <textarea id="optionsDescription_bundle_<?php echo $Bundle["info"]["id"]?>_lang_<?php echo $lang["id"]?>" class="materialize-textarea optionDescription_<?php echo $Bundle["info"]["id"]?>"></textarea>
        <label><?php echo $Core->Translator->translate("Option Description");?> (<?php echo $lang["name"]?>)</label>
    </div>
</div>
    <?php }?>

<div class="col s12 input-field">
    <select id="optionCurrency_<?php echo $Bundle["info"]["id"]?>" onchange="selectProductOption(<?php echo $Bundle["info"]["id"]?>,$(this))">
        <option disabled selected><?php echo $Core->Translator->translate("Choose currency");?></option>
        <?php
        foreach($data["currency"] as $currency){
        ?>
        <option value="<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate($currency["name"]);?> (<?php echo $currency["code"];?>)</option>
        <?php
        }
        ?>
    </select>
</div>

<?php
foreach($data["currency"] as $currency){
?>
<div style="float:left" class="ishidden containerOptionPrice" id="option_price_<?php echo $currency["id"]?>_bundle_<?php echo $Bundle["info"]["id"]?>">
    <div class="col  s12">
        <input name="optionDefaultCurrency_<?php echo $Bundle["info"]["id"]?>" type="radio" class="optiondefaultCurrency_<?php echo $Bundle["info"]["id"]?>" value="<?php echo $currency["id"]?>" id="optionDefaultCurrency_<?php echo $Bundle["info"]["id"]?>_currency_<?php echo $currency["id"]?>"/>
        <label for="optionDefaultCurrency_<?php echo $Bundle["info"]["id"]?>_currency_<?php echo $currency["id"]?>"><?php echo $Core->Translator->translate("Default Curency");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this currency if selected currency by the customer is not available");?>">help_outline</i></label>
    </div>
    <div class="col s12 input-field">
        <input class="priceOption_<?php echo $Bundle["info"]["id"]?> validate" type="number" step="any" data-currency="<?php echo $currency["id"]?>" data-error="<?php echo $Core->Translator->translate("Please use only numbers")?>"/>
        <label><?php echo $Core->Translator->translate("Price");?> (<?php echo $currency["code"]?>)</label>
    </div>
</div>
<?php } ?>
<div id="foodAllergiesOptions">
    <div class="col s12 input-field" id="selectAllergicsOptions_<?php echo $Bundle["info"]["id"]?>">
        <label for="allergies" class="active"><?php echo $Core->Translator->translate("Allergics in this Option");?></label>
        <select multiple id="allergiesOptions_<?php echo $Bundle["info"]["id"]?>">
            <option disabled selected value=""><?php echo $Core->Translator->translate("Please select allergics");?></option>
            <?php foreach($data["foodAllergies"] as $allergy){?>
            <option value="<?php echo $allergy["id"]?>"><?php echo $Core->Translator->translate($allergy["name"]);?></option>
            <?php }?>
        </select>
    </div>
    <div class="col s12">
        <input type="checkbox" id="noAllergicContentOption_<?php echo $Bundle["info"]["id"]?>" onclick="noAllergicsInOption(<?php echo $Bundle["info"]["id"]?>,$(this))"/>
        <label for="noAllergicContentOption_<?php echo $Bundle["info"]["id"]?>"><?php echo $Core->Translator->translate("This product has no allergic content");?></label>
    </div>
</div>
<div class="col s12">
    <div class="file-field input-field">
        <div class="btn-flat">
            <span><?php echo $Core->Translator->translate("Image"); ?></span>
            <input type="file" id="upload_image_<?php echo $Bundle["info"]["id"]?>" />
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" id="path_image_<?php echo $Bundle["info"]["id"]?>">
        </div>
    </div>
</div>

<div class="col s12">
    <button type="button" class="btn" onclick="createOption(<?php echo $Bundle["info"]["id"]?>)"><?php echo $Core->Translator->translate("Create Option")?></button>
</div>
</div>
</div>
</li>
<script>
 $('.collapsible').collapsible();
$('select').material_select();
function noAllergicsInOption(bundleId,input){

    if(input.prop("checked")){
        $("#selectAllergicsOptions_"+bundleId).fadeOut();
    }else{
        $("#selectAllergicsOptions_"+bundleId).fadeIn();
    }
}
 
</script>

