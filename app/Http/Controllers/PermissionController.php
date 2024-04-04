<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }

    public function permissionModules(Request $request)
    {
        if ($request->ajax()) {
            $permissionModules = PermissionModule::get();
            return DataTables::of($permissionModules)
                    ->addIndexColumn()
                    ->addColumn('sr_no', function ($row) {
                        return '1';
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->make(true);
        }
        return view('acl.permission_modules');
    }

    public function createPermissionModule(Request $request)
    {
        return view('acl.create_permission_module');
    }

    public function savePermissionModule(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);
            $permissionModule = new PermissionModule();
            $permissionModule->name = strtolower($request->name);
            $permissionModule->save();
            return response()->json([
                'status' => true,
                'message' => 'Permission module saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
