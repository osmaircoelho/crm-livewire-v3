<?php

use App\Livewire\Auth\Logout;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('should be able to logout of the app', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect('login');

    expect(auth())
    ->guest()
        ->toBeTrue();

});
