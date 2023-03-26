<?php
$Core = $data["Core"];
?>
<ul class="collection">
<?php 
if(!empty($data["productCategories"])){
    foreach($data["productCategories"] as $pc){
    $title;
    $description;
    foreach($pc["description"] as $text){
        if($text["default"] == 1){
            $title = $text["title"];
            $description = $text["description"];
        }
    }
   ?>
    <li class="collection-item">
        <div class="col s12" style="margin:0;padding:0;">
        <div class="col s12 valign-wrapper">
        <div class="col s1">
            <p>
            
            <input type="checkbox" id="productCategory_<?php echo $pc["info"]["id"]?>" data-category-id="<?php echo $pc["info"]["id"]?>" name="category[]" value="<?php echo $pc["info"]["id"]?>" onClick="categoryClicked(<?php echo $pc["info"]["id"]?>)"/>
            <label for="productCategory_<?php echo $pc["info"]["id"]?>"></label>
            </p>
        </div>
        <div class="col s11">
            <h5 class="title"><?php echo $title; ?></h5>
            <p class="grey-text"><?php echo $description; ?></p>
        </div>
        
        <?php
        if($pc["info"]["hasRestrictions"] == 1){?>
        <a href="#!" class="secondary-content tooltipped" data-position="bottom" data-delay="50" data-tooltip="
           <?php 
           echo $Core->Translator->translate("Restrictions:");
           echo " ";
            foreach ($pc["restrictions"] as $key => $restriction){

                echo "(".$Core->Translator->translate($key).") ";
                 
            }
           ?>
           "><i class="material-icons grey-text">pan_tool</i></a>
        <?php } ?>
        </div>
        <div class="col s12">
        <ul class="collapsible category-ul" data-collapsible="accordion">
                <li>
                  <div class="collapsible-header category-li grey-text"><i class="material-icons">store</i><?php echo $Core->Translator->translate("Show in this shops");?> (<?php echo count($pc["inShops"])?> <?php echo $Core->Translator->translate("Available")?>)<br><br></div>
                  <div class="collapsible-body category-li">
                    <div style="display:grid">
                       <ul class="collection">
                        <?php

                        foreach($pc["inShops"] as $shop){

                            ?>
                            <li class="collection-item avatar" style="background:none;border-color:#fff;">
                                <img src="<?php echo $shop[0]["info"]["logo"]?>" alt="" class="circle">
                                <span class="title"><?php echo $shop[0]["info"]["name"];?></span>
                                <p><?php echo $shop[0]["address"]["googleString"]?></p>
                                <span class="secondary-content">
                                    
                                    <input type="checkbox" onClick="shopClicked(<?php echo $pc["info"]["id"]?>,<?php echo $shop[0]["info"]["id"];?>)" name="productIsInShop[]" value="['<?php echo $pc["info"]["id"]?>','<?php echo $shop[0]["info"]["id"];?>']" class="shopsCategory_<?php echo $pc["info"]["id"]?>" id="category_<?php echo $pc["info"]["id"]?>_shop_<?php echo $shop[0]["info"]["id"];?>" data-shop-id="<?php echo $shop[0]["info"]["id"];?>"/>
                                    <label for="category_<?php echo $pc["info"]["id"]?>_shop_<?php echo $shop[0]["info"]["id"];?>"></label>
                                </span>
                            </li>
                        <?php              
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

 
<?php
}}else{
?>
<li class="collection-item avatar">
    <i class="material-icons circle yellow black-text">priority_high</i>
    <span class="title"><?php echo $Core->Translator->translate("No Categories found!");?></span>
    <p><?php echo $Core->Translator->translate("Your product needs a category in order to show up on your shop.");?><br/><a href="#modalAddCategory" class="modal-trigger"><?php echo $Core->Translator->translate("Create Shop Category");?></a>
    </p>
</li>
<?php }?>
</ul>
<a href="#modalAddCategory" class="modal-trigger btn-flat"><?php echo $Core->Translator->translate("Create Shop Category");?></a>
