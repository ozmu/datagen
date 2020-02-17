<?php

use App\Models\Text;
use App\Models\Tag;
use App\Models\TextUser;
use Illuminate\Database\Seeder;

class TextAndTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text1 = Text::create(['text' => 'Hello World. I\'m from Spain.']);

        $textUser1 = TextUser::create(['user_id' => 1, 'text_id' => $text1->id, 'tagged_text' => 'Hello <START:location> World <END> . I\'m from <START:location> Spain <END>']);
        $tag1 = Tag::create(['text_user_id' => $textUser1->id, 'type' => 'location', 'entity' => 'World']);
        $tag2 = Tag::create(['text_user_id' => $textUser1->id, 'type' => 'location', 'entity' => 'Spain', 'is_verified' => true]);
    }
}
