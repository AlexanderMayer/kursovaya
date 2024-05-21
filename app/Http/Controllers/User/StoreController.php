<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request){
        $data = $request->validated();

        if($request->file('avatar')){
            $path = $request->file('avatar')->store('uploads', 'public');
            $this->service->store($data, $path);
        }else{
            $this->service->store($data);
        }

    }
}

//$path = $request->file('image')->store('uploads', 'public');
//auth()->user()->avatar = $path;
