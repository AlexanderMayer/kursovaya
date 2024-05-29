<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'message'=> 'Hello, admin!'
        ]);
    }

    public function lots(Request $request){
        $category_id= $request->input('category_id', null);
        $status_id= $request->input('status_id', null);

        $query = Lot::query()->orderBy('created_at', 'asc');

        if($category_id && $status_id){

            $lots = $query->where('category_id', $category_id)->where('status', $status_id)->get();
        }else if($category_id) {
            $lots = $query->where('category_id', $category_id)->get();
        }else if($status_id){
            $lots = $query->where('status', $status_id)->get();
        }else{
            $lots = $query->get();
        }

        return $lots;
    }

    public function complaints(){
        $compl= Complaint::all();
        return $compl;
    }

    public function users(){
        $users= User::all();
        return $users;
    }

    public function showUser(User $user){
        return $user;
    }

    public function userLots(User $user){
        return response()->json([
            "user"=>$user->load('sold','bought'),
        ]);
    }

    public function userComplaints(User $user){
        return response()->json([
            "user"=>$user->load('writtenComplaints','receivedComplaints'),
        ]);
    }


    public function userChangeRating(User $user, Complaint $complaint, Request $request){
        //ожидает ключи (2шт, каждый 1 или 0) в запросе для изменения рейтинга поведения
        $target= $complaint->target;

        if($target == $user->id ){
            try{
                Db::beginTransaction();
                $data = $request->validate([
                    'viewed' => 'required|integer',
                    'decision' => 'required|integer'
                ]);

                if ($data['viewed'] == 1) {
                    $complaint->viewed = $data['viewed'];

                    if(!$complaint->decision){
                        $complaint->decision = $data['decision'];
                    }
                }

                $complaint->save();

                if ($data['viewed'] == 1 && $data['decision'] == 1) {
                    $user->rate_decency -= 5;
                    $user->save();
                }
                Db::commit();

            }catch(\Exception $exception){
                Db::rollBack();
                return $exception->getMessage();
            }

        }else{
            return response()->json([
                "message"=>"Invalid request."
            ]);
        }

    }

    public function changeStatus(User $user){

    }

}
