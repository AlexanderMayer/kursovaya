<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(){
        $user= auth()->user();
        $user->delete();

        return response()->json([
            "message"=>"User was delete."
        ]);
    }
}
