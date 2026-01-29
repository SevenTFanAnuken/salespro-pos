<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesPro POS | NextGen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .product-card:active { transform: scale(0.95); }
        /* Style for the active Category button */
        .cat-btn.active div { background-color: #4f46e5; color: white; border-color: #4f46e5; }
        .cat-btn.active span { color: #4f46e5; font-weight: 800; }
        /* Style for Payment buttons */
        .pay-btn.active { border-color: #4f46e5; background-color: #f5f3ff; color: #4f46e5; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 font-sans antialiased">

    <nav class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
            <i data-lucide="zap" class="text-white w-6 h-6"></i>
        </div>
        <span class="text-xl font-black tracking-tight text-slate-800">SalesPro <span class="text-indigo-600">POS</span></span>
    </div>

    <div class="flex-1 max-w-xl px-12 flex items-center gap-4">
        @if(auth()->user() && auth()->user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-slate-900 transition-all shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            BACK TO PANEL
        </a>
        @endif

        <div class="relative group flex-1">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" id="pos-search" placeholder="Search by product name..." 
                class="w-full bg-slate-100 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 rounded-2xl py-2.5 pl-11 transition-all outline-none text-sm">
        </div>
    </div>

    <div class="flex items-center gap-6">
        <div class="text-right hidden md:block">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ date('D, M jS') }}</p>
            <p class="text-sm font-black text-slate-700" id="live-clock">{{ date('H:i') }}</p>
        </div>
        <div class="h-10 w-[1px] bg-slate-200"></div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                <i data-lucide="log-out" class="w-5 h-5"></i>
            </button>
        </form>
    </div>
</nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <aside class="w-24 bg-white border-r border-slate-200 flex flex-col items-center py-6 gap-6 overflow-y-auto custom-scrollbar">
            <button class="cat-btn active flex flex-col items-center gap-1 group" data-category="all">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center transition-all shadow-sm">
                    <i data-lucide="layout-grid" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold uppercase text-slate-400">All</span>
            </button>
            @foreach(\App\Models\Category::all() as $cat)
            <button class="cat-btn flex flex-col items-center gap-1 group" data-category="{{ $cat->id }}">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center transition-all">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold uppercase text-slate-400 truncate w-16 text-center">{{ $cat->name }}</span>
            </button>
            @endforeach
        </aside>

        <main class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-[2rem] p-5 shadow-sm border border-transparent hover:border-indigo-200 hover:shadow-xl transition-all cursor-pointer group relative overflow-hidden"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->sale_price }}"
                     data-category="{{ $product->category_id }}">
                    
                    @if($product->qty < 10)
                    <div class="absolute top-4 left-4 z-10 bg-orange-500 text-white text-[10px] font-black px-2 py-1 rounded-lg">LOW STOCK</div>
                    @endif

                    <div class="aspect-square bg-slate-50 rounded-[1.5rem] mb-4 flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="object-cover h-full w-full group-hover:scale-110 transition-transform duration-500">
                        @else
                            <i data-lucide="image" class="w-12 h-12 text-slate-200"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-slate-800 leading-tight truncate">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xl font-black text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                        <div class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>

        <section class="w-[380px] bg-white border-l border-slate-200 flex flex-col shadow-2xl">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-800">My Order</h2>
                <button onclick="clearCart()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-slate-50/50"></div>
            <div class="p-6 bg-white border-t border-slate-100 space-y-4">
                <div class="flex justify-between items-end pt-2">
                    <span class="text-slate-400 font-bold">Total Amount</span>
                    <span id="cart-total" class="text-3xl font-black text-indigo-600 tracking-tighter">$0.00</span>
                </div>
                <button onclick="openCheckout()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    Review Checkout
                </button>
            </div>
        </section>
    </div>

    <div id="checkout-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="p-8 border-b text-center relative">
                <button onclick="closeCheckout()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-2xl font-black text-slate-800">Finalize Sale</h2>
                <p class="text-slate-400 text-sm">Select payment method</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="bg-slate-50 rounded-2xl p-6 flex justify-between items-center">
                    <span class="text-slate-800 font-bold text-lg">Total Due</span>
                    <span id="modal-total" class="text-3xl font-black text-indigo-600">$0.00</span>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="setPayment('Cash', this)" class="pay-btn active border-2 border-indigo-600 p-4 rounded-2xl flex flex-col items-center gap-2 transition-all">
                        <i data-lucide="banknote" class="w-6 h-6"></i>
                        <span class="text-xs font-black">CASH</span>
                    </button>
                    <button type="button" onclick="setPayment('Card', this)" class="pay-btn border-2 border-slate-100 p-4 rounded-2xl flex flex-col items-center gap-2 transition-all text-slate-400">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                        <span class="text-xs font-black">CARD</span>
                    </button>
                    <button type="button" onclick="setPayment('QR', this)" class="pay-btn border-2 border-slate-100 p-4 rounded-2xl flex flex-col items-center gap-2 transition-all text-slate-400">
                        <i data-lucide="smartphone" class="w-6 h-6"></i>
                        <span class="text-xs font-black">QR PAY</span>
                    </button>
                </div>

                <form action="{{ route('point_of_sale.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart-data-input">
                    <input type="hidden" name="payment_type" id="payment-type-input" value="Cash">
                    
                    <button type="submit" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-indigo-600 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        Complete & Print Receipt
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let currentCategory = 'all';

        // 1. Filtering
        function filterProducts() {
            const searchTerm = document.getElementById('pos-search').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const catId = card.dataset.category;
                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = (currentCategory === 'all' || catId === currentCategory);
                card.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
            });
        }

        // 2. Category Buttons
        document.querySelectorAll('.cat-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.dataset.category;
                filterProducts();
            });
        });

        document.getElementById('pos-search').addEventListener('input', filterProducts);

        // 3. Cart Functions
        document.getElementById('product-grid').addEventListener('click', function(e) {
            const card = e.target.closest('.product-card');
            if (card) {
                const id = card.dataset.id;
                const name = card.dataset.name;
                const price = parseFloat(card.dataset.price);
                const item = cart.find(i => i.id === id);
                if (item) { item.qty++; } else { cart.push({ id, name, price, qty: 1 }); }
                renderCart();
            }
        });

        function updateQty(id, delta) {
            const item = cart.find(i => i.id == id);
            if (item) {
                item.qty += delta;
                if (item.qty <= 0) cart = cart.filter(i => i.id != id);
            }
            renderCart();
        }

        function clearCart() { cart = []; renderCart(); }

        function renderCart() {
            const container = document.getElementById('cart-items');
            let total = 0;
            container.innerHTML = '';
            cart.forEach(item => {
                total += item.price * item.qty;
                container.innerHTML += `
                    <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                        <div class="flex-1 min-w-0 mr-2">
                            <p class="text-sm font-bold truncate text-slate-800">${item.name}</p>
                            <p class="text-xs font-bold text-indigo-500">$${item.price.toFixed(2)}</p>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-lg">
                            <button onclick="updateQty('${item.id}', -1)" class="text-slate-400 hover:text-red-500"><i data-lucide="minus-square" class="w-4 h-4"></i></button>
                            <span class="font-black text-xs min-w-[12px] text-center">${item.qty}</span>
                            <button onclick="updateQty('${item.id}', 1)" class="text-slate-400 hover:text-indigo-500"><i data-lucide="plus-square" class="w-4 h-4"></i></button>
                        </div>
                    </div>`;
            });
            document.getElementById('cart-total').innerText = `$${total.toFixed(2)}`;
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            lucide.createIcons();
        }

        // 4. Modal & Payment Logic
        function openCheckout() {
            if (cart.length === 0) return alert('Cart is empty!');
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            document.getElementById('modal-total').innerText = `$${total.toFixed(2)}`;
            document.getElementById('checkout-modal').classList.remove('hidden');
        }

        function closeCheckout() { document.getElementById('checkout-modal').classList.add('hidden'); }

        function setPayment(type, element) {
            document.getElementById('payment-type-input').value = type;
            document.querySelectorAll('.pay-btn').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-600', 'text-indigo-600');
                btn.classList.add('border-slate-100', 'text-slate-400');
            });
            element.classList.add('active', 'border-indigo-600', 'text-indigo-600');
            element.classList.remove('border-slate-100', 'text-slate-400');
        }

        setInterval(() => {
            document.getElementById('live-clock').innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }, 1000);
        lucide.createIcons();
    </script>
</body>
</html>