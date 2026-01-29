<aside class="flex flex-col w-64 bg-slate-900 h-full text-gray-300">
    <div class="flex items-center justify-center h-16 bg-slate-950">
        <span class="text-xl font-bold text-white">SALE<span class="text-indigo-400">M</span><span class="text-xl font-bold text-white">SYSTEM</span></span>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

        @if(auth()->user()->role === 'admin')
            <div class="pt-4 mt-4 border-t border-slate-800">
                <p class="text-[10px] font-bold text-gray-500 uppercase px-4 mb-2 tracking-widest text-purple-400">Management</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="home" class="w-5 h-5 mr-3"></i></i> Dashboard
                </a>

                <a href="{{ route('inventory') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('inventory') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="package" class="w-5 h-5 mr-3 text-amber-400"></i> Inventory
                </a>

                <a href="{{ route('products.create') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('products.create') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="plus-circle" class="w-5 h-5 mr-3 text-indigo-400"></i> Add Product
                </a>

                <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('employees.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="users" class="w-5 h-5 mr-3 text-blue-400"></i> Staff List
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3 text-purple-400"></i> Reports
                </a>

                <p class="text-[10px] font-bold text-gray-500 uppercase px-4 mt-4 mb-2 tracking-widest text-slate-500">Configuration</p>

                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                    <i data-lucide="settings" class="w-5 h-5 mr-3 text-slate-400"></i> Store Settings
                </a>
            </div>
        @endif
    </nav>

    <div class="p-4 border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-red-400 hover:bg-red-900/20 rounded-lg transition-colors group">
                <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                <span class="font-medium">Sign Out</span>
            </button>
        </form>
    </div>
</aside>