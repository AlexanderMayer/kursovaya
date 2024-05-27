<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __invoke(User $user, Request $request){
        $content= $request->validate([
            'content'=>['required', 'string']
        ]);
        $author= auth()->id();
        $target= $user->id;

        Complaint::create([
            'author'=>$author,
            'target'=>$target,
            'content'=>$content['content']
        ]);
    }
}
