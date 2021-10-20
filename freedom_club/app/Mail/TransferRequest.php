<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $stockTransfer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($stockTransfer)
    {
        $this->stockTransfer = $stockTransfer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.TransferRequest');
    }
}
