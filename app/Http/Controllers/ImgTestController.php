<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImgTestController extends Controller
{
    public function create(){
        return view('imgtest');
    }

    public function store(Request $request){
        $path = $request->file('image')->store('uploads', 'public');
        return view('imgtest', compact('path'));
    }
}
