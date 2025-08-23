<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::all()->each(function ($user) {
            UserAddress::create([
                'user_id' => $user->id,
                'address' => '123 Đường ABC',
                'ward' => 'Phường 1',
                'district' => 'Quận 1',
                'province' => 'TP.HCM',
            ]);
        });
    }
}

