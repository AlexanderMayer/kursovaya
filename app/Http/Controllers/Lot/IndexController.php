<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(){
        $user = auth()->user();
        $data = $user->sold()->get();

        $data = $data->map(function ($item) {
            $photos = Photo::where('lot_id', $item->id)->get();
            $images = $photos->pluck('adress')->toArray();

            $item->images = $images;

            return $item;
        });

        return $data;
    }
}
