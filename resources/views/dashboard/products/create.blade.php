@extends('layouts.app')
@section('header', 'Add Product')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">Add New Product</h2>
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold">
        {{ session('error') }}
    </div>
@endif
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Product Name</label>
                <input type="text" name="name" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-slate-700">Product Image</label>
                <input type="file" name="image" class="w-full mt-1 p-1 border rounded-lg outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Barcode (Unique)</label>
                <input type="text" name="barcode" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Stock Quantity</label>
                <input type="number" name="qty" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Cost Price ($)</label>
                <input type="number" step="0.01" name="cost_price" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Sale Price ($)</label>
                <input type="number" step="0.01" name="sale_price" class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Category</label>
                <select name="category_id" class="w-full mt-1 p-2 border rounded-lg outline-none">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Supplier</label>
                <select name="supplier_id" class="w-full mt-1 p-2 border rounded-lg outline-none">
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="w-full mt-6 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">
            Save Product
        </button>
    </form>
</div>
@endsection