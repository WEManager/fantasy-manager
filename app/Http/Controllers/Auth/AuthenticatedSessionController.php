<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
  /**
   * Show the login page.
   */
  public function create()
  {
    return Inertia::render('auth/login/page', [
      'canResetPassword' => Route::has('password.request'),
      'status' => session('status'),
    ]);
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request)
  {
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route('home'));
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request)
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('home');
  }
}
