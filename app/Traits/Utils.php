<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\TextUser;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\Entity;

trait Utils {
    /**
     * Check for verified text
     */
    private function verifiedTags($textUser){
        $maximum_user_for_text = Setting::where('key', 'maximum_user_for_text')->first() ? (int) Setting::where('key', 'maximum_user_for_text')->first()->value : 10;
        if ($textUser->text->users->count() == $maximum_user_for_text){
            $tag_verify_rate = Setting::where('key', 'tag_verify_rate')->first() ? (int) Setting::where('key', 'tag_verify_rate')->first()->value : 70;
            $allTexts = TextUser::where('text_id', $textUser->text_id)->get();
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
            $this->checkVerifiedTags($textUser, $verified_tags); // Format: ['type:entity']
            $this->checkVerifiedTexts($textUser);
        }
    }

    /**
     * Check verified tags for each TextUser object...
     */
    private function checkVerifiedTags($textUser, $verified_tags){
        $allTexts = TextUser::where('text_id', $textUser->text_id)->get();
        foreach($allTexts as $text){
            $tags = Tag::where('text_user_id', $text->id)->get();
            foreach ($tags as $tag){
                if (in_array($tag->entityType->entity . ":" . $tag->entity_mention, $verified_tags)){
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
    private function checkVerifiedTexts($textUser){
        $text_verify_rate = Setting::where('key', 'text_verify_rate')->first() ? (int) Setting::where('key', 'text_verify_rate')->first()->value : 50;
        $tags = $textUser->tags;
        if ($tags->where('is_verified', true)->count() * 100 / $tags->count() >= $text_verify_rate){
            $textUser->is_verified = true;
            $textUser->verified_at = (string) Carbon::now();
            $textUser->save();
        }
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
}