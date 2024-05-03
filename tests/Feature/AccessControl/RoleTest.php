<?php

namespace Tests\Feature\AccessControl;

use App\Models\AccessControl\Permission;
use App\Models\AccessControl\Role;
use App\Models\AccessControl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_role_list_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-read',
            'display_name' => 'acl-read',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/roles');

        $response->assertOk();
    }

    public function test_role_list_page_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-wrong-reads',
            'display_name' => 'acl-wrong-reads',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/roles');
        $response->assertForbidden();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_roles_list_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-read',
            'display_name' => 'acl-read',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/roles/get-data');
        $response->assertOk();
    }

    public function test_get_roles_list_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-wrong-reads',
            'display_name' => 'acl-wrong-reads',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/roles/get-data');
        $response->assertForbidden();
    }

    public function test_delete_role_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-isnt-delete',
            'display_name' => 'acl-isnt-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleDelete = Role::create([
            'name' => 'test-role',
            'display_name' => 'test-role',
        ]);

        $response = $this->actingAs($user)->delete('/roles/' . $roleDelete->id);
        $response->assertForbidden();
    }

    public function test_delete_role_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-delete',
            'display_name' => 'acl-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleDelete = Role::create([
            'name' => 'test-role',
            'display_name' => 'test-role',
        ]);

        $response = $this->actingAs($user)->delete('/roles/' . $roleDelete->id);
        $response->assertJson([
            'success' => true,
            'message' => 'Role deleted successfully',
        ], true);
    }

    public function test_delete_role_is_not_found(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-delete',
            'display_name' => 'acl-delete',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->delete('/roles/22222');
        $response->assertNotFound();
    }

    public function test_create_role_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-create',
            'display_name' => 'acl-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/roles/create');
        $response->assertOk();
    }

    public function test_create_role_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-wrong-create',
            'display_name' => 'acl-wrong-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->get('/roles/create');
        $response->assertForbidden();
    }

    public function test_create_role_is_error_validation(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-create',
            'display_name' => 'acl-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/roles', [
            'role_name' => '',
            'permission' => [],
        ]);
        $response->assertSessionHasErrors(['permission', 'role_name'])->assertStatus(302);
    }

    public function test_create_role_is_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-create',
            'display_name' => 'acl-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExists = Role::create([
            'name' => 'test-role',
            'display_name' => 'test-role',
        ]);

        $response = $this->actingAs($user)->post('/roles', [
            'role_name' => $roleExists->display_name,
            'permission' => [$permission->id]
        ]);
        $response->assertSessionHasErrors(['role_name'])->assertStatus(302);
    }

    public function test_create_role_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-create',
            'display_name' => 'acl-create',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $response = $this->actingAs($user)->post('/roles', [
            'role_name' => 'Test Role Create',
            'permission' => [$permission->id]
        ]);
        $response->assertStatus(302);
    }

    public function test_edit_role_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-update',
            'display_name' => 'acl-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExists = Role::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);

        $response = $this->actingAs($user)->get('/roles/' . $roleExists->id . '/edit');
        $response->assertOk();
    }

    public function test_update_role_is_forbidden(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-wrong-update',
            'display_name' => 'acl-wrong-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExists = Role::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);

        $response = $this->actingAs($user)->get('/roles/' . $roleExists->id . '/edit');
        $response->assertForbidden();
    }

    public function test_update_role_is_error_validation(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-update',
            'display_name' => 'acl-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExist = Role::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);
        $response = $this->actingAs($user)->patch('/roles/' . $roleExist->id, [
            'role_name' => '',
            'permission' => [$permission->id]
        ]);
        $response->assertSessionHasErrors(['role_name'])->assertStatus(302);
    }

    public function test_update_role_is_exists(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-update',
            'display_name' => 'acl-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExist = Role::create([
            'name' => 'acl-test-role',
            'display_name' => 'acl-test-role',
        ]);
        $roleExist2 = Role::create([
            'name' => 'acl-test2-role',
            'display_name' => 'acl-test2-role',
        ]);

        $response = $this->actingAs($user)->patch('/roles/' . $roleExist2->id, [
            'role_name' => $roleExist->display_name,
            'permission' => [$permission->id],
        ]);
        $response->assertSessionHasErrors(['role_name'])->assertStatus(302);
    }

    public function test_update_role_is_success(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-update',
            'display_name' => 'acl-update',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExist = Role::create([
            'name' => 'acl-test2-update',
            'display_name' => 'acl-test2-update',
        ]);

        $response = $this->actingAs($user)->patch('/roles/' . $roleExist->id, [
            'role_name' => $roleExist->display_name. '-update',
            'permission' => [$permission->id],
        ]);
        $response->assertStatus(302);
    }

    public function test_view_role_is_displayed(): void
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
        ]);
        $permission = Permission::create([
            'name' => 'acl-read',
            'display_name' => 'acl-read',
        ]);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $roleExist = Role::create([
            'name' => 'acl-test-create',
            'display_name' => 'acl-test-create',
        ]);

        $response = $this->actingAs($user)->get('/roles/' . $roleExist->id);
        $response->assertOk();
    }
}
