<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lot\UpdateRequest;
use App\Models\Lot;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    public function __invoke(Lot $lot, UpdateRequest $request){
        $data = $request->validated();
        $res = $this->service->update($lot ,$data);
        return $res;
    }
}
