@extends('layouts.app')

@section('header', 'User Management')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">System Users</h3>
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                Total: {{ $users->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Joined Date</th>
                        <th class="p-4 text-center">Change Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="p-4 text-sm">{{ $user->email }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.user.role', $user->id) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()" 
                                        class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-1 shadow-sm">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Set as User</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Set as Admin</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection