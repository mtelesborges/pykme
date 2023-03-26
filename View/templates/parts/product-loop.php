<?php

$shop = $data["Shop"];
if($_POST){
    $Core = $data["Core"];
    $mode = $_POST["tranportationType"];
}else{
    $Core = $view["Core"];
    $mode = "delivery";
}

//var_dump($Core->Tracker);
?>
<?php
        foreach($shop["Categories"] as $c){
            if(!empty($c["products"])){
        ?>
            <div id="category_<?php echo $c["info"]["id"]?>" class="section scrollspy titleShopCategory">
                <div class="categoryHeader">
                    <h5><?php echo $c["description"]["title"]?></h5>
                    <p class="grey-text"><?php echo $c["description"]["description"];?></p>
                </div>

                <!-- product loop -->
                <div class="gridProducts">
                <?php foreach ($c["products"] as $product){
                   ?>
                    <div class="products hoverable" onclick="addToCart(<?php echo $product["id"] ?>)">
                        <div class="contentProducts">
                            <h6><?php echo $product["default"]["Description"]["title"];?></h6>
                            <p class="pykmegreen">
                                <?php if(count($product["default"]["Prices"]) == 1){

                                    $productPrice = null;

                                    if($mode == "delivery"){ 
                                       $productPrice = $product["default"]["Prices"][0]["delivery_price"];
                                    }

                                    if($mode == "takeaway"){
                                        $productPrice = $product["default"]["Prices"][0]["store_price"];
                                    }

                                    if($product["default"]["PriceConditions"]){
                                        foreach ($product["default"]["PriceConditions"] as $condition){
                                           if($condition["type"] == "Time"){
                                               foreach ($condition["object"]["shops"] as $shopId){
                                                   if($shopId == $shop["info"]["id"]){
                                                        date_default_timezone_set($shop["address"]["timezone"]);
                                                        $today      = new DateTime();
                                                        $dateFrom   = new DateTime($condition["object"]["dateFrom"]);
                                                        $dateUntil  = new DateTime($condition["object"]["dateUntil"]);
                                                        if($today >= $dateFrom && $today <= $dateUntil){
                                                            echo "ok";
                                                        }
                                                   }
                                               }
                                           } 
                                        }
                                    }
                                    echo $Core->currentCurrencySymbol." ".number_format($productPrice,2);
                                }?>
                            </p>
                            <p class="grey-text"><?php echo $product["default"]["Description"]["description"];?></p>
                            <?php 
                            $obj = unserialize($product["default"]["AdditionalInfo"]["object"]);
                            if(!empty($obj->contentCalories)){?>
                                <p class="grey-text productCalories">
                                    <i class="material-icons left">local_fire_department</i>
                                <?php
                                echo $obj->contentCalories." ".$Core->Translator->translate("Calories");
                                ?>
                                </p>
                            <?php
                            }
                            ?>
                                <p class="grey-text">
                                    <i class="material-icons left">hourglass_top</i>
                                    <?php echo $product["default"]["PreparationTime"]["time"]." "; 
                                    if($product["default"]["PreparationTime"]["type"] == "i"){ echo $Core->Translator->translate("Minutes");}
                                    if($product["default"]["PreparationTime"]["type"] == "h"){ echo $Core->Translator->translate("Hours");}
                                    if($product["default"]["PreparationTime"]["type"] == "d"){ echo $Core->Translator->translate("Days");}
                                    if($product["default"]["PreparationTime"]["type"] == "w"){ echo $Core->Translator->translate("Weeks");}
                                    ?>
                                </p>
                            
                            <p>
                                <?php if(!empty($product["default"]["Properties"])){
                                    foreach ($product["default"]["Properties"] as $p){?>
                                        <div class="chip green lighten-2 white-text">
                                          <?php echo $Core->Translator->translate($p["name"]);?>
                                          <i class="material-icons iconChip">check</i>
                                        </div>
                                <?php        
                                    }
                                }?>
                                <?php if(!empty($product["default"]["Allergies"])){
                                    foreach ($product["default"]["Allergies"] as $p){?>
                                        <div class="chip yellow lighten-2 grey-text">
                                          <?php echo $Core->Translator->translate($p["name"]);?>
                                          <i class="material-icons iconChip">report</i>
                                        </div>
                                <?php        
                                    }
                                }?>
                            </p>
                            
                            
                            
                        </div>       
                        <?php if(isset($product["default"]["Photos"])){?>
                        <div class="carousel carousel-slider">
                            
                            <?php
                            foreach($product["default"]["Photos"] as $p){
                            ?>
                                 <a class="carousel-item"><img src="<?php echo $p["img"]?>" class="productImage"></a>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                
                <?php } ?>
                 <!-- end product loop -->
                </div>
            </div>
        <?php
        }}
        ?>
<script>
$(document).ready(function(){
    $('.carousel.carousel-slider').carousel({
        fullWidth: true
    });
 });

 
 $( window ).resize(function() {
    $('.carousel.carousel-slider').carousel({
    fullWidth: true
  });
 }); 
</script>