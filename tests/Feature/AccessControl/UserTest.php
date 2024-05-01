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

    public function test_create_user_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-create',
            'display_name' => 'users-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/users/create');
        $response->assertOk();
    }

    public function test_create_user_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-wrong-create',
            'display_name' => 'users-wrong-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/users/create');
        $response->assertForbidden();
    }

    public function test_create_user_is_error_validation(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-create',
            'display_name' => 'users-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/users', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['name', 'email', 'password'])->assertStatus(302);
    }

    public function test_create_user_is_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-create',
            'display_name' => 'users-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userExist = User::factory()->create();

        $response = $this->actingAs($user)->post('/users', [
            'name' => $userExist->name,
            'email' => $userExist->email,
            'password' => '1234567',
        ]);
        $response->assertSessionHasErrors(['email'])->assertStatus(302);
    }

    public function test_create_user_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-create',
            'display_name' => 'users-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'John Doe',
            'email' => 'xJg9v@example.com',
            'password' => '1234567',
        ]);
        $response->assertStatus(302);
    }

    public function test_edit_user_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-update',
            'display_name' => 'users-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userExist = User::factory()->create();

        $response = $this->actingAs($user)->get('/users/' . $userExist->id . '/edit');
        $response->assertOk();
    }

    public function test_update_user_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-wrong-update',
            'display_name' => 'users-wrong-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userExist = User::factory()->create();

        $response = $this->actingAs($user)->get('/users/' . $userExist->id . '/edit');
        $response->assertForbidden();
    }

    public function test_update_user_is_error_validation(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-update',
            'display_name' => 'users-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userExist = User::factory()->create();
        $userExist2 = User::factory()->create();

        $response = $this->actingAs($user)->patch('/users/' . $userExist2->id, [
            'name' => '',
            'email' => $userExist->email,
            'password' => '',
            'active' => 1,
        ]);
        $response->assertSessionHasErrors(['name'])->assertStatus(302);
    }

    public function test_update_user_is_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-update',
            'display_name' => 'users-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $userExist = User::factory()->create();
        $userExist2 = User::factory()->create();

        $response = $this->actingAs($user)->patch('/users/' . $userExist2->id, [
            'name' => $userExist->name,
            'email' => $userExist->email,
            'password' => '1234567',
        ]);
        $response->assertSessionHasErrors(['email'])->assertStatus(302);
    }

    public function test_update_user_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'users-create',
            'display_name' => 'users-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/users', [
            'name' => 'John Doe',
            'email' => 'xJg9v@example.com',
            'password' => '1234567',
        ]);
        $response->assertStatus(302);
    }
}
