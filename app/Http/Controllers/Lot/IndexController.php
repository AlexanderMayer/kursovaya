<?php

namespace App\Http\Controllers\Lot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __invoke(){
        $user = auth()->user();
        return $user->sold()->get();
    }
}
