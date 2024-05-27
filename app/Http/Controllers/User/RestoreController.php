<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\RestoreMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RestoreController extends Controller
{
    public function __invoke(Request $request){

        $email= $request->validate([
            'email'=>['required', 'email:rfc,dns', 'email']
        ]);

        $pass= Str::random(7);
        Mail::to($email)->send(new RestoreMail( $pass));


        $user= User::where('email', $email)->first();

        $user->update([
           'password'=>$pass
        ]);
        $user->save();
    }
}
