<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Follow extends Component
{

    public function follow(){
        // follow 100 random user
        $users = \App\Models\User::inRandomOrder()->take(100)->get();
        foreach ($users as $user) {
            auth()->user()->toggleFollow($user);
        }

    }

    public function render()
    {
        return view('livewire.follow');
    }
}
