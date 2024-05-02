<?php

namespace App\Http\Controllers\AccessControl;

use App\Helpers\General;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stringable;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        General::checkPermission('acl-read');

        return view('access-control.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        General::checkPermission('acl-create');

        return view('access-control.permissions.create-or-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        General::checkPermission('acl-create');

        $request->validate([
            'permission' => ['required', 'in:create,read,update,delete'],
            'module' => ['required'],
        ]);

        $name = \Str::slug($request->module . ' ' . ucfirst($request->permission));
        $display_name = ucfirst($request->permission) . ' ' . ucfirst($request->module);
        $description = $request->description;

        $check_perm = Permission::where('name', $name)->first();
        if ($check_perm) {
            throw ValidationException::withMessages([
                'module' => 'This permission already exists.',
            ]);
        }

        Permission::create([
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
        ]);

        return redirect()->route('permissions')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'Permission created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        General::checkPermission('acl-read');

        $permission = Permission::find($id);
        if ($permission == null) {
            abort(404, 'Permission not found');
        }
        $userLogin = Auth::user();

        return view('access-control.permissions.view', compact('permission', 'userLogin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        General::checkPermission('acl-update');

        $permission = Permission::find($id);
        if ($permission == null) {
            abort(404, 'Permission not found');
        }

        return view('access-control.permissions.create-or-edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        General::checkPermission('acl-update');

        $request->validate([
            'permission' => ['required', 'in:create,read,update,delete'],
            'module' => ['required'],
        ]);

        $name = \Str::slug($request->module . ' ' . ucfirst($request->permission));
        $display_name = ucfirst($request->permission) . ' ' . ucfirst($request->module);
        $description = $request->description;

        $check_perm = Permission::where('name', $name)->where('id', '!=', $id)->first();
        if ($check_perm) {
            throw ValidationException::withMessages([
                'module' => 'This permission already exists.',
            ]);
        }

        Permission::where('id', $id)->update([
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
        ]);

        return redirect()->route('permissions')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'Permission updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        General::checkPermission('acl-delete');

        $model = Permission::find($id);
        if($model == null) {
            abort(404, 'Permission not found');
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully',
        ]);
    }

    public function getData()
    {
        General::checkPermission('acl-read');

        $model = Permission::query();

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->rawColumns(['active'])
            ->addColumn('created_at', function ($model) {
                return $model->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('updated_at', function ($model) {
                return $model->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($model) {
                return view('access-control.permissions.partials.action', [
                    'model' => $model,
                    'user' => Auth::user(),
                ]);
            })
            ->toJson();
    }
}
