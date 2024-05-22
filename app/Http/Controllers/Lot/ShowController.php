<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use App\Models\Photo;
use Illuminate\Http\Request;

class ShowController extends BaseController
{
    public function __invoke(Lot $lot){
        $lot_id = $lot->id;
        $lot_photo = Photo::where('lot_id', $lot_id)->get()->pluck('adress')->toArray();
        $lot->images = $lot_photo;
        return $lot;
    }
}
