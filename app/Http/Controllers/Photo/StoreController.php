<?php

namespace App\Http\Controllers\Photo;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use App\Models\Photo;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(Lot $lot, Request $request){
        if(auth()->id() == $lot->seller){
            $photos = $request->file('photos');
            $this->service->store($lot, $photos);

            return response()->json([
                'message'=>'The request has been processed.'
            ], 200);

        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
