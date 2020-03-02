<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $table = "drafts";

    protected $fillable = [
        "user_id", "text_id", "tagged_text"
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function text(){
        return $this->belongsTo('App\Models\Text', 'text_id', 'id');
    }
}
