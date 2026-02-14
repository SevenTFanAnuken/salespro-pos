@extends('layouts.app')

@section('header', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
            <h3 class="text-2xl font-bold text-gray-800">${{ number_format($totalSales, 2) }}</h3>
            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('orders.index') }}" class="text-xs text-blue-600 hover:underline">View History →</a>
                <div class="p-2 bg-green-100 rounded-lg text-green-600">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Today's Sales</p>
            <h3 class="text-2xl font-bold text-gray-800">${{ number_format($todaySales, 2) }}</h3>
            <p class="text-xs text-green-600 mt-4 flex items-center">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> Live
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Staff Members</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $activeUsers }}</h3>
            <div class="mt-4 p-2 w-fit bg-blue-100 rounded-lg text-blue-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Products</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</h3>
            <div class="mt-4 p-2 w-fit bg-orange-100 rounded-lg text-orange-600">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
        </div>
    </div>
</div>
@endsection