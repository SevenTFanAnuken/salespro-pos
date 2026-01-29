@extends('layouts.app')

@section('header', 'Recent Orders')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-gray-800">Sales History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="p-4">Order ID</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="p-4 text-indigo-600 font-mono">#ORD-7721</td>
                        <td class="p-4 text-gray-800">Sarah Connor</td>
                        <td class="p-4 text-gray-500">Jan 26, 2026</td>
                        <td class="p-4 font-bold">$1,240.00</td>
                        <td class="p-4"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Paid</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection