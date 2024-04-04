<?php

namespace App\Http\Controllers;

use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::get();
        return view('acl.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $permissions = Permission::get();
        $permissionModule = PermissionModule::get();
        return view('acl.create_role', compact('permissions', 'permissionModule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $permissionIds = $request->permission_id;
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role = Role::create([
                'name' => strtolower($request->role_name),
                'guard_name' => 'web'
            ]);
            $role->syncPermissions($permissions);
            return response()->json([
                'status' => true,
                'message' => 'Successfull'
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $role = Role::find($id);
        $permissions = Permission::get();
        $permissionModule = PermissionModule::get();
        return view('acl.edit_role', compact('role', 'permissionModule', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $permissionIds = $request->permission_id;
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role = Role::find($id);
            $role->name = strtolower($request->role_name);
            $role->save();
            $role->syncPermissions([]);
            $role->syncPermissions($permissions);
            return response()->json([
                'status' => true,
                'message' => 'Successfull'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
