<?php

namespace App\Models;

use App\Models\Draft;
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
            $draft = Draft::where(['text_id' => $text->id, 'user_id' => $userId]);
            if (!$draft->count() && TextUser::where('text_id', $text->id)->count() < $maximum_user_for_text && !in_array($userId, $text->users()->pluck('user_id')->toArray())){
                $texts->push($text->id);
            }
        }
        return Text::find($texts->random());
    }
}
