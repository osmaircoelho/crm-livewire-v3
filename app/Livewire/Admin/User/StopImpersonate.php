<?php

namespace App\Livewire\Admin\User;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class StopImpersonate extends Component
{
    public function render(): View
    {
        $user = auth()->user();

        return view('livewire.admin.user.stop-impersonate', [
            'user' => $user,
        ]);

    }

    public function stop()
    {
        session()->forget('impersonate');

        $this->redirect(route('admin.users'));
    }
}
