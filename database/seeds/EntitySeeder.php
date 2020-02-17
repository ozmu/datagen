<?php

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entity::create(["entity" => "PERSON", "localized" => "ŞAHIS", "color" => "#C62828"]);
        Entity::create(["entity" => "LOCATION", "localized" => "LOKASYON", "color" => "#1565C0"]);
        Entity::create(["entity" => "ORGANIZATION", "localized" => "ORGANİZASYON", "color" => "#42A5F5"]);
        Entity::create(["entity" => "MISC", "localized" => "ÇEŞİTLİ", "color" => "#E57373"]);
    }
}
