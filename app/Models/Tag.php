<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";

    protected $appends = [
        "entity_type"
    ];

    protected $fillable = [
        'text_user_id', 'entity_mention', 'entity_type_id', 'is_verified'
    ];
    
    public function textUser(){
        return $this->belongsTo('App\Models\TextUser', 'text_user_id', 'id');
    }

    public function entityType(){
        return $this->belongsTo('App\Models\Entity', 'entity_type_id', 'id');
    }

    public function getEntityTypeAttribute(){
        return $this->entityType()->first();
    }
}
