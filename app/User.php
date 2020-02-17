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
        'password', 'remember_token', 'texts'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'balance'
    ];

    public function texts(){
        return $this->hasMany('App\Models\TextUser', 'user_id', 'id');
    }

    public function tags(){
        $tags = [];
        foreach($this->texts as $text){
            $tags = array_merge($tags, $text->tags->toArray());
        }
        return collect($tags);
    }

    public function getBalanceAttribute(){
        return $this->balance();
    }

    public function balance(){
        $coin_factor = Setting::where('key', 'coin_factor')->first() ? (float) Setting::where('key', 'coin_factor')->first()->value : 1;
        return $this->texts->where('is_verified', true)->count() * $coin_factor;
        /*
        $tags = $this->tags();
        return [
            "verified" => $tags->where('is_verified', true)->count() * $coin_factor,
            "pending" => $tags->where('is_verified', false)->count() * $coin_factor
        ];
        */
    }
}
