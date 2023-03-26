<?php
$Core = $data["Core"];
?>
<div class="col s12">
	<div class="file-field input-field">
      <div class="btn">
        <span><?php echo $Core->Translator->translate("Images");?></span>
        <input type="file" multiple id="productImage">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text" placeholder="<?php echo $Core->Translator->translate("Upload multiple product images");?>">
      </div>
    </div>
</div>

<?php
if(!empty($data["selectedVariations"])){
?>
<div class="col s12">
 <ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header"><h5 class="grey-text"><?php echo $Core->Translator->translate("Variations");?></h5></div>
      	<div class="collapsible-body" style="display: grid">
            <?php
            foreach($data["selectedVariations"] as $v){
            ?>
            <div class="col s12">
                    <p><?php echo $v["description"]["title"];?><span class="grey-text"> <?php echo $v["description"]["description"];?></span></p>
            </div>
            <div class="col s12 file-field input-field">
                <div class="btn">
                    <span><?php echo $Core->Translator->translate("Images");?></span>
                    <input type="file" multiple name="productImageVariations[]" class="productVariationImages" data-variation-id="<?php echo $v["info"]["id"];?>">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="<?php echo $Core->Translator->translate("Upload multiple product images");?>">
                </div>
            </div>
            <?php
            }
            ?>	
            </div>
    </li>
</ul>
</div>
<?php
}
?>
<script>
 $('.collapsible').collapsible();
</script>
