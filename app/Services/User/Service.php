<?php

namespace App\Services\User;
use App\Models\User;

class Service{
    public function store($data, $path= null){
        $user = User::create($data);

        if($path){
            $relativePath = 'uploads/' . basename($path);
            $user->avatar = $relativePath;
            $user->save();
        }
    }
}
