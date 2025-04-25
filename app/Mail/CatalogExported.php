<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable class for sending product catalog export completion notifications
 */
class CatalogExported extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance
     *
     * @param string $filename Name of the exported catalog file
     * @param string $downloadUrl Temporary download URL for the exported catalog
     */
    public function __construct(
        public string $filename,
        public string $downloadUrl
    ) {
    }

    /**
     * Build the message
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address')) // todo: constructor
            ->subject(__('catalog.exported_successfully'))
            ->view('emails.catalog-exported')
            ->with([
                'filename' => $this->filename,
                'downloadUrl' => $this->downloadUrl
            ]);
    }
}
