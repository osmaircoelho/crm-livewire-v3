<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

it('should be able to delete a user', function () {
    // Create a user to be deleted
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted(
        'users',
        [
            'id' => $forDeletion->id,
        ]
    );

});

it('should hava a confirmation before the deletion', function () {
    // Create a user to be deleted
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->call('destroy')
        ->assertHasErrors(['confirmation' => 'confirmed'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $forDeletion->id]);

});

it('should send a notification to the user telling him that he has no long access to the application ', function () {

    Notification::fake();
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $forDeletion)
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy');

    Notification::assertSentTo($forDeletion, \App\Notifications\UserDeletedNotificaion::class);
});

it('should not be possible to delete the logged user ', function () {

    $user = User::factory()->admin()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class)
        ->set('user', $user)
        ->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy')
        ->assertHasErrors(['confirmation'])
        ->assertNotDispatched('user::deleted');

    assertNotSoftDeleted('users', ['id' => $user->id]);
});
