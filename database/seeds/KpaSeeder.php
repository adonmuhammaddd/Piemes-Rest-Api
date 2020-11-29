<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kpa')->insert([
            'nama_kpa' => 'nama_kpa',
            'cabang' => 'cabang',
            'alamat' => 'alamat'
        ]);
    }
}
