<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sendmail;
use App\Events\ClientCreated;

class SendClientEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $pdfPath;

    public function __construct(Client $client, string $pdfPath)
    {
        $this->client = $client;
        $this->pdfPath = $pdfPath;
    }

    public function handle(ClientCreated $event)
    {
        // Déléguer l'envoi de l'email à un job
        SendClientEmailJob::dispatch($event->client, $this->pdfPath);
    }
}
