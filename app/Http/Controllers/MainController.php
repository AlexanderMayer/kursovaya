<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function enter(){
        return view('layouts.main');
    }

    public function index(){
        $query = Lot::query()->orderBy('created_at', 'asc')->where('status', 'active');
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
        return array_slice($activeLots, 0, 10);
    }


    public function betUp(Lot $lot, Request $request){
        $user= auth()->user();

        $data= $request->validate([
           'new_cost'=>'integer',
           'bet_up'=>'integer'
        ]);
        $new_cost= $data['new_cost'];
        $bet_up= $data['bet_up'];

        try{
            Db::beginTransaction();
            if($bet_up){

                if(!$lot->cost){

                    $lot->cost = $lot->start_cost + $lot->bet_step;
                    $lot->buyer = $user->id;

                }else{
                    $lot->cost += $lot->bet_step;
                    $lot->buyer = $user->id;
                }

            }else{
                $lot->cost += $new_cost;
                $lot->buyer = $user->id;
            }
            $lot->save();
            Db::commit();

            return response()->json([
                "message"=>"Your bet is accepted."
            ]);

        }catch(\Exception $exception){
            Db::rollBack();
            return $exception->getMessage();
        }
    }
}
