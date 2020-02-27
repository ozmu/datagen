<?php

namespace App\Jobs\TextsUsers;

use Carbon\Carbon;
use App\Models\TextUser;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\Entity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Traits\Utils;

class UpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Utils;
    protected $textUser, $beforeTaggedText;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TextUser $textUser, $beforeTaggedText)
    {
        $this->textUser = $textUser;
        $this->beforeTaggedText = $beforeTaggedText;
    }

    /**
     * Compute all entity tags and save DB
     */
    private function allTags(){
        $pattern = "/<START:(.+?)>(.+?)<END>/i";
        preg_match_all($pattern, $this->beforeTaggedText, $matchesBefore);
        preg_match_all($pattern, $this->textUser["tagged_text"], $matchesAfter);
        if (count($matchesAfter) >= count($matchesBefore)){
            $diff = $this->entities($matchesAfter)->diff($this->entities($matchesBefore));
            foreach($diff as $key => $value){
                $entity = Entity::where('entity', $value["type"]);
                if ($entity->count()){
                    $e = $entity->first();
                    $data = [
                        "text_user_id" => $this->textUser->id,
                        "entity_type_id" => $e->id,
                        "entity_mention" => $value["entity"]
                    ];
                    Tag::create($data);
                }
            }
        }
        else {
            $diff = $this->entities($matchesBefore)->diff($this->entities($matchesAfter));
            foreach($diff as $key => $value){
                $entity = Entity::where('entity', $value["type"]);
                if ($entity->count()){
                    $e = $entity->first();
                    $tag = Tag::where(["text_user_id" => $this->textUser->id, "entity_type_id" => $e->id, "entity_mention" => $value["entity"]]);
                    if ($tag->count()){
                        $tag->first()->delete();
                    }
                }
            }
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
        $this->verifiedTags($this->textUser); // Check "tags" column on database and compute percent and verify.
    }
}
