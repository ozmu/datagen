<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = "entities";

    protected $fillable = [
        "entity", "localized", "color"
    ];

    protected $hidden = [
        "created_at", "updated_at"
    ];
}
