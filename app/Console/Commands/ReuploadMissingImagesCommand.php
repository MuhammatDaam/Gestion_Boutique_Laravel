<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Jobs\VerifyAndReuploadMissingImages;

class ReuploadMissingImagesCommand extends Command
{
    protected $signature = 'reupload:missing-images';
    protected $description = 'Vérifie et réupload les images manquantes des clients';

    public function handle()
    {
        $clients = Client::whereNull('photo')->get();

        foreach ($clients as $client) {
            VerifyAndReuploadMissingImages::dispatch($client);
        }

        $this->info('Les images manquantes ont été vérifiées et la relance est en cours.');
    }
}
