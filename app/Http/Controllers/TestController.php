<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function create(){
        return view('imgtest');
    }

    public function store(Request $request){
//        $path = $request->file('image')->store('uploads', 'public');
//        auth()->user()->avatar()->path = $path;
        $blank = User::where('id',6)->first()->avatar;
        return view('imgtest', compact('blank'));
    }
}
