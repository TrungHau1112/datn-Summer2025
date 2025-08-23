<?php

if (!function_exists('sendMessageTele')) {
    function sendMessageTele($message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $client = new GuzzleHttp\Client();
        $client->post($url, [
            'form_params' => [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
                'parse_mode' => 'Markdown',
            ],
        ]);

    }
}