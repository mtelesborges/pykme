<?php

//@TODO
class Translator
{

    public $lang;
    public $langId;
    private $Core;

    function __construct($core)
    {
        $this->Core = $core;
        if (isset($_SESSION["lang"])) {
            $this->lang = $_SESSION["lang"];
        } else {
            $this->lang = "en";
        }

        $db = $this->Core->getDB();
        $getLang = $db->query("SELECT id FROM languages WHERE code=? AND status='active'", array("i", $this->lang), false);
        $this->langId = $getLang[0]["id"];
    }

    public function getLanguages()
    {
        $db = $this->Core->getDB();
        $results = $db->query("SELECT * FROM languages WHERE status=?", array("s", "active"), false);
        return ($results);
    }

    function translate($value)
    {
        // Function to translate
        include("Core/lang/" . $this->lang . ".php");

        if (empty($lang[$value])) {
            return $value;
        } else {
            return $lang[$value];
        }
    }

    function changeLang($lang, $url)
    {

        $_SESSION["lang"] = $lang;
        $this->lang = $lang;


        $db = $this->Core->getDB();
        $query = $db->query("SELECT * FROM url WHERE url = ?", array('s', $url), false);

        if (!empty($query)) {
            $url = $query[0];
            $query2 = $db->query("SELECT * FROM url WHERE page_id = ? AND lang = ?", array('ss', $url["page_id"], $this->lang), false);
            $url2 = $query2[0]["url"];
            header("Location:/" . $url2 . "");
        } else {
            header("Location:/" . $url . "");
        }
    }

}
