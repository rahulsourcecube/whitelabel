<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\company\CompanyWelcomeMail;
use Illuminate\Support\Facades\Log;




class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $message;
    protected $userName;
    protected $data;
    protected $type;
    // protected $details;

    public function __construct($to, $subject, $message, $userName, $data = [], $type = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->userName = $userName;
        $this->data = $data;
        $this->type = $type;
        
        
        // $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //  try {
            // Log before sending the email
            Log::info('Sending email to: ' . $this->to);
            if ($this->type == 'user') {
                Mail::send('user.email.welcome', $this->data, function ($message) {
                    $message->to($this->to);
                    $message->subject($this->subject);
                });
            } else {
        
                Mail::to($this->to)->send(new CompanyWelcomeMail($this->subject, $this->userName, $this->data));
                // Mail::send('company.email.company_welcome', $this->data, function ($message) {
                //     $message->to($this->to);
                //     $message->subject($this->subject);
                // });
            }

            // Send the email

            // Log after sending the email
            Log::info('Email sent successfully to: ' . $this->to);
        // } catch (\Exception $e) {
        //     // Log any errors that occur
        //     Log::error('Failed to send email to ' . $this->to . ': ' . $e->getMessage());
        // }
    }
}
