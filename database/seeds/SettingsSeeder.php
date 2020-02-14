<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['key' => 'coin_factor', 'value' => '0.30']);
        Setting::create(['key' => 'tag_verify_rate', 'value' => '70']); 
        Setting::create(['key' => 'text_verify_rate', 'value' => '50']);
        Setting::create(['key' => 'maximum_user_for_text', 'value' => '10']);
    }
}
