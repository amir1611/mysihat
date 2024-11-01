<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'patient', 'guard_name' => 'web']);
        Role::create(['name' => 'doctor', 'guard_name' => 'web']);

    }
}
