<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class IsAdmin
{
    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        $user = Auth::guard($guard)->user();

        if ($user && $user->admin) {
            return $next($request);
        }

        return redirect()->to('/');
    }
}
