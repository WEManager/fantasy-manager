<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that is loaded on the first page visit.
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determine the current asset version.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return string|null
   */
  public function version(Request $request)
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function share(Request $request)
  {
    return [
      ...parent::share($request),
      'auth' => [
        'user' => $request->user() ? $request->user()->load('club') : null,
      ],
      'ziggy' => fn(): array => [
        ...(new Ziggy())->toArray(),
        'location' => $request->url(),
      ],
      'flash' => fn() => [
        'message' => $request->session()->get('message'),
        'type' => $request->session()->get('type') ?? 'success',
        'data' => $request->session()->get('data'),
      ],
    ];
  }
}
