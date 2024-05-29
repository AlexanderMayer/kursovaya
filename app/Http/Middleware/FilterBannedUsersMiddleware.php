<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterBannedUsersMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->activity == 'banned'){
            return response()->json([
                "message"=> "You was banned. You cannot use the service anymore."
            ], 403);
        }else{
            return $next($request);
        }
    }
}
