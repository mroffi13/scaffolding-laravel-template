<?php

namespace App\Http\Controllers\AccessControl;

use App\Helpers\General;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\Permission;
use App\Models\AccessControl\Role;
use App\Models\AccessControl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        General::checkPermission('users-read');

        return view('access-control.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        General::checkPermission('users-create');

        return view('access-control.users.create-or-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        General::checkPermission('users-create');

        $request->validate([
            'name' => 'required',
            'email' => [ 'required', 'email', 'unique:users,email' ],
            'password' => 'required',
        ]);

        $model = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'User created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        General::checkPermission('users-read');
        $userLogin = Auth::user();
        $user = User::with(['roles.permissions', 'permissions'])->find($id);
        if($user == null) {
            abort(404, 'User not found');
        }

        $roles = Role::all();
        $permissions = Permission::orderBy('name', 'asc')->get();

        return view('access-control.users.view', compact('user', 'userLogin', 'roles', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        General::checkPermission('users-update');

        $user = User::find($id);
        if($user == null) {
            abort(404, 'User not found');
        }

        return view('access-control.users.create-or-edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        General::checkPermission('users-update');

        $request->validate([
            'name' => 'required',
            'email' => [ 'required', 'email', Rule::unique('users')->ignore($id) ],
        ]);

        $user = User::where('id', $id)->first();
        if($user == null) {
            abort(404, 'User not found');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->active = $request->active ?? 0;
        $user->save();

        return redirect()->route('users')->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        General::checkPermission('users-delete');

        $model = User::find($id);
        if($model == null) {
            abort(404, 'User not found');
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    public function getData()
    {
        General::checkPermission('users-read');

        $model = User::query();

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->rawColumns(['active'])
            ->addColumn('created_at', function ($model) {
                return $model->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($model) {
                return view('access-control.users.partials.action', [
                    'model' => $model,
                    'user' => Auth::user(),
                ]);
            })
            ->addColumn('active', function ($model) {
                switch ($model->getAttributes()['active']) {
                    case 1:
                        return '<div class="badge badge-pill badge-success">' . $model->active . '</div>';
                        break;

                    default:
                        return '<div class="badge badge-pill badge-danger">' . $model->active . '</div>';
                        break;
                }
            })
            ->toJson();
    }

    public function assignAcl(Request $request, string $id)
    {
        General::checkPermission('users-update');

        $permission_validasi = ['array'];
        if(!empty($request->permission))
            $permission_validasi[] = 'exists:permissions,id';

        $request->validate([
            'role' => ['required', 'array', 'exists:roles,id'],
            'permission' => $permission_validasi,
        ]);

        $user = User::where('id', $id)->first();
        if($user == null) {
            abort(404, 'User not found');
        }
        $user->syncRoles($request->role);
        $user->syncPermissions($request->permission);

        return redirect()->route('users.show', $id)->with([
            'success' => true,
            'toastr' => 'success',
            'message' => 'Role / Permission assigned successfully',
        ]);
    }
}
