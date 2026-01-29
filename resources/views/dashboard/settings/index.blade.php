@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold mb-4">Categories</h3>
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2 mb-6">
            @csrf
            <input type="text" name="name" placeholder="New Category" class="flex-1 p-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500" required>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Add</button>
        </form>

        <ul class="divide-y">
            @foreach($categories as $cat)
                <li class="py-3 flex justify-between items-center">
                    <span class="text-slate-700 font-medium">{{ $cat->name }}</span>
                    <div class="flex items-center gap-2">
                        <button onclick="openEditModal('categories', '{{ $cat->id }}', '{{ $cat->name }}')" class="p-2 text-slate-400 hover:text-indigo-600">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold mb-4">Suppliers</h3>
        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-3 mb-6">
            @csrf
            <input type="text" name="name" placeholder="Supplier Name" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-slate-800" required>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="phone" placeholder="Phone" class="p-2 border rounded-lg outline-none focus:ring-2 focus:ring-slate-800">
                <input type="text" name="address" placeholder="Address" class="p-2 border rounded-lg outline-none focus:ring-2 focus:ring-slate-800">
            </div>
            <button type="submit" class="w-full bg-slate-800 text-white py-2 rounded-lg text-sm font-bold hover:bg-slate-900 transition">Add Supplier</button>
        </form>

        <ul class="divide-y">
            @foreach($suppliers as $sup)
                <li class="py-3 flex justify-between items-start">
                    <div>
                        <div class="font-bold text-slate-800">{{ $sup->name }}</div>
                        <div class="text-xs text-slate-500">{{ $sup->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="flex items-center">
                        <button onclick="openEditModal('suppliers', '{{ $sup->id }}', '{{ $sup->name }}', '{{ $sup->phone }}', '{{ $sup->address }}')" class="p-2 text-slate-300 hover:text-indigo-600">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <form action="{{ route('suppliers.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Delete supplier?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div id="edit-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 id="modal-title" class="text-xl font-black text-slate-800">Edit</h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>

        <form id="edit-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                <input type="text" name="name" id="edit-name" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div id="supplier-additional-fields" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="edit-phone" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-slate-800">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Address</label>
                    <textarea name="address" id="edit-address" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-slate-800" rows="2"></textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg hover:bg-indigo-700 transition-all">
                Update Information
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(routePart, id, name, phone = '', address = '') {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const title = document.getElementById('modal-title');
    const supplierFields = document.getElementById('supplier-additional-fields');

    // BUILD THE URL: This ensures it works even if your project is in a subfolder
    // It creates a URL like: your-domain.com/categories/5
    const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');
    form.action = `${routePart}/${id}`; 
    
    title.innerText = `Edit ${routePart.slice(0, -1)}`; // "Edit categories" -> "Edit categorie" (rough but works)
    
    // Fill values
    document.getElementById('edit-name').value = name;

    if (routePart === 'suppliers') {
        supplierFields.classList.remove('hidden');
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-address').value = address;
    } else {
        supplierFields.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    lucide.createIcons();
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
</script>
@endsection