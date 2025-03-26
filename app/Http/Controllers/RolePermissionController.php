<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\PermissionGroupModel;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (is_null(Auth::user()) || ! Auth::user()->can('role.index')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }
        // Get the search query from the request
        $searchQuery = $request->input('search', '');

        // Fetch roles filtered by the search query
        $roles = Role::with(['permissions.group'])
            ->when($searchQuery, fn($query) => 
                $query->where('name', 'like', "%{$searchQuery}%")
            )
            ->paginate(5)
            ->through(fn($role) => [
                'roleid' => $role->id,
                'rolename' => $role->name,
                'groups' => $role->permissions->groupBy(fn($permission) => $permission->group->name)
                    ->map(fn($permissions, $groupName) => [
                        'groupname' => $groupName,
                        'permissions' => $permissions->map(fn($permission) => [
                            'name' => $permission->name,
                        ]),
                    ])->values(),
            ]);
        return Inertia::render('RolePermission/Index', [
            'roles' => $roles,
            'searchQuery' => $searchQuery, // Pass the search query to the frontend
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        if (is_null(Auth::user()) || ! Auth::user()->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $permission=PermissionGroupModel::with('permissions')->get();
        return Inertia::render('RolePermission/Create',[
            'items' => $permission,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_null(Auth::user()) || ! Auth::user()->can('role.store')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $validated = $request->validate([
            'rolename' => 'required',
        ]);
        // Handle the submitted data
        $role = Role::create(['name'=>$validated['rolename']]);
        $permission = Permission::whereIn('id',$request->input('checkedPermissions'))->get(['id'])->pluck('id');
        if (!empty($permission)) {
            $role->syncPermissions($permission);
        }

        return redirect()->route('rolepermission.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (is_null(Auth::user()) || ! Auth::user()->can('role.edit')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        // Fetch all permission groups with their permissions
        $permission = PermissionGroupModel::with('permissions')->get()
        ->map(fn($group) => [
            'id' => $group->id,
            'name' => $group->name,
            'permissions' => $group->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ])->values()
        ]);
        // Fetch role with assigned permissions
        $roles = Role::with(['permissions.group'])
        ->where('id', $id)
        ->get()
        ->map(fn($role) => [
            'roleid' => $role->id,
            'rolename' => $role->name,
            'groups' => $role->permissions->groupBy(fn($permission) => $permission->group->name)
                ->map(fn($permissions, $groupName) => [
                    'groupname' => $groupName,
                    'permissions' => $permissions->map(fn($permission) => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ])->values(),
                ])->values(),
        ]);

        return Inertia::render('RolePermission/Edit', [
            'items' => $permission,
            'role' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (is_null(Auth::user()) || ! Auth::user()->can('role.update')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $role = Role::findOrFail($id);

        $request->validate([
            'rolename' => 'required|string|max:255',
            'checkedPermissions' => 'array'
        ]);

        // Update role name
        $role->name = $request->rolename;
        $role->save();

        // Sync permissions
        $role->permissions()->sync($request->checkedPermissions);

        return redirect()->route('rolepermission.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (is_null(Auth::user()) || ! Auth::user()->can('role.destory')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $role = Role::findOrFail($id);

        // Prevent deleting essential roles (Optional)
        if ($role->name === 'superadmin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        // Detach all permissions
        $role->permissions()->detach();

        // Delete the role
        $role->delete();

        return redirect()->route('rolepermission.index')->with('success', 'Role deleted successfully.');
    }

}
