<?php
$Core = $data["Core"];
if(!empty($data["inShops"])){
$languages = $Core->Translator->getLanguages();?>
<div>
    <div class="col s12 input-field">
        <select id="inputVariationLanguage" onchange="variationLanguage()">
            <?php 
            foreach($languages as $lang){
                $default = $lang["code"] == $Core->Translator->lang;
            ?>
            <option value="<?php echo $lang["code"]?>" <?php if($default){ echo "selected";}?>><?php echo $Core->Translator->translate($lang["name"]);?></option>
            <?php
            }
            ?>
            
        </select>
        <label><?php echo $Core->Translator->translate("Language");?></label>
    </div>
    <?php
    foreach ($languages as $lang){
        $default = $lang["code"] == $Core->Translator->lang;
    ?>
    <div id="containerVariation_<?php echo $lang["code"]?>" class="containerVariations" <?php if($default){ echo"style='display:block'";}?>>
        <div class="col s12">
            <input id="inputVariationDefault_<?php echo $lang["code"]?>" type="radio" class="radioVariationDefault" <?php if($default){ echo"checked";}?>name="variationDefault" value="<?php echo $lang["code"]?>"/>
            <label for="inputVariationDefault_<?php echo $lang["code"]?>"><span><?php echo $Core->Translator->translate("Default Language");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this language if selected language by the user is not translated");?>">help_outline</i></span>
            </label>    
        </div>
        <div class="col m6 s12 input-field">
            <input id="inputVariationTitle_<?php echo $lang["code"]?>" type="text" class="variationName" data-lang="<?php echo $lang["code"]?>" data-lang-id="<?php echo $lang["id"];?>"/>
            <label><?php echo $Core->Translator->translate("Name Variation");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
            <p class="grey-text"><?php echo $Core->Translator->translate("Exp.: 50cm or Black or Size M and so on.");?></p>
        </div>
        <div class="col m6 s12 input-field">
            <input id="inputVariationDescription_<?php echo $lang["code"]?>" class="variationDescription" type="text"/>
            <label><?php echo $Core->Translator->translate("Description Variation");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
        </div>
        <div class="col s12 input-field">
            <button type="button" class="btn-flat" onClick="addVariation()"><?php echo $Core->Translator->translate("Create Variation")?></button>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<?php
if($data["Variations"]){?>
<ul class="collection">
    <?php
    foreach($data["Variations"] as $variation){
        $variSelected = false;
       if(isset($data["selectedVariations"])){
           if(in_array($variation["info"]["id"],$data["selectedVariations"])){ 
               $variSelected = true;
               
           } 
        }
    ?>
    <li class="collection-item valign-wrapper">
        <div class="col s12">
        <div class="valign-wrapper">
        <div class="col s2">
            <input name="productVariation[]" type="checkbox" value="<?php echo $variation["info"]["id"]?>" id="variation_id_<?php echo $variation["info"]["id"]?>" onclick="productChanged()" <?php if($variSelected == true){ echo "checked";}?> />
            <label for="variation_id_<?php echo $variation["info"]["id"]?>"></label>
        </div>
        <div class="col s10">
            <div class="col m6 s12">
                <p class="grey-text"><b><?php echo $Core->Translator->translate("Variation Title");?></b></p>
                <h5>
                <?php foreach($variation["descriptions"] as $descriptions){
                    if($descriptions["default"] == 1){
                        echo $descriptions["title"];
                    }
                }?>
                </h5>
            </div>
            <div class="col m6 s12">
                <p  class="grey-text"><b><?php echo $Core->Translator->translate("Variation Description");?></b></p>
                <p>
                <?php foreach($variation["descriptions"] as $descriptions){
                    if($descriptions["default"] == 1){
                        echo $descriptions["description"];
                    }
                }?>
                </p>
            </div>
            </div>
            </div>
            <div class="col s12">
              <ul  class="collapsible" data-collapsible="accordion">
                    <li>
                    <div class="collapsible-header"><i class="material-icons">store</i><?php echo $Core->Translator->translate("Show in this shops");?> (<?php echo count($data["inShops"])?> <?php echo $Core->Translator->translate("Available")?>)<br><br></div>
                        <div class="collapsible-body">
                            <div style="display:grid">
                            <?php
                            foreach($data["inShops"] as $shop){
                                $shopSelected = false;
                                if($variSelected && isset($data["selectedVariationsShop"])){
                                    foreach($data["selectedVariationsShop"] as $s){
                                        $match = json_decode(str_replace("'",'"',$s));
                                        if($match[0] == $variation["info"]["id"] && $match[1] == $shop["id"]){
                                            $shopSelected = true;
                                        }
                                    }
                                }
                            ?>
                                <div class="col s12">
                                    <div class="col s1">
                                      <input type="checkbox" name="variationIsInShop[]" data-shop-id="<?php echo $shop["id"];?>" value="['<?php echo $variation["info"]["id"];?>','<?php echo $shop["id"];?>']" class="variationInshop_<?php echo $variation["info"]["id"]?>" id="variation_<?php echo $variation["info"]["id"]?>_shop_<?php echo $shop["id"];?>" onClick="variationShopClicked(<?php echo $variation["info"]["id"]?>)" <?php if($shopSelected){ echo "checked";}?>/>
                                      <label for="variation_<?php echo $variation["info"]["id"]?>_shop_<?php echo $shop["id"];?>"></label>
                                    </div>
                                    <div class="col s11">
                                        <div class="chip">
                                          <img src="<?php echo $shop["logo"]?>" alt="<?php echo $shop["name"]?>">
                                          <?php echo $shop["name"];?> (<?php echo $shop["address"]["googleString"]?>)
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </li>
    <?php
    }
    ?>
</ul>
<?php
}
?>
<script>
$('.radioVariationDefault').change(function() {
  $('.radioradioVariationDefault').not(this).prop('checked', false);
});

function variationLanguage(){
    var val = $("#inputVariationLanguage").val();
    
    $(".containerVariations").hide();
    $("#containerVariation_"+val).fadeIn();
}
function variationShopClicked(vId){
    // check if variation should be checked
    updateVariationsOnSections();
    var isActive = false;
    var values = $(".variationInshop_"+vId).map(function(){
        if($(this).prop("checked")){
            isActive = true;
            return $(this).val();
        }
    }).get();
    
    if(isActive && $("#variation_id_"+vId).prop("checked") == false){
        $("#variation_id_"+vId).prop("checked",true);
        productChanged();
    }
    
    if(isActive == false && $("#variation_id_"+vId).prop("checked") == true){
        $("#variation_id_"+vId).prop("checked",false);
        productChanged();
    }
    
}
function addVariation(){

    var defaultLang = $("input[name='variationDefault']:checked").val();
    var variations = $(".variationName").map(function(){

            
                var lang = $(this).data("lang");
                var isDefault = 0;
                if(lang == defaultLang){
                    isDefault = 1;
                }
                
                if(isDefault == true && $(this).val() == ""){
                    alert("<?php echo $Core->Translator->translate("Default language has no variation name!");?>");
                    throw new Error();
                }else{
                    if($(this).val() != ""){

                        var data = {
                            title: $(this).val(),
                            description: $("#inputVariationDescription_"+lang).val(),
                            langId: $(this).data("lang-id"),
                            isDefault: isDefault
                        };
                        return data;
                    }
                    
                }
            
        
    }).get();
    

    
    var objectString = JSON.stringify(variations);
    
    var sendData = {
        variations: objectString,
        shops:getShops()
    };
 

      $.ajax({
        url: "/merchant/addVariation",
        data: sendData,
        type: 'POST',
        success: function (data) {

            $("#load_product_Variation").html(data);
            $('.collapsible').collapsible();
            $('.tooltipped').tooltip();
            $('select').material_select();
        },
                error: function(e){
                       // console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
    });
    
    
}
$('.collapsible').collapsible();
$('.tooltipped').tooltip();
</script>
<?php } else {?>
<ul class="collection">
    <li class="collection-item avatar">
    <i class="material-icons circle yellow black-text">priority_high</i>
    <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
    <p><?php echo $Core->Translator->translate("Your product needs a category in order to show up on your shop.");?><br/>
    </p>
</li>
</ul>
<?php }?>
