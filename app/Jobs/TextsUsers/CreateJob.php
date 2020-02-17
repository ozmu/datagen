<?php

namespace App\Jobs\TextsUsers;

use Carbon\Carbon;
use App\Models\TextUser;
use App\Models\Tag;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $textUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TextUser $textUser)
    {
        $this->textUser = $textUser;
    }

    /**
     * Compute all entity tags and save DB
     */
    private function allTags(){
        $pattern = "/<START:(.+?)>(.+?)<END>/i";
        preg_match_all($pattern, $this->textUser["tagged_text"], $matches);
        $tags = [];
        for ($c = 0; $c < count($matches[2]); $c++){
            $data = [
                "tag_user_id" => $this->textUser->id,
                "entity" => trim($matches[2][$c]),
                "type" => $matches[1][$c]
            ];
            $created = Tag::create($data);
        }
    }

    /**
     * Check for verified text
     */
    private function verifiedTags(){
        $maximum_user_for_text = Setting::where('key', 'maximum_user_for_text')->first() ? (int) Setting::where('key', 'maximum_user_for_text')->first()->value : 10;
        if ($this->textUser->text->users->count() == $maximum_user_for_text){
            $tag_verify_rate = Setting::where('key', 'tag_verify_rate')->first() ? (int) Setting::where('key', 'tag_verify_rate')->first()->value : 70;
            $allTexts = TextUser::where('text_id', $this->textUser->text_id)->get();
            $allTags = [];
            foreach($allTexts as $text){
                foreach($text->tags as $tag){
                    array_push($allTags, $tag->type . ":" . $tag->entity);
                }
            }
            $counts = array_count_values($allTags);
            $verified_tags = [];
            foreach ($counts as $tag => $count){
                if ($count * 100 / $maximum_user_for_text >= $tag_verify_rate){
                    array_push($verified_tags, $tag);
                }
            }
            $this->checkVerifiedTags($verified_tags); // Format: ['type:entity']
            $this->checkVerifiedTexts();
        }
    }

    /**
     * Check verified tags for each TextUser object...
     */
    private function checkVerifiedTags($verified_tags){
        $allTexts = TextUser::where('text_id', $this->textUser->text_id)->get();
        foreach($allTexts as $text){
            $tags = Tag::where('text_user_id', $text->id)->get();
            foreach ($tags as $tag){
                if (in_array($tag->type . ":" . $tag->entity, $verified_tags)){
                    $tag->is_verified = true;
                    $tag->verified_at = (string) Carbon::now();
                    $tag->save();
                }
            }
        }
    }

    /**
     * Check texts
     */
    private function checkVerifiedTexts(){
        $text_verify_rate = Setting::where('key', 'text_verify_rate')->first() ? (int) Setting::where('key', 'text_verify_rate')->first()->value : 50;
        $tags = $this->textUser->tags;
        if ($tags->where('is_verified', true)->count() * 100 / $tags->count() >= $text_verify_rate){
            $this->textUser->is_verified = true;
            $this->textUser->verified_at = (string) Carbon::now();
            $this->textUser->save();
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->allTags(); // Check all tags in string and store to database.
        $this->verifiedTags(); // Check "tags" column on database and compute percent and verify.
    }
}
