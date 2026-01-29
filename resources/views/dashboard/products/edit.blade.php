@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">Edit Product: {{ $product->name }}</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Product Name</label>
                <input type="text" name="name" value="{{ $product->name }}" class="w-full mt-1 p-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div class="col-span-2 mb-4">
                <label class="block text-sm font-medium text-slate-700">Current Image</label>
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-32 h-32 object-cover rounded-xl mt-2 border">
                @else
                <p class="text-gray-400 text-xs italic">No image uploaded</p>
                @endif
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Change Product Image</label>
                <input type="file" name="image" class="w-full mt-1 p-1 border rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Barcode</label>
                <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full mt-1 p-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Stock Quantity</label>
                <input type="number" name="qty" value="{{ $product->qty }}" class="w-full mt-1 p-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Cost Price ($)</label>
                <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" class="w-full mt-1 p-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Sale Price ($)</label>
                <input type="number" step="0.01" name="sale_price" value="{{ $product->sale_price }}" class="w-full mt-1 p-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Category</label>
                <select name="category_id" class="w-full mt-1 p-2 border rounded-lg">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Supplier</label>
                <select name="supplier_id" class="w-full mt-1 p-2 border rounded-lg">
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ $product->supplier_id == $sup->id ? 'selected' : '' }}>
                        {{ $sup->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">
                Update Product
            </button>
            <a href="{{ route('products.index') }}" class="flex-1 bg-slate-100 text-slate-600 text-center font-bold py-3 rounded-xl hover:bg-slate-200 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection