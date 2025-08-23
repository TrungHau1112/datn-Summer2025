<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Role::create(['name' => 'Super Admin']);
        $userAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('admin'),
        ]); 
        // $userCustomer = User::create([
        //     'name' => 'customer',
        //     'email' => 'customer@gmail.com',
        //     'password' => Hash::make('admin'),
        // ]); 
        $userAdmin->assignRole('Super Admin');
    }
}
