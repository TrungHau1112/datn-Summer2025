<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $data;

    public $customer;
    public function __construct($data, $customer = false)
    {
        $this->data = $data;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->data;
        try {
            if ($this->customer) {
                Mail::send('client.pages.contact.user_contact', $this->data, function ($message) {
                    $message->to($this->data['email'])
                        ->subject('Chúng tôi đã nhận được liên hệ của bạn - TheGioiNoiThat');
                });
            }
            Mail::send('client.pages.contact.send', $data, function ($message) {
                $message->to('vudevweb@gmail.com')
                    ->subject('Thông tin liên hệ của khách: ' . $this->data['name']);
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
