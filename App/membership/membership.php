<?php
class membership{
	
	public $Core;
	public function __construct($Core){
		$this->Core = $Core;
	}
	
	public function VIEW_plans(){
		$this->renderPlans();
	}
	
	public function VIEW_updateMemberships(){
		$today = date("Y-m-d H:i:s",strtotime("now"));
		
		$db = $this->Core->getDB();
		
		
		$memberships = $db->query("SELECT * FROM shop_has_membership WHERE status='active' AND paid='1'",array(),false);
		
		foreach($memberships as $membership){
			if($db->query("SELECT * FROM paidMemberships WHERE shop_id=? AND valid_until > ?",array("is",$membership['shop_id'],$today),false)){
				// Merchant has valid Membership
			}else{
				$db->query("UPDATE shop_has_membership SET paid='0' WHERE status='active' AND paid='1' AND shop_id=?",array("i",$membership["shop_id"]),true);
			}
		}
	}
	/*
	Helper Functions
	*/
	/*
	Render Functions
	*/
	public function renderPlans($msg = null){
		$view = [
				"File" 				=> "membership-plans.php",
				"PageTitle"                     => $this->Core->Translator->translate("Membership Plans"),//TOTRANLATE
				"PageDescription"               => $this->Core->Translator->translate("Membership Plans on pykme.com"), //TOTRANLATE
				"Design"			=> "Default",
				"style"				=> null/*array("")*/,
				"JS"				=> null/*array("")*/,
				"Keywords"			=> array(
									$this->Core->Translator->translate("Delivery"),
									$this->Core->Translator->translate("Restaurants")
										),
				"Data"				=> array("results"),
				"Core"				=> $this->Core,
				"Message"			=> $msg
			];
			$this->Core->FrontController->render($view);
	}
}