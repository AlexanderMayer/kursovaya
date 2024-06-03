<?php

namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use App\Models\Photo;
use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Lot $lot, Photo $photo, Request $request){

        if(auth()->id() == $lot->seller){
            if($photo->lot_id == $lot->id){
                $photo->delete();

                return response()->json([
                    'message'=>'The request has been processed.'
                ], 200);
//                return redirect()->route('lot.edit',$lot->id);
            }
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
