@extends('layouts.admin')
@section('header', 'User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="text-left font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="text-left font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th scope="col" class="relative">
                                Edit
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center">
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="flex items-center">
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit">Roles</i></a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection