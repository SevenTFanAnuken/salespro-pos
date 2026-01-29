@extends('layouts.app')
@section('header', 'Financial Reports')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="p-6 bg-white rounded-xl border-l-4 border-green-500 shadow-sm">
        <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($revenue) }}</h3>
    </div>
    <div class="p-6 bg-white rounded-xl border-l-4 border-red-500 shadow-sm">
        <p class="text-sm text-gray-500 font-medium">Total Expenses</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($expenses) }}</h3>
    </div>
    <div class="p-6 bg-white rounded-xl border-l-4 border-indigo-500 shadow-sm">
        <p class="text-sm text-gray-500 font-medium">Net Profit</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($profit) }}</h3>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">Monthly Breakdown</h3>
    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-200">
        <p class="text-gray-400">Chart.js graph will render here</p>
    </div>
</div>
@endsection