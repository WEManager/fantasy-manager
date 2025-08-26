<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerUser;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PlayerController extends Controller
{
  public function index()
  {
    $players = Player::with(['nation', 'club', 'contract'])->get();

    return Inertia::render('players/list/page', ['players' => $players]);
  }

  public function create()
  {
    // Todo: Create a blade template for a formular where we can create a user with role player

    return view('users.create');
  }

  public function edit(User $user)
  {
    return view('users.edit', ['player' => $user]);
  }

  public function show(Player $player)
  {
    $player->load([
      'nation',
      'club',
      'contract'
    ]);

    return Inertia::render('players/show/page', ['person' => $player]);
  }

  public function store(StorePlayerUser $request)
  {
    // Todo: Validate that the email is a valid email

    $password = Str::random(8);

    $data = [
      'name' => $request->get('name'),
      'email' => $request->get('email'),
      'password' => Hash::make($password),
    ];

    User::create($data);

    // Todo: set up event for emailing password on user creation

    return redirect()->back();
  }

  public function update(User $user, StorePlayerUser $player)
  {
    $user->name = $player->name;
    $user->email = $player->email;
    $user->save();

    session()->flash('success', 'Player updated successfully!');

    return redirect()->back();
  }
}
