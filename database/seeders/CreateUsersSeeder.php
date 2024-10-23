<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
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
                'password' => bcrypt('1234'),
                'gender' => 'male',
                'date_of_birth' => '1980-01-01',
                'phone_number' => '60123456789',
                'type' => 1, // Admin type
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
                'type' => 0, // Patient type
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
                'type' => 0, // Patient type
                'email_verified_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
