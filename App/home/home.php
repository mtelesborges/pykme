<?php

class home{
	public $Core;
	public function __construct($core){
		$this->Core = $core;
                $shops = $this->getShopsNearBy();
                $categories = $this->Core->getDB()->query("SELECt * FROM category WHERE status=?",array("s","active"),false);
		$view = [
			"File" 				=> "home.php",
			"PageTitle" 		=> $core->Translator->translate("Take your pick! Food, groceries and more."),
			"PageDescription" 	=> $core->Translator->translate("Welcome to pykme.com. We are a marketplace for restaurants, retail, shops and taxi"),
			"Design"			=> "Intro",
			"CSS"				=> array("home.css"),
			"JS"				=> array(""),
			"Keywords"			=> array(
										$core->Translator->translate("Delivery"),
										$core->Translator->translate("Restaurants"),
										$core->Translator->translate("Groceries"),
										$core->Translator->translate("Free delivery Software"),
										),
			"Data"				=> array("shops" => $shops, "categories" => $categories),
			"Core"				=> $this->Core
		];
		$this->Core->FrontController->render($view);
	}
        
        public function getShopsNearBy(){
            $lat = $this->Core->Tracker->lat;
            $lng = $this->Core->Tracker->lng;
            
            include_once("App/search/index.php");
            
            $s = new searchShops($this->Core);
            return $s->searchNearBy($lat,$lng);
        }
}