<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AccountsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $roles = json_decode($user->roles, true);
        
        if(in_array('Administrator', $roles) || in_array('Accounts', $roles)){
            return $next($request);   
        }
        else {
            abort(400, 'Unauthorized action');
        }
    }
}
