<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SalesPro') }} - Admin</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        @include('layouts.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/60 h-16 flex items-center justify-between px-8">
    
    <div class="flex items-center gap-4">
        <div class="md:hidden">
            <i data-lucide="menu" class="w-5 h-5 text-slate-500"></i>
        </div>
        <h2 class="text-lg font-black text-slate-800 tracking-tight">
            @yield('header', 'Dashboard')
        </h2>
    </div>

    <div class="flex items-center gap-3">
        
        <a href="{{ route('point_of_sale.index') }}" 
           class="hidden sm:flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-xs font-black hover:bg-indigo-100 transition-all border border-indigo-100 mr-2">
            <i data-lucide="layout-grid" class="w-4 h-4"></i>
            OPEN POINT FOR SALE
        </a>

        <div class="h-6 w-[1px] bg-slate-200 mx-2"></div>

        <div class="flex items-center gap-3 pl-2">
            <div class="text-right hidden sm:block">
                <p class="text-[11px] font-black text-indigo-600 uppercase tracking-wider leading-none">
                    {{ auth()->user()->role ?? 'Staff' }}
                </p>
                <p class="text-sm font-bold text-slate-700">
                    {{ auth()->user()->name }}
                </p>
            </div>

            <div class="relative group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-indigo-100 group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-4 border-white rounded-full"></span>
            </div>
        </div>

    </div>
</header>


            <main class="p-6">
                @include('partials.alerts')

                @yield('content')
            </main>

        </div>
    </div>
    
    <script>
    lucide.createIcons();

    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
    </script>
</body>
</html>