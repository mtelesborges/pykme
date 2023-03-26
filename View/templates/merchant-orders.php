<?php
$Core = $view["Core"];
$data = $view["Data"];
?>
<div class="container">
     <div class="row">
        <?php
        if(empty($data["orders"])){
        ?>
            <div class="col m6 s12">
                <h3><?php echo $Core->Translator->translate("Orders");?></h3>
                <p><?php echo $Core->Translator->translate("You have no orders yet. Make sure people can find you. Promote your shop on social media and tell your community about it!");?></p>
            </div>
            <div class="col m6 s12">
                <img src="/View/img/orders.svg" class="introIllustration"/>
            </div>
        <?php }else{ ?>
            <h3><?php echo $Core->Translator->translate("Orders");?></h3>
            <ul class="collection">
                <?php foreach ($data["orders"] as $order){?>
                    <li class="collection-item avatar">
                        <span class="title" style="text-transform: uppercase"><?php echo "#" . $order["id"] . " - " . $order["username"];?></span>
                        <div style="display: flex">
                            <div style="display: flex">
                                <b><small><?php echo $Core->Translator->translate("Quantity");?></small></b>:&nbsp;
                                <p><?php echo $order["quantity"] ?></p>
                            </div>
                            <div style="display: flex; margin-left: 1em">
                                <b><small><?php echo $Core->Translator->translate("Amount");?> (<?php echo $order["currency_code"] ?>)</small></b>:&nbsp;
                                <p><?php echo $order["amount"] ?></p>
                            </div>
                        </div>
                        <div style="display: flex">
                            <div style="display: flex; align-items: center">
                                <i class="material-icons" style="margin-right: .25em;">home</i>
                                <p style="font-size: 12px"><?php echo $order["address"] ?></p>
                            </div>
                            <div style="display: flex; align-items: center; margin-left: 1em">
                                <i class="material-icons" style="margin-right: .25em;">local_shipping</i>
                                <p style="font-size: 12px"><?php echo $order["type"] ?></p>
                            </div>
                        </div>
                        <a href="/merchant/orderDetail/<?php echo $order["id"]?>" class="secondary-content"><i class="material-icons">visibility</i></a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>

