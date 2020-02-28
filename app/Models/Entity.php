<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = "entities";

    protected $fillable = [
        "entity", "type", "localized", "color", "is_active"
    ];

    protected $hidden = [
        "created_at", "updated_at"
    ];
}
