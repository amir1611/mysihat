<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon; class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Patient',
                'email' => 'patient@mysihat.com',
                'type' => 0,
                'password' => bcrypt('1234'),
                'email_verified_at' => Carbon::now(), 
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mysihat.com',
                'type' => 1,
                'password' => bcrypt('1234'),
                'email_verified_at' => Carbon::now(), 
            ],
            [
                'name' => 'Doctor',
                'email' => 'doctor@mysihat.com',
                'type' => 2,
                'password' => bcrypt('1234'),
                'email_verified_at' => Carbon::now(), 
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}