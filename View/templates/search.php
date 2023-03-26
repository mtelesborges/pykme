<?php
$Core 	= $view["Core"];
$shops 	= $view["Data"];
?>
<div class="container">
    <div class="grid">
        <?php foreach($shops as $shop) { ?>
        <a href="/<?php echo $shop["name"] ?>" class="gridCards">
          <div class="card sticky-action">
              <div class="card-image waves-effect waves-block waves-light">
                  <img class="activator" src="<?php echo $shop["logo"];?>">
              </div>
            <div class="card-content">
                <h3 class="card-title activator shopTitleCard"><?php echo $shop["name"];?></h3>
                <p>
                 <span><?php echo $shop["address"]["googleString"]?></span>
                </p>
            </div>
          </div>
        </a>
        <?php } ?>
    </div>
</div>
