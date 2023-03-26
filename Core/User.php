<?php

class User
{
    public $Core;

    public function __construct($core)
    {
        $this->Core = $core;
    }

    public function trackUser(): void
    {

        $db = $this->Core->DB;

        $db->query("INSERT INTO user_visits VALUES(0,?,?,?,?,NOW(),?)",
            array("sssss",
                "" . $_SERVER['REMOTE_ADDR'] . "",
                "" . $this->Core->FrontController->Router->Controller . "",
                "" . $this->Core->FrontController->Router->Action . "",
                "" . $this->Core->FrontController->Router->Parameters . "",
                $this->getUserId(),
            ), true);


    }

    public function getUserId(): string
    {
        return "0";
    }
}