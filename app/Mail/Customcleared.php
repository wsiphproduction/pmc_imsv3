<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Customcleared extends Mailable
{
    use Queueable, SerializesModels;

    public $h;

    public function __construct($po)
    {
        $this->h = $po;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.customcleared')
                    ->subject('PO Update');
    }
}
