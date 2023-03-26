<?php
$Core = $view["Core"];
//@TODO translation
?>
<div class="main">
	<div class="container">
	<div class="row">
		<div class="col m6 s12">
		 <div class="card">
			<div class="card-content">
				<h4><?php echo $Core->Translator->translate("Normal");?></h4>
			  <p><?php echo $Core->Translator->translate("This membership is <strong>100% free</strong>. No fees or commissions!"); ?></p>
			</div>
			<div class="card-tabs">
			  <ul class="tabs tabs-fixed-width">
				<li class="tab"><a href="#normalDescription" class="active"><?php echo $Core->Translator->translate("Description");?></a></li>
				<li class="tab"><a href="#normalFeatures"><?php echo $Core->Translator->translate("Features");?></a></li>
				<li class="tab"><a href="#normalPrice"><?php echo $Core->Translator->translate("Price");?></a></li>
			  </ul>
			</div>
			<div class="card-content grey lighten-4">
			  	<div id="normalDescription">
					<p><?php echo $Core->Translator->translate("You want your Business to accept online orders today? Then signup and you can start selling online in a few minutes. We want to help Businessowners in this pandamic, so we created a completly free system for Restaurants, Markets and other Merchants. You don't have delivery system? No problem, our system takes care of it. We connect business owner with delivery services. Start selling today with us!");?></p>
				</div>
			  	<div id="normalFeatures">
					<p>- <?php echo $Core->Translator->translate("Accept orders via Email or Webapplication.");?></p>
					<p>- <?php echo $Core->Translator->translate("Integrate our Code in your Website and sell directly from your website.");?></p>
					<p>- <?php echo $Core->Translator->translate("Products can be customized to be available only on certain hours of the day or week.");?></p>
					<p>- <?php echo $Core->Translator->translate("Use our advanced marketing tool where you can select customers by their Street or Cityarea.");?></p>
				</div>
			  	<div id="normalPrice">
					<p><?php echo $Core->Translator->translate("Our selling service is completly Free but we also so use other services. The Following services are not included:<br/>-Marketing Campaign Tool<br/>- Delivery Drivers (Paid directly to drivers)<br/>- SMS or Voice notice on incoming orders.<br/><br/>This services are only available with our membershio Proffesional for $9.90 a month.");?></p>
				</div>
			</div>
			 <a class="waves-effect waves-light btn membership-button" href="/merchant/signup"><?php echo $Core->Translator->translate("Signup");?></a>
		  </div>
		</div>
		<div class="col m6 s12">
		
		
		<div class="card">
			<div class="card-content">
				<h4><?php echo $Core->Translator->translate("Profesional");?></h4>
			  <p><?php echo $Core->Translator->translate("This membership is for Merchants that want to sell proffesionaly online."); ?></p>
			</div>
			<div class="card-tabs">
			  <ul class="tabs tabs-fixed-width" id="tabs2">
				<li class="tab"><a href="#royalDescription" class="active"><?php echo $Core->Translator->translate("Description");?></a></li>
				<li class="tab"><a href="#royalFeatures"><?php echo $Core->Translator->translate("Features");?></a></li>
				<li class="tab"><a href="#royalPrice"><?php echo $Core->Translator->translate("Price");?></a></li>
			  </ul>
			</div>
			<div class="card-content grey lighten-4">
			  	<div id="royalDescription">
					<p><?php echo $Core->Translator->translate("Create a better expierience for your customers! We provide you with the best online ordering service you can get for your money. We don't take commissions so you can sell as much as you want with us. Make sure your order arrive with our Voice or SMS notification.");?></p>
				</div>
			  	<div id="royalFeatures">
					<p>- <?php echo $Core->Translator->translate("Usage for 1 Shop.");?></p>
					<p>- <?php echo $Core->Translator->translate("Accept credit card payments.");?></p>
					<p>- <?php echo $Core->Translator->translate("Accept orders via Email,SMS,Phone call or Webapplication.");?></p>
					<p>- <?php echo $Core->Translator->translate("Get better position and display on Merchant Search List.");?></p>
					<p>- <?php echo $Core->Translator->translate("Benefit from google adwords Campaign.");?></p>
					<p>- <?php echo $Core->Translator->translate("Display of your Phonenumber on our Plattform.");?></p>
					<p>- <?php echo $Core->Translator->translate("Integrate our Code in your Website and sell directly from your website. Without our logo.");?></p>
					<p>- <?php echo $Core->Translator->translate("Products can be customized to be available only on certain hours of the day or week.");?></p>
					<p>- <?php echo $Core->Translator->translate("Use our advanced marketing tool where you can select customers by their Street or Cityarea.");?></p>
				</div>
			  	<div id="royalPrice">
					<p><?php echo $Core->Translator->translate("Our proffesional service costs only $9.90 a month. 3rd party Services like SMS, Voice Notification, credit card transactions and marketing campaign are not included and are billed sepratly according to usage.");?><a href=""><?php echo $Core->Translator->translate("See 3rd-Party costs");?></a></p>
				</div>
			</div>
			<a class="waves-effect waves-light btn membership-button" href="/merchant/signup/2"><?php echo $Core->Translator->translate("Signup");?></a>
		  </div>
		</div>
		</div>
	</div>
</div><script>
    var el = document.querySelector('.tabs');
    var instance = M.Tabs.init(el, {});
    
    var el = document.querySelector('#tabs2');
    var instance = M.Tabs.init(el, {});
  </script>