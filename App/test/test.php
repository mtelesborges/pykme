<?php

class test{
	public $Core;
	public function __construct($core){
		$this->Core = $core;
	}
	public function VIEW_test($parm){
		echo $parm;
		$view = [
			"File" 				=> "view.php",
			"PageTitle" 		=> "This is a Test",
			"PageDescription" 	=> "Hallo this is a test site",
			"Design"			=> "Default",
			"CSS"				=> array("style2.css","main2.css"),
			"JS"				=> array("functions2.js"),
			"Keywords"			=> array("test","its a test","testing"),
			"Data"				=> array("results"),
			"Core"				=> $this->Core
		];
		$this->Core->FrontController->render($view);

	}
}