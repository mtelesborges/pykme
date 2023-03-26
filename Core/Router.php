<?php

class Router
{

    public $Controller;
    public $Action;
    public $Parameters;
    public $Url;
    public $Core;

    public function __construct($core)
    {
        $this->Core = $core;
    }

    public function getUrl($toParse = null)
    {
        if ($toParse == null) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        } else {
            $path = parse_url($toParse, PHP_URL_PATH);
        }

        $url = trim($path, "/");
        $request_url = explode("/", $url, 3);

        // API ACCESS
        if ($request_url[0] == "api") {
            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Max-Age: 1000');
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        }


        if ($request_url[0] == "translate") {
            $lang = $request_url[1];

            if (empty($request_url[2])) {
                $link = "home";
            } else {
                $link = $request_url[2];
            }
            $this->Core->Translator->changeLang($lang, $link);
            // Redirected
            die();
        }

        if (empty($request_url[0])) {
            $this->Controller = "home";
        } else {
            $this->Controller = strtolower($request_url[0]);
        }

        if (!empty($request_url[1])) {
            $this->Action = strtolower($request_url[1]);
        }

        if (!empty($request_url[2])) {
            $this->Parameters = $request_url[2];
        }

        $this->Url = $url;

    }

}