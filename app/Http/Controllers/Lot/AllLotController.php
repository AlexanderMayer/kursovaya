<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Illuminate\Http\Request;

class AllLotController extends Controller
{
    public function __invoke(Request $request){
        $cat_id = $request->input('category_id');

        $query = Lot::query()->orderBy('created_at', 'asc');;

        if ($cat_id) {
            $lots = $query->where('category_id', $cat_id)->get();
        } else {
            $lots = $query->get();
        }

        return $lots;
    }
}
