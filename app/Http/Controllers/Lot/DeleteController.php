<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Lot $lot, Request $request){
        $user_id = auth()->id();
        if($lot->seller == $user_id){
            $lot->delete();
//            return redirect()->route('lot.index');
            return response()->json([
                'message'=>"Lot was deleted."
            ]);
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
