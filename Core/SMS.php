<?php
require_once('Vendors/Twilio/autoload.php');

use Twilio\Rest\Client;

class sms
{
    public $sid = 'AC5f20ffa08891ab95aebfc22e4c4a7708';
    public $token = '4e9faec99d45d8f734be9acdb9e95afd';
    public $TwilioNumber = "pykme";

    public function __construct()
    {

    }

    public function checkPhone($phone)
    {

        try {
            $twilio = new Client($this->sid, $this->token);
            $phone_number = $twilio->lookups->v1->phoneNumbers($phone)->fetch(["type" => ["carrier"]]);;
            return true;

        } catch (\Exception $e) {
            return false;
        }

    }

    public function sendSMS($recever, $msg)
    {
        try {

            $client = new Client($this->sid, $this->token);

            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
            // the number you'd like to send the message to
                $recever,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $this->TwilioNumber,
                    // the body of the text message you'd like to send
                    'body' => $msg
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }
}
