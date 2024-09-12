<?php
namespace App\Services;

use Twilio\Rest\Client as TwilioClient; // Renommer Twilio Client en TwilioClient
use App\Models\Client; // Renommer votre modèle Client en AppClient
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;


interface MessengerTwillioService
{
    
    public function sendSms($to, $message);
    public function notifyClientsWithDebts();

}