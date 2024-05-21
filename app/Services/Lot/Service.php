<?php

namespace App\Services\Lot;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Service{
    public function store($data){
        $lot = Lot::create([
            'seller'=>auth()->user()->id,
            'title'=>$data['title'],
            'description'=>$data['description'],
            'status'=>'active',
            'start_cost'=>$data['start_cost'],
            'bet_step'=>$data['bet_step'],
            'category_id'=>$data['category_id'],
        ]);
        return $lot;

    }
}
