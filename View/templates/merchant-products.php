<?php
$Core = $view["Core"];
$data = $view["Data"];

?>
<div class="container">
     <div class="row">
        <?php
        if(empty($data["products"])){
        ?>
            <div class="col m6 s12">
                <?php 
                if(empty($data["shops"])){?>
                     <h3><?php echo $Core->Translator->translate("Products");?></h3>
                     <p><?php echo $Core->Translator->translate("You have no shops registred. Please add a shop first before you can create a product. If you have multiple shops, create them first and add the products later");?><a href="/merchant/addShop"> <?php echo $Core->Translator->translate("Create my first shop.");?></a></p>
                <?php }else{?>
                <h3><?php echo $Core->Translator->translate("Products");?></h3>
                <p><?php echo $Core->Translator->translate("Create your first product! We have the perfect solution for every business. you can assign one product to multiple shops, make your product display time sensitive, show diffrent prices at diffrent times and distance. Give it a go!");?></p>
                <p>
                    <a href="/merchant/addProduct" class="btn">
                        <i class="large material-icons left">add_circle</i><?php echo $Core->Translator->translate("Create First Product");?>
                    </a>
                </p>
                <?php }?>
            </div>
            <div class="col m6 s12">
                <img src="/View/img/products.svg" class="introIllustration"/>
            </div>
        <?php }else{ ?>
            <h3><?php echo $Core->Translator->translate("Products");?></h3>
            <p><?php echo $Core->Translator->translate("Here is the Place where you can manage all of your products. you can assign one product to multiple shops!");?></p>
            <p>
                <a href="/merchant/addProduct" class="btn">
                    <i class="large material-icons left">add_circle</i><?php echo $Core->Translator->translate("Add Product");?>
                </a>
            </p>
            <ul class="collection">
                <?php foreach ($data["products"] as $product){?>
                <li class="collection-item avatar">
                    <?php if($product["Photos"][0]["img"]){?>
                    <img src="<?php echo $product["Photos"][0]["img"];?>" alt="" class="circle">
                    <?php } ?>
                    <span class="title"><?php echo $product["Description"]["title"];?></span>
                    <p><?php echo $product["Description"]["description"];?></p>
                    <a href="/merchant/editProduct/<?php echo $product["Info"]["id"]?>" class="secondary-content"><i class="material-icons">edit</i></a>
                </li>
                <?php } ?>
            </ul>
            
        <?php } ?>
    </div>
</div>

