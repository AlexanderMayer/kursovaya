<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Models\User;


class StoreController extends BaseController
{
    public function __invoke(User $recipient, StoreRequest $request){
        $sender = auth()->user();
        $data= $request->validated();
        $photo= $request->file('photo');

        $res = $this->service->store($sender, $recipient, $data, $photo);
        if($res){
            return $res;
        }
        return redirect()->route('message.index');
    }
}
