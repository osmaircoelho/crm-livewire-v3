<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // You can set up any necessary preconditions here
    $this->admin       = User::factory()->admin()->create();
    $this->userDeleted = User::factory()->deleted()->create();

    actingAs($this->admin);

});

it('should be able to show all the details of the user in the component', function () {

    $userDeleted   = $this->userDeleted;
    $userDeletedId = $this->userDeleted->id;

    Livewire::test(Admin\Users\Show::class)
        ->call('loadUser', $userDeletedId)
        // propriedades do componente show
        ->assertSet('user.id', $userDeletedId)
        ->assertSet('modal', true)

        ->assertSee($userDeleted->name)
        ->assertSee($userDeleted->email)

        ->assertSee($userDeleted->created_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->updated_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deleted_at->format('d/m/Y H:i'))
        ->assertSee($userDeleted->deletedBy->name);

});

it('should open the modal when the event is dispatched', function () {

    $lwShow = Livewire::test(Admin\Users\Show::class)
        ->assertSet('user', null)
        ->assertSet('modal', false);

    Livewire::test(Admin\Users\Index::class)
        ->call('showUser', $this->userDeleted->id)
        ->assertDispatched('user::show', id: $this->userDeleted->id);

    /*    $lwShow->assertSet('modal', true)
                ->assertSet('user.id', $this->userDeleted->id);*/
});

it('making sure that the method loadUser has the attribute On', function () {
    $reflection = new ReflectionClass(Admin\Users\Show::class);

    $attributes = $reflection->getMethod('loadUser')->getAttributes();

    expect(count($attributes))->toBe(1);

    $attribute = $attributes[0];

    expect($attribute)->getName()->toBe('Livewire\Attributes\On')
        ->and($attribute->getArguments())->toHaveCount(1);

    $argument = $attribute->getArguments()[0];
    expect($argument)->toBe('user::show');
});
