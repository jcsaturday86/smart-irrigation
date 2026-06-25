<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
{
    if (!Auth::check()) {
        return redirect('login');
    }
    if (Auth::user()->role !== $role) {
        Log::warning('User does not have required role.');
        abort(403, 'Unauthorized access.');
    }
    return $next($request);
}
}

