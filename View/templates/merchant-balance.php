<?php
$Core = $view["Core"];

?>
<div class="container">
     <div class="row">
        <?php
        if(empty($data["balance"])){
        ?>
            <div class="col m6 s12">
                <h3><?php echo $Core->Translator->translate("Balance");?></h3>
                <p><?php echo $Core->Translator->translate("You have no orders yet. Make sure people can find you. Promote your shop on social media and tell your community about it!");?></p>
            </div>
            <div class="col m6 s12">
                <img src="/View/img/balance.svg" class="introIllustration"/>
            </div>
        <?php }else{ ?>
            <h3><?php echo $Core->Translator->translate("Balance");?></h3> 
        <?php } ?>
    </div>
</div>

