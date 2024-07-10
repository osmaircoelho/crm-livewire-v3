<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@joe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@joe.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should be make sure to inform the user an error email and password doesnt work', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@joe.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(__('auth.failed'));
});
