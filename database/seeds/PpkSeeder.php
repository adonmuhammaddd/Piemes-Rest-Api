<?php

use Illuminate\Database\Seeder;

class PpkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ppk')->insert([
            'id_kpa' => 'id_kpa',
            'nama_ppk' => 'nama_ppk'
        ]);
    }
}
