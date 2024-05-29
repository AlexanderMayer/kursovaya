<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{

    public function index(){

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

        }catch(\Exception $exception){
            Db::rollBack();
            return $exception->getMessage();
        }
    }
}
