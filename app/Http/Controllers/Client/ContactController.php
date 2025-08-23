<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailContact;

class ContactController extends Controller
{
    public function contact()
    {
        $config = $this->config();
        return view('client.pages.contact.index', compact('config'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Nhập sai định dạng tên.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email phải đúng định dạng.',

            'message.required' => 'Vui lòng nhập lời nhắn.',
            'message.string' => 'Tin nhắn phải là một chuỗi ký tự.',
        ]);


        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_message' => $request->message,
        ];


        SendMailContact::dispatch($data, true);
        SendMailContact::dispatch($data, false);


        return back()->with('success', 'Gửi liên hệ thành công!');
    }



    private function config()
    {
        return [
            'css' => [
                "client_asset/custom/css/contact.css",
            ],
            'js' => [
                "https://freshcart.codescandy.com/assets/libs/rater-js/index.js",
                "client_asset/custom/js/home.js",
            ],
            'model' => ''
        ];
    }
}
