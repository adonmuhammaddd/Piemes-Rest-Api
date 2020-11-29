<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(AttractionSeeder::class);
        $this->call(KpaSeeder::class);
        $this->call(PpkSeeder::class);
        $this->call(UserSeeder::class);
    }
}
