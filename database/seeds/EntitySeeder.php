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
        // named entities
        Entity::create(["entity" => "PERSON", "type"=> "named", "localized" => "ŞAHIS", "color" => "#C62828", "is_active" => true]);
        Entity::create(["entity" => "LOCATION", "type"=> "named", "localized" => "LOKASYON", "color" => "#1565C0", "is_active" => true]);
        Entity::create(["entity" => "ORGANIZATION", "type"=> "named", "localized" => "ORGANİZASYON", "color" => "#42A5F5", "is_active" => true]);
        Entity::create(["entity" => "MISC", "type"=> "named", "localized" => "ÇEŞİTLİ", "color" => "#E57373"]);

        // numerical entities
        Entity::create(["entity" => "MONEY", "type"=> "numerical", "localized" => "PARA", "color" => "#2E7D32"]);
        Entity::create(["entity" => "NUMBER", "type"=> "numerical", "localized" => "SAYI", "color" => "#66BB6A"]);
        Entity::create(["entity" => "ORDINAL", "type"=> "numerical", "localized" => "SIRA", "color" => "#F57C00"]);
        Entity::create(["entity" => "PERCENT", "type"=> "numerical", "localized" => "YÜZDE", "color" => "#FF7043"]);

        // temporal entities
        Entity::create(["entity" => "DATE", "type"=> "temporal", "localized" => "TARİH", "color" => "#00838F"]);
        Entity::create(["entity" => "TIME", "type"=> "temporal", "localized" => "ZAMAN", "color" => "#26C6DA"]);
        Entity::create(["entity" => "DURATION", "type"=> "temporal", "localized" => "SÜRE", "color" => "#AD1457"]);

        // fine-grained entities
        Entity::create(["entity" => "EMAIL", "type"=> "fine-grained", "localized" => "EPOSTA", "color" => "#EC407A"]);
        Entity::create(["entity" => "URL", "type"=> "fine-grained", "localized" => "URL", "color" => "#6A1B9A"]);
        Entity::create(["entity" => "CITY", "type"=> "fine-grained", "localized" => "ŞEHİR", "color" => "#4527A0"]);
        Entity::create(["entity" => "STATE_OR_PROVINCE", "type"=> "fine-grained", "localized" => "BÖLGE_VEYA_İL", "color" => "#7E57C2"]);
        Entity::create(["entity" => "COUNTRY", "type"=> "fine-grained", "localized" => "ÜLKE", "color" => "#00695C"]);
        Entity::create(["entity" => "NATIONALITY", "type"=> "fine-grained", "localized" => "MİLLİYET", "color" => "#26A69A"]);
        Entity::create(["entity" => "RELIGION", "type"=> "fine-grained", "localized" => "DİN", "color" => "#558B2F"]);
        Entity::create(["entity" => "TITLE", "type"=> "fine-grained", "localized" => "POZİSYON(İŞ)", "color" => "#9CCC65", "is_active" => true]);
        Entity::create(["entity" => "IDEOLOGY", "type"=> "fine-grained", "localized" => "İDEOLOJİ", "color" => "#EF6C00"]);
        Entity::create(["entity" => "CAUSE_OF_DEATH", "type"=> "fine-grained", "localized" => "ÖLÜM_SEBEBİ", "color" => "#6DCC41"]);
    }
}
