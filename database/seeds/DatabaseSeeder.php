<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UserSeeder::class,
             OauthClientSeeder::class,
             MswLocationSeeder::class,
             MswForecastSeeder::class,
             SpotSeeder::class,
         ]);
    }
}
