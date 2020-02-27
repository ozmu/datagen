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

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Utils;
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
                "text_user_id" => $this->textUser->id,
                "entity_mention" => trim($matches[2][$c]),
            ];
            $entity = Entity::where('entity', strtoupper($matches[1][$c]));
            if ($entity->count()){
                $data["entity_type_id"] = $entity->first()->id;
            }
            $created = Tag::create($data);
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
