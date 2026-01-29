@extends('layouts.app')
@section('header', 'User Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="font-bold text-gray-800">System Users</h3>
    </div>
    <table class="w-full text-left">
        <thead class="bg-gray-50 text-gray-600 text-sm">
            <tr>
                <th class="p-4">Name</th>
                <th class="p-4">Email</th>
                <th class="p-4">Role</th>
                <th class="p-4">Joined</th>
                <th class="p-4">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
            <tr>
                <td class="p-4 font-medium">{{ $user->name }}</td>
                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </td>
                <td class="p-4 text-gray-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="p-4">
                    <button class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Edit</button>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection