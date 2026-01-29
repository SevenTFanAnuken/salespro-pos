@extends('layouts.app')
@section('header', 'Business Intelligence Reports')

@section('content')
<div class="flex flex-col md:flex-row gap-6">
    <aside class="w-full md:w-64 space-y-2">
        <button class="w-full flex items-center justify-between px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100">
            <span class="flex items-center gap-3"><i data-lucide="bar-chart-3" class="w-5 h-5"></i>List Details</span>
        </button>
        <button onclick="document.getElementById('broken-modal').classList.remove('hidden')" 
                class="w-full flex items-center gap-3 px-4 py-3 bg-white text-red-600 hover:bg-red-50 rounded-xl font-bold border border-transparent hover:border-red-100 transition">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i> Report Broken
        </button>
    </aside>

    <div class="flex-1 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Products</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $products->count() }}</h4>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-black text-emerald-500 uppercase tracking-wider">Stock In Logs</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $movements->where('type', 'in')->count() }}</h4>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-black text-red-500 uppercase tracking-wider">Broken Items</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ abs($movements->where('type', 'broken')->sum('quantity')) }}</h4>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-black text-indigo-600 uppercase tracking-wider">System Users</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ \App\Models\User::count() }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-800">Recent Movements Log</h3>
                <span class="text-xs font-bold text-slate-400">Live Updates</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] uppercase font-black text-slate-400 border-b bg-slate-50/30">
                        <tr>
                            <th class="p-4">Type</th>
                            <th class="p-4">Product</th>
                            <th class="p-4">Qty</th>
                            <th class="p-4">Staff</th>
                            <th class="p-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($movements as $m)
                        <tr>
                            <td class="p-4">
                                @if($m->type == 'broken')
                                    <span class="flex items-center gap-2 text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg w-fit">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i> BROKEN
                                    </span>
                                @else
                                    <span class="flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg w-fit">
                                        <i data-lucide="arrow-down-left" class="w-3 h-3"></i> STOCK IN
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-sm font-bold text-slate-700">{{ $m->product->name }}</td>
                            <td class="p-4 text-sm font-black {{ $m->quantity < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $m->quantity }}
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-500">{{ $m->user->name }}</td>
                            <td class="p-4 text-xs text-slate-400">{{ $m->created_at->format('M d, y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 font-medium">No stock movements recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-50 bg-slate-50/20">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>

<div id="broken-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-8 border-b flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Report Damage</h2>
                <p class="text-xs font-bold text-slate-400">Inventory Loss Logging</p>
            </div>
            <button onclick="document.getElementById('broken-modal').classList.add('hidden')" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 shadow-sm transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('reports.broken') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Target Product</label>
                <select name="product_id" class="w-full p-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-red-500 font-bold text-slate-700 transition-all" required>
                    <option value="" disabled selected>Select an item...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Available: {{ $product->qty }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Quantity Damaged</label>
                <input type="number" name="quantity" min="1" placeholder="0" class="w-full p-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-red-500 font-black text-lg transition-all" required>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Internal Note</label>
                <textarea name="notes" placeholder="Why is this item being removed?" class="w-full p-4 bg-slate-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-red-500 text-sm min-h-[100px] transition-all"></textarea>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-red-600 transition-all flex items-center justify-center gap-3 active:scale-95">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Confirm & Remove Stock
            </button>
        </form>
    </div>
</div>

<script>
    // Ensure icons load
    lucide.createIcons();
</script>
@endsection