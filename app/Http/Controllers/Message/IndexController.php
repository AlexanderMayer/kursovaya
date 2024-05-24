<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request){
        $user = auth()->user();
        $msg = $user->sentMsg;
        $get_from= Message::where('recipient', $user->id)->get();
        $contacts=[];

        foreach($msg as $el){
            $rect_id = $el->recipient;
            if(!in_array($rect_id, $contacts)){
                $contacts[]= $rect_id;
            }
        }
        foreach($get_from as $el){
            $snd_id = $el->sender;
            if(!in_array($snd_id, $contacts)){
                $contacts[]= $snd_id;
            }
        }

        $contactObjects = [];
        foreach($contacts as $key => $el){
            $obj = User::where('id', $el)->first();
            $contactObjects[$key] = new UserResource($obj);
        }

        return response()->json([
            "user" => new UserResource($user),
            "contacts" => UserResource::collection(collect($contactObjects))
        ]);
    }



//    public function __invoke(Request $request){
//        $user = auth()->user();
//        $user->load('sentMsg', 'recMsg');
//
//        return response()->json([
//            'user' => $user,
//        ]);
//    }
}
