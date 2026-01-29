@extends('layouts.app')
@section('header', 'Supplier Directory')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
        <div>
            <h4 class="text-lg font-bold text-gray-800">FreshMilk Co.</h4>
            <p class="text-sm text-gray-500">Contact: Sarah Smith</p>
            <p class="text-xs text-indigo-600 font-medium">+1 234 567 890</p>
        </div>
        <div class="bg-emerald-50 p-3 rounded-full">
            <i data-lucide="truck" class="text-emerald-600 w-6 h-6"></i>
        </div>
    </div>
</div>
@endsection