<?php
$Core = $view["Core"];
$data = $view["Data"];
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
            <ul class="collection">
                <?php foreach ($data["balance"] as $shop){?>
                    <li class="collection-item">
                        <span class="title" style="text-transform: uppercase"><?php echo "#" . $shop["shop_id"] . " " . $shop["shop_name"];?></span>
                        <div style="display: flex; align-items: center;">
                            <div style="display: flex; align-items: center;">
                                <b><small><?php echo $Core->Translator->translate("Quantity");?> (orders)</small></b>:&nbsp;
                                <p><?php echo $shop["quantity"] ?></p>
                            </div>
                            <div style="display: flex;; align-items: center; margin-left: 1em">
                                <b><small><?php echo $Core->Translator->translate("Amount");?> ($)</small></b>:&nbsp;
                                <p><?php echo $shop["amount"] ?></p>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>

