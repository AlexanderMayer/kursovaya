<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestoreMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pass;

    public function __construct( $pass)
    {
        $this->pass = $pass;
    }

    public function build()
    {
        return $this->view('mail.restore')->with(['pass'=> $this->pass])->subject('Your new password.');
    }
}
