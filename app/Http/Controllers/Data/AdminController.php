<?php

namespace App\Http\Controllers\Data;

use App\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function getUsers(Request $request){
        return User::where('id', '!=', $request->user()->id)->with('balance')->get();
    }

    public function getSettings(Request $request){
        return Setting::all();
    }

    public function updateSettings(Request $request){
        return $request;
    }
}
