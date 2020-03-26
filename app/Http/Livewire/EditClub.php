<?php

namespace App\Http\Livewire;

use App\Club;
use Livewire\Component;

class EditClub extends Component
{
    public $club;
    public $name;
    public $mainColor;
    public $secondColor;
    public $thirdColor;

    public function mount(Club $club)
    {
        $this->club = $club;
        $this->name = $club->name;
        $this->mainColor = $club->colors[0];
        $this->secondColor = $club->colors[1];
        $this->thirdColor = $club->colors[2];
    }

    public function save()
    {
        $colors = [
            $this->mainColor,
            $this->secondColor,
            $this->thirdColor,
        ];

        $this->club->name = $this->name;
        $this->club->colors = $colors;
        $this->club->save();

        return $this->redirectRoute('edit_club', [
            'locale' => app()->getLocale(),
            'club' => $this->club,
        ]);
    }

    public function render()
    {
        return view('livewire.edit-club')->with([
            'name' => $this->name,
        ]);
    }
}
