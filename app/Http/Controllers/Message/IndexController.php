<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request){
        $user = auth()->user();
        $user->load('sentMsg', 'recMsg');

        return response()->json([
            'user' => $user,
        ]);
    }
}
