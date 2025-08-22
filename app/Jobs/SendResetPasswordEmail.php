<?php
namespace App\Jobs;

use App\Mail\CustomResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notifiable;
    protected $resetUrl;

    public function __construct($notifiable, $resetUrl)
    {
        $this->notifiable = $notifiable;
        $this->resetUrl = $resetUrl;
    }

    public function handle()
    {
        Mail::to($this->notifiable->email)
            ->send(new CustomResetPasswordMail($this->notifiable, $this->resetUrl));
    }
}
