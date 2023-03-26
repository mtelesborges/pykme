<?php
$Core = $view["Core"];
$data = $view["Data"];
?>
<div class="container">
    <div class="row">
        <?php
        if(empty($data["drivers"])){
            ?>
            <div class="col m6 s12">
                <h3><?php echo $Core->Translator->translate("Drivers");?></h3>
                <p><?php echo $Core->Translator->translate("No drivers available in your area.");?></p>
                <button class="btn"><?php echo $Core->Translator->translate("Register Driver");?></button>
            </div>
            <div class="col m6 s12">
                <img src="/View/img/driver.svg" class="introIllustration"/>
            </div>
        <?php }else{ ?>
            <h3><?php echo $Core->Translator->translate("Drivers");?></h3>
            <ul class="collection">
                <?php foreach ($data["drivers"] as $driver){?>
                    <li class="collection-item avatar">
                        <span class="title" style="text-transform: uppercase"><?php echo "#" . $driver["id"] . " - " . $driver["first_name"] . " " . $driver["last_name"];?></span>
                        <div style="display: flex">
                            <div style="display: flex">
                                <b><small><?php echo $Core->Translator->translate("Country");?></small></b>:&nbsp;
                                <p><?php echo $driver["country_name"] ?></p>
                            </div>
                            <div style="display: flex; margin-left: 1em">
                                <b><small><?php echo $Core->Translator->translate("Whatsapp");?></small></b>:&nbsp;
                                <p><?php echo $driver["whatsapp"] ?></p>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>



