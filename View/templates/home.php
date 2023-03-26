<?php
$Core = $view["Core"];
?>
<style>

</style>
<div id="intro" class="valign-wrapper">
    <div id="introVideo">
        <video poster="/View/img/bike-delivery.jpg" playsinline autoplay muted loop>
            <source src="/View/vid/home.mp4" type="video/mp4">
            <source src="/View/vid/home.webm" type="video/webm">
        </video>
        
    </div>
<div class="row" style="width:100%">
    <div class="col s12 l5 offset-l6">
        <div id="intro_box">
            <p id="intro_text">
                    <?php echo $Core->Translator->translate("Simply Delivered."); ?>
            </p>
            <!-- <div class="row">

                    <div class="input-field col s12">
                      <i class="material-icons prefix">location_on</i>
                      <input type="text" id="autocomplete-input" class="autocomplete">
                    </div>
                    <input class="btn select_home_option" type="submit" value="<?php echo $Core->Translator->translate("Search"); ?>" onclick="searchAddress()"/>
            </div> -->
            <button class="btn select_home_option" type="submit" onclick="searchAddress()"><?php echo $Core->Translator->translate("Search"); ?></button>

        </div>
    </div>
</div>
        
</div>
<section id="discoverCategories" class="valign-wrapper">
<div class="row" style="width:100%">
    <div class="col s12">
        <h3 class="center"><?php echo $Core->Translator->translate("Discover")?></h3>
        <p class="grey-text center-align space-bottom"><?php echo $Core->Translator->translate("Take your pick! Get everything delivered to your doorstep!");?></p>
    </div> 
    <div class="col s12">
        <div class="carousel">
                <?php
                foreach($view["Data"]["categories"] as $category){
                   if($category["id"] == 13){}else{
                ?>
                <a class="carousel-item category-caroussel" href="/categories/1">
                      <div class="card" style="background:none;">
                        <div class="card-image-category" style="background:url('View/img/categories/<?php echo $category["id"]?>.jpg') center;background-size:cover;">
                        </div>
                        <div class="card-content">
                            <span class="card-title center-align pykmegreen" style="margin-bottom:0;"><?php echo $Core->Translator->translate($category["name"])?></span>
                        </div>
                      </div>
                </a>
                <?php
                }}
                ?>
        </div>
    </div>
</div>
</section>
<?php
if(!empty($view["Data"]["shops"])){
?>
<section id="shopsNearBy" class="fullHeight">
<h3 class="center-align"><?php echo $Core->Translator->translate("New shops near")?> <?php if(empty($Core->Tracker->city)){$location = $Core->Tracker->country;}else{$location = $Core->Tracker->city;};echo $Core->Translator->translate($location)?></h3>
<p class="center-align grey-text space-bottom"><?php echo $Core->Translator->translate("Change Location");?> <i class="material-icons">place</i></p>
<div class="grid">
		<?php	
		foreach($view["Data"]["shops"] as $shop){  
		?>
            <a href="shop/show/<?php echo $shop["id"]?>">
		<div class="gridCards">
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
		</div>
            </a>
		<?php
		}
		?>
	</div>
</section>
<?php
}
?>
<section id="home_sell_on_pykme" class="valign-wrapper fullHeight">
    <div class="row">
        <div class="col s12 m6">
            <div class="container white-text">
                <h3><?php echo $Core->Translator->translate("Sell on pykme");?></h3>
                <p><?php echo $Core->Translator->translate("If you have a Restaurant, Shop, Retail store or have products that can be sold online you should join pykme. <b>It's completly free!</b>");?></p>
                <a href="/merchant/signup"><div class="btn"><?php echo $Core->Translator->translate("Signup as merchant");?></div></a>
            </div>
        </div>
    </div>
</section>
<section id="more_on_pykme"  class="valign-wrapper fullHeight">
   <div class="row" style="width:100%;">
        <div class="col s12">
            <h3 class="center-align"><?php echo $Core->Translator->translate("Do More")?> </h3>
            <p class="center-align grey-text space-bottom"><?php echo $Core->Translator->translate("We are not done yet! Here what you can also do on pykme!");?></p>  
        </div>
        <div class="col s12 m4">
            <div class="card" style="padding:15px;">
                <div class="card-image-category" style="background:url('View/img/taxi.jpg') center no-repeat;background-size:cover;">
                </div>
                <div class="card-content">
                    <span class="card-title center-align pykmegreen"><?php echo $Core->Translator->translate("Order a taxi")?></span>
                </div>
              </div>
        </div>
        <div class="col s12 m4">
            <div class="card" style="padding:15px;">
                <div class="card-image-category" style="background:url('View/img/transporter.jpg') center no-repeat;background-size:cover;">
                </div>
                <div class="card-content">
                    <span class="card-title center-align pykmegreen"><?php echo $Core->Translator->translate("Send a package")?></span>
                </div>
              </div>
        </div>
        <div class="col s12 m4">
            <div class="card" style="padding:15px;">
                <div class="card-image-category" style="background:url('View/img/express-delivery.jpg') center no-repeat;background-size:cover;">
                </div>
                <div class="card-content">
                    <span class="card-title center-align pykmegreen"><?php echo $Core->Translator->translate("Express Delivery")?></span>
                </div>
              </div>
        </div>
</section>
<section id="drive_with_us"  class="valign-wrapper fullHeight">
  <div class="row">
        <div class="col s12 offset-m6 m6">
            <div class="container white-text right-align">
                <h3><?php echo $Core->Translator->translate("Drive with us<br/> not for us!");?></h3>
                <p><?php echo $Core->Translator->translate("If you have a Restaurant, Shop, Retail store or have products that can be sold online you should join pykme. <b>It's completly free!</b>");?></p>
                <a href=""><div class="btn"><?php echo $Core->Translator->translate("Merchant Memberships");?></div></a>
            </div>
        </div>
    </div>   
</section>
 
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLry7Ka0e1DMXwDoRq-NwEgRr5ERheNg4&libraries=places&callback=initialize">
</script>
<script>
    $('.carousel').carousel({dist:0});
    
    function initialize() {
        var input = document.getElementById('autocomplete-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        
        input.value="";
    }

google.maps.event.addDomListener(window, 'load', initialize);
    </script>

