@extends('layouts.app')
@section('header', 'Employee Management')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Staff List</h3>
        <button onclick="openEmployeeModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
            Add New Employee
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="p-4">Staff Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            @if(auth()->id() === $user->id)
                                <span class="text-[10px] bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded">YOU</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $user->role ?? 'User' }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openEditEmployeeModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            
                            @if(auth()->id() !== $user->id) {{-- Prevent deleting yourself --}}
                            <form action="{{ route('employees.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Remove this employee?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="employee-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-black text-slate-800">Edit Staff Role</h2>
            <button onclick="closeEmployeeModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>

        <form id="employee-form" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Employee Name</label>
                <input type="text" id="emp-name" class="w-full p-3 border rounded-xl bg-slate-50 text-slate-500" disabled>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Set Role</label>
                <select name="role" id="emp-role" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="user">User (Cashier)</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg hover:bg-indigo-700 transition-all">
                Update Employee
            </button>
        </form>
    </div>
</div>

<script>
function openEditEmployeeModal(id, name, role) {
    const form = document.getElementById('employee-form');
    form.action = `employees/${id}`; 
    
    document.getElementById('emp-name').value = name;
    document.getElementById('emp-role').value = role || 'user';
    
    document.getElementById('employee-modal').classList.remove('hidden');
}

function closeEmployeeModal() {
    document.getElementById('employee-modal').classList.add('hidden');
}
</script>
@endsection