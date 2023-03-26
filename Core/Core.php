<?php

class Core
{
    public FrontController $FrontController;
    public DB $DB;
    public User $User;
    public Tracker $Tracker;
    public Translator $Translator;
    public Email $Email;
    public array $weekday = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    public array $currencies;
    public int $currentCurrencyId;
    public string $currentCurrencyCode;
    public string $currentCurrencySymbol;


    public function __construct()
    {
    }

    public function launch(): void
    {

        //get Database Controller
        require_once("Core/DB.php");
        $this->DB = new DB();

        // get Translator
        require_once("Core/Translator.php");
        $this->Translator = new Translator($this);

        //get User Tracker
        require_once("Core/Tracker.php");
        $this->Tracker = new Tracker($this);

        //get Front Controller
        require_once("Core/FrontController.php");
        $this->FrontController = new FrontController($this);

        //get Email function
        require_once("Core/Email.php");
        $this->Email = new Email();

        //get Currencies
        $this->currencies = $this->DB->query("SELECT * FROM currency WHERE status=?", array("s", "active"), false);

        // set currency
        $key = array_search($this->Tracker->currency, array_column($this->currencies, "code"));
        if ($key) {
            $this->currentCurrencyId = $this->currencies[$key]["id"];
            $this->currentCurrencyCode = $this->currencies[$key]["code"];
            $this->currentCurrencySymbol = $this->currencies[$key]["symbol"];
        } else {
            //set euros default
            $this->currentCurrencyId = $this->currencies[3]["id"];
            $this->currentCurrencyCode = $this->currencies[3]["code"];
            $this->currentCurrencySymbol = $this->currencies[3]["symbol"];
        }

    }

    public function getDB(): DB
    {
        return $this->DB;
    }

    public function getPayment(): Payment
    {
        //get Payment function
        require_once("Core/Payment.php");
        return new Payment($this);
    }
}