<?php
$Core = $view["Core"];
?>
<div class="container">
    <h3><?php echo $Core->Translator->translate("Welcome");?> <?php echo $view["Data"]["name"]?></h3>
    <p><?php echo $Core->Translator->translate("This is your Dashboard. Here you can create Shops and Products. <br/>If you have multiple Shops be sure to create them all before creating Products."); ?></p>
    
    <div class="row">
        
        <div class="col s12 m6">
            <div class="card">
                <div class="card-image center-align">
                    <i class="large material-icons" style="margin-top:50px">store</i>
                    <h2><?php if($view["Data"]["shops"] == 0){ echo "0";}else{ echo $view["Data"]["shops"];}?> Shops</h2>                
                </div>
                <div class="card-content">
                    
                </div>
                <div class="card-action">
                    <a href="/merchant/shops"><?php echo $Core->Translator->translate("Create a Shop");?></a>
                </div>
            </div>
        </div>
        
        <div class="col s12 m6">
            <div class="card">
                <div class="card-image center-align">
                    <i class="large material-icons" style="margin-top:50px">sell</i>
                    <h2><?php if($view["Data"]["products"] == 0){ echo "0";}else{ echo $view["Data"]["products"];}?> Products</h2>                
                </div>
                <div class="card-content">
                    
                </div>
                <div class="card-action">
                    <a href="/merchant/products"><?php echo $Core->Translator->translate("Create a Product");?></a>
                </div>
            </div>
        </div>
        
    </div>
    
    
</div>