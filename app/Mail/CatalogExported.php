<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CatalogExported extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $filename,
        public string $downloadUrl
    ) {}

    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->subject('Product Catalog Export Completed')
            ->view('emails.catalog-exported')
            ->with([
                'filename' => $this->filename,
                'downloadUrl' => $this->downloadUrl
            ]);
    }
}
