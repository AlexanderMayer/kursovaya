<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Message $message){
        $user = auth()->user();
        if($user->id == $message->sender){
            $message->delete();
            return redirect()->route('message.index');
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
