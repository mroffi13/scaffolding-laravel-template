<?php

namespace Tests\Feature\AccessControl;

use App\Models\AccessControl\Permission;
use App\Models\AccessControl\Role;
use App\Models\AccessControl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_list_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-read',
            'display_name' => 'users-read',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/users');

        $response->assertOk();
    }

    public function test_user_list_page_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-reads',
            'display_name' => 'users-reads',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/users');
        $response->assertForbidden();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_user_list_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-read',
            'display_name' => 'users-read',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/users/get-data');
        $response->assertOk();
    }

    public function test_get_user_list_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-reads',
            'display_name' => 'users-reads',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/users/get-data');
        $response->assertForbidden();
    }

    public function test_delete_user_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-isnt-delete',
            'display_name' => 'users-isnt-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userDelete = User::factory()->create();

        $response = $this->actingAs($user)->delete('/users/' . $userDelete->id);
        $response->assertForbidden();
    }

    public function test_delete_user_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-delete',
            'display_name' => 'users-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userDelete = User::factory()->create();

        $response = $this->actingAs($user)->delete('/users/' . $userDelete->id);
        $response->assertJson([
            'success' => true,
            'message' => 'User deleted successfully',
        ], true);
    }

    public function test_delete_user_is_not_found(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-delete',
            'display_name' => 'users-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->delete('/users/2');
        $response->assertNotFound();
    }
}
