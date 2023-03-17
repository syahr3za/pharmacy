<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('admin.role.index');
    }
    public function api()
    {
        $roles = Role::all();

        $datatables = datatables()->of($roles)
                        ->addColumn('date', function($role){
                            return convert_date($role->created_at);
                        })
                        ->addIndexColumn();

        return $datatables->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ]);

        Role::create($request->all());

        return redirect('roles');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'=>'required',
        ]);

        $role->update($request->all());

        return redirect('roles');
    }

    public function destroy(Role $role)
    {
        $role->delete();
    }

    public function givePermission(Request $request, Role $role)
    {
        if($role->hasPermissionTo($request->permission)){
            return back()->with('message', 'Permission exists.');
        }
        $role->givePermissionTo($request->permission);
        return back()->with('message', 'Permission added.');
    }

    public function revokePermission(Role $role, Permission $permission)
    {
        if($role->hasPermissionTo($permission)){
            $role->revokePermissionTo($permission);
            return back()->with('message', 'Permission revoked.');
        }
        return back()->with('message', 'Permission not exists.');
    }
}
