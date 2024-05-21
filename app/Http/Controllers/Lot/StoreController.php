<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lot\StoreRequest;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request){
        $data= $request->validated();
        $photos = $request->file('photos');
        $obj = $this->service->store($data, $photos);
        return $obj;
    }
}
