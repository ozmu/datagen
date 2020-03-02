<?php

namespace App\Models;

use App\Models\TextUser;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    protected $table = "texts";

    protected $fillable = [
        "text"
    ];

    public function users(){
        return $this->belongsTo('App\Models\TextUser', 'id', 'text_id'); 
    }

    public static function random($userId){
        $maximum_user_for_text = Setting::where('key', 'maximum_user_for_text')->first() ? (int)Setting::where('key', 'maximum_user_for_text')->first()->value : 10;
        $texts = collect([]);
        foreach(Text::all() as $text){
            if (TextUser::where('text_id', $text->id)->count() < $maximum_user_for_text && !in_array($userId, $text->users()->pluck('user_id')->toArray())){
                $texts->push($text->id);
            }
        }
        return Text::find($texts->random());
        /*
        $textUser = TextUser::groupBy('text_id')->selectRaw('count(*) as total, text_id')->get()->where('total', '<', $maximum_user_for_text);
        foreach($textUser as $text){
            $user = TextUser::where(['text_id' => $text->text_id, 'user_id' => $userId]);
            if (!$user->count()){
                return Text::find($text->text_id); 
            }
        }
        return [];
        */

        /*
        $user = TextUser::where('user_id', '!=', $userId);
        if ($user->count()){
            return [];
        }
        $rand = $user->get()->countBy('text_id')->map(function($item, $key){return ["count" => $item, "text_id" => $key];})->where('count', '<', $maximum_user_for_text);
        if ($rand->count()){
            return Text::find($rand->random()->text_id);
        }
        return [];
        */
    }
}
