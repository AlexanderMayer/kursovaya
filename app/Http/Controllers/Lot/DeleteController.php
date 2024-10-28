<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke(Lot $lot, Request $request){
        $user = Auth::user();
        if($lot->seller == $user->id || $user->role_id == 2){
            $lot->delete();
            return response()->json([
                'message'=>"Lot was deleted."
            ]);
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 403);
    }
}
