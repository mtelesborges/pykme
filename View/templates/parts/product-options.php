<?php
$Core = $data["Core"];
$langs = $Core->Translator->getLanguages();?>
<p class="grey-text"><?php echo $Core->Translator->translate("Options are greate for things that implement your product. Lets say your product is a pizza. You can add options like extra salami or if you have a shoe as a product, you can add extra shoepolish to it. You can make required options when a product is very individual and needs the customer to make a choice.");?></p>
<?php
if($data["productOptions"]){
if($data["inShops"]){

?>
<ul class="collapsible" data-collapsible="expandable">
 <?php
 foreach($data["inShops"] as $shop){
 ?>
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
 <ul class="collapsible"  data-collapsible="accordion">
            <?php foreach($data["productOptions"] as $bundle){
                if($bundle["hasOptions"]){
                ?>
                <li>
                    <div class="collapsible-header">
                        <span style="width:100%;">
                            
                            <input type="checkbox" name="productOptions_shop_<?php echo $shop["id"]?>[]" value="<?php echo $bundle["info"]["id"]?>" id="checkOptionBundle_<?php echo $bundle["info"]["id"]?>_<?php echo $shop["id"]?>">
                            <label for="checkOptionBundle_<?php echo $bundle["info"]["id"]?>_<?php echo $shop["id"]?>"><span></span>
                            </label>
                        <?php echo $bundle["descriptions"]["title"]?>
                        <?php if($bundle["info"]["required"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo $Core->Translator->translate("Is required. Customer has to choose one or more options in this bundle to purchase product.");?>">verified</i> <?php }?>
                        <?php if($bundle["info"]["hasAmount"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo str_replace("%",$bundle["info"]["maxAmount"],$Core->Translator->translate("Is limited. Customer can only choose % option(s)"));?>">pan_tool</i> <?php }?>
                        </span>
                    </div>
                    <div class="collapsible-body">
                        <div style="display:grid">
                       <?php
                            if(!empty($bundle["hasOptions"])){?>
                            <ul class="collection">
                               <?php
                               foreach($bundle["hasOptions"] as $o){

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
                                           $currency_id = $p["currency_id"];
                                       }
                                   }
                                    $key = array_search($currency_id,array_column($Core->currencies,"id"));
                                    $currency = $Core->currencies[$key];
                               ?>
                                <li class="collection-item col m6 s12" style="background:none"><div><?php echo $title;?><span class="secondary-content"><?php echo $price; echo " ".$currency["symbol"];?></span></div></li>
                               <?php
                               }
                               ?>
                            </ul>
                            <?php }?>
                        </div>
                    </div>
                </li>
            <?php }} ?>
            </ul>
            <?php
            if($data["activeVariations"]){?>
            <h6><?php echo $Core->Translator->translate("Variations");?></h6>
            <ul class="collapsible">
            <?php
                foreach($data["activeVariations"] as $variation){
            ?>
                <li>
                    <div class="collapsible-header"><?php 
                        foreach($variation["descriptions"] as $d){
                            if($d["default"] == 1){
                                echo $d["title"];
                            }
                        }
                    ?>
                    </div>
                    <div class="collapsible-body">
                        <ul class="collapsible"  data-collapsible="accordion">
                            <?php foreach($data["productOptions"] as $bundle){
                                if($bundle["hasOptions"]){
                                ?>
                                <li>
                                    <div class="collapsible-header">
                                        <span style="width:100%;">
                                            <input type="checkbox" name="productOptionsVariation_<?php echo $variation["info"]["id"]; ?>_<?php echo $shop["id"]?>[]" value="<?php echo $bundle["info"]["id"]?>" id="checkOptionBundle_<?php echo $bundle["info"]["id"]?>_Variation_<?php echo $variation["info"]["id"]; ?>_<?php echo $shop["id"]?>">
                                            <label for="checkOptionBundle_<?php echo $bundle["info"]["id"]?>_Variation_<?php echo $variation["info"]["id"]; ?>_<?php echo $shop["id"]?>"></label>
                                        <?php echo $bundle["descriptions"]["title"]?>
                                        <?php if($bundle["info"]["required"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo $Core->Translator->translate("Is required. Customer has to choose one or more options in this bundle to purchase product.");?>">verified</i> <?php }?>
                                        <?php if($bundle["info"]["hasAmount"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo str_replace("%",$bundle["info"]["maxAmount"],$Core->Translator->translate("Is limited. Customer can only choose % option(s)"));?>">pan_tool</i> <?php }?>
                                        </span>
                                    </div>
                                    <div class="collapsible-body">
                                        <div style="display:grid">
                                       <?php
                                            if(!empty($bundle["hasOptions"])){?>
                                            <ul class="collection">
                                               <?php
                                               foreach($bundle["hasOptions"] as $o){

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
                                                           $currency_id = $p["currency_id"];
                                                       }
                                                   }
                                                   $key = array_search($currency_id,array_column($Core->currencies,"id"));
                                                   $currency = $Core->currencies[$key];
                                               ?>
                                                <li class="collection-item col m6 s12" style="background:none"><div><?php echo $title;?><span class="secondary-content"><?php echo $price; echo " ".$currency["symbol"];?></span></div></li>
                                               <?php
                                               }
                                               ?>
                                            </ul>
                                            <?php }?>
                                        </div>
                                    </div>
                                </li>
                            <?php }} ?>
                            </ul>
                    </div>
                </li>
            <?php }?>
            </ul> 
          </div>
      </li>
 <?php }} ?>
      </ul>

      <?php
        }else{
       ?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product can have diffrent options at diffrent shops. Please select at least one category so we know in wich shops the product will be available.");?>
        </p>
    </li>
</ul>            
            
        <?php }}else{ ?>
<p class="grey-text"><?php echo $Core->Translator->translate("Please create option");?></p>
        <?php } ?>
<script>

 $('.collapsible').collapsible();


</script>