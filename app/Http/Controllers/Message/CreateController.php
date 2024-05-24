<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(User $recipient){
        $user= auth()->user();
        $sent= Message::where('sender', $user->id)->where('recipient', $recipient->id)->get();
        $recieved= Message::where('sender', $recipient->id)->where('recipient', $user->id)->get();

        return response()->json([
            'user'=> new UserResource($user),
            'recipient'=>new UserResource($recipient),
            'sent'=>$sent,
            'recieved'=>$recieved
        ]);
    }
}
