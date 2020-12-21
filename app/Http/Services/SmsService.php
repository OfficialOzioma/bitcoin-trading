<?php
/**
 * Created by PhpStorm.
 * User: bacchu
 * Date: 8/24/17
 * Time: 1:34 PM
 */

namespace App\Http\Services;

use Aloha\Twilio\Twilio;

class SmsService
{
    protected $twilio;

    public function __construct()
    {
        $sid = allsetting('twillo_secret_key');
        $token = allsetting('twillo_auth_token');
        $from = allsetting('twillo_number');

        $this->twilio = new Twilio($sid, $token, $from);
    }

    public function send($number, $message)
    {
        try {
            $this->twilio->message($number, $message);
        } catch (\Exception $e) {


            return false;

        }

        return true;
    }
}
