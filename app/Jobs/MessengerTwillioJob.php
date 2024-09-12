<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\Dette;
use App\Services\MessengerTwillioService;
use App\Services\MessengerTwillioServiceImpl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class MessengerTwillioJob implements ShouldQueue
{
    protected $smsService;

    
    public function handle()
    {
        $notification= new MessengerTwillioServiceImpl();
        $notification->sendSms('+221776722843', 'Hello');    // Appeler la méthode pour envoyer des SMS aux clients avec des dettes
    }
}