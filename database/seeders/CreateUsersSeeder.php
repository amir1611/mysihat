<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            // Admin
            [
                'name' => 'Admin',
                'ic_number' => '800101-14-1234',
                'email' => 'admin@mysihat.com',
                'password' => bcrypt('password'),
                'gender' => 'male',
                'date_of_birth' => '1980-01-01',
                'phone_number' => '60123456789',
                'type' => 0, // Admin type
                'email_verified_at' => Carbon::now(),
            ],

            // Patients
            [
                'name' => 'Nurul',
                'ic_number' => '900725-10-7890',
                'email' => 'nurul@mysihat.com',
                'password' => bcrypt('1234'),
                'gender' => 'female',
                'date_of_birth' => '1990-07-25',
                'phone_number' => '60123456792',
                'type' => 1, // Patient type
                'email_verified_at' => Carbon::now(),
            ],
            [
                'name' => 'Ming',
                'ic_number' => '951130-08-1234',
                'email' => 'ming@mysihat.com',
                'password' => bcrypt('1234'),
                'gender' => 'male',
                'date_of_birth' => '1995-11-30',
                'phone_number' => '60123456793',
                'type' => 1, // patient type
                'email_verified_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            $savedUser = User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );

            if ($savedUser->type == 1) {
                $savedUser->assignRole('patient');
            } elseif ($savedUser->type == 2) {
                $savedUser->assignRole('doctor');
            }
        }
    }
}
