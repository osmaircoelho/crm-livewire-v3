<?php

use App\Livewire\Admin\Users\Impersonate;
use App\Models\User;

use function Pest\Laravel\{actingAs, get};
use function PHPUnit\Framework\{assertSame, assertTrue};

beforeEach(function () {

    $this->user  = User::factory()->create();
    $this->admin = User::factory()->admin()->create();

    actingAs($this->admin);

});

it('should add a key impersonate to the session  with the given user', function () {

    Livewire::test(Impersonate::class)
        ->call('impersonate', $this->user->id);

    assertTrue(session()->has('impersonate'));

    assertSame(session()->get('impersonate'), $this->user->id);

});

it('should make sure that we are logged with the impersonated user', function () {
    expect(auth()->id())
        ->ToBe($this->admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $this->user->id);

    get(route('dashboard'))
    ->assertSee(__("You're impersonating :name, click here to stop the impersonation.", ['name' => $this->user->name]));

});
