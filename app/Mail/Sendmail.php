<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;

class Sendmail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    protected $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param Client $client
     * @param string $pdfPath
     */
    public function __construct(Client $client, string $pdfPath)
    {
        $this->client = $client;
        $this->pdfPath = $pdfPath;

        // Vérifiez que le fichier PDF existe
        if (!file_exists($this->pdfPath)) {
            throw new \Exception("Le fichier PDF n'existe pas à l'emplacement spécifié : " . $this->pdfPath);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue chez nous !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // Attache le fichier PDF avec le chemin spécifié
            Attachment::fromPath($this->pdfPath)
                ->as('client_details.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
