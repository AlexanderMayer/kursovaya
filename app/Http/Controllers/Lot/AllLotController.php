<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AllLotController extends Controller
{
    public function __invoke(Request $request)
    {
        $cat_id = $request->input('category_id');

        $query = Lot::query()->orderBy('created_at', 'asc')->where('status', 'active');

        if ($cat_id) {
            $query->where('category_id', $cat_id);
        }

        $currentDate = Carbon::now();

        $lots = $query->get();

        $activeLots = [];

        foreach ($lots as $item) {
            if ($item->end_bidding < $currentDate) {
                $item->checkInactiveStatus($item);
            }

            if ($item->status == 'active') {
                $activeLots[] = $item;
            }
        }

        return $activeLots;
    }
}
