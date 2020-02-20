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

    public function widgets(Request $request){
        $scope = $request->input('scope');
        if ($scope == "balance"){
            return [
                "balance" => $request->user()->balance(),
                "notVerifiedBalance" => $request->user()->balance(false)
            ];
        }
    }
}
