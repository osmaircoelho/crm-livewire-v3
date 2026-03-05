<?php

use App\Livewire\Admin\User\StopImpersonate;
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
    assertTrue(session()->has('impersonator'));

    assertSame(session()->get('impersonate'), $this->user->id);
    assertSame(session()->get('impersonator'), $this->admin->id);
});

it('should make sure that we are logged with the impersonated user', function () {
    expect(auth()->id())
        ->ToBe($this->admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $this->user->id)
        ->assertRedirect(route('dashboard'));

    get(route('dashboard'))
        ->assertSee(__("You're impersonating :name, click here to stop the impersonation.", ['name' => $this->user->name]));

    expect(auth()->id())->ToBe($this->user->id);

});

it('should be able to stop impersonation', function () {
    expect(auth()->id())
        ->ToBe($this->admin->id);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $this->user->id)
        ->assertRedirect(route('dashboard'));

    Livewire::test(StopImpersonate::class)
        ->call('stop')
        ->assertRedirect(route('admin.users'));

    expect(session('impersonate'))->ToBeNull();

    get(route('dashboard'))
        ->assertDontSee(__("You're impersonating :name, click here to stop the impersonation.", ['name' => $this->user->name]));

    expect(auth()->id())->ToBe($this->admin->id);

});

it('should have the correct permission to impersonate someone', function () {
    $admin    = User::factory()->admin()->create();
    $nonAdmin = User::factory()->create();
    $user     = User::factory()->create();

    actingAs($nonAdmin);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertForbidden();

    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $user->id)
        ->assertRedirect();

});

it('should not be possible to impersonate myself', function () {
    $admin = User::factory()->admin()->create();
    actingAs($admin);

    Livewire::test(Impersonate::class)
        ->call('impersonate', $admin->id);
    //->assertForbidden();
})->throws(Exception::class);
