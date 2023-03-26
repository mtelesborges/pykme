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
                <h3><?php echo $Core->Translator->translate("Order Detail");?></h3>
                <p><?php echo $Core->Translator->translate("You have no orders yet. Make sure people can find you. Promote your shop on social media and tell your community about it!");?></p>
            </div>
            <div class="col m6 s12">
                <img src="/View/img/orders.svg" class="introIllustration"/>
            </div>
        <?php }else{ ?>
            <h3><?php echo "#" . $data["orderId"] . " " . $Core->Translator->translate("Order Detail");?></h3>
            <div style="display: flex">
                <div style="display: flex">
                    <b><small>Quantity</small></b>:&nbsp;
                    <span><?php echo $data["total"]["quantity"] ?></span>
                </div>
                <div style="display: flex; margin-left: 1em;">
                    <b><small>Amount (<?php echo $data["order"]["currency_code"] ?>)</small></b>:&nbsp;
                    <span><?php echo $data["total"]["amount"] ?></span>
                </div>
            </div>
            <div style="display: flex">
                <div style="display: flex; align-items: center">
                    <i class="material-icons" style="margin-right: .25em;">home</i>
                    <p style="font-size: 12px"><?php echo $data["order"]["address"] ?></p>
                </div>
                <div style="display: flex; align-items: center; margin-left: 1em">
                    <i class="material-icons" style="margin-right: .25em;">local_shipping</i>
                    <p style="font-size: 12px"><?php echo $data["order"]["type"] ?></p>
                </div>
            </div>
            <ul class="collection">
                <?php foreach ($data["products"] as $product){?>
                    <li class="collection-item avatar">
                        <span class="title" style="text-transform: uppercase"><?php echo "#" . $product["sequence"] . " - " . $product["product_name"];?></span>
                        <div>
                            <div style="display: flex">
                                <b><small>Quantity</small></b>:&nbsp;
                                <p><?php echo $product["quantity"] ?></p>
                            </div>
                            <div style="display: flex">
                                <b><small>Amount (<?php echo $data["order"]["currency_code"] ?>)</small></b>:&nbsp;
                                <p><?php echo $product["amount"] ?></p>
                            </div>
                            <div>
                                <?php if (!empty($product["options"])) { ?>
                                    <b><small>Options</small></b>:&nbsp
                                    <ul style="font-size: 12px; margin-left: 1em;">
                                        <?php foreach ($product["options"] as $key => $value) { ?>
                                            <li>
                                                <b><?php echo $key ?></b>:&nbsp;
                                                <span><?php echo $value ?></span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>