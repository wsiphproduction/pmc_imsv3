<?php

namespace App\Mail;
use App\logistics;
use App\PO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PoUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $logistic_id;
    public $status;
    public $l;
    public $p;
    

    public function __construct($logistic_id, $status)
    {
        $this->logistic_id = $logistic_id;
        $this->status = $status;

        $this->l = logistics::where('id', $this->logistic_id)->first();
        $this->p = PO::where('id', $this->l->poId)->first();
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
                ->view('mail.poupdate');
      
    }
}
