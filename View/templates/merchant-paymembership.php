<?php
$Core = $view["Core"];
?>
<script src="https://checkout.stripe.com/checkout.js"></script>
<div class="container">
	<h3><?php echo $Core->Translator->translate("Pay Membership");?></h3>
	<p><?php echo $Core->Translator->translate("You are a few steps away to unlock the full potential of pykme");?></p>
	<div class="row">
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content">
					<h4 class="membershipPayTitle">
					<span class="monthToPay">1</span>
					/<?php echo $Core->Translator->translate("Month");?>
					</h4>
				  	<p><?php echo $Core->Translator->translate("1 Month for:")?>
						<select id="membershipMonth">
						<?php
						foreach($view["Prices"] as $price){
							if($price["months"] == 1){
							$priceAmount = str_replace(".", "",number_format($price["price"],2));
						?>
							<option value='{"currency_id":"<?php echo $price["currency"]["id"];?>","code":"<?php echo $price["currency"]["code"] ?>","amount":"<?php echo str_replace(".", "",number_format($price["price"],2))?>"}'><?php echo number_format($price["price"],2)." ".$price["currency"]["code"]." (".$price["currency"]["symbol"].")";?></option>
							
							
						<?php
						}
						}
						?>
						</select>
					</p>
					<button id="plan1" class="btn purchaseMembership">Purchase 1 Month</button>
				</div>
			</div>
		</div>
		
		<div class="col s12 m6">
			<div class="card">
				<div class="card-content">
					<h4 class="membershipPayTitle">
					<span class="monthToPay">12</span>
					/<?php echo $Core->Translator->translate("Months");?>
					</h4>
				  	<p><?php echo $Core->Translator->translate("12 Months for <b>50% off</b>:")?>
						<select id="membershipYear">
						<?php
						foreach($view["Prices"] as $price){
							if($price["months"] == 12){
						?>
							<option value='{"currency_id":"<?php echo $price["currency"]["id"];?>","code":"<?php echo $price["currency"]["code"] ?>","amount":"<?php echo str_replace(".", "",number_format($price["price"],2))?>"}'><?php echo number_format($price["price"],2)." ".$price["currency"]["code"]." (".$price["currency"]["symbol"].")";?></option>
						<?php
						}
						}
						?>
						</select>
					</p>
					
					<button id="plan2" class="btn purchaseMembership">Purchase 12 Months</button>
					
				</div>
			</div>
		</div>
	
	</div>
<form method="post" action="/merchant/chargeMembership" id="chargeForm">
<input type="hidden" id="plan" name="plan_id" readonly/>
<input type="hidden" id="currency" name="currency" readonly/>
<input type="hidden" id="shop_id" name="shop_id" value="<?php echo $Core->FrontController->Router->Parameters;?>" readonly/>
<input type="hidden" id="token_id" name="token_id" readonly/>
</form>
</div>

<script>
var handler = StripeCheckout.configure({
  key: '<?php echo $Core->getPayment()->TEST_OpenKey;?>',
  locale: 'auto',
  token: function(token) {
    // You can access the token ID with `token.id`.
    // Get the token ID to your server-side code for use.
		document.getElementById("token_id").value = token.id;
	  	document.getElementById("chargeForm").submit();
  }
});

document.getElementById('plan1').addEventListener('click', function(e) {
  // Open Checkout with further options:
  document.getElementById("plan").value = "1";
  var currency = document.getElementById("membershipMonth").value;
  var Objcurrency = JSON.parse(currency);
  document.getElementById("currency").value = currency;

	
  handler.open({
    name: 'pykme.com',
    description: '<?php echo $Core->Translator->translate("1 Month / Professional Membership");?>',
    zipCode: false,
	currency: Objcurrency.code,
    amount: Objcurrency.amount
  });
  e.preventDefault();
});
document.getElementById('plan2').addEventListener('click', function(e) {
  // Open Checkout with further options:
  document.getElementById("plan").value = "2";
  var currency = document.getElementById("membershipYear").value;
  var Objcurrency = JSON.parse(currency);
  document.getElementById("currency").value = currency;
  handler.open({
    name: 'pykme.com',
    description: '<?php echo $Core->Translator->translate("12 Months / Professional Membership");?>',
    zipCode: false,
	currency: Objcurrency.code,
    amount: Objcurrency.amount
  });
  e.preventDefault();
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
  handler.close();
});

</script>