<?php

namespace App\Events;

use App\Models\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientCreated
{
    use Dispatchable, SerializesModels;

    public $client;
    public $tempPhotoPath;
    public $pdfPath;

    /**
     * Create a new event instance.
     *
     * @param Client $client
     * @param string $tempPhotoPath
     */
    public function __construct(Client $client, $tempPhotoPath, $pdfPath = null)
    {
        $this->client = $client;
        $this->tempPhotoPath = $tempPhotoPath;
        $this->pdfPath = $pdfPath;
    }
}
