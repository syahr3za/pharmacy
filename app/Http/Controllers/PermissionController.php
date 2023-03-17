<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('admin.permission.index');
    }
    public function api()
    {
        $permissions = Permission::all();

        $datatables = datatables()->of($permissions)
                        ->addColumn('date', function($permission){
                            return convert_date($permission->created_at);
                        })
                        ->addIndexColumn();

        return $datatables->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ]);

        Permission::create($request->all());

        return redirect('permissions');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'=>'required',
        ]);

        $permission->update($request->all());

        return redirect('permissions');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
    }

    public function assignRole(Request $request, Permission $permission)
    {
        if ($permission->hasRole($request->role)) {
            return back()->with('message', 'Role exists.');
        }

        $permission->assignRole($request->role);
        return back()->with('message', 'Role assigned.');
    }

    public function removeRole(Permission $permission, Role $role)
    {
        if ($permission->hasRole($role)) {
            $permission->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }
}
