@extends('layouts.app')

@section('header', 'Inventory Management')

@section('content')

<script>
    function handleQtyChange(event, id, delta) {
        event.preventDefault();

        const adjInput = document.getElementById('adj-input-' + id);
        const totalSpan = document.getElementById('total-' + id);

        if (!adjInput || !totalSpan) return;

        let currentAdj = parseInt(adjInput.value) || 0;
        let originalQty = parseInt(totalSpan.getAttribute('data-original')) || 0;

        let newAdj = currentAdj + delta;
        let newTotal = originalQty + newAdj;

        if (newTotal < 0) return;

        adjInput.value = newAdj;
        totalSpan.innerText = newTotal;

        // Visual Feedback
        if (newAdj !== 0) {
            adjInput.style.color = "#4f46e5";
            totalSpan.style.color = "#4f46e5";
            totalSpan.style.fontWeight = "800";
        } else {
            adjInput.style.color = "";
            totalSpan.style.color = "#9ca3af";
            totalSpan.style.fontWeight = "400";
        }
    }

    function confirmDelete(id) {
        if (confirm('Delete this product?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<div class="space-y-4">

    <form action="{{ route('products.bulk-adjust') }}" method="POST" id="bulk-stock-form">
    @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header Section --}}
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Current Stock</h3>
                    <p class="text-sm text-gray-500">Prepare changes and click Save All.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('products.create') }}" class="bg-gray-50 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition border border-gray-200">
                        + Add Product
                    </a>

                    {{-- CHANGED: Added ID to form and used a manual submit trigger --}}
                    <button type="button"
                        onclick="document.getElementById('bulk-stock-form').submit()"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-bold flex items-center hover:bg-indigo-700 transition shadow-md">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save All Changes
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 text-[11px] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-4">Product Info</th>
                            <th class="p-4 text-center">Quick Adjust</th>
                            <th class="p-4 text-center">Final Stock</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $product->barcode }}</div>
                            </td>

                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-3 bg-gray-50 w-fit mx-auto px-3 py-1.5 rounded-2xl border border-gray-100">
                                    <button type="button" onclick="handleQtyChange(event, '{{ $product->id }}', -1)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-red-500 transition shadow-sm">
                                        <i data-lucide="minus" class="w-4 h-4"></i>
                                    </button>

                                    <input type="number" name="adjustments[{{ $product->id }}]" id="adj-input-{{ $product->id }}" value="0" class="w-10 text-center bg-transparent border-none font-black text-sm pointer-events-none" readonly>

                                    <button type="button" onclick="handleQtyChange(event, '{{ $product->id }}', 1)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-emerald-500 transition shadow-sm">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>

                            <td class="p-4 text-center">
                                <span id="total-{{ $product->id }}" data-original="{{ $product->qty }}" class="font-mono font-bold text-gray-400">
                                    {{ $product->qty }}
                                </span>
                            </td>

                            <td class="p-4">
                                @if($product->qty > 5)
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase">Healthy</span>
                                @elseif($product->qty > 0)
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase">Low</span>
                                @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase">Out</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ $product->id }}')" class="p-2 text-slate-400 hover:text-red-600 transition">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

@foreach($products as $product)
<form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function handleQtyChange(event, id, delta) {
        event.preventDefault();
        const adjInput = document.getElementById('adj-input-' + id);
        const totalSpan = document.getElementById('total-' + id);
        if (!adjInput || !totalSpan) return;

        let currentAdj = parseInt(adjInput.value) || 0;
        let originalQty = parseInt(totalSpan.getAttribute('data-original')) || 0;
        let newAdj = currentAdj + delta;
        let newTotal = originalQty + newAdj;

        if (newTotal < 0) return;

        adjInput.value = newAdj;
        totalSpan.innerText = newTotal;

        if (newAdj !== 0) {
            adjInput.style.color = "#4f46e5";
            totalSpan.style.color = "#4f46e5";
            totalSpan.style.fontWeight = "800";
        } else {
            adjInput.style.color = "";
            totalSpan.style.color = "#9ca3af";
            totalSpan.style.fontWeight = "400";
        }
    }

    function confirmDelete(id) {
    Swal.fire({
        title: '<span class="text-xl font-black text-slate-800">Delete Product?</span>',
        html: `
            <div class="text-slate-500 text-sm mt-2">
                This action is irreversible. The product data and its stock history will be removed.
            </div>
        `,
        icon: 'warning',
        iconColor: '#ef4444', // Red-500
        showCancelButton: true,
        confirmButtonText: 'Confirm Delete',
        cancelButtonText: 'Keep Product',
        reverseButtons: true, // Puts "Cancel" on the left, "Confirm" on the right
        
        // Modal Styling
        background: '#ffffff',
        width: '400px',
        padding: '2rem',
        showClass: {
            popup: 'animate__animated animate__fadeInUp animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutDown animate__faster'
        },
        customClass: {
            popup: 'rounded-[2rem] border-0 shadow-2xl',
            confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-xl font-bold transition-all duration-200 focus:ring-4 focus:ring-red-100 mx-2',
            cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-8 py-3 rounded-xl font-bold transition-all duration-200 mx-2'
        },
        buttonsStyling: false // Crucial to use our own Tailwind classes
    }).then((result) => {
        if (result.isConfirmed) {
            // Elegant loading pulse
            Swal.fire({
                title: 'Removing...',
                html: '<div class="mt-2 text-slate-400">Updating inventory database</div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    const b = Swal.getHtmlContainer().querySelector('b');
                }
            });
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
    lucide.createIcons();
</script>
@endsection