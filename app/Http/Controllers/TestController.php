<?php

namespace App\Http\Controllers;

use App\Mail\SentMail;
use App\Models\Complaint;
use App\Models\Lot;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index(Request $request){
        $compl= Complaint::get()->random();
        $author= $compl->author;
        $target= $compl->target;
        return response()->json([
            'complaint'=>$compl,
            'author'=>$author,
            'target'=>$target
        ]);
    }
}
