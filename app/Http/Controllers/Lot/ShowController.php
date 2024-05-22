<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use App\Models\Photo;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Lot $lot){
        $user = auth()->user();
        $user_lots = $user->sold()->pluck('id')->toArray();
        $lot_id = $lot->id;

        foreach($user_lots as $item){
            if($item == $lot_id){
                $lot_photo = Photo::where('lot_id', $lot_id)->get()->pluck('adress')->toArray();
                $lot->images = $lot_photo;
                return $lot;
            }
        }
        return response()->json([
            'message'=>'Error: the transferred lot id does not belong to the user.'
        ], 400);
    }
}
