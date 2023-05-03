<?php
$Core = $data["Core"];
$products = $data["products"];

if(!empty($data["inShops"])){
    if(!empty($products)){?>
    <div class="col s12">
        <ul class="collapsible" data-collapsible="expandable">
         <?php
         foreach($data["inShops"] as $shop){  ?>
            <li>
                <div class="collapsible-header">
                    <ul class="collection">
                      <li class="collection-item avatar">
                          <img src="<?php echo $shop["logo"];?>" alt="" class="circle">
                          <h5 class="card-title shopTitleCard"><?php echo $shop["name"];?></h5>
                          <p><?php echo $shop["address"]["googleString"];?></p>
                      </li>
                    </ul>
                </div>
                <div class="collapsible-body">
                    <div style="display:grid">
                        <div class="col s12 input-field">
                            <input type="text" id="searchCrossSellingIn_<?php echo $shop["id"];?>" onKeyUp="searchCrossSelling($(this).val(),<?php echo $shop["id"];?>)"/>
                            <label for="searchCrossSellingIn_<?php echo $shop["id"];?>"><?php echo $Core->Translator->translate("Search by product name");?></label>
                        </div>
                        <ul class="collapsible" data-collapsible="expandable" id="listProductsIn_<?php echo $shop["id"];?>">
                            <?php
                            foreach($products as $product){
                                $isInShop = false;
                                $hasVariation = false;
                                $variations = array();
                                foreach($product["inShops"] as $s){
                                    if($s["shop_id"] == $shop["id"]){
                                        $isInShop = true;
                                        if($s["variation_id"] != 0){
                                           $hasVariation = true;
                                           $variations[] = $s;
                                        }
                                    }
                                }
                                if($isInShop){
                                    if(!empty($product["imgs"])){
                                    $key = array_search(0, array_column($product["imgs"],"varId"));
                                    $img = $product["imgs"][$key]["img"];
                                    }else{
                                        $img = "";
                                    }
                                ?>
                                <li data-name="<?php echo $product["description"]["title"];?>">
                                    <div class="collapsible-header">
                                        <ul class="collection">
                                          <li class="collection-item avatar">
                                              <?php
                                              if(!empty($img)){
                                              ?>
                                              <img src="<?php echo $img;?>" alt="" class="circle">
                                              <?php
                                              }
                                              ?>
                                              <h5 class="card-title shopTitleCard"><?php echo $product["description"]["title"];?></h5>
                                              <p><?php echo $product["description"]["description"];?></p>
                                          </li>
                                        </ul>
                                    </div>
                                    <div class="collapsible-body">
                                        <div style="display:grid">
                                            <h6><?php echo $Core->Translator->translate("Include in Cross-Selling");?></h6>
                                            <p>
                                                <input
                                                    id="product_<?php echo $product["product_id"];?>_shop_<?php echo $shop["id"];?>_var_0"
                                                    type="checkbox"
                                                    data-shop-id="<?php echo $shop["id"];?>"
                                                    data-product-id="<?php echo $product["product_id"];?>"
                                                    data-variation-id="0" name="crossselling[]"
                                                    <?php if(!empty(array_filter($data["WholeProduct"]["CrossSelling"] ?? [], fn($item) => $item["product"] == $product["product_id"] && !empty(array_filter($item["inShops"] ?? [], fn($s) => $s["shop_id"] == $shop["id"]))))) echo "checked"; ?>
                                                />
                                                <label for="product_<?php echo $product["product_id"];?>_shop_<?php echo $shop["id"];?>_var_0"><span><?php echo $Core->Translator->translate("Default Product")?></span></label>
                                            </p>
                                            <?php
                                            if($hasVariation){
                                                foreach($variations as $v){
                                                    $varId = $v["variation_id"];
                                                    $var = $data["variations"][$varId];
                                                    foreach ($var["descriptions"] as $d){
                                                        if($d["default"] == 1){
                                                    ?>
                                            <p>
                                                <input id="product_<?php echo $product["product_id"];?>_shop_<?php echo $shop["id"];?>_var_<?php echo $varId?>" type="checkbox" data-shop-id="<?php echo $shop["id"];?>" data-product-id="<?php echo $product["product_id"];?>" data-variation-id="<?php echo $varId?>" name="crossselling[]"/>
                                                <label for="product_<?php echo $product["product_id"];?>_shop_<?php echo $shop["id"];?>_var_<?php echo $varId?>"><span><?php echo $d["title"]?></span>
                                                </label>
                                            </p>
                                            <?php
                                            }}}}
                                            ?>
                                        </div>
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
         <?php
         }
         ?>
        </ul>
    </div>
    <?php }else{ ?>
    <p class="grey-text"><?php echo $Core->Translator->translate("You have no products yet");?></p>
    <?php
}}else{
?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product needs a category in order to show up on your shop.");?><br/></p>
    </li>
</ul>
<?php } ?>

