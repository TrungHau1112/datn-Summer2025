<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $notifiable;
    protected $resetUrl;

    public function __construct($notifiable, $resetUrl)
    {
        $this->notifiable = $notifiable;
        $this->resetUrl = $resetUrl;
    }

    // public function build()
    // {
    //     return $this->markdown('emails.reset-password')
    //                 ->with([
    //                     'user' => $this->notifiable,
    //                     'resetUrl' => $this->resetUrl,
    //                 ]);
    // }
    public function content(): Content
    {
        return new Content(
            view: 'client.emails.reset-password',
            with: [
                'user' => $this->notifiable,
                'resetUrl' => $this->resetUrl,
            ]
        );
    }
}
