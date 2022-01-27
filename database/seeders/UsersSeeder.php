<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default admin user 
        User::create([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
