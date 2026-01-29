@extends('layouts.app')

@section('header', 'Inventory Management')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Current Stock</h3>
        <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm flex items-center hover:bg-indigo-700">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Product
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-sm">
                <tr>
                    <th class="p-4">Product Name</th>
                    <th class="p-4">Barcode/SKU</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Stock</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-lg border">
                        @else
                        <div class="w-12 h-12 bg-gray-100 flex items-center justify-center rounded-lg">
                            <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-xs text-gray-400">{{ $product->category->name ?? 'No Category' }}</div>
                    </td>
                    <td class="p-4 text-gray-500 text-sm">{{ $product->barcode }}</td>
                    <td class="p-4 font-semibold text-gray-800">${{ number_format($product->sale_price, 2) }}</td>
                    <td class="p-4 {{ $product->qty <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                        {{ $product->qty }}
                    </td>
                    <td class="p-4">
                        @if($product->qty > 5)
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">In Stock</span>
                        @elseif($product->qty > 0)
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Low Stock</span>
                        @else
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Out of Stock</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>