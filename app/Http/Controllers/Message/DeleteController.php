<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Message $message){
        $user = auth()->user();
        $recipient_id= $message->recipient;
        if($user->id == $message->sender){
            $message->delete();
            return response()->json([
                'message'=>'The request has been processed.'
            ], 200);

//            return redirect()->route('message.create', $recipient_id);
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
