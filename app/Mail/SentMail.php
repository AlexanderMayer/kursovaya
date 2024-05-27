<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $pass;

    public function __construct($name, $pass)
    {
        $this->name = $name;
        $this->pass = $pass;
    }

    public function build()
    {
        return $this->view('mail.mail')->with(['name' => $this->name, 'pass'=>$this->pass])->subject('Your password');
    }
}
