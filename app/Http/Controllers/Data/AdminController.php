<?php

namespace App\Http\Controllers\Data;

use App\User;
use App\Models\TextUser;
use App\Models\Setting;
use App\Jobs\TextsUsers\CreateJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function getSettings(Request $request){
        return Setting::all();
    }

    public function updateSettings(Request $request){
        if(is_array($request->input('settings'))){
            foreach($request->input('settings') as $setting){
                $s = Setting::where('key', $setting["key"]);
                if ($s->count() && $s->first()->value != $setting["value"]){
                    $s = $s->first();
                    $s->value = $setting["value"];
                    $s->save();
                    if ($s->key == "tag_verify_rate" || $s->key == "text_verify_rate"){
                        // If verify rates changes, all textuser objects calculate again.
                        foreach(collect(TextUser::all())->unique('text_id') as $textUser){
                            CreateJob::dispatch($textUser);
                        }
                    }
                }
                else {
                    continue;
                }
            }
            return "Update success!";
        }
        abort(403);
    }
}
