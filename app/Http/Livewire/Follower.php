<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Follower extends Component
{
    public $user_id;
    public $isFollow;
    public $user;

    public function mount(int $user_id, $isFollower)
    {
        $this->user_id = $user_id;
        $this->user = User::find($user_id);
        $this->isFollow = $isFollower ? true : false;
    }

    public function follow()
    {
        auth()->user()->toggleFollow($this->user);
        // dd($this->isFollow);

        $this->emit('followed');
    }

    public function render()
    {
        return view('livewire.follower');
    }
}
