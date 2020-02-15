<?php

namespace App;

use App\Models\Setting;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function texts(){
        return $this->belongsTo('App\Models\TextUser', 'id', 'user_id');
    }

    public function balance(){
        $coin_factor = Setting::where('key', 'coin_factor')->first() ? (float) Setting::where('key', 'coin_factor')->first()->value : 1;
        $all = ["verified" => 0, "pending" => 0];
        foreach($this->texts()->get() as $text){
            if (isset($text->tags)){
                if (isset($text->verified_tags)){
                    $all["verified"] += count(json_decode($text->verified_tags)) * $coin_factor;
                }
                else {
                    $all["pending"] += count(json_decode($text->tags)) * $coin_factor;
                }
            }
        }
        return $all;
    }
}
