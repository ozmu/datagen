<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";

    protected $fillable = [
        'text_user_id', 'type', 'entity', 'is_verified'
    ];
    
    public function textUser(){
        return $this->belongsTo('App\Models\TextUser', 'text_user_id', 'id');
    }
}
