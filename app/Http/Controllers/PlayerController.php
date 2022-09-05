<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerUser;
use App\Models\Player;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class PlayerController extends Controller {
    public function index() {
        $response = Player::with('club')->paginate();

        return Inertia::render('Player/Index', compact('response'));
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

    public function show(Player $player) {
        return Inertia::render('Player/Show', compact('player'));
    }

    public function store(StorePlayerUser $user)
    {

        // Todo: Validate that the email is a valid email

        $password = str_random(8);
        $user->password = Hash::make($password);

        $data = [
            'name' => $user->get('name'),
            'email' => $user->get('email'),
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

        \request()->session()->flash('success', 'Player updated successfully!');

        return redirect()->back();
    }
}
