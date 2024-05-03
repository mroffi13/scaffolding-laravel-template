<?php

namespace App\Http\Controllers\AccessControl;

use App\Helpers\General;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\Permission;
use App\Models\AccessControl\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stringable;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        General::checkPermission('acl-read');

        return view('access-control.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        General::checkPermission('acl-create');

        $permissions = Permission::orderBy('name', 'asc')->get();

        return view('access-control.roles.create-or-edit', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        General::checkPermission('acl-create');

        $request->validate([
            'role_name' => ['required', 'unique:roles,display_name'],
            'permission' => ['required', 'exists:permissions,id'],
        ]);

        $name = \Str::slug($request->role_name);
        $display_name = ucwords($request->role_name);
        $description = $request->description;

        DB::beginTransaction();
        $role = Role::create([
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
        ]);

        $role->syncPermissions($request->permission);
        DB::commit();

        return redirect()->route('roles')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'Role created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        General::checkPermission('acl-read');

        $role = Role::with('permissions')->find($id);
        if ($role == null) {
            abort(404, 'Role not found');
        }
        $userLogin = Auth::user();

        return view('access-control.roles.view', compact('role', 'userLogin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        General::checkPermission('acl-update');

        $role = Role::with('permissions')->find($id);
        if ($role == null) {
            abort(404, 'Role not found');
        }

        $permissions = Permission::orderBy('name', 'asc')->get();

        return view('access-control.roles.create-or-edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        General::checkPermission('acl-update');

        $request->validate([
            'role_name' => ['required', Rule::unique('roles','display_name')->ignore($id)],
            'permission' => ['required', 'exists:permissions,id'],
        ]);

        $name = \Str::slug($request->role_name);
        $display_name = ucwords($request->role_name);
        $description = $request->description;

        DB::beginTransaction();
        $role = Role::where('id', $id)->first();
        if($role == null) {
            abort(404, 'Role not found');
        }

        $role->name = $name;
        $role->display_name = $display_name;
        $role->description = $description;
        $role->save();

        $role->syncPermissions($request->permission);
        DB::commit();

        return redirect()->route('roles')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'Role updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        General::checkPermission('acl-delete');

        $model = Role::find($id);
        if($model == null) {
            abort(404, 'Role not found');
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully',
        ]);
    }

    public function getData()
    {
        General::checkPermission('acl-read');

        $model = Role::query();

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('created_at', function ($model) {
                return $model->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('updated_at', function ($model) {
                return $model->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($model) {
                return view('access-control.roles.partials.action', [
                    'model' => $model,
                    'user' => Auth::user(),
                ]);
            })
            ->toJson();
    }
}
