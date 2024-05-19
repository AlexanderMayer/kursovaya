<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, User $user){
        $data= $request->validated();
        dd($user);

        if($request->file('avatar')){

            $path = $request->file('avatar')->store('uploads', 'public');
            $this->service->update($data, $path);
        }else{
            $this->service->update($data);
        }
    }
}
