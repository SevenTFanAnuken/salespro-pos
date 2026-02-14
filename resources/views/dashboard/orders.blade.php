@extends('layouts.app')

@section('header', 'Transaction History')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">All Transactions</h3>
        <span class="text-sm text-gray-500">{{ $orders->count() }} Total Orders</span>
    </div>
    <table class="w-full text-left">
        <thead class="bg-gray-50 text-gray-600 text-sm">
            <tr>
                <th class="p-4">Order ID</th>
                <th class="p-4">Cashier</th>
                <th class="p-4">Method</th>
                <th class="p-4">Amount</th>
                <th class="p-4">Date</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse($orders as $order)
<tr class="border-t border-gray-100 hover:bg-gray-50">
    <td class="p-4 font-mono text-sm">{{ $order->invoice_number }}</td>
    
    <td class="p-4">{{ $order->user->name ?? 'System' }}</td>
    
    <td class="p-4">
        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs uppercase font-bold">
            {{ $order->payment_type }}
        </span>
    </td>
    
    <td class="p-4 font-bold text-green-700">${{ number_format($order->total_amount, 2) }}</td>
    <td class="p-4 text-sm">{{ $order->created_at->format('M d, Y H:i') }}</td>
</tr>
@empty
@endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-gray-100">
        {{ $orders->links() }} </div>
</div>
@endsection