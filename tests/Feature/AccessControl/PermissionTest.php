<?php

namespace Tests\Feature\AccessControl;

use App\Models\AccessControl\Permission;
use App\Models\AccessControl\Role;
use App\Models\AccessControl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_permission_list_page_is_displayed(): void
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

        $response = $this->actingAs($user)->get('/permissions');

        $response->assertOk();
    }

    public function test_permission_list_page_is_forbidden(): void
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

        $response = $this->actingAs($user)->get('/permissions');
        $response->assertForbidden();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_permissions_list_is_success(): void
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

        $response = $this->actingAs($user)->post('/permissions/get-data');
        $response->assertOk();
    }

    public function test_get_permissions_list_is_forbidden(): void
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

        $response = $this->actingAs($user)->post('/permissions/get-data');
        $response->assertForbidden();
    }

    public function test_delete_permission_is_forbidden(): void
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

        $userDelete = User::factory()->create();

        $response = $this->actingAs($user)->delete('/permissions/' . $userDelete->id);
        $response->assertForbidden();
    }

    public function test_delete_permission_is_success(): void
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

        $permDelete = Permission::create([
            'name' => 'acl-test-delete',
            'display_name' => 'acl-test-delete',
        ]);

        $response = $this->actingAs($user)->delete('/permissions/' . $permDelete->id);
        $response->assertJson([
            'success' => true,
            'message' => 'Permission deleted successfully',
        ], true);
    }

    public function test_delete_permission_is_not_found(): void
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

        $response = $this->actingAs($user)->delete('/permissions/22222');
        $response->assertNotFound();
    }

    public function test_create_permission_page_is_displayed(): void
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

        $response = $this->actingAs($user)->get('/permissions/create');
        $response->assertOk();
    }

    public function test_create_permission_is_forbidden(): void
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

        $response = $this->actingAs($user)->get('/permissions/create');
        $response->assertForbidden();
    }

    public function test_create_permission_is_error_validation(): void
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

        $response = $this->actingAs($user)->post('/permissions', [
            'permission' => 'creates',
            'module' => '',
        ]);
        $response->assertSessionHasErrors(['permission', 'module'])->assertStatus(302);
    }

    public function test_create_permission_is_exists(): void
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

        $userExist = Permission::create([
            'name' => 'test-create',
            'display_name' => 'test create',
        ]);

        $exp = explode('-', $userExist->name);
        $new_exp = $exp;
        unset($new_exp[count($new_exp) - 1]);
        $new_exp = implode(' ', $new_exp);
        $response = $this->actingAs($user)->post('/permissions', [
            'permission' => $exp[count($exp) - 1],
            'module' => $new_exp,
        ]);
        $response->assertSessionHasErrors(['module'])->assertStatus(302);
    }

    public function test_create_permission_is_success(): void
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

        $response = $this->actingAs($user)->post('/permissions', [
            'permission' => 'read',
            'module' => 'Test Module',
        ]);
        $response->assertStatus(302);
    }

    public function test_edit_permission_page_is_displayed(): void
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

        $permissionExists = Permission::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);

        $response = $this->actingAs($user)->get('/permissions/' . $permissionExists->id . '/edit');
        $response->assertOk();
    }

    public function test_update_permission_is_forbidden(): void
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

        $permissionExists = Permission::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);

        $response = $this->actingAs($user)->get('/permissions/' . $permissionExists->id . '/edit');
        $response->assertForbidden();
    }

    public function test_update_permission_is_error_validation(): void
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

        $permissionExist = Permission::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);
        $permissionExist2 = Permission::create([
            'name' => 'acl-test2-update',
            'display_name' => 'acl-test2-update',
        ]);

        $response = $this->actingAs($user)->patch('/permissions/' . $permissionExist2->id, [
            'permission' => 'update',
            'module' => '',
        ]);
        $response->assertSessionHasErrors(['module'])->assertStatus(302);
    }

    public function test_update_user_is_exists(): void
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

        $permissionExist = Permission::create([
            'name' => 'acl-test-update',
            'display_name' => 'acl-test-update',
        ]);
        $permissionExist2 = Permission::create([
            'name' => 'acl-test2-update',
            'display_name' => 'acl-test2-update',
        ]);

        $response = $this->actingAs($user)->patch('/permissions/' . $permissionExist2->id, [
            'permission' => 'update',
            'module' => 'acl-test',
        ]);
        $response->assertSessionHasErrors(['module'])->assertStatus(302);
    }

    public function test_update_permission_is_success(): void
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

        $permissionExist2 = Permission::create([
            'name' => 'acl-test2-update',
            'display_name' => 'acl-test2-update',
        ]);

        $response = $this->actingAs($user)->patch('/permissions/' . $permissionExist2->id, [
            'permission' => 'read',
            'module' => 'acl-test',
        ]);
        $response->assertStatus(302);
    }

    public function test_view_permission_is_displayed(): void
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

        $permissionExists = Permission::create([
            'name' => 'acl-test-create',
            'display_name' => 'acl-test-create',
        ]);

        $response = $this->actingAs($user)->get('/permissions/' . $permissionExists->id);
        $response->assertOk();
    }
}
