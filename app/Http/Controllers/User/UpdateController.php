<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request){

        $data= $request->validated();
        $pass1= $request->input('password1',0);
        $pass2= $request->input('password2',0);

        if($pass1 && $pass2){
            if($pass1 == $pass2){
                $this->service->update($data, $pass1);
            }else{
                return response()->json([
                    "message"=> "Password1 and password2 are different."
                ]);
            }
        }else{
            $this->service->update($data);
        }

    }
}
