<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request){
        return view('app.index');
    }

    public function tagging(Request $request){
        return view('app.tagging');
    }

    public function tagged(Request $request){
        return view('app.tagged');
    }
}
