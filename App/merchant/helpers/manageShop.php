<?php
class manageShop{
	public $Core;
function __construct($core){
	$this->Core = $core;

}
function addShop(){

	$membership		= $_POST["membership"]; // 1 = normal / 2 = Professional
	
	$distanceSystem = $_POST["distanceSystem"]; 
	$currency		= $_POST["currency"];
	
	$categoryId		= $_POST["category"];
	$subCategories 	= $_POST["subCategory"]; // array
	
	$shopName		= $_POST["shopName"];
	$shopDescription= $_POST["shopDescription"];
	$logo			= $_POST["path_image"];
	
	$shopAdressString= $_POST["shopAddress"]; // google string
	$streetNumber	= $_POST["street_number"];
	$streetName		= $_POST["route"];
	$neighbourhood 	= $_POST["neighbourhood"];
	$city			= $_POST["city"];
	$state 			= $_POST["state"];
	$postalCode		= $_POST["postal_code"];
	$country		= $_POST["country"];
	$lat			= round($_POST["lat"],9);
	$lng			= round($_POST["lng"],9);
	$timezone		= $_POST["timezone"];
	$addressObject	= $_POST["addressObject"];//Json Object
	
	$MondayFrom		= $_POST["Monday_from"];//array
	$MondayTo		= $_POST["Monday_to"];//array
	$TuesdayFrom	= $_POST["Tuesday_from"];//array
	$TuesdayTo		= $_POST["Tuesday_to"];//array
	$WednesdayFrom	= $_POST["Wednesday_from"];//array
	$WednesdayTo	= $_POST["Wednesday_to"];//array
	$ThursdayFrom	= $_POST["Thursday_from"];//array
	$ThursdayTo		= $_POST["Thursday_to"];//array
	$FridayFrom		= $_POST["Friday_from"];//array
	$FridayTo		= $_POST["Friday_to"];//array
	$SaturdayFrom	= $_POST["Saturday_from"];//array
	$SaturdayTo		= $_POST["Saturday_to"];//array
	$SundayFrom		= $_POST["Sunday_from"];//array
	$SundayTo		= $_POST["Sunday_to"];//array
	
	$holidayFrom	= $_POST["holiday_from"];//array
	$holidayTo		= $_POST["holiday_to"];//array
	
	$noDelivery     = $_POST["noDelivery"];//boolean
	$pykmeDelivery	= $_POST["pykmeDelivery"];//boolean
	$selfDelivery	= $_POST["selfDelivery"];//boolean
	
	//IF SELF DELIVERY get min Order Amout by distance
	$orderDistance	= $_POST["orderDistance"];//array
	$minPrice		= $_POST["minPrice"];//array
	
	//IF SELF DELIVERY get delivery Rates by Distance
	$distance		= $_POST["distance"];//array
	$distancePrice	= $_POST["distancePrice"];//array
	$distanceTime	= $_POST["distanceTime"];//array
	
	$sameDeliveryHours	= $_POST["sameDeliveryHours"];//boolean
	
	$DMonday_from	= $_POST["MondayDelivery_from"];//array
	$DMonday_to		= $_POST["MondayDelivery_to"];//array
	$DTuesday_from	= $_POST["TuesdayDelivery_from"];//array
	$DTuesday_to	= $_POST["TuesdayDelivery_to"];//array
	$DWednesday_from= $_POST["WednesdayDelivery_from"];//array
	$DWednesday_to	= $_POST["WednesdayDelivery_to"];//array
	$DThursday_from	= $_POST["ThursdayDelivery_from"];//array
	$DThursday_to	= $_POST["ThursdayDelivery_to"];//array
	$DFriday_from	= $_POST["FridayDelivery_from"];//array
	$DFriday_to		= $_POST["FridayDelivery_to"];//array
	$DSaturday_from	= $_POST["SaturdayDelivery_from"];//array
	$DSaturday_to	= $_POST["SaturdayDelivery_to"];//array
	$DSunday_from	= $_POST["SundayDelivery_from"];//array
	$DSunday_to		= $_POST["SundayDelivery_to"];//array
	
	$orderByEmail	= $_POST["orderByEmail"];
	$orderbySMS		= $_POST["full_number"];
	
	$cashPayment	= $_POST["cash_payment"];//boolean
	$onlineCredit	= $_POST["credit_online_payment"];//boolean
	$onDeliveryCredit= $_POST["credit_delivery_payment"];//boolean
	
	
	//IF ONLINE CREDITCARD get Bank details
	$benificiary	= $_POST["benificiary"];
	$iban			= $_POST["IBAN"];
	$bicSwift		= $_POST["BIC/SWIFT"];
	$clearing		= $_POST["Clearing"];
	$bank			= $_POST["Bank"];
	$BankAddress	= $_POST["BankAddress"];
	
	
	
	//Handling Checkboxes
	if($noDelivery == "on"){
		$noDelivery = 1;
	}else{
		$noDelivery = 0;
	}
	
	if($pykmeDelivery == "on"){
		$pykmeDelivery = 1;
	}else{
		$pykmeDelivery = 0;
	}
	
	if($selfDelivery == "on"){
		$selfDelivery = 1;
	}else{
		$selfDelivery = 0;
	}
	
	if($onlineCredit == "on"){
		$onlineCredit = 1;
	}else{
		$onlineCredit = 0;
	}
	
	if($onDeliveryCredit =="on"){
		$onDeliveryCredit = 1;
	}else{
		$onDeliveryCredit = 0;
	}
	
	if($cashPayment == "on"){
		$cashPayment = 1;
	}else{
		$cashPayment = 0;
	}
	
	
	// CHECK FOR ESSENTIAL VALUES
	if(empty($shopName) || empty($membership) || empty($currency) || empty($distanceSystem) || empty($timezone) || empty($lat) || empty($lng) || empty($orderByEmail) || empty($categoryId) || empty($streetName) || empty($streetNumber)){
		echo "Please activate JavaScript on your Browser";
		die();
		exit();
	}
	
	
	
	
	
	// START REGISTERING SHOP
	$msg = array();
	$shop	= array();
	
	// REGISTER SHOP
	$db = $this->Core->getDB();
		//Check if shop exists
	$isShopRegistred = $db->query("SELECT * FROM `shops` WHERE name=? AND lat=? AND lng=? AND status!='deleted'",array("sss",$shopName,$lat,$lng),false);
	if($isShopRegistred){
		$msg[] = [
						"type" => "error",
						"text" => $this->Core->Translator->translate("This Shop already registred")
					];
		return $msg;
		exit;
	}
	
	if($db->query("INSERT INTO shops VALUES(0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),'on',?)",array("isssissiiiddssiiii",
														$_SESSION["merchant"]["merchantId"],
														$shopName,
														$shopDescription,
														$distanceSystem,
														$currency,
														$logo,
														$timezone,
														$noDelivery,
														$pykmeDelivery,
														$selfDelivery,
														$lat,
														$lng,
														$orderByEmail,
														$orderbySMS,
														$cashPayment,
														$onlineCredit,
														$onDeliveryCredit,
														$categoryId
													   ),true)){
		$getshop = $db->query("SELECT * FROM `shops` WHERE name=? AND merchant_id=? AND lat=? AND lng=? ORDER BY id DESC LIMIT 1",array("sidd",$shopName,$_SESSION["merchant"]["merchantId"],$lat,$lng),false);
		
		$shop = $getshop[0];

		
	}else{
		$msg[] = [
						"type" => "error",
						"text" => $this->Core->Translator->translate("Could not register shop")
					];
	}
	// END REGISTER SHOP
	
	// REGISTER MEMBERSHIP
	if($db->query("INSERT INTO shop_has_membership VALUES (0,?,?,NOW(),0,'active')",array("ii",$shop["id"],$membership),true)){
		// Registred
	}else{
			$msg[] = [
						"type" => "error",
						"text" => $this->Core->Translator->translate("Could not register membership")
					]; 
	}
	// END MEMBERSHIP
	
	// REGISTER SHOP URL
	require_once("Core/Slug.php");
	$slugMaker = new Slug();
	$prettyUrl = $slugMaker->create($shop["name"]);
	$r = $db->query("SELECT page_id FROM url ORDER BY ? DESC",array("s","page_id"),false);
	$nextPageId = $r[0]["page_id"] + 1;
	$hasPrettyUrl = $db->query("SELECT url, status FROM url WHERE url=? AND (status='active' OR status='disabled')",array("s",$prettyUrl),false);
	if(!empty($hasPrettyUrl)){
		// FOUND SAME PRETTY URL
		$newpart = $slugMaker->create($streetName);
		$newUrl = $prettyUrl."-".$newpart;
		$hasNewUrl = $db->query("SELECT url, status FROM url WHERE url=? AND (status='active' OR status='disabled')",array("s",$newUrl),false);
		if(!empty($hasNewUrl)){
			// FOUND SAME PRETTY URL
			$newpart = $slugMaker->create($neighbourhood);
			$lastUrl = $newUrl."-".$newpart;
			$hasLastUrl = $db->query("SELECT url, status FROM url WHERE url=? AND (status='active' OR status='disabled')",array("s",$lastUrl),false);
			if(!empty($hasLastUrl)){
				$msg[] = [
						"type" => "error",
						"text" => $this->Core->Translator->translate("Could not register URL")
					];
				return($msg);
				exit;
			}else{
				// REGISTER $lastUrl
				$db->query("INSERT INTO url VALUES(0,?,?,'shop','show',?,'global','active')",array("isi",$nextPageId,$lastUrl,$shop["id"]),true);
			}
		}else{
			// REGISTER $newUrl
			$db->query("INSERT INTO url VALUES(0,?,?,'shop','show',?,'global','active')",array("isi",$nextPageId,$newUrl,$shop["id"]),true);
		}
	}else{
		
		if($db->query("INSERT INTO url VALUES(0,?,?,'shop','show',?,'global','active')",array("isi",$nextPageId,$prettyUrl,$shop["id"]),true)){}else{
			$msg[] = [
						"type" => "error",
						"text" => $this->Core->Translator->translate("Could not register URL")
					];
			return $msg;
			exit;
		}
	}
	// END SHOP URL

	
	//REGISTER SUBCATEGORIES
	if(!empty($subCategories)){
		foreach($subCategories as $subCat){
			if($db->query("INSERT INTO shop_has_subcategory VALUES(0,?,?,'active')",array("ii",$subCat,$shop["id"]),true)){
				//registred
			}else{
				$msg[] = [
							"type" => "error",
							"text" => $this->Core->Translator->translate("Could not register subcatgory")." ".$subCat
							];
			}
		}
	}else{
		$msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("No subcatgory")." ".$subCat
                    ];
	}
	//END REGISTER SUBCATEGORIES
	
