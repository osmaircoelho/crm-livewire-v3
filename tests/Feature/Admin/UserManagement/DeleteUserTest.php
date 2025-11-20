<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

it('should be able to delete a user', function () {
    // Create a user to be deleted
    $user        = User::factory()->admin()->create();
    $forDeletion = User::factory()->create();

    actingAs($user);

    Livewire::test(Admin\Users\Delete::class, ['user' => $forDeletion])
        ->call('destroy')
        ->assertDispatched('user::deleted');

    assertSoftDeleted(
        'users',
        [
            'id' => $forDeletion->id,
        ]
    );

});
