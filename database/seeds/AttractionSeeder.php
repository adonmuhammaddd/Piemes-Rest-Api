<?php

use Illuminate\Database\Seeder;

class AttractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attractions')->insert([
            'tempat_wisata'=>'Borobudur',
            'provinsi' => 'Jawa Tengah',
            'jumlah_pengunjung' => 5000
        ]);

        DB::table('attractions')->insert([
            'tempat_wisata'=>'Labuan Bajo',
            'provinsi' => 'Nusa Tenggara Timur',
            'jumlah_pengunjung' => 3000
        ]);


        DB::table('attractions')->insert([
            'tempat_wisata'=>'Candi Prambanan',
            'provinsi' => 'Yogyakarta',
            'jumlah_pengunjung' => 2400
        ]);

        DB::table('attractions')->insert([
            'tempat_wisata'=>'Pulau Komodo',
            'provinsi' => 'Nusa Tenggara Timur',
            'jumlah_pengunjung' => 3000
        ]);


        DB::table('attractions')->insert([
            'tempat_wisata'=>'Tangkuban Perahu',
            'provinsi' => 'Jawa Barat',
            'jumlah_pengunjung' => 4000
        ]);


        DB::table('attractions')->insert([
            'tempat_wisata'=>'Bromo',
            'provinsi' => 'Jawa Timur',
            'jumlah_pengunjung' => 7000
        ]);

        DB::table('attractions')->insert([
            'tempat_wisata'=>'Monas',
            'provinsi' => 'Jakarta',
            'jumlah_pengunjung' => 1000
        ]);

        DB::table('attractions')->insert([
            'tempat_wisata'=>'Taman Mini Indonesia',
            'provinsi' => 'Jakarta',
            'jumlah_pengunjung' => 1200
        ]);
    }
}
