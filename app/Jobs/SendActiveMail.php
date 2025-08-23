<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyAccount;

class SendActiveMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $account;
    public function __construct($account)
    {
        $this->account = $account;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->account->email)->send(new VerifyAccount($this->account));
    }
}
