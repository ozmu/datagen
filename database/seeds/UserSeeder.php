<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Admin', 'email' => 'q@q.q', 'password' => Hash::make('password'), 'is_admin' => 1]);
    }
}