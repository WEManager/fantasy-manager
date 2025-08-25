<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Level0
{
  /**
   * Handle an incoming request.
   *
   * @return mixed
   */
  public function handle(Request $request, Closure $next, $guard = null)
  {
    if (Auth::guard($guard)->check() && Auth::user()->level < 1) {
      return $next($request);
    }

    return redirect()->back();
  }
}
