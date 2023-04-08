<?php
$Core = $view["Core"];
$data= $view["Data"];
$data["Core"] = $Core;
?>

<div class="container">
	<div class="row">
	<form action="/merchant/addProduct" method="post" id="addProduct" enctype="multipart/form-data">
        <div class="col m9 s12">
            <h3><?php echo $Core->Translator->translate("Edit Product"); ?></h3>
            <section id="productInfo" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">info</i><?php echo $Core->Translator->translate("Basic Information");?><span class="formRequired black-text">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
                 <?php echo "<pre>";echo json_encode($data["WholeProduct"]);echo "</pre>";?>
                <div class="input-field col s12">
                    <select name="lang[]" id="infoLang">
                        <?php
                        $langs = $this->Core->Translator->getLanguages();
                        foreach($langs as $lang){  
                            ?>
                        <option value="<?php echo $lang["code"]?>" <?php foreach($data["WholeProduct"]["Descriptions"] as $description){ if($description["default"] == 1 && $description["lang_id"] == $lang["id"]){echo "selected";}}?> ><?php echo $Core->Translator->translate($lang["name"]);?> (<?php echo $lang["local_name"]?>)</option>
                        <?php } ?>
                    </select>
                    <label for="lang[]"><?php echo $Core->Translator->translate("Language");?></label>
                </div>
                <?php
                foreach($langs as $lang){
                ?>
                <div id="info_<?php echo $lang["code"]?>"  <?php foreach($data["WholeProduct"]["Descriptions"] as $description){ if($description["default"] == 1 && $description["lang_id"] == $lang["id"]){?> style="display:block"<?php }}?> class="productInfo">
                    <div class="col s12">
                        
                        <input name="defaultInfoLang" class="with-gap" value="<?php echo $lang["id"]?>" <?php foreach($data["WholeProduct"]["Descriptions"] as $description){ if($description["default"] == 1 && $description["lang_id"] == $lang["id"] ){ ?>checked="checked"<?php }}?> type="radio" id="defaultInfoL_<?php echo $lang["id"]?>"/>
                        <label for="defaultInfoL_<?php echo $lang["id"]?>"><span><?php echo $Core->Translator->translate("Default Language");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this language if selected language by the customer is not translated");?>">help_outline</i></span>
                        </label>
                    </div>
                    <div class="input-field col s12">
                        <label for="name_<?php echo $lang["id"]?>"><?php echo $Core->Translator->translate("Product Name");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                        <input name="name_<?php echo $lang["id"]?>" data-lang="<?php echo $lang["id"]?>" type="text" class="productName validate" <?php foreach($data["WholeProduct"]["Descriptions"] as $description){ if($description["lang_id"] == $lang["id"] ){ ?>value="<?php echo $description["title"];?>"<?php }}?>/>
                    </div>
                    <div class="input-field col s12">
                        <label for="description_<?php echo $lang["id"]?>"><?php echo $Core->Translator->translate("Product Description");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                        <textarea id="description_<?php echo $lang["id"]?>" data-lang="<?php echo $lang["id"]?>" class="materialize-textarea productDescription validate"><?php foreach($data["WholeProduct"]["Descriptions"] as $description){ if($description["lang_id"] == $lang["id"] ){ ?><?php echo $description["description"];?><?php }}?></textarea>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="col s12 input-field">
                    <label for="productType" class="active"><?php echo $Core->Translator->translate("Product Type");?></label>
                    <select id="productType" class="validate">
                        <option disabled selected value=""><?php echo $Core->Translator->translate("Please select product type");?></option>
                        <?php 
                        foreach($data["productType"] as $type){
                        ?>
                        <option value="<?php echo $type["id"]?>"  <?php if($data["WholeProduct"]["Info"]["type_id"] == $type["id"]){ echo "selected";}?>><?php echo $Core->Translator->translate($type["name"]);?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div id="ifBeverage" style="display: none">
                    <div class="col s6 input-field ">
                        <label for="content"><?php echo $Core->Translator->translate("Content Amount");?></label>
                        <input type="number" id="contentBevarage" class="validate" min="0" step="any"/>
                    </div>
                    <div class="col s6 input-field ">
                        <select id="contentScalaType">
                            <option value="l"><?php echo $Core->Translator->translate("Liters (l)")?></option>
                            <option value="dl"><?php echo $Core->Translator->translate("Deciliters (dl)")?></option>
                            <option value="cl"><?php echo $Core->Translator->translate("Centiliters (cl)")?></option>
                        </select>
                    </div>
                </div>
                <div id="ifCalories" <?php if($data["WholeProduct"]["Info"]["type_id"] == 3 || $data["WholeProduct"]["Info"]["type_id"] == 4 ){?>style="display: none"<?php }?>>
                    <div class="col s12 input-field ">
                        <label for="calories"><?php echo $Core->Translator->translate("Calories");?></label>
                        <input type="number" id="calories" value="<?php echo $data["WholeProduct"]["AdditionalInfo"]->contentCalories;?>"/>
                    </div>
                </div>
                <div id="productPropeties" class="col s12 input-field" <?php if($data["WholeProduct"]["Info"]["type_id"] == 1 || $data["WholeProduct"]["Info"]["type_id"] == 2 ){?>style="display: block"<?php }?>>
                    <select id="selectProductPropeties" multiple class="validate">
                        <option disabled ><?php echo $Core->Translator->translate("Please select propeties");?></option>
                        <?php
                        foreach($data["productProperties"] as $property){
                        ?>
                            <option value="<?php echo $property["id"]?>" <?php foreach($data["WholeProduct"]["Properties"] as $prop){if($prop["id"] == $property["id"]){ echo "selected='true'";}}?>><?php echo $Core->Translator->translate($property["name"]);?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <label><?php echo $Core->Translator->translate("Properties");?></label>
                </div>
                <div id="ifMedical" class="ishidden" <?php if($data["WholeProduct"]["Info"]["type_id"] == 3){?>style="display: block"<?php }?>>
                    <div class="col s12">
                        
                        <input name="needPrescription" id="needPrescription" type="checkbox"/>
                        <label for="needPrescription"><span><?php echo $Core->Translator->translate("Check this if doctor prescription is needed for this product.")?></span>
                        </label>
                    </div>
                </div>
                <div id="foodAllergies" <?php if($data["WholeProduct"]["Info"]["type_id"] == 3 || $data["WholeProduct"]["Info"]["type_id"] == 4 || $data["WholeProduct"]["Info"]["has_allergic"] == 0){?>style="display:none"<?php }?>>
                    <div class="col s12 input-field" id="selectAllergics">
                        <label for="allergies" class="active"><?php echo $Core->Translator->translate("Allergics in this Product");?></label>
                        <select multiple id="allergies">
                            <option disabled ><?php echo $Core->Translator->translate("Please select allergenics");?></option>
                            <?php foreach($data["foodAllergies"] as $allergy){?>
                            <option value="<?php echo $allergy["id"]?>" <?php foreach($data["WholeProduct"]["Allergies"] as $a){if($a["id"] == $allergy["id"] ){echo "selected='true'";}}?>><?php echo $Core->Translator->translate($allergy["name"]);?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col s12">
                        
                        <input type="checkbox" id="noAllergicContent" <?php if($data["WholeProduct"]["Info"]["has_allergic"] == 0){echo "checked";}?>/>
                        <label for="noAllergicContent"><span><?php echo $Core->Translator->translate("This product has no allergic content");?></span>
                        </label>
                    </div>
                </div>
            </section>
            <section id="productCategory" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">list</i><?php echo $Core->Translator->translate("Shop Category");?><span class="formRequired black-text">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
                <?php if(!empty($data["productCategories"])){?>
                <div class="col s12" class="productGreyBox">
                    <div class="col m6 s12 input-field">
                        <input type="text" id="searchCategoryName" onKeyUp="searchCategories()"/>
                        <label><?php echo $Core->Translator->translate("Search Category");?></label>
                    </div>
                    <div class="col m6 s12 input-field">
                        <!-- Filter by shop => $s -->
                        <select  onChange="searchCategories()" id="searchCategoryShop">
                            <option value="0"><?php echo $Core->Translator->translate("All Shops");?></option>
                        <?php
                        $availableShops = array();
                        foreach($data["productCategories"] as $pc){
                            foreach($pc["inShops"] as $shop){
                                foreach($shop as $s){
                                $availableShops[$s["info"]["id"]] = $s;
                                }
                            } 
                        }
                        foreach($availableShops as $s){
                        ?>
                            <option value="<?php echo $s["info"]["id"]?>"><?php echo $s["info"]["name"]?></option>
                        <?php
                        }
                        ?>

                        </select>
                    </div>
                </div>
                <?php }?>
                <div id="load_productCategory"  class="scrollspy section">
                    <?php $Core->FrontController->partialRender("product-category-list.php",$data);?>
                </div>
            </section>
            <section id="productVariation" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">bubble_chart</i><?php echo $Core->Translator->translate("Product Variation");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5>
                <div id="load_product_Variation">
                    <?php $Core->FrontController->partialRender("product-variation.php",$data);?>
                </div>
            </section>
            <section id="transportation"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">local_shipping</i><?php echo $Core->Translator->translate("Transportation");?><span class="formRequired black-text">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
                <p class="grey-text"><?php echo $Core->Translator->translate("Please give us information about the size and weight when product is in a package (size and weight of package with product inside).");?></p>
                <div id="load_product_Transportation">
                    <?php $Core->FrontController->partialRender("product-transportation.php",$data);?>
                </div>
            </section>
            <section id="productPreparation" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">timer</i><?php echo $Core->Translator->translate("Preparation Time");?><span class="formRequired black-text">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
                <p class="grey-text"><?php echo $Core->Translator->translate("After the order is accepted by the store, how long does it usually take for this product to be ready for delivery or consumption?");?></p>
                <div id="load_product_Preparation">
                    <?php $Core->FrontController->partialRender("product-preparation-time.php",$data);?>
                </div>
            </section>
            <section id="productPrice"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">attach_money</i><?php echo $Core->Translator->translate("Price & Inventory");?><span class="formRequired black-text">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
                <div id="load_product_pricelist">
                <?php $Core->FrontController->partialRender("product-price-list.php",$data);?>
                </div>
            </section>
            <section id="productImages" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">photo_camera</i><?php echo $Core->Translator->translate("Product Images");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5>
                <div id="load_product_Images">
                    <?php $Core->FrontController->partialRender("product-images.php",$data);?>
                </div>
            </section>
            <section id="productOptions" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">group_work</i><?php echo $Core->Translator->translate("Product Options");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5>
                <div id="load_product_Options">
                    <?php $Core->FrontController->partialRender("product-options.php",$data);?>
                </div>
                <a class="btn-flat modal-trigger" href="#modalProductOptions"><?php echo $Core->Translator->translate("Create Options");?></a>
                <!-- Modal Options -->
                <div id="modalProductOptions" class="modal">
                  <div class="modal-content" id="load_productOptionsModal">
                      <?php $Core->FrontController->partialRender("product-modal-productOptions.php",$data);?>
                  </div>
                  <div class="modal-footer">
                  <a href="#!" class=" modal-action modal-close waves-effect waves-green btn red"><?php echo $Core->Translator->translate("Close");?></a>
                </div>
                </div>
            </section>
            <section id="productOrderOptions" class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">shopping_cart</i><?php echo $Core->Translator->translate("Order Options");?><span class="formRequired grey-text">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5>
                <p class="grey-text"><?php echo $Core->Translator->translate("Choose order options before user can purchase your product.");?></p>
                <div id="load_product_orderOptions">
                <?php $Core->FrontController->partialRender("product-order-options.php",$data);?>
                </div>
            </section>
            <section id="productExpiryDate"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">event_busy</i><?php echo $Core->Translator->translate("Expiry Date");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5> 
                <div id="load_product_expiryDate">
                    <?php $Core->FrontController->partialRender("product-expiry-date.php",$data); ?>
                </div>
            </section>
            <section id="productPriceConditions"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">price_check</i><?php echo $Core->Translator->translate("Price Conditions");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5> 
                <div id="load_product_priceConditions">
                   <?php $Core->FrontController->partialRender("product-price-conditions.php",$data); ?> 
                </div>
            </section>
            <section id="productRestrictions"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">pan_tool</i><?php echo $Core->Translator->translate("Product Restrictions");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5> 
                <div id="load_product_Restrictions">
                    <?php $this->Core->FrontController->partialRender("product-restrictions.php",$data);?>
                </div>
            </section>
            <section id="productCrossSelling"  class="scrollspy section">
                <h5 class="h5divider"><i class="material-icons left">compare_arrows</i><?php echo $Core->Translator->translate("Cross-Selling");?><span class="formRequired">(<?php echo $Core->Translator->translate("Optional");?>)</span></h5> 
                <div id="load_product_CrossSelling">
                    <?php $this->Core->FrontController->partialRender("product-crossselling.php",$data);?>
                </div>
            </section>
            <div class="col s12 input-field">
                <button type="button" class="waves-effect waves-light btn" onclick="addProduct()"><i class="material-icons right">arrow_forward</i><?php echo $Core->Translator->translate("Create Product");?></button>
            </div>
        </form>
        </div>
        <div class="col hide-on-small-only m3 s12">
            <div class="target">
                <ul class="section table-of-contents" style="max-width: 100%">
                    <li><a href="#productInfo"><i class="material-icons left tiny iconSpy">info</i><?php echo $Core->Translator->translate("Basic Information");?></a></li>
                    <li><a href="#productCategory"><i class="material-icons left tiny iconSpy">list</i><?php echo $Core->Translator->translate("Shop Category");?></a></li>
                    <li><a href="#productVariation"><i class="material-icons left tiny iconSpy">bubble_chart</i><?php echo $Core->Translator->translate("Product Variation");?></a></li>
                    <li><a href="#transportation"><i class="material-icons left tiny iconSpy">local_shipping</i><?php echo $Core->Translator->translate("Transportation");?></a></li>
                    <li><a href="#productPreparation"><i class="material-icons left tiny iconSpy">timer</i><?php echo $Core->Translator->translate("Preparation Time");?></a></li>
                    <li><a href="#productPrice"><i class="material-icons left tiny iconSpy">attach_money</i><?php echo $Core->Translator->translate("Price & Inventory");?></a></li>
                    <li><a href="#productImages"><i class="material-icons left tiny iconSpy">photo_camera</i><?php echo $Core->Translator->translate("Product Images");?></a></li>
                    <li><a href="#productOptions"><i class="material-icons left tiny iconSpy">group_work</i><?php echo $Core->Translator->translate("Product Options");?></a></li>
                    <li><a href="#productOrderOptions"><i class="material-icons left tiny iconSpy">shopping_cart</i><?php echo $Core->Translator->translate("Order Options");?></a></li>
                    <li><a href="#productExpiryDate"><i class="material-icons left tiny iconSpy">event_busy</i><?php echo $Core->Translator->translate("Expiry Date");?></a></li>
                    <li><a href="#productPriceConditions"><i class="material-icons left tiny iconSpy">price_check</i><?php echo $Core->Translator->translate("Price Conditions");?></a></li>
                    <li><a href="#productRestrictions"><i class="material-icons left tiny iconSpy">pan_tool</i><?php echo $Core->Translator->translate("Product Restrictions");?></a></li>
                    <li><a href="#productCrossSelling"><i class="material-icons left tiny iconSpy">compare_arrows</i><?php echo $Core->Translator->translate("Cross-Selling");?></a></li>
                    <input type="hidden" id="mId" value="<?php echo $_SESSION["merchant"]["merchantId"]?>"/> 
                </ul>
            </div>
        </div>
    </div>
</div>




  <!-- Modal Create Category -->
  <div id="modalAddCategory" class="modal">
    <form id="newCategory">
    <div class="modal-content">
      <h4><?php echo $Core->Translator->translate("Create Shop Category");?></h4>
      	<div class="row">
            <h5 class="h5divider"><?php echo $Core->Translator->translate("Basic Information");?><span class="formRequired">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
            <div class="col s12 input-field">
                <select id="categoryLanguage">
                    <?php 
                    foreach($langs as $lang){?>
                            <option value="<?php echo $lang["code"]?>" <?php if($lang["code"] == $Core->Translator->lang){ echo "selected";}?>><?php echo $Core->Translator->translate($lang["name"]);?> (<?php echo $lang["local_name"]?>)</option>
                    <?php } ?>
                </select>
            </div>
            <?php
            foreach($langs as $lang){
            ?>
            <div id="categoryInfo_<?php echo $lang["code"]?>"  <?php if($lang["code"] == $Core->Translator->lang){?>style="display:block"<?php }?> class="categoryInfo">
                <div class="col s12">
                    
                    <input name="defaultLang" class="with-gap" value="<?php echo $lang["code"]?>" <?php if($lang["code"] == $Core->Translator->lang){?>checked="checked"<?php }?> type="radio" id="defaultL_<?php echo $lang["code"]?>"/>
                    <label for="defaultL_<?php echo $lang["code"]?>"><span><?php echo $Core->Translator->translate("Default Language");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this language if selected language by the customer is not translated");?>">help_outline</i></span>
                    </label>
                </div>
                <div class="input-field col s12">
                    <label for="categoryName_<?php echo $lang["code"]?>"><?php echo $Core->Translator->translate("Category Name");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                    <input name="categoryName_<?php echo $lang["code"]?>" type="text" id="categoryName_<?php echo $lang["code"]?>"/>
                </div>
                <div class="input-field col s12">
                    <label for="categoryDescription_<?php echo $lang["code"]?>"><?php echo $Core->Translator->translate("Category Description");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                    <textarea name="categoryDescription_<?php echo $lang["code"]?>" class="materialize-textarea"></textarea>
                </div>
            </div>
            <?php
            }
            ?>
            <h5 class="h5divider"><?php echo $Core->Translator->translate("Shops");?><span class="formRequired">(<?php echo $Core->Translator->translate("Required");?>)</span></h5>
            <p class="grey-text lighten-1"><?php echo $Core->Translator->translate("Please assign the category to at least one shop.");?></p>
            <ul class="collection">
                <?php 
                foreach($data["shops"] as $shop){
                ?>
                <li class="collection-item avatar">
                    <div class="col s10">
                        <img src="<?php echo $shop["logo"]?>" alt="" class="circle">
                        <span class="title"><b><?php echo $shop["name"]?></b></span>
                        <p>
                           <?php echo $shop["address"]["googleString"]?><br>
                           <?php echo $Core->Translator->translate("Status");?>: <?php echo $Core->Translator->translate($shop["status"]);?>
                        </p>
                    </div>
                    <div class="col s2">
                    
                        <input name="shop[]" id="shop_<?php echo $shop["id"];?>" type="checkbox" value="<?php echo $shop["id"];?>"/>
                     <label for="shop_<?php echo $shop["id"];?>">   <span></span>
                    </label>
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
            <?php $this->Core->FrontController->partialRender("product-category-restrictions.php",$data);?>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <div class="errorTxt"></div>
        <a href="#!" class="waves-effect waves-green btn" onClick="addNewCategory()"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Create Shop Category");?></a>

    </div>
  </div>	
		
	
<script>
var validator = $("#addProduct").validate({
        rules: {
            "shop[]":{
                required:true
            },
			
        },
        //For custom messages
        messages: {
            "shop[]":{
                required:'<?php echo $Core->Translator->translate("Please choose at least one shop to assign the category.")?>'
            }
        },
	errorClass: "invalid form-error",
        errorElement : 'div',
        errorLabelContainer:'.errorTxt',
        errorPlacement: function(error, element) {
          var placement = $(element).data('error');
          if (placement) {
            $(placement).append(error)
          } else {
            error.insertAfter(element);
          }
        }
     });    
    
    
    
    
    
    
function checkTransportation(input){
    if(input.prop("checked")){
        $("#checkTransportation").fadeOut();
        $(".onlyPickup").fadeOut();
        $("#onlyDelivery").prop("checked",false);
    }else{
        $("#checkTransportation").fadeIn();
        $(".onlyPickup").fadeIn();
    }
}
function checkOnlyDelivery(input){
    if(input.prop("checked")){
        $(".checkForDelivery").fadeOut();
    }else{
        $(".checkForDelivery").fadeIn();
    }    
}
$.validator.addMethod('hasLang', function(value, element) {
    var title = $("#categoryName_"+value).val();
    if(title == ""){
        return false
    }else{
        return true;
    }
});
var validator = $("#newCategory").validate({
        rules: {
            "shop[]":{
                required:true
            },
            "defaultLang":{
                hasLang: true
            }
			
        },
        //For custom messages
        messages: {
            "shop[]":{
                required:'<?php echo $Core->Translator->translate("Please choose at least one shop to assign the category.")?>'
            },
            "defaultLang":{
                hasLang: '<?php echo $Core->Translator->translate("Default category name missing.")?>'
            }
        },
	errorClass: "invalid form-error",
        errorElement : 'div',
        errorLabelContainer:'.errorTxt',
        errorPlacement: function(error, element) {
          var placement = $(element).data('error');
          if (placement) {
            $(placement).append(error)
          } else {
            error.insertAfter(element);
          }
        }
     });
  
function productChanged(){

    $("#overallLoader").show();

    var shopIds = getShops();
    var variationIds = getVariations(shopIds);

    updateVariations(shopIds,variationIds);
    updateTransportation(shopIds, variationIds);
    updateOrderOptions(shopIds,variationIds);
    getPreparationTime(variationIds);
    getProductImages(variationIds);
    getProductExpiryDate(shopIds,variationIds);
    getPriceConditions(shopIds,variationIds);
    getRestrictions(shopIds,variationIds);
    getOptions(variationIds,shopIds);
    getPriceList(shopIds,variationIds);
    getCrossSelling(shopIds,variationIds);
    $('.collapsible').collapsible();
    $('select').material_select();
    $("#overallLoader").fadeOut();

}
function getCrossSelling(shops,variations){
    var variationInShops = getVariationsOnSections();
    var data = {
        shops:shops,
        activeVariations:variations,
        variationInShops:variationInShops
    };
     $.ajax({
        url: "/merchant/getCrossSelling",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_CrossSelling").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function updateVariationsOnSections(){
    $("#overallLoader").show();
    var shopIds = getShops();
    var variationIds = getVariations(shopIds);
    updateTransportation(shopIds, variationIds)
    getPriceList(shopIds,variationIds);
    updateOrderOptions(shopIds,variationIds);
    $('.collapsible').collapsible();
    $('select').material_select();
    $("#overallLoader").fadeOut();
}
function updateOrderOptions(shops, variations){
    var variationInShops = getVariationsOnSections();
    var data = {
        inShops:shops,
        activeVariations:variations,
        variationInShops:variationInShops
    };
     $.ajax({
        url: "/merchant/showOrderOptions",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_orderOptions").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function updateTransportation(shops, variations){
    
    var variationInShops = getVariationsOnSections();
    var data = {
        inShops:shops,
        activeVariations:variations,
        variationInShops:variationInShops
    };
     $.ajax({
        url: "/merchant/showTransportation",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_Transportation").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function getRestrictions(shops, variations){
    var data = {
        shops:shops,
        variations:variations
    };
     $.ajax({
        url: "/merchant/showProductRestrictions",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            
            $("#load_product_Restrictions").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function getPriceConditions(shops, variations){

    var data = {
        shops:shops,
        variations:variations
    };
     $.ajax({
        url: "/merchant/showPriceConditions",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            
            $("#load_product_priceConditions").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function getOptions(variations,shops){
    var data = {
        variations:variations,
        shops:shops
    };
     $.ajax({
        url: "/merchant/updateProductoptions",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) { 
            $("#load_product_Options").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}
function getProductExpiryDate(shops, variations){

    var data = {
        shops:shops,
        variations:variations
    };
     $.ajax({
        url: "/merchant/getExpiryDate",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            
            $("#load_product_expiryDate").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
}

function getVariationsOnSections(){
    var variations = $("input[name='productVariation[]']:checked").map(function(){
            var varId = $(this).val();

            var shops = $(".variationInshop_"+varId).map(function(){
                if($(this).prop("checked")){
                    return $(this).attr("data-shop-id");
                }
            }).get();
            var object = {
                varId:varId,
                varInShops:shops
            }
            return object;
    });
    return JSON.stringify(variations.get());
}
function getVariations(shopIds){
    
    if(shopIds != ""){
        var values = $("input[name='productVariation[]']").map(function(){
            hasActiveShop = false;
            if($(this).prop("checked")){
                
                $(".variationInshop_"+$(this).val()).map(function(){
                    if($(this).prop("checked")){
                         hasActiveShop = true;
                    }
                });
                if(hasActiveShop == false){
                    $(".variationInshop_"+$(this).val()).prop("checked",true);
                }
                return $(this).val();
            }
        }).get();

        var IDS = [...new Set(values)];
        return IDS;
    }
}
function updateVariations(shops,variationIds){
    
    var sVShops = $("input[name='productVariation[]']").map(function(){
            var hasActive = false;
            if($(this).prop("checked")){
                var ids = $(".variationInshop_"+$(this).val()).map(function(){
                    if($(this).prop("checked")){
                        return $(this).val();
                    }
                }).get();
                
              
                    return ids;   
            }
        }).get();
    var data = {
        shops:shops,
        selectedVariations:variationIds,
        selectedVariationsShop: sVShops
    };
    $.ajax({
        url: "/merchant/getVariations",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            
            $("#load_product_Variation").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                } 
    });
    
}
function getProductImages(variationIds){
	var data = {
        variations: variationIds
    };
    $.ajax({
        url: "/merchant/getProductImages",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_Images").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
    });
}
function getPreparationTime(variationIds){
	var data = {
        variations: variationIds
    };
    $.ajax({
        url: "/merchant/getPreparationTime",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_Preparation").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
    });
}
function shopClicked(catId,shopId){
    var input = $("#category_"+catId+"_shop_"+shopId);
    
    if(input.prop("checked")){
        var inputCat = $("#productCategory_"+catId);
        if(inputCat.prop("checked") === false){
            inputCat.prop("checked",true);
        }
    }
    productChanged();
}
function categoryClicked(catId){
    var cat = $("#productCategory_"+catId);


    if(cat.prop("checked")){
        $(".shopsCategory_"+catId).prop("checked",true);
    }else{
        $(".shopsCategory_"+catId).prop("checked",false);
    }
    
    productChanged();
}
function getShops(){
    var values = $("input[name='category[]']").map(function(){
        if($(this).prop("checked")){
            return $(this).val();
        }
    }).get();
    
    var shops = new Array;
    
    values.forEach(function(catId){
       var hasShops = false;
       $(".shopsCategory_"+catId).map(function(){
            if($(this).prop("checked")){
            shops.push($(this).val());
            hasShops = true;
            }
        });
        if(hasShops == false){
            // if no shops selected uncheck category
            $("#productCategory_"+catId).prop("checked",false);
        }else{
            $("#productCategory_"+catId).prop("checked",true);
        }
    });

    
    
    shopsId = new Array;

    shops.forEach(function(ids){
        var a = ids;
        a = a.replace(/'/g, '"');
        a = JSON.parse(a);
        shopsId.push(a[1]);
    });
    
  
    var IDS = [...new Set(shopsId)];
   
    return IDS;
}

function getPriceList(ids,variations){ 
    var variationInShops = getVariationsOnSections();
    var data = {
        ids: ids,
        variations: variations,
        variationInShops:variationInShops
    };
    $.ajax({
        url: "/merchant/getPriceList",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_product_pricelist").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
    });
}


function  searchCategories(){
    var name    = $("#searchCategoryName").val();
    var shop_id = $("#searchCategoryShop").val();
    var data = {
         name:name,
         shop_id:shop_id
     };
    $.ajax({
        url:"/merchant/searchProductCategories/",
        data: data,
        type: 'POST',
        async:false,
        success: function (data) {
            $("#load_productCategory").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                }
       
    });
}   
function addNewCategory(){
    var myform = document.getElementById("newCategory");
    var fd = new FormData(myform);
    if(validator.form()){
        $.ajax({
            url: "/merchant/addProductCategory",
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                $("#load_productCategory").html(data);
            },
                    error: function(e){
                            console.log(e);
                            alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
                    }
        });
        $('#modalAddCategory').modal('close');
    }
}
$("#noAllergicContent").click(function(){
	if($(this).prop('checked') == true){
	    $("#selectAllergics").fadeOut();
	}else{
		$("#selectAllergics").fadeIn();	   
	}
});
	
$("#productType").change(function(){
	
	if($(this).val() == 1){
	   $("#ifBeverage").fadeIn();
	}else{
	   $("#ifBeverage").fadeOut();
	}
	
	if($(this).val() == 1 || $(this).val() == 2){
	   $("#foodAllergies").fadeIn();
           $("#productPropeties").fadeIn();
           $("#ifCalories").fadeIn();
	}else{
	   $("#foodAllergies").fadeOut();
           $("#productPropeties").fadeOut();
           $("#ifCalories").fadeOut();
	}
        
        if($(this).val() == 3){
            $("#ifMedical").fadeIn(); 
        }else{
            $("#ifMedical").fadeOut(); 
        }
	
});
										   
$('#infoLang').change(function(){
    var valId = $(this).val();
	$(".productInfo").hide();
	$("#info_"+valId).show();
});

$('#categoryLanguage').change(function(){
    var valId = $(this).val();
	$(".categoryInfo").hide();
	$("#categoryInfo_"+valId).show();
});


  function createOption(bundleId){
    var titles = $(".optionTitle_"+bundleId);
    var defaultLanguage = $("input[name='optionDefaultLang_bundle_"+bundleId+"']").map(function(){
        if($(this).prop("checked")){
            return $(this).val();
        }
    }).get();
    var isDefaultLanguage;
    var info = titles.map(function(){
        
        var lang = $(this).attr("data-language");
        if($(this).val() == "" && lang == defaultLanguage){
            alert("<?php echo $Core->Translator->translate("Default language can not be empty!");?>");
            throw new Error;
        }else{
            if($(this).val() != ""){
                if(lang == defaultLanguage){
                   isDefaultLanguage = 1;
                }else{
                    isDefaultLanguage = 0;
                }
                var object = {
                    title: $(this).val(),
                    description: $("#optionDescription_bundle_"+bundleId+"_lang_"+lang).val(),
                    lang_id: lang,
                    isdefault: isDefaultLanguage
                }
                return object;
            }
        }
        
    }).get();
    
    var defaultCurrencyInput = $("input[name='optionDefaultCurrency_"+bundleId+"']");
    var defaultCurrency = defaultCurrencyInput.map(function(){
        if($(this).prop("checked")){
            return $(this).val();
        }
    }).get();
    
    if(defaultCurrency == ""){
        alert("<?php echo $Core->Translator->translate("Please select a default currency");?>");
        throw new Error;
    }
    
    var pricesInput = $(".priceOption_"+bundleId);
    
    var prices = pricesInput.map(function(){
        var currency = $(this).attr("data-currency");

        if(currency == defaultCurrency && $(this).val() == ""){
            alert("<?php echo $Core->Translator->translate("Defaul currency can not be empty");?>");
            throw new Error;
        }else{
            if(currency == defaultCurrency){
                var isDefaultCurrency =1;
            }else{
                var isDefaultCurrency =0;
            }
            if($(this).val() != ""){
                var object = {
                    price: $(this).val(),
                    currency:currency,
                    isDefaultCurrency:isDefaultCurrency
                };
                return object;
            }
        }
    }).get();
    
    var hasAllergies = $("#allergiesOptions_"+bundleId).val();
    var noAllergies = $("#noAllergicContentOption_"+bundleId);
    
    if(noAllergies.prop("checked")==false && hasAllergies == ""){
        alert("<?php echo $Core->Translator->translate("Please select allergies or declare there are no allergic content.");?>");
        throw new Error;
    }
    
    if(noAllergies.prop("checked")==true){
        var allergies = 0;
    }else{
        var allergies = hasAllergies;
    }
    
    var dataOption = {
        info:JSON.stringify(info),
        prices:JSON.stringify(prices),
        bundleId:bundleId,
        allergies:allergies
    };
    
    var img = $("#upload_image_"+bundleId).val();
    var hasImage = false;
    
    if(img != ""){
        hasImage = true;
        var imgsize = $("#upload_image_"+bundleId)[0].files[0].size;

        if(imgsize > 8000000){
            alert("<?php echo $Core->Translator->translate("Image is to big. Max. 8 MB")?>");
            throw new Error;
        }

         $.ajax({
            url:"/Vendors/upload-and-crop-image/upload.php",
            type: "POST",
            data:{
                "image": img,
                "id": <?php echo $_SESSION["merchant"]["merchantId"];?>,
                "userType": "merchant"
            },
            success:function(path)
            {
              dataOption.image = path;
              postOptionData(dataOption,bundleId)
            }
          });
    }
    if(hasImage == false){
    postOptionData(dataOption,bundleId);
    }
}

function postOptionData(dataOption,bundleId){
    $.ajax({
            url: "/merchant/addOption",
            data: dataOption,
            type: 'POST',
            success: function (data) {
                $("#load_optionsBody_"+bundleId).html(data);
                 updateProductOptions();
            },
                    error: function(e){
                        alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
                    }
        });
}
function addOptionBundle(){
    var defaultLang = $("input[name='optionBundledefaultLang']:checked").val();
    var info = $(".optionBundleName").map(function(){
        var lang = $(this).attr("data-language");
        if(lang == defaultLang && $(this).val() == ""){
            alert("<?php echo $Core->Translator->translate('Default language can not be empty!')?>");
            throw new Error();
        }
        if($(this).val() != ""){
            if(lang == defaultLang){
                isDefault = 1;
            }else{
                isDefault = 0;
            }
            var values = {
                title:$(this).val(),
                description:$("#optionsBundleDescription_"+lang).val(),
                lang_id:lang,
                isDefault:isDefault
            }
            return values
        }
    }).get();
    info = JSON.stringify(info);
    
    var conditions;
    
    var isRequired = $("#bundleOptionRequired");
    if(isRequired.prop("checked")){
        var requiredMin = $("#requiredMin").val();
        var requiredMax = $("#requiredMax").val();
        if(requiredMin ==""){
           alert("<?php echo $Core->Translator->translate('Min. required can not be empty!')?>");
           throw new Error(); 
        }
        
        conditions = {
            isRequired:1,
            requiredMin:requiredMin,
            requiredMax:requiredMax
        };

    }else{
        var bundleOptionAmount = $("#bundleOptionAmount");
        if(bundleOptionAmount.prop("checked")){
            var max = $("#bundleMax").val();
            if(max == ""){
                alert("<?php echo $Core->Translator->translate('Max. Amount can not be empty!')?>");
                throw new Error();  
            }
            conditions = {
                hasAmount:1,
                max:max
            };
        }
    
    }
    
    conditions = JSON.stringify(conditions);
    
    var data = {
        info:info,
        conditions:conditions,

    };


     $.ajax({
            url: "/merchant/addOptionBundle",
            data: data,
            type: 'POST',
            success: function (data) {
                $("#load_productOptionsModal").html(data);
                updateProductOptions();
            },
                    error: function(e){
                            console.log(e);
                            alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
                    }
        });

     
}
function updateProductOptions(){
    var selected = $("input[name='productOptions[]']").map(function(){
        if($(this).prop("checked")){
            return $(this).val();
        }
    }).get();
    
    var shops = getShops();
    var variations = getVariations(shops);
    var data = {
        selected:selected,
        shops:shops,
        variations:variations
    };

     $.ajax({
        url: "/merchant/updateProductOptions",
        data: data,
        type: 'POST',
        success: function (data) {
            $("#load_product_Options").html(data);
        },
                error: function(e){
                        console.log(e);
                        alert("<?php echo $Core->Translator->translate('Error, please contact support@pykme.com')?>");
                }
    });
}

$('.scrollspy').scrollSpy({
    activeClass : "sidebarActive"
});
        
$('.target').pushpin({
    top: 70,
    offset: 0
});
$('body').on('focus',".timepicker", function(){
    $(this).pickatime({
        default: 'now', // Set default time: 'now', '1:30AM', '16:30'
        fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
        twelvehour: false, // Use AM/PM or 24-hour format
        donetext: 'OK', // text for done-button
        cleartext: 'Clear', // text for clear-button
        canceltext: 'Cancel', // Text for cancel-button,
        container: undefined, // ex. 'body' will append picker to body
        autoclose: true, // automatic close timepicker
        ampmclickable: true, // make AM PM clickable
        aftershow: function(){} //Function for after opening timepicker
    });
});
$('body').on('focus',".datepicker", function(){
    var date = new Date();
    var today1 = '31-12-' + (date.getFullYear() + 30);
    $(this).pickadate({
        format: 'dd-mm-yyyy',
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 100, // Creates a dropdown of 15 years to control year,
        today: '<?php echo $Core->Translator->translate("Today"); ?>',
        clear: '<?php echo $Core->Translator->translate("Clear"); ?>',
        close: 'Ok',
        closeOnSelect: false, // Close upon selecting a date,
        container: $("#picker"), // ex. 'body' will append picker to body
        max: today1
    });
});

</script>