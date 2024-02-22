<?php

namespace App\Mail\company;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title, $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($title, $userName)
    {
        $this->title = $title;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mail from Referdio')
            ->view('company.email.company_welcome');
    }
}
