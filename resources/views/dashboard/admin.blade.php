@extends('layouts.app')

@section('header', 'Admin Management Console')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-lg text-green-600">
                    <i data-lucide="dollar-sign"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-4 flex items-center">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> Live Data
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Staff Members</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $activeUsers }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                    <i data-lucide="users"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</h3>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg text-orange-600">
                    <i data-lucide="package"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Recent Transactions</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-sm">
                <tr>
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Cashier</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($recentSales as $sale)
                <tr class="border-t border-gray-100">
                    <td class="p-4 font-mono text-sm">#{{ $sale->id }}</td>
                    <td class="p-4">{{ $sale->user->name ?? 'System' }}</td>
                    <td class="p-4 font-bold">${{ number_format($sale->total_amount, 2) }}</td>
                    <td class="p-4">
                        <span class="text-xs text-gray-500">{{ $sale->created_at->diffForHumans() }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">No transactions found yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection