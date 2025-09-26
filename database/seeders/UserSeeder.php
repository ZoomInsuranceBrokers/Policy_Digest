<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => 1,
                'company_id' => 0,
                'first_name' => 'Ritesh',
                'last_name' => 'Aggarwal',
                'full_name' => 'Ritesh Aggarwal',
                'email' => 'ritesh.aggarwal@zoominsurancebrokers.com',
                'password' => Hash::make('Maxout@180'),
                'is_delete' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
