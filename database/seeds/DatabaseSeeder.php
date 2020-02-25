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
        // $this->call(UsersTableSeeder::class);
        $this->call(EntitySeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TextAndTagSeeder::class);
        $this->call(TextsSeeder::class);
    }
}
