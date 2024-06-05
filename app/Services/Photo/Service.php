<?php

namespace App\Services\Photo;
use App\Models\Lot;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class Service{
    public function store(Lot $lot, $photos){

        foreach($photos as $photo){
            $path= $photo->store('uploads', 'public');
            $lot->photos()->create([
                'lot_id'=>$lot->id,
                'adress'=>$path,
            ]);
        }
    }
}
