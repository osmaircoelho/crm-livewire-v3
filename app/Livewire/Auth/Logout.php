<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function render(): string
    {
        return view('livewire.auth.logout');
        /*return <<<BLADE

                <x-button
                    tooltip-left="logoff"
                    icon="o-power"
                    class="btn-circle btn-ghost btn-xs"
                    wire:click="logout"
                />

            BLADE;*/

    }

    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'));
    }
}
