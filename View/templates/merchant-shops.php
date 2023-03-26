<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.1.0/croppie.min.js" integrity="sha512-OHrlqGLXjADlnhjsfWQOdm+x45eZp9LFSSYa2qNnSUSm8hsv41R4U5pcwb8RWbePh3phfFlWMHl7Q8QSoPnueg==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="/Vendors/upload-and-crop-image/croppie.css"/>
<!-- Modal Structure -->
<div id="uploadimageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title center-align"><?php echo $Core->Translator->translate("Select Logo")?></h4>
        </div>
        <div class="modal-body">
            <div class="valign-wrapper">
                <div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px; margin-top:30px"></div>
                         <button class="btn btn-success crop_image left-align"><?php echo $Core->Translator->translate("Select")?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL BOX FOR EDITING SHOP LOGOS -->

<script>
function editShop(part,shopId){
	$('#modal1').modal('open');
	$("#loadEdit").empty();
	$("#loadEdit").load("/merchant/editshop",{ "load":part, "shopId":shopId});
}
function updateVisibility(el){
	console.log(el);
	el.submit();
}
</script>
<?php
		$Core 	= $view["Core"];
		$shops 	= $view["Data"];
?>

  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
	<i class="material-icons right modal-close">close</i>
      	<div id="loadEdit">
			
	 	</div>
    </div>
  </div>


<div class="container">
    <?php if(!empty($shops)){?>
    <h3><?php echo $Core->Translator->translate("Shops");?></h3>
        <p><?php echo $Core->Translator->translate("Here is the Place where you can manage all of your shops.");?></p>
        <p>
            <a href="/merchant/addShop" class="btn">
                    <i class="large material-icons left">add_circle</i><?php echo $Core->Translator->translate("Add Shop");?>
            </a>
        </p>
    <?php } ?>
	<div class="grid">
		<?php
		if(!empty($shops)){	
		foreach($shops as $shop){
		?>
		<div class="gridCards" <?php if($shop["status"] == "off"){?>style="opacity: 0.5"<?php } ?>>
		  <div class="card sticky-action">
			<div class="card-image waves-effect waves-block waves-light">
				<div class="switch switch-shop">
                      <label>
                          <form method="post" action="/merchant/updateStatus">
                            <?php echo $Core->Translator->translate("Off");?>
                            <input type="checkbox" <?php if($shop["status"] == "on"){?> checked <?php } ?> onclick="this.form.submit()" name="status">
                            <span class="lever"></span>
                            <?php echo $Core->Translator->translate("On");?>
                          <input type="hidden" name="shopId" value="<?php echo $shop["id"]?>"/>
                          </form>
                      </label>
                  </div>
			  <img class="activator" src="<?php echo $shop["logo"];?>">
			</div>
			<div class="card-content">
				
				<h3 class="card-title activator shopTitleCard"><?php echo $shop["name"];?></h3>
				
				<p>
				 <span><?php echo $shop["address"]["googleString"]?></span>
				</p>
			  	<p><?php echo $Core->Translator->translate("Membership");?>:
					<?php
						if($shop["membership"]["membership_id"] == 2){
							echo "<b>".$Core->Translator->translate("Professional")."</b>";
							if($shop["membership"]["paid"] == 0){?>
							<br/><span style="color:red"><?php echo $Core->Translator->translate("Your Membership has not been paid.")?></span>
							<br/><span><a href="/merchant/pay_membership/<?php echo $shop["id"];?>"><?php echo $Core->Translator->translate("Pay Membership and activate") ?></a></span>
						<?php	}
						}else{
							echo "<b>".$Core->Translator->translate("Normal")."</b>";
						}
					?>
				
				<?php
				if($shop["membership"]["membership_id"] == 1){
				?>
				
				<br/><a href="#" onClick="editShop('Membership','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Upgrade to Professional");?></a>
				 <?php
				}
				?>
				</p>
				
			</div>
				<div class="card-action">
					<span class="shopIcons">
						<a href="#"><i class="material-icons activator">settings</i></a>
						<a href="/merchant/products/<?php echo $shop["id"]?>"><i class="material-icons">sell</i></a>
						<?php if($shop["status"] == "on"){?>
						<a href="/shop/show/<?php echo $shop["id"];?>" target="_blank"><i class="material-icons">remove_red_eye</i></a>
						<?php } ?>
					</span>
					
				</div>
			<div class="card-reveal">
			  <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i><?php echo $Core->Translator->translate("Edit");?></span>
			  	<p class="editShopButton" onClick="editShop('Membership','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Membership");?></p>
				<p class="editShopButton" onClick="editShop('System','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("System Information");?></p>
				<p class="editShopButton" onClick="editShop('Basic','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Basic Information");?></p>
				<p class="editShopButton" onClick="editShop('Opening_H','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Opening Hours");?></p>
				<p class="editShopButton" onClick="editShop('Holidays','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Holidays");?></p>
				<p class="editShopButton" onClick="editShop('Delivery_O','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Delivery Options");?></p>
				<?php if($shop["noDelivery"] == 0 && $shop["deliverySameOpening"] == false){?>
				<p class="editShopButton" onClick="editShop('Delivery_H','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Delivery Hours");?></p>
				<?php }?>
				<p class="editShopButton" onClick="editShop('Orders_Notification','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Order Notifications");?></p>
				<p class="editShopButton" onClick="editShop('Payment_Options','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Payment");?></p>
				<p class="editShopButton delete" onClick="editShop('Delete','<?php echo $shop["id"]?>')"><?php echo $Core->Translator->translate("Delete");?></p>
			</div>
		  </div>
		</div>
		<?php
		}
		}else{
		?>
                <div class="col m6 s12">
                    <h3><?php echo $Core->Translator->translate("Shops");?></h3>
                    <p><?php echo $Core->Translator->translate("Create your first shop! You can create as many shops as you want, in every country of the world!");?></p>
                    <p>
                        <a href="/merchant/addShop" class="btn">
                            <i class="large material-icons left">add_circle</i><?php echo $Core->Translator->translate("Create First Shop");?>
                        </a>
                    </p>
                </div>
                <div class="col m6 s12">
                    <img src="/View/img/shops.svg" class="introIllustration"/>
                </div>
    		<?php
		}?>
	</div>
</div>