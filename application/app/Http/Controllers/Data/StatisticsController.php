<?php

namespace App\Http\Controllers\Data;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function entities(Request $request){
        $period = $request->input('period');
        if ($request->input('last')){
            return $request->user()->tags()->sortByDesc("id")->take($request->input('last'));
        }
        if ($period == 'daily'){
            return $request->user()->tags();
        }
    }
}
