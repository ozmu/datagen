<?php

namespace App\Http\Controllers\Data;

use App\Models\Entity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UtilsController extends Controller
{
    public function entities(Request $request){
        return Entity::all();
    }
}