	//REGISTER TIME
	
	$weekday = $this->Core->weekday;
	$hasTime = false;
	foreach($weekday as $day){
		$Arrayfrom 	= $day."From";
		$Arrayto	= $day."To";
		
		if(!empty($$Arrayfrom) && $$Arrayfrom != $$Arrayto){
			for($i = 0; $i < count($$Arrayfrom);$i++){
				if(!empty($$Arrayfrom[$i] && !empty($$Arrayto[$i]))){
					if($db->query("INSERT INTO time VALUES(0,?,'opening',?,?,?,NOW(),'active')",array("isss",$shop["id"],$day,$$Arrayfrom[$i],$$Arrayto[$i]),true)){
						//registred
						$hasTime = true;
						if($sameDeliveryHours == "on"){
							$db->query("INSERT INTO time VALUES(0,?,'delivery',?,?,?,NOW(),'active')",array("isss",$shop["id"],$day,$$Arrayfrom[$i],$$Arrayto[$i]),true);
						}
					}else{
						$msg[] = [
									"type" => "error",
									"text" => $this->Core->Translator->translate("Could not register $day Time")
									];
					}
				}else{
					$msg[] = [
								"type" => "error",
								"text" => $this->Core->Translator->translate("Missing Hours in $day Time")
								];
				}
			}
		}
		
	}
	if(empty($sameDeliveryHours)){
		foreach($weekday as $day){
			$Arrayfrom 	= "D".$day."_from";
			$Arrayto	= "D".$day."_to";
			if(!empty($$Arrayfrom) && $$Arrayfrom != $$Arrayto){
				for($i = 0; $i < count($$Arrayfrom);$i++){
					if(!empty($$Arrayfrom[$i] && !empty($$Arrayto[$i]))){
						if($db->query("INSERT INTO time VALUES(0,?,'delivery',?,?,?,NOW(),'active')",array("isss",$shop["id"],$day,$$Arrayfrom[$i],$$Arrayto[$i]),true)){
							//registred
						}else{
							$msg[] = [
										"type" => "error",
										"text" => $this->Core->Translator->translate("Could not register $day Delivery Time")
										];
						}
					}else{
						$msg[] = [
									"type" => "error",
									"text" => $this->Core->Translator->translate("Missing Hours in $day Delivery Time")
									];
					}
				}
				
			}
			
		}
	}else{
		$db->query("INSERT INTO deliverySameOpening VALUES(0,?,'active',NOW())",array("i",$shopId),true);
	}
	if($hasTime == false){

		$msg[] = [
                  "type" => "error",
                  "text" => $this->Core->Translator->translate("Your shop has no opening hours. It will not be visible and customers can't order.")
				];}
	// END REGISTER TIME
	
