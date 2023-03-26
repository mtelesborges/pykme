<?php
$Core 	            = $view["Core"];
$shops 	            = $view["Data"]["shops"];
$categories         = $view["Data"]["categories"];
$lat                = $view["Data"]["lat"];
$lng                = $view["Data"]["lng"];
$searchProperties   = $view["Data"]["searchProperties"];
?>
<div class="container">
    <div class="col s12" id="filterPlaceholder">
        <form class="col s12" id="filterHeader" method="get" style="padding-bottom: .25em; padding-left: .25em;">
            <input name="lat" id="lat" value="<?php echo $lat ?>" hidden>
            <input name="lng" id="lat" value="<?php echo $lng ?>" hidden>
            <div class="nav-wrapper">
                <div class="input-field col m2 s12">
                    <select id="searchProperties" name="searchProperties[]" multiple>
                        <option disabled selected><?php echo $Core->Translator->translate("Select Properties"); ?></option>
                        <?php foreach($categories as $category) { ?>
                        <option value="<?php echo $category["id"]?>" <?php if (in_array($category["id"], $searchProperties ?? [])) { ?> selected <?php } ?> ><?php echo $category["title"]?></option>
                        <?php } ?>
                    </select>
                </div>
                <div style="margin-bottom: 1rem" class="col m2 s12">
                    <button class="btn" type="submit">Pesquisar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="grid" style="position: relative; top: 5rem;">
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
