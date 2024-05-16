<?php

namespace App\Services\User;
use App\Models\User;

class Service{
    public function store($data){
        User::create($data);
    }
}
