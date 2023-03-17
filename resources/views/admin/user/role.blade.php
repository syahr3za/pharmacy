@extends('layouts.admin')
@section('header', 'User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary pull-left"><i class="fas fa-backward">Back</i></a>
            </div>
            <div class="flex flex-col p-2 bg-slate-100">
                <table>
                    <tr>
                        <td>User Name</td>
                        <td>: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>User Email</td>
                        <td>: {{ $user->email }}</td>
                    </tr>
                </table>
            </div>
            <div class="mt-6 p-2 bg-slate-100">
                <h2 class="text-xl font-semibold">Roles :</h2>
                <div class="d-inline-flex p-1">
                    @if ($user->roles)
                        @foreach ($user->roles as $user_role)
                        <form class="p-2 bg-red-500 hover:bg-red-700 text-white rounded-md" method="POST" action="{{ route('admin.users.roles.remove', [$user->id, $user_role->id]) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-md btn-danger" type="submit">{{ $user_role->name }}</button>
                        </form>
                        @endforeach
                    @endif
                </div>
                <div class="max-w-xl mt-6">
                    <form method="POST" action="{{ route('admin.users.roles', $user->id) }}">
                        @csrf
                        <div class="form-group" style="width: 200px;">
                            <label>Asign New Roles</label>
                            <select name="role" id="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        @error('role')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                        <div class="sm:col-span-6">
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-6 p-2 bg-slate-100">
                <h2 class="text-xl font-semibold">Permissions :</h2>
                <div class="d-inline-flex p-1">
                    @if ($user->permissions)
                        @foreach ($user->permissions as $user_permission)
                        <form class="p-2 bg-red-500 hover:bg-red-700 text-white rounded-md" method="POST" action="{{ route('admin.users.permissions.revoke', [$user->id, $user_permission->id]) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-md btn-danger" type="submit">{{ $user_permission->name }}</button>
                        </form>
                        @endforeach
                    @endif
                </div>
                <div class="max-w-xl mt-6">
                    <form method="POST" action="{{ route('admin.users.permissions', $user->id) }}">
                        @csrf
                        <div class="form-group" style="width: 200px;">
                            <label>Asign New Permissions</label>
                            <select name="permission" id="permission" class="form-control">
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        @error('permission')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                        <div class="sm:col-span-6">
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection