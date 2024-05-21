<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function create(){
        return view('imgtest');
    }

    public function index(Request $request){
//        $path = $request->file('image')->store('uploads', 'public');
//        auth()->user()->avatar()->path = $path;
        $lot = Lot::where('id',14)->first();
        $blank = Photo::where('lot_id',14)->get();
        return view('imgtest', compact('blank'));
    }
}