	// REGISTER ADDRESS
	if($db->query("INSERT INTO address VALUES(0,0,?,?,?,?,?,?,?,?,?,?,?,?,?,'active',NOW())",array("issssssssddss",$shop["id"],$shopAdressString,$streetName,$streetNumber,$neighbourhood,$city,$state,$postalCode,$country,$lat,$lng,$timezone,$addressObject),true)){
		// Registred
	}else{
		$msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Could not register address")
                    ];
	}
	// END REGISTER ADDRESS
	
	// REGISTER HOLIDAYS
	if($holidayFrom){
		for($i = 0; $i < count($holidayFrom); $i++){
			$from = date('Y-m-d',strtotime($holidayFrom[$i]));
			if(!empty($holidayTo[$i])){
			$to = date('Y-m-d',strtotime($holidayTo[$i]));	
			}else{
			$to = $from;
			}
			
			if($db->query("INSERT INTO holiday VALUES (0,?,?,?,'active')",array("iss",$shop["id"],$from,$to),true)){
				// Registred
			}else{
				$msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Could not register holiday")
                    ];
			}
			
		}
	}
	// END HOLIDAYS
	
	
	// IF SELF DELIVERY
	if($selfDelivery == 1){
		// MIN. ORDER
		for($i = 0; $i < count($orderDistance);$i++){
			
			if($db->query("INSERT INTO minOrder VALUES(0,?,?,?,NOW(),'active')",array("idd",$shop["id"],$orderDistance[$i],$minPrice[$i]),true)){
				// Registred
			}else{
				$msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Could not register min. Order")
                    ];
			}
			
		}
	
		// DELIVERY RATES
		for($i = 0; $i < count($distance);$i++){
			if($db->query("INSERT INTO deliveryRates VALUES(0,?,?,?,?,?,?NOW(),'active')",array("idsdsdd",$shop["id"],$distance[$i],$distancePrice[$i], $distanceTime[$i],$shop["lat"],$shop["lng"]),true)){
				
			}else{
				$msg[] = [
                    "type" => "error",
                    "text" => $this->Core->Translator->translate("Could not register delivery Rates")
                    ];
			}
		}
	}
	// END REGISTER SELF DELIVERY
	
	
	// IF ONLINE CREDIT CARD
	
	if($onlineCredit == 1){
		if(!empty($iban)){
			if($db->query("INSERT INTO shopBank VALUES(0,?,?,?,?,?,?,?,NOW(),'active')",array("issssss",$shop["id"],$benificiary,$iban,$bicSwift,$clearing,$bank,$BankAddress),true)){
				// registred
			}else{
				$msg[] = [
					"type" => "error",
					"text" => $this->Core->Translator->translate("Could not register your bank information")
				];
			}
		}else{
			$msg[] = [
					"type" => "error",
					"text" => $this->Core->Translator->translate("Could not register your bank information. Your IBAN was empty")
				];
		}
	}
	
	if(empty($msg)){
		$msg[] = [
                    "type" => "success",
                    "text" => $this->Core->Translator->translate("Your shop was successfully registred!")
                    ];
	}

	//return $msg;
	
	
}
// END addShop
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
/////////////////	
// DELETE
public function load_Delete($shopId){
	$data["Core"] = $this->Core;
	$data["shop"]["id"] = $shopId;
	$this->Core->FrontController->partialRender("edit-shop-delete.php",$data);
	
}
public function edit_Delete($shopId){
	$db= $this->Core->getDB();
	if($db->query("UPDATE shops SET status='deleted' WHERE id=?",array("i",$shopId),true)){
		$msg[] = [
			"type" => "success",
			"text" => $this->Core->Translator->translate("Shop was deleted")
		];
	}else{
		$msg[] = [
			"type" => "error",
			"text" => $this->Core->Translator->translate("Not able to delete this shop")
		];
	}
	return $msg;
}
	
/////////////////	
// PAYMENT
public function load_Payment_Options($shopId){
	
	$db = $this->Core->getDB();
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);
	$membership = $db->query("SELECT * FROM shop_has_membership WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	if($shop[0]["onlineCreditCard"] == 1){
		$bank = $db->query("SELECT * FROM shopBank WHERE shop_id=? AND status='active'",array("i",$shopId),false);
		$data["bank"] = $bank[0];
	}
	
	$data["shop"] = $shop[0];
	$data["membership"] = $membership[0];
	$data["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-payment-options.php",$data);
}	
public function edit_Payment_Options($shopId){
	$cashPayment	= $_POST["cash_payment"];//boolean
	$onlineCredit	= $_POST["credit_online_payment"];//boolean
	$onDeliveryCredit= $_POST["credit_delivery_payment"];//boolean
	//IF ONLINE CREDITCARD get Bank details
	$benificiary	= $_POST["benificiary"];
	$iban			= $_POST["IBAN"];
	$bicSwift		= $_POST["BIC/SWIFT"];
	$clearing		= $_POST["Clearing"];
	$bankName			= $_POST["Bank"];
	$BankAddress	= $_POST["BankAddress"];
	
	if($onlineCredit == "on"){
		$onlineCredit = 1;
	}else{
		$onlineCredit = 0;
	}
	
	if($onDeliveryCredit =="on"){
		$onDeliveryCredit = 1;
	}else{
		$onDeliveryCredit = 0;
	}
	
	if($cashPayment == "on"){
		$cashPayment = 1;
	}else{
		$cashPayment = 0;
	}
	
	if(empty($cashPayment) && empty($onlineCredit) && empty($onDeliveryCredit)){
		$msg[] = [
			"type" => "error",
			"text" => $this->Core->Translator->translate("You have to choose at least 1 payment method!")
		];
	}else{
		$db = $this->Core->getDB();
		$db->query("UPDATE shops SET cashOnDelivery=?,onlineCreditCard=?,creditOnDelivery=? WHERE id=?",array("iiii",$cashPayment,$onlineCredit,$onDeliveryCredit,$shopId),true);
		if($onlineCredit){
			$bank = $db->query("SELECT * FROM shopBank WHERE shop_id=? AND status='active' ORDER BY id DESC LIMIT 1",array("i",$shopId),false);
			if($bank[0]["benificiary"] == $benificiary && $bank[0]["iban"] == $iban && $bank[0]["bic_swift"] == $bicSwift && $bank[0]["clearing"] == $clearing && $bank[0]["bank"] == $bankName && $bank[0]["bankAddress"] == $BankAddress){
				// same values as in DB do nothing
			}else{
				$db->query("UPDATE shopBank SET status='disabled' WHERE shop_id=?",array("i",$shopId),true);
				$db->query("INSERT INTO shopBank VALUES(0,?,?,?,?,?,?,?,NOW(),'active')",array("issssss",$shopId,$benificiary,$iban,$bicSwift,$clearing,$bankName,$BankAddress),true);
			}
		}
		$msg[] = [
			"type" => "success",
			"text" => $this->Core->Translator->translate("Payments updated.")
		];
	}
	return($msg);
}	
	
	
/////////////////	
// ORDERS NOTIFICATION
public function load_Orders_Notification($shopId){
	$db = $this->Core->getDB();
	
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);
	$membership = $db->query("SELECT * FROM shop_has_membership WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$data["shop"] = $shop[0];
	$data["membership"] = $membership[0];
	$data["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-order-notifications.php",$data);
}
public function edit_Orders_Notification($shopId){
	$email = $_POST["orderByEmail"];
	$tel = $_POST["full_number"];
	$db = $this->Core->getDB();

		if($db->query("UPDATE shops SET orderByEmail=?,orderBySMS=? WHERE id=?",array("ssi",$email,$tel,$shopId),true)){
			$msg[] = [
				"type" => "success",
				"text" => $this->Core->Translator->translate("Order notification was updated")
			];
		}else{
			$msg[] = [
				"type" => "warning",
				"text" => $this->Core->Translator->translate("Order notification was not updated")
			];
		}
	return($msg);
}	
/////////////////	
// DELIVERY HOURS
public function load_Delivery_H($shopId){

	$db = $this->Core->getDB();
	
	$data["deliveryHours"] = $db->query("SELECT * FROM time WHERE shop_id=? AND type='delivery' AND status='active'",array("i",$shopId),false);
	$data["Core"] = $this->Core;
	$data["shop_id"] = $shopId;
	
	$this->Core->FrontController->partialRender("edit-shop-delivery-hours.php",$data);
	
}
public function edit_Delivery_H($shopId){
	$DMonday_from	= $_POST["MondayDelivery_from"];//array
	$DMonday_to		= $_POST["MondayDelivery_to"];//array
	$DTuesday_from	= $_POST["TuesdayDelivery_from"];//array
	$DTuesday_to	= $_POST["TuesdayDelivery_to"];//array
	$DWednesday_from= $_POST["WednesdayDelivery_from"];//array
	$DWednesday_to	= $_POST["WednesdayDelivery_to"];//array
	$DThursday_from	= $_POST["ThursdayDelivery_from"];//array
	$DThursday_to	= $_POST["ThursdayDelivery_to"];//array
	$DFriday_from	= $_POST["FridayDelivery_from"];//array
	$DFriday_to		= $_POST["FridayDelivery_to"];//array
	$DSaturday_from	= $_POST["SaturdayDelivery_from"];//array
	$DSaturday_to	= $_POST["SaturdayDelivery_to"];//array
	$DSunday_from	= $_POST["SundayDelivery_from"];//array
	$DSunday_to		= $_POST["SundayDelivery_to"];//array
	$weekday = $this->Core->weekday;
	$db = $this->Core->getDB();
	$oldDelivery =  $db->query("SELECT * FROM time WHERE shop_id=? AND type='delivery' AND status='active'",array("i",$shopId),false);
	// disable all
	$db->query("UPDATE time SET status='disabled' WHERE shop_id=?",array("i",$shopId),true);
	foreach($weekday as $day){
			$Arrayfrom 	= "D".$day."_from";
			$Arrayto	= "D".$day."_to";
			if(!empty($$Arrayfrom) && $$Arrayfrom != $$Arrayto){
				for($i = 0; $i < count($$Arrayfrom);$i++){
					// search for same times
					$begin = date("H:i:s",strtotime($$Arrayfrom[$i]));
					$end = date("H:i:s",strtotime($$Arrayto[$i]));
					$matchKeys = array_keys(array_column($oldDelivery,"begin"),$begin);
					if($matchKeys){
						foreach($matchKeys as $key){
							if($oldDelivery[$key]["begin"] == $begin && $oldDelivery[$key]["end"] == $end && $oldDelivery[$key]["day"] == $day){
								// same Values as in DB so activate it again
								$db->query("UPDATE time SET status='active' WHERE id=?",array("i",$oldDelivery[$key]["id"]),true);
							}else{
								if(!empty($begin && !empty($begin))){
									if($db->query("INSERT INTO time VALUES(0,?,'delivery',?,?,?,NOW(),'active')",array("isss",$shopId,$day,$begin,$end),true)){
										//registred
									}else{
										$msg[] = [
													"type" => "error",
													"text" => $this->Core->Translator->translate("Could not register $day Delivery Time")
													];
									}
								}else{
									$msg[] = [
												"type" => "error",
												"text" => $this->Core->Translator->translate("Missing Hours in $day Delivery Time")
												];
								}
							}
						}
					}else{
						// no matchkey found its new value
						$db->query("INSERT INTO time VALUES(0,?,'delivery',?,?,?,NOW(),'active')",array("isss",$shopId,$day,$begin,$end),true);
					}
				}
				
			}
			
		}
	if(empty($msg)){
			$msg[] = [
                    "type" => "success",
                    "text" => $this->Core->Translator->translate("Delivery hours updated")
                    ];
	}
	return $msg;
}
	
/////////////////	
// DELIVERY OPTIONS
public function load_Delivery_O($shopId){
	$db = $this->Core->getDB();
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);
	$currency = $db->query("SELECT * FROM currency WHERE id=?",array("i",$shop[0]["currency_id"]),false);
	$data["currency"] = $currency[0];
	$data["shop"] = $shop[0];
	$data["Core"] = $this->Core;
	if($shop[0]["selfDelivery"] == 1){
		$data["rates"] = $db->query("SELECT * FROM deliveryRates WHERE shop_id=? AND status='active'",array("i",$shopId),false);
		$data["minOrder"] = $db->query("SELECT * FROM minOrder WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	}
	
	$this->Core->FrontController->partialRender("edit-shop-delivery-options.php",$data);
}
public function edit_Delivery_O($shopId){
	$noDelivery		= $_POST["noDelivery"];//boolean
	$pykmeDelivery	= $_POST["pykmeDelivery"];//boolean
	$selfDelivery	= $_POST["selfDelivery"];//boolean
		//Handling Checkboxes
	if($noDelivery == "on"){
		$noDelivery = 1;
	}else{
		$noDelivery = 0;
	}
	
	if($pykmeDelivery == "on"){
		$pykmeDelivery = 1;
	}else{
		$pykmeDelivery = 0;
	}
	
	if($selfDelivery == "on"){
		$selfDelivery = 1;
	}else{
		$selfDelivery = 0;
	}
	
	//IF SELF DELIVERY get min Order Amout by distance
	$orderDistance	= $_POST["orderDistance"];//array
	$minPrice		= $_POST["minPrice"];//array
	
	//IF SELF DELIVERY get delivery Rates by Distance
	$distance		= $_POST["distance"];//array
	$distancePrice	= $_POST["distancePrice"];//array
	$distanceTime	= $_POST["distanceTime"];//array
	
	$db = $this->Core->getDB();
	
	if(empty($noDelivery) && empty($pykmeDelivery) && empty($selfDelivery)){
		$msg[] = [
			"type" => "warning",
			"text" => $this->Core->Translator->translate("Nothing was updated. You have to choose at least one option.")
		];
	}else{
		if($db->query("UPDATE shops SET noDelivery=?,pykmeDelivery=?,selfDelivery=? WHERE id=?",array("iiii",$noDelivery,$pykmeDelivery,$selfDelivery,$shopId),true)){}
		
	// BEGIN minOrder update
		if($noDelivery == 0){
			$oldMinOrder = $db->query("SELECT * FROM minOrder WHERE shop_id=? AND status='active'",array("i",$shopId),false);
			// disable all
			if($db->query("UPDATE minOrder SET status='disabled' WHERE shop_id=? AND status='active'",array("i",$shopId),true)){}
			
			// check if there is someting to update
			if(!empty($orderDistance) && !empty($minPrice) && !empty($selfDelivery)){
				for($i = 0; $i < count($orderDistance);$i++){
					$matchKeys = array_keys(array_column($oldMinOrder,"distance"),floatval($orderDistance[$i]));
					if($matchKeys){
						foreach($matchKeys as $matchKey){
							if($oldMinOrder[$matchKey]["distance"] == $orderDistance[$i] && $oldMinOrder[$matchKey]["min_order"] == $minPrice[$i]){
								// Same values as in DB activate it again
								$db->query("UPDATE minOrder SET status='active' WHERE id=?",array("i",$oldMinOrder[$matchKey]["id"]),true);
							}else{
								// Diffrent Values so Insert them
								$db->query("INSERT INTO minOrder VALUES (0,?,?,?,NOW(),'active')",array("iii",$shopId,$orderDistance[$i],$minPrice[$i]),true);

							}
						}
					}else{
						// Diffrent Values so Insert them
						$db->query("INSERT INTO minOrder VALUES (0,?,?,?,NOW(),'active')",array("iii",$shopId,$orderDistance[$i],$minPrice[$i]),true);
					}
				}
			}
	
		// END minOrder update

		// BEGIN deliveryRates update
			$shop = $db->query("SELECT lat, lng, id FROM shops WHERE id=?",array("i",$shopId),false);

			$oldRates = $db->query("SELECT * FROM deliveryRates WHERE shop_id=? AND status='active'",array("i",$shopId),false);
			//disable all
			$db->query("UPDATE deliveryRates SET status='disabled' WHERE shop_id=? AND status='active'",array("i",$shopId),true);
			if(!empty($distance) && !empty($distanceTime) && !empty($distancePrice)){
				for($i = 0; $i < count($distance);$i++){
					// see if there is macht with old values from DB
					$matchKeys = array_keys(array_column($oldRates,"distance"),$distance[$i]);
					if($matchKeys){
						foreach($matchKeys as $matchKey){
							if($oldRates[$matchKey]["distance"] == $distance[$i] && $oldRates[$matchKey]["price"] == $distancePrice[$i] && $oldRates[$matchKey]["time"] == $distanceTime[$i]){
								// same value as in DB so activate it again
								$db->query("UPDATE deliveryRates SET status='active' WHERE id=?",array("i",$oldRates[$matchKey]["id"]),true);
							}else{
								// new Values
								$db->query("INSERT INTO deliveryRates VALUES (0,?,?,?,?,?,?,NOW(),'active')",array("isssdd",$shopId,$distance[$i],$distancePrice[$i],$distanceTime[$i],$shop[0]["lat"],$shop[0]["lng"]),true);
							}
						}

					}else{
						// new Values
                        $db->query("INSERT INTO deliveryRates VALUES (0,?,?,?,?,?,?,NOW(),'active')",array("isssdd",$shopId,$distance[$i],$distancePrice[$i],$distanceTime[$i],$shop[0]["lat"],$shop[0]["lng"]),true);
					}
				}
			}
			// END deliveryRates updates
		} 
		$msg[] = [
			"type" => "success",
			"text" => "Delivery options updated"
		];
	}
	return($msg);
}
	
/////////////////	
// HOLIDAYS
public function load_Holidays($shopId){
	$db = $this->Core->getDB();
	
	$holidays = $db->query("SELECT * FROM holiday WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$data["shop_id"] = $shopId;
	$data["holidays"] = $holidays;
	$data["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-holidays.php",$data);
}
public function edit_Holidays($shopId){
		$db = $this->Core->getDB();
		$holidayFrom	= $_POST["holiday_from"];//array
		$holidayTo		= $_POST["holiday_to"];//array
		$registred 		= false;
		if($db->query("SELECT * FROM holiday WHERE shop_id=? AND status='active'",array("i",$shopId),false)){
			$db->query("UPDATE holiday SET status='disabled' WHERE shop_id=? AND status='active'",array("i",$shopId),true);
		}
		if($holidayFrom){
			for($i = 0; $i < count($holidayFrom); $i++){
				if(!empty($holidayFrom[$i])){
					$from = date('Y-m-d',strtotime($holidayFrom[$i]));
					if(!empty($holidayTo[$i])){
					$to = date('Y-m-d',strtotime($holidayTo[$i]));	
					}else{
					$to = $from;
					}
					if($db->query("INSERT INTO holiday VALUES (0,?,?,?,'active')",array("iss",$shopId,$from,$to),true)){
						// Registred
						$registred = true;
					}else{
						$registred = false;
					}
				}

			}
              if($registred == true){
                  $msg[] = [
                              "type" => "success",
                              "text" => $this->Core->Translator->translate("Holiday was registred")
                              ];
              }else{
                  $msg[] = [
                              "type" => "error",
                              "text" => $this->Core->Translator->translate("Could not register holiday")
                              ];
              }
            }else{
				   $msg[] = [
                              "type" => "warning",
                              "text" => $this->Core->Translator->translate("You have no holidays or closed days registred.")
                              ];
            }
	

	return $msg;
}

	
/////////////////	
// OPENING HOURS
public function load_Opening_H($shopId){
	$db = $this->Core->getDB();
	
	$hours = $db->query("SELECT * FROM time WHERE shop_id=? AND type='opening' AND status='active'",array("i",$shopId),false);
	$same = $db->query("SELECT * FROM  deliverySameOpening WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$data["hours"] = $hours;
	$data["Core"] = $this->Core;
	$data["shopId"] = $shopId;
	$data["sameDelivery"] = $same;
	$data["shop_id"] = $shopId;
	
	$this->Core->FrontController->partialRender("edit-shop-opening-H.php",$data);
}

public function edit_Opening_H($shopId){
	
	$sameDeliveryHours= $_POST["sameDeliveryHours"];//boolean
	$MondayFrom		= $_POST["Monday_from"];//array
	$MondayTo		= $_POST["Monday_to"];//array
	$TuesdayFrom	= $_POST["Tuesday_from"];//array
	$TuesdayTo		= $_POST["Tuesday_to"];//array
	$WednesdayFrom	= $_POST["Wednesday_from"];//array
	$WednesdayTo	= $_POST["Wednesday_to"];//array
	$ThursdayFrom	= $_POST["Thursday_from"];//array
	$ThursdayTo		= $_POST["Thursday_to"];//array
	$FridayFrom		= $_POST["Friday_from"];//array
	$FridayTo		= $_POST["Friday_to"];//array
	$SaturdayFrom	= $_POST["Saturday_from"];//array
	$SaturdayTo		= $_POST["Saturday_to"];//array
	$SundayFrom		= $_POST["Sunday_from"];//array
	$SundayTo		= $_POST["Sunday_to"];//array
	
	$db = $this->Core->getDB();
	
	$hasTime = $db->query("SELECT * FROM time WHERE status='active' AND shop_id=? AND (type='Opening' OR type='Delivery')",array("i",$shopId),false);
	
	if($hasTime){
		if($sameDeliveryHours){
			$db->query("UPDATE time SET status='disabled' WHERE shop_id=? AND (type='Opening' OR type='Delivery') AND status='active'",array("i",$shopId),true);
			
			$db->query("INSERT INTO deliverySameOpening VALUES(0,?,'active',NOW())",array("i",$shopId),true);
		}else{
			$db->query("UPDATE time SET status='disabled' WHERE shop_id=? AND type='Opening' AND status='active'",array("i",$shopId),true);
			
			$db->query("UPDATE deliverySameOpening SET status='disabled' WHERE shop_id=? AND status='active'",array("i",$shopId),true);
		}
		
	}
	
	$error = false;
	$weekday = $this->Core->weekday;
	foreach($weekday as $day){
		$Arrayfrom 	= $day."From";
		$Arrayto	= $day."To";
		
	
		
		if(!empty($$Arrayfrom) && $$Arrayfrom != $$Arrayto){
			for($i = 0; $i < count($$Arrayfrom);$i++){
				if(!empty($$Arrayfrom[$i] && !empty($$Arrayto[$i]))){
					if($db->query("INSERT INTO time VALUES(0,?,'opening',?,?,?,NOW(),'active')",array("isss",$shopId,$day,$$Arrayfrom[$i],$$Arrayto[$i]),true)){
						//registred
						if($sameDeliveryHours == "on"){
							$db->query("INSERT INTO time VALUES(0,?,'delivery',?,?,?,NOW(),'active')",array("isss",$shopId,$day,$$Arrayfrom[$i],$$Arrayto[$i]),true);
						}
					}else{
						$msg[] = [
									"type" => "error",
									"text" => $this->Core->Translator->translate("Could not register $day Time")
									];
						$error = true;
					}
				}else{
					$msg[] = [
								"type" => "error",
								"text" => $this->Core->Translator->translate("Missing Hours in $day Time")
								];
						$error = true;
				}
			}
		}
		
	}
	if($error == false){
		$msg[] = [
                "type" => "success",
                "text" => $this->Core->Translator->translate("Opening hours were updated")
                ];
	}
	return $msg;
}
	
////////////////////	
// BASIC INFORMATION
public function load_Basic($shopId){
	$db = $this->Core->getDB();
	
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);
	
	$category = $db->query("SELECT * FROM category WHERE status=?",array("s","active"),false);
	
	$subCategory = $db->query("SELECT * FROM subCategory WHERE status=?",array("s","active"),false);
	
	$hasSubcategory = $db->query("SELECT * FROM shop_has_subcategory WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$address = $db->query("SELECT * FROM address WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$data["shop"] = $shop[0];
	$data["allCategories"] = $category;
	$data["allSubcategories"] = $subCategory;
	$data["hasSubcategories"] = $hasSubcategory;
	$data["address"] = $address[0];
	$data["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-basic.php",$data);
}
public function edit_Basic($shopId){
	$db = $this->Core->getDB();
	
	$categoryId		= $_POST["category"];
	$subCategories 	= $_POST["subCategory"]; // array
	
	$shopName		= $_POST["shopName"];
	$shopDescription= $_POST["shopDescription"];
	$logo			= $_POST["path_image"];
	
	$shopAdressString= $_POST["shopAddress"]; // google string
	$streetNumber	= $_POST["street_number"];
	$streetName		= $_POST["route"];
	$neighbourhood 	= $_POST["neighbourhood"];
	$city			= $_POST["city"];
	$state 			= $_POST["state"];
	$postalCode		= $_POST["postal_code"];
	$country		= $_POST["country"];
	$lat			= round($_POST["lat"],9);
	$lng			= round($_POST["lng"],9);
	$timezone		= $_POST["timezone"];
	$addressObject	= $_POST["addressObject"];//Json Object
	
	

	// update Category, shop Name, description and logo
	if($db->query("UPDATE shops SET category_id=?,name=?,description=?,logo=? WHERE id=?",array("isssi",$categoryId,$shopName,$shopDescription,$logo,$shopId),true)){
		$msg[] = [
			"type" => "success",
			"text" => $this->Core->Translator->translate("Shop was updated")
		];

	}
	
	// Check if address changed
	$address = $db->query("SELECT * FROM address WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	if($address[0]["object"] != $addressObject){
		// if changed update
		if($db->query("UPDATE address SET status='disabled' WHERE shop_id=? AND status='active'",array("i",$shopId),true)){
			if($db->query("INSERT INTO address VALUES(0,0,?,?,?,?,?,?,?,?,?,?,?,?,?,'active',NOW())",array("issssssssddss",$shopId,$shopAdressString,$streetName,$streetNumber,$neighbourhood,$city,$state,$postalCode,$country,$lat,$lng,$timezone,$addressObject),true)){
				if($db->query("UPDATE shops SET lat=?,lng=?,timezone=? WHERE id=?",array("sssi",$lat,$lng,$timezone,$shopId),true)){
					if($db->query("UPDATE deliveryRates SET lat=?,lng=? WHERE shop_id=?",array("ddi",$lat,$lng,$shopId),true)){}
					$msg[] = [
						"type" => "success",
						"text" => $this->Core->Translator->translate("Address was updated")
					];
				}
			}
		}
	}
	
	//update Subcategories
	$subCategoryOld = $db->query("SELECT * FROM shop_has_subcategory WHERE status='active' AND shop_id=?",array("i",$shopId),false);
	
	$toDelete;
	$toInsert;
	
	//check if needs to delete
	foreach($subCategoryOld as $old){
		if(in_array($old["sub_category_id"],$subCategories)){
			// no need to delete
		}else{
			// need to delete
			$toDelete[] = $old["sub_category_id"];
		}
	}
	
	// check if needs to update
	foreach($subCategories as $new){
		if(in_array($new,array_column($subCategoryOld,"sub_category_id"))){
			// no need to update
		}else{
			//update
			$toInsert[] = $new;
		}
	}
	
	$updatedCategory = false;
	// delete old
	if(!empty($toDelete)){
		foreach($toDelete as $del){
		if($db->query("UPDATE shop_has_subcategory SET status='disabled' WHERE shop_id=? AND sub_category_id=?",array("ii",$shopId,$del),true)){
			$updatedCategory = true;	
		}
		}
	}
	// update new
	if(!empty($toInsert)){
		foreach($toInsert as $ins){
		if($db->query("INSERT INTO shop_has_subcategory VALUES(0,?,?,'active')",array("ii",$ins,$shopId),true)){
			$updatedCategory = true;	
		}
		}
	}
	
	if($updatedCategory == true){
		$msg[] = [
					"type" => "success",
					"text" => $this->Core->Translator->translate("Subcategory was updated")
				];
	}
	
	if(empty($msg)){
		$msg[] = [
					"type" => "warning",
					"text" => $this->Core->Translator->translate("Shop was not updated")
				];
	}
	
	return($msg);
	

}
////////////////////	
//SYSTEM INFORMATION
public function load_System($shopId){
	$db = $this->Core->getDB();
	
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);
	
	$allCurrency = $db->query("SELECT * FROM currency ORDER BY ?",array("s","id"),false);
	
	$data["shop"] = $shop[0];
	$data["allCurrency"] = $allCurrency;
	$data["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-system.php",$data);
}
public function edit_System($shopId){
	$db = $this->Core->getDB();
	
	$currencyId = $_POST["currency"];
	$distanceSystem = $_POST["distanceSystem"];
	
	$shop = $db->query("SELECT * FROM shops WHERE id=?",array("i",$shopId),false);

	
	if($shop[0]["currency_id"] == $currencyId && $shop[0]["distanceSystem"] == $distanceSystem){
		$msg[] = [
			"type" => "warning",
			"text" => $this->Core->Translator->translate("Nothing was updated")
		];
	}
	
	if($shop[0]["currency_id"] != $currencyId && $shop[0]["distanceSystem"] != $distanceSystem){
		if($db->query("UPDATE shops SET currency_id=?, distanceSystem=? WHERE id=?",array("isi",$currencyId,$distanceSystem,$shop[0]["id"]),true)){
			$msg[] = [
				"type" => "success",
				"text" => $this->Core->Translator->translate("Currency and distance system was updated")
			];
		}
		
	}
	
	if($shop[0]["currency_id"] != $currencyId && $shop[0]["distanceSystem"] == $distanceSystem){
		if($db->query("UPDATE shops SET currency_id=? WHERE id=?",array("ii",$currencyId,$shop[0]["id"]),true)){
			$msg[] = [
				"type" => "success",
				"text" => $this->Core->Translator->translate("Currency was updated")
			];
		}
		
	}
	
	if($shop[0]["currency_id"] == $currencyId && $shop[0]["distanceSystem"] != $distanceSystem){
		if($db->query("UPDATE shops SET distanceSystem=? WHERE id=?",array("si",$distanceSystem,$shop[0]["id"]),true)){
			$msg[] = [
				"type" => "success",
				"text" => $this->Core->Translator->translate("Distance system was updated")
			];
		}
	}
	
	return $msg;
}
////////////	
//MEMBERSHIP
public function load_Membership($shopId){
	$db = $this->Core->getDB();
	
	$data = $db->query("SELECT * FROM shop_has_membership WHERE shop_id=? AND status='active'",array("i",$shopId),false);
	
	$data[0]["Core"] = $this->Core;
	
	$this->Core->FrontController->partialRender("edit-shop-membership.php",$data);
}

public function edit_Membership($shopId){
	$db = $this->Core->getDB();
	
	$membershipId = $_POST["membership"]; 
	
	$status;
	

	
	$membership = $db->query("SELECT * FROM shop_has_membership WHERE shop_id=? AND status='active' AND membership_id=?",array("ii",$shopId,$membershipId),false);
	
	if(empty($membership)){
		
		// disable old Membership if ist not paid membership
		if($db->query("UPDATE shop_has_membership SET status='disabled' WHERE shop_id=? AND status='active' and paid='0'",array("i",$shopId),true)){
			//SET new Membership
			if($db->query("INSERT INTO shop_has_membership VALUES (0,?,?,NOW(),0,'active')",array("ii",$shopId,$membershipId,),true)){
				$msg[] = [
						"type" => "success",
						"text" => $this->Core->Translator->translate("Your Membership was updated")
						];
			}
		}else{
			$msg[] = [
				"type" => "error",
				"text" => $this->Core->Translator->translate("You can't change this Membership")
			];
		}
		
		
		
		
		
	}else{
		$msg[] = [
			"type" => "error",
			"text" => $this->Core->Translator->translate("This Membership is already active.")
			
		];
	}
	
	
	return $msg;
	
}
	


	
}