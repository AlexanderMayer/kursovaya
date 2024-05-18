<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(){
        $user = auth()->user();

        return new UserResource($user);
    }
}
