<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesPro POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .product-card:active {
            transform: scale(0.96);
        }

        .cat-btn.active div {
            background-color: #4f46e5;
            color: white;
        }

        .stock-warning {
            transition: all 0.2s ease;
            pointer-events: none;
            opacity: 0;
            transform: scale(0.8);
        }

        .stock-warning.show {
            opacity: 1;
            transform: scale(1);
        }

        .cat-btn.active span {
            color: #4f46e5;
            /* Indigo color for the text */
        }

        /* Optional: add a smooth fade when filtering */
        .product-card {
            transition: opacity 0.2s ease-in-out;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900 font-sans antialiased">

    <nav class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-50">
        <div class="flex items-center gap-4">
            @if(auth()->user() && auth()->user()->role==='admin')
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 bg-slate-900 text-white rounded-xl hover:bg-indigo-600 transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span class="text-xs font-bold">Admin Panel</span>
            </a>
            @endif

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="zap" class="text-white w-5 h-5"></i>
                </div>
                <span class="text-lg font-black tracking-tight">SalesPro</span>
            </div>
        </div>

        <div class="flex-1 max-w-md px-8">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" id="pos-search" placeholder="Search..."
                    class="w-full bg-slate-100 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 rounded-2xl py-2 pl-11 outline-none text-sm">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ date('D, M jS') }}</p>
                <p class="text-sm font-black text-slate-700" id="live-clock">00:00</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-10 h-10 rounded-xl text-slate-400 hover:text-red-500 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </nav>

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <aside class="w-20 bg-white border-r border-slate-200 flex flex-col items-center py-6 gap-6 overflow-y-auto custom-scrollbar">
            <button class="cat-btn active flex flex-col items-center gap-1" data-category="all">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center"><i data-lucide="layout-grid" class="w-5 h-5"></i></div>
                <span class="text-[9px] font-bold uppercase text-slate-400">All</span>
            </button>
            @foreach(\App\Models\Category::all() as $cat)
            <button class="cat-btn flex flex-col items-center gap-1" data-category="{{ $cat->id }}">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center"><i data-lucide="package" class="w-5 h-5"></i></div>
                <span class="text-[9px] font-bold uppercase text-slate-400 truncate w-14 text-center">{{ $cat->name }}</span>
            </button>
            @endforeach
        </aside>

        <main class="flex-1 p-6 overflow-y-auto custom-scrollbar bg-[#F1F5F9]">
            <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-[1.5rem] p-4 shadow-sm border border-transparent hover:border-indigo-300 transition-all cursor-pointer group relative"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->sale_price }}"
                    data-stock="{{ $product->qty }}"
                    data-category="{{ $product->category_id }}">

                    <div class="absolute top-6 left-6 z-20 bg-white/90 backdrop-blur px-2 py-1 rounded-lg border border-slate-100 shadow-sm">
                        <span class="text-[10px] font-black text-slate-600">STOCK: <span class="text-indigo-600">{{ $product->qty }}</span></span>
                    </div>

                    <div id="stock-warn-{{ $product->id }}" class="stock-warning absolute top-6 right-6 z-30 bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded-lg">
                        NO STOCK
                    </div>

                    <div class="aspect-square bg-slate-50 rounded-2xl mb-3 flex items-center justify-center overflow-hidden">
                        @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="object-cover h-full w-full group-hover:scale-110 transition-transform">
                        @else
                        <i data-lucide="image" class="w-10 h-10 text-slate-200"></i>
                        @endif
                    </div>

                    <h3 class="font-bold text-slate-800 truncate text-sm">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-lg font-black text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                        <div class="w-7 h-7 bg-slate-900 text-white rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>

        <section class="w-[360px] bg-white border-l border-slate-200 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-black text-slate-800">Order</h2>
                <div id="clear-cart-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
                    <div class="bg-white w-full max-w-xs rounded-[2rem] p-8 text-center shadow-2xl">
                        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="trash-2" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-xl font-black mb-2">Clear Cart?</h2>
                        <p class="text-slate-500 text-sm mb-6">This will remove all items from your current order.</p>

                        <div class="flex flex-col gap-2">
                            <button onclick="confirmClear()" class="w-full bg-red-500 text-white font-black py-3 rounded-xl hover:bg-red-600 transition-all">
                                Yes, Clear Everything
                            </button>
                            <button onclick="closeClearModal()" class="w-full bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition-all">
                                No, Keep Items
                            </button>
                        </div>
                    </div>
                </div>
                <button onclick="clearCart()" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <i data-lucide="trash" class="w-4 h-4"></i>
                </button>
            </div>

            @if(session('success'))
            <div id="success-modal" class="fixed inset-0 z-[120] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white w-full max-w-xs rounded-[2rem] p-8 text-center shadow-2xl animate-in zoom-in duration-300">
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-xl font-black mb-2">Sale Complete!</h2>
                    <p class="text-slate-500 text-sm mb-6">{{ session('success') }}</p>
                    <button onclick="document.getElementById('success-modal').remove()" class="w-full bg-slate-900 text-white font-black py-3 rounded-xl hover:bg-indigo-600 transition-all">
                        Great!
                    </button>
                </div>
            </div>
            @endif

            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar"></div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-4">
                <div class="flex justify-between font-black">
                    <span>Total</span>
                    <span id="cart-total" class="text-2xl text-indigo-600">$0.00</span>
                </div>
                <button onclick="openCheckout()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-indigo-600 transition-all">
                    Checkout
                </button>
            </div>
        </section>
    </div>

    <div id="checkout-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-xs rounded-[2rem] p-8 text-center">
            <h2 class="text-xl font-black mb-6">Total: <span id="modal-total" class="text-indigo-600"></span></h2>
            <form action="{{ route('point_of_sale.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cart-data-input">
                <input type="hidden" name="total_amount" id="total-amount-input">
                <button type="submit" class="w-full bg-indigo-600 text-white font-black py-3 rounded-xl mb-2">Complete Sale</button>
                <button type="button" onclick="closeCheckout()" class="text-slate-400 font-bold py-2 text-sm">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('pos_cart')) || [];

        function addToCart(card) {
            const id = card.dataset.id;
            const name = card.dataset.name;
            const price = parseFloat(card.dataset.price);
            const stock = parseInt(card.dataset.stock);
            const warnLabel = document.getElementById(`stock-warn-${id}`);

            const existingItem = cart.find(i => i.id === id);
            const currentQty = existingItem ? existingItem.qty : 0;

            if (currentQty >= stock) {
                warnLabel.classList.add('show');
                setTimeout(() => warnLabel.classList.remove('show'), 1500);
                return;
            }

            if (existingItem) {
                existingItem.qty++;
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1,
                    maxStock: stock
                });
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            let total = 0;
            container.innerHTML = '';
            cart.forEach(item => {
                total += item.price * item.qty;
                container.innerHTML += `
                <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100">
                    <div class="flex-1 truncate"><p class="font-bold text-xs truncate">${item.name}</p></div>
                    <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-lg ml-2">
                        <button onclick="updateQty('${item.id}', -1)" class="p-1"><i data-lucide="minus" class="w-3 h-3"></i></button>
                        <span class="font-black text-xs">${item.qty}</span>
                        <button onclick="updateQty('${item.id}', 1)" class="p-1"><i data-lucide="plus" class="w-3 h-3"></i></button>
                    </div>
                </div>`;
            });
            document.getElementById('cart-total').innerText = `$${total.toFixed(2)}`;
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            document.getElementById('total-amount-input').value = total.toFixed(2);
            localStorage.setItem('pos_cart', JSON.stringify(cart));
            lucide.createIcons();
        }

        function updateQty(id, delta) {
            const item = cart.find(i => i.id == id);
            if (item) {
                if (delta > 0 && item.qty >= item.maxStock) {
                    const warn = document.getElementById(`stock-warn-${id}`);
                    warn.classList.add('show');
                    setTimeout(() => warn.classList.remove('show'), 1500);
                    return;
                }
                item.qty += delta;
                if (item.qty <= 0) cart = cart.filter(i => i.id != id);
            }
            renderCart();
        }

        // Function to show the clear confirmation popup
        function clearCart() {
            if (cart.length > 0) {
                document.getElementById('clear-cart-modal').classList.remove('hidden');
            }
        }

        // Function that actually wipes the data
        function confirmClear() {
            cart = [];
            renderCart();
            closeClearModal();
        }

        function closeClearModal() {
            document.getElementById('clear-cart-modal').classList.add('hidden');
        }

        // Ensure your renderCart still saves to local storage
        function renderCart() {
            const container = document.getElementById('cart-items');
            let total = 0;
            container.innerHTML = '';

            cart.forEach(item => {
                total += item.price * item.qty;
                container.innerHTML += `
            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100">
                <div class="flex-1 truncate"><p class="font-bold text-xs truncate">${item.name}</p></div>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-lg ml-2">
                    <button onclick="updateQty('${item.id}', -1)" class="p-1"><i data-lucide="minus" class="w-3 h-3"></i></button>
                    <span class="font-black text-xs">${item.qty}</span>
                    <button onclick="updateQty('${item.id}', 1)" class="p-1"><i data-lucide="plus" class="w-3 h-3"></i></button>
                </div>
            </div>`;
            });

            document.getElementById('cart-total').innerText = `$${total.toFixed(2)}`;
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            document.getElementById('total-amount-input').value = total.toFixed(2);
            localStorage.setItem('pos_cart', JSON.stringify(cart));
            lucide.createIcons();
        }

        function finishSale() {
            // 1. Clear the browser memory
            localStorage.removeItem('pos_cart');

            // 2. Clear the current JS variable
            cart = [];

            // 3. Remove the modal from view
            document.getElementById('success-modal').remove();

            // 4. Refresh the UI
            renderCart();
        }

        function openCheckout() {
            if (cart.length > 0) {
                document.getElementById('modal-total').innerText = document.getElementById('cart-total').innerText;
                document.getElementById('checkout-modal').classList.remove('hidden');
            }
        }

        function closeCheckout() {
            document.getElementById('checkout-modal').classList.add('hidden');
        }

        document.getElementById('product-grid').addEventListener('click', e => {
            const card = e.target.closest('.product-card');
            if (card) addToCart(card);
        });

        setInterval(() => {
            // Adding 'window.' tells the editor this is a browser API
            document.getElementById('live-clock').innerText = new window.Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }, 1000);
        // Category Filtering Logic
        document.querySelectorAll('.cat-btn').forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-category');

                // 1. Update UI: Change active button style
                document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // 2. Filter Products
                const products = document.querySelectorAll('.product-card');
                products.forEach(product => {
                    const productCat = product.getAttribute('data-category');

                    if (categoryId === 'all' || productCat === categoryId) {
                        product.style.display = 'block'; // Show
                    } else {
                        product.style.display = 'none'; // Hide
                    }
                });
            });
        });

        // Search Filtering Logic
        document.getElementById('pos-search').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');

            products.forEach(product => {
                const name = product.dataset.name.toLowerCase();
                if (name.includes(term)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });

        window.onload = renderCart;
        lucide.createIcons();
    </script>
</body>

</html>