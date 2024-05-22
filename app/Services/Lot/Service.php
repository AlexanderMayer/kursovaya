<?php

namespace App\Services\Lot;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Service{
    public function store($data, $photos){
        $lot = Lot::create([
            'seller'=>auth()->id(),
            'title'=>$data['title'],
            'description'=>$data['description'],
            'status'=>'active',
            'start_cost'=>$data['start_cost'],
            'bet_step'=>$data['bet_step'],
            'category_id'=>$data['category_id'],
        ]);

        if($photos){
            foreach($photos as $photo){
                $path= $photo->store('uploads', 'public');
                $lot->photos()->create([
                    'adress'=>$path,
                ]);
            }
        }
        return $lot;
    }

    public function update($lot, $data){
        $lot = Lot::where('id', $lot->id)->first();
        $lot->update($data);
        return $lot;
    }

}
