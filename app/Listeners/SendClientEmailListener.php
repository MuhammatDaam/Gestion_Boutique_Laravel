<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sendmail;

class SendClientEmailListener
{
    public function handle(ClientCreated $event)
    {
        $client = $event->client;
        // Vérifiez si la propriété $pdfPath est définie dans l'événement
        if (property_exists($event, 'pdfPath')) {
            $pdfPath = $event->pdfPath;

            // Envoyer un email au client après sa création
            Mail::to($client->user->login)->send(new Sendmail($client, $pdfPath));
        }
    }
}
