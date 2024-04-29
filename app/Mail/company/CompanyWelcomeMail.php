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

    public $title, $userName, $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($title, $userName, $data)
    {
        $this->title = $title;
        $this->userName = $userName;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome To Referdio')
            ->view('company.email.company_welcome');
    }
}
