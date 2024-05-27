<?php

namespace App\Http\Controllers;

use App\Mail\SentMail;
use App\Models\Lot;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index2(){
//        Mail::send(['text'=>'mail.mail'], ['name', 'TEST NAME'], function($message){
//            $message->to('spcc888@ya.ru', 'Some text')->subject('test email');
//            $message->from('servLaravel@ya.ru', 'Web dev blog');
//        });

        $name = 'Igor';

        Mail::to('spcc888@ya.ru')->send(new SentMail($name));
    }




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
