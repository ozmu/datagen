<?php

namespace App\Models;

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
}
