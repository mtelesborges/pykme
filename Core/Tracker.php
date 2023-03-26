<?php

class Tracker
{
    protected Core $Core;
    public $lat;
    public $lng;
    public $city;
    public $currency;
    public $country;
    public $timezone;
    public $ip;
    public $userLang;

    public function __construct($core)
    {
        $this->Core = $core;
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function trackUser(): void
    {
        $new_arr[] = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));


        $this->lat = $new_arr[0]['geoplugin_latitude'];
        $this->lng = $new_arr[0]['geoplugin_longitude'];
        $this->currency = $new_arr[0]['geoplugin_currencyCode'];
        $this->country = $new_arr[0]['geoplugin_countryName'];
        $this->timezone = $new_arr[0]['geoplugin_timezone'];
        $this->userLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if (empty($new_arr[0]['geoplugin_city'])) {
            $this->city = $new_arr[0]['geoplugin_region'];
        } else {
            $this->city = $new_arr[0]['geoplugin_city'];
        }

        if (empty($this->city)) {
            $this->city = $new_arr[0]['geoplugin_regionName'];
        }

        $db = $this->Core->DB;
        $db->query("INSERT INTO visits VALUES(0,?,?,?,?,?,?,?,?,?,?,NOW(),?)",
            array("sssssssssss",
                "" . $this->ip . "",
                "" . $this->Core->FrontController->Router->Controller . "",
                "" . $this->Core->FrontController->Router->Action . "",
                "" . $this->Core->FrontController->Router->Parameters . "",
                "" . $this->lat . "",
                "" . $this->lng . "",
                "" . $this->city . "",
                "" . $this->country . "",
                "" . $this->currency . "",
                "" . $this->userLang . "",
                "" . $this->getUserId() . "",
            ), true);
        $this->checkUserIp($this->ip);
    }

    public function getUserId()
    {
        return "0";
    }

    public function checkUserIp($ip)
    {
        $lockedIps = array("123.58.212.79", "49.7.21.105", "49.7.21.106");

        if (in_array($ip, $lockedIps)) {
            echo "We blocked your IP. Want to contact us? whiterose@pykme.com";
            die();
            exit;
        }
    }
}