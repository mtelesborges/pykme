<?php

class Payment{
	public $Core;
	public $Stripe;
	public $TEST_SecretKey = 'sk_test_51ISOldAb5atfMOqXkg3Q226Pz7DNgOOqlyT4oT7WGy5jXKNcMd7uagq4iwgmrdDKCp1Zl0UIYUkXxwHP3jkNhgYJ00HSj8KquC';
	public $TEST_OpenKey = 'pk_test_51ISOldAb5atfMOqXxninG7iYY4mK9NfRafAm6P3Nl2EKzfb0L4W9mDj1A76kpGhzz2N0UgI8cmRjEJCBkXnUvAYI00yLHPMIAp';
	public $SecretKey =  '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';
	public $OpenKey = 'pk_live_51ISOldAb5atfMOqXpRfvKsWNvyL7fbLWeNOZihjodxtr7Ck4hqubWBzc3FTDjhKhvQGEn5ChZMnjpYsF40jPF4fN00oBytDwaA';
	
	
	public function __construct($Core){
		$this->Core = $Core;
		
		require_once('Vendors/stripe-php/init.php');
		$this->Stripe = new \Stripe\StripeClient($this->TEST_SecretKey);
		
		return($this->Stripe);
	}
	
	
	
	
	
}