<?php

class message{
    public function __construct($Core) {
        if($_POST){
            $data["Core"] = $Core;
            $data["Message"] = $_POST;
            $Core->FrontController->partialRender("modal-messages.php",$data);
        }
    }
}
