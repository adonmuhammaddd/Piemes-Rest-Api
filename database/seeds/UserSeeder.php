<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->inser([
            'nama' => 'nama',
            'cabang' => 'cabang',
            'jabatan' => 'jabatan',
            'username' => 'username',
            'email' => 'email',
            'password' => Hash::make('password'),
            'role' => 'role',
        ]);
    }
}
