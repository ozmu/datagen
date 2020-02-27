<?php

namespace App\Jobs\TextsUsers;

use App\Models\TextUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
     * Get entities to array
     * Ex. ["entity" => $entity, "type" => $type]
     */
    private function entities($data){
        $d = collect();
        for ($i = 0; $i < count($data[2]); $i++){
            $d->push(collect([
                'entity' => trim($data[2][$i]),
                'type' => $data[1][$i]
            ]));
        }
        return $d;
    }

    /**
     * Compute all entity tags and save DB
     */
    private function allTags(){
        $pattern = "/<START:(.+?)>(.+?)<END>/i";
        preg_match_all($pattern, $this->beforeTaggedText, $matchesBefore);
        preg_match_all($pattern, $this->textUser["tagged_text"], $matchesAfter);
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
