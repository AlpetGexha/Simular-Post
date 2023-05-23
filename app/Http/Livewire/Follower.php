<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Follower extends Component
{
    public $user_id;
    public $isFollow;
    public $user;

    public function mount(int $user_id)
    {
        $this->user_id = $user_id;
        $this->user = User::find($user_id);
        $this->isFollow = $this->user->followed_at ? true : false;
    }

    public function follow()
    {
        auth()->user()->toggleFollow($this->user);

        $this->emit('followed');
    }

    public function render()
    {
        return view('livewire.follower');
    }
}
