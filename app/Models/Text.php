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
        $rand = TextUser::where('user_id', '!=', $userId)->get()->countBy('text_id')->map(function($item, $key){return ["count" => $item, "text_id" => $key];})->where('count', '<', $maximum_user_for_text);
        if ($rand->count()){
            return Text::find($rand->random()->text_id);
        }
        return [];
    }
}
