<?php

use App\Enum\Can;
use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

it('should be able to give an user a permission to do something', function () {

    /** @var User $user */
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(Can::BE_AN_ADMIN)
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('key', '=', Can::BE_AN_ADMIN->value)->first()->id,
    ]);

});

test('permission must have a seeder', function () {
    $this->seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);
});

test('seed with an admin user', function () {
    seed([PermissionSeeder::class, UserSeeder::class]);

    assertDatabaseHas('permissions', [
        'key' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where('key', '=', Can::BE_AN_ADMIN->value)->first()?->id,
    ]);
});

test('should block the access to an admin page if the user does not hava the permission to be an admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

});

test('lets make sure that we are using cache to store user permissions', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $cacheKey = "user::{$user->id}::permissions";

    expect(\Illuminate\Support\Facades\Cache::has($cacheKey))->toBeTrue('Checking if cache has the key')
        ->and(\Illuminate\Support\Facades\Cache::get($cacheKey))->toBe($user->permissions, 'Checking if permissions are the same as the user');
});

test('lets make sure that we are using the cache the retrieve/check when the user has the given permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    // Verificar que eu nao teve nenhum hit no bd a partir desse ponto
    DB::listen(fn ($query) => throw new Exception('We got a hit'));
    $user->hasPermissionTo(Can::BE_AN_ADMIN->value);

    expect(true)->toBeTrue();
});
