<?php

class FrontController
{

    public Router $Router;
    private Core $Core;

    public function __construct(Core $core)
    {
        $this->Core = $core;
        require_once("Core/Router.php");
        $this->Router = new Router($this->Core);
        $this->Router->getUrl();
    }

    public function checkUrl(): bool
    {
        if (!$this->checkPrettyUrl() && !$this->checkPath()) {
            return false;
        } else {
            return true;
        }
    }

    public function checkPath(): bool
    {
        if (is_dir("App/" . $this->Router->Controller)) {
            return true;
        } else {
            return false;
        }
    }

    public function set_url($url): void
    {
        $this->Router->getUrl($url);
        echo("<script>history.replaceState({},'','$url');</script>");

    }

    public function checkPrettyUrl()
    {
        if (empty($this->Router->Url)) {
           return;
        }

        $db = $this->Core->getDB();
        $query = $db->query("SELECT * FROM url WHERE url=?", array('s', $this->Router->Url), false);

        if (empty($query)) {
            $searchPrettyUrl = $db->query("SELECT * FROM url WHERE controller=? AND action=? AND parameters=?", array("sss", $this->Router->Controller, $this->Router->Action, $this->Router->Parameters), false);

            if (empty($searchPrettyUrl)) {
                return (false);
            }

            header("Location:/" . $searchPrettyUrl[0]["url"]);
            die();
        }

        $result = $query[0];

        if ($result["lang"] == $this->Core->Translator->lang || $result["lang"] == "global") {
            $this->Router->Controller = $result["controller"];
            $this->Router->Action = $result["action"];
            $this->Router->Parameters = $result["parameters"];
            return true;
        }

        // search for translated url
        $langSearch = $db->query("SELECT * FROM url WHERE controller=? AND action=? AND parameters=? AND lang=? AND status='active'", array("ssss", $result["controller"], $result["action"], $result["parameters"], $this->Core->Translator->lang), false);
        if (!empty($langSearch)) {
            header("Location:/" . $langSearch[0]["url"]);
            die();
        }

        $getShopPage = $db->query("SELECT * FROM url WHERE controller=? AND action=? AND parameters=? AND lang='global' AND status='active'", array("sss", $result["controller"], $result["action"], $result["parameters"]), false);
        header("Location:/" . $getShopPage[0]["url"]);
        die();
    }

    public function partialRender($template, $data): void
    {
        if (file_exists("View/templates/parts/" . $template)) {
            include("View/templates/parts/" . $template);
        } else {
            echo "Part Template does not exist";
        }

    }

    public function render($view): void
    {
        /*
                
                EXAMPLE
        $view = [
            "File" 				=> "view.php",
            "PageTitle"                     => "This is a Test",
            "PageDescription"               => "Hallo this is a test site",
            "Design"			=> "Default",
            "CSS"				=> array("style.css","main.css"),
            "JS"				=> array("functions.js"),
            "Keywords"			=> array("test","its a test","testing"),
            "Data"				=> array("results"),
            "Message"			=> array()
        ]

        */

        if (file_exists("View/templates/" . $view["File"])) {
            require_once("View/templates/html/HTMLHeader.php");
            switch ($view["Design"]) {
                case "Default":
                    require_once("View/templates/designs/DefaultHeader.php");
                    break;
                case "Signup":
                    require_once("View/templates/designs/signupHeader.php");
                    break;
                case "Merchant":
                    require_once("View/templates/designs/merchantHeader.php");
                    break;
                case "Intro":
                    require_once("View/templates/designs/IntroHeader.php");
                    break;
            }
            echo "<div class='page-wrapper'>";
            require_once("View/templates/" . $view["File"]);
            echo "</div>";
            switch ($view["Design"]) {
                case "Default":
                    require_once("View/templates/designs/DefaultFooter.php");
                    break;
                case "Signup":
                    require_once("View/templates/designs/signupFooter.php");
                    break;
                case "Merchant":
                    require_once("View/templates/designs/merchantFooter.php");
                    break;
                case "Intro":
                    require_once("View/templates/designs/IntroFooter.php");
                    break;
            }

            require_once("View/templates/html/HTMLFooter.php");
        } else {
            echo "View File does not exist.";
        }


    }

}