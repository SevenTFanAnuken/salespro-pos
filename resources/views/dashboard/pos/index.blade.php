@extends('dashboard.point_of_sale')

@section('content')
<div class="flex h-[calc(100vh-64px)] overflow-hidden">
    <aside class="w-24 bg-white border-r border-slate-200 flex flex-col items-center py-6 gap-6 overflow-y-auto custom-scrollbar">
        <button class="cat-btn active flex flex-col items-center gap-1" data-category="all">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shadow-sm">
                <i data-lucide="layout-grid" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-bold uppercase text-slate-400">All</span>
        </button>
        
        @foreach(\App\Models\Category::all() as $cat)
        <button class="cat-btn flex flex-col items-center gap-1" data-category="{{ $cat->id }}">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-bold uppercase text-slate-400 truncate w-16 text-center">{{ $cat->name }}</span>
        </button>
        @endforeach
    </aside>

    <main class="flex-1 p-8 overflow-y-auto custom-scrollbar bg-[#F1F5F9]">
        <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            @foreach($products as $product)
            <div class="product-card bg-white rounded-[2rem] p-5 shadow-sm border border-transparent hover:border-indigo-200 hover:shadow-xl transition-all cursor-pointer group relative"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->sale_price }}"
                 data-stock="{{ $product->qty }}"
                 data-category="{{ $product->category_id }}">
                
                <div id="stock-warn-{{ $product->id }}" class="stock-warning opacity-0 absolute top-4 right-4 z-30 bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded-lg shadow-lg">
                    OUT OF STOCK
                </div>

                <div class="aspect-square bg-slate-50 rounded-[1.5rem] mb-4 flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="object-cover h-full w-full group-hover:scale-110 transition-transform duration-500">
                    @else
                        <i data-lucide="image" class="w-12 h-12 text-slate-200"></i>
                    @endif
                </div>
                
                <h3 class="font-bold text-slate-800 truncate">{{ $product->name }}</h3>
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

    <section class="w-[380px] bg-white border-l border-slate-200 flex flex-col">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-black text-slate-800">Current Order</h2>
            <button onclick="clearCart()" class="text-slate-300 hover:text-red-500 transition-colors">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </div>

        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
            </div>

        <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-bold">Total Amount</span>
                <span id="cart-total" class="text-2xl font-black text-slate-900">$0.00</span>
            </div>
            <button onclick="openCheckout()" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-lg hover:bg-indigo-700 transition-all">
                Place Order
            </button>
        </div>
    </section>
</div>

<div id="checkout-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-sm rounded-[2rem] p-8">
        <h2 class="text-2xl font-black mb-6">Confirm Payment</h2>
        <div class="space-y-4">
            <div class="flex justify-between p-4 bg-slate-50 rounded-xl">
                <span class="font-bold">Total</span>
                <span id="modal-total" class="font-black text-indigo-600">$0.00</span>
            </div>
            <form action="{{ route('point_of_sale.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cart-data-input">
                <input type="hidden" name="total_amount" id="total-amount-input">
                <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-xl">Complete Sale</button>
                <button type="button" onclick="closeCheckout()" class="w-full mt-2 text-slate-400 font-bold py-2">Cancel</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = JSON.parse(localStorage.getItem('pos_cart')) || [];

    function addToCart(card) {
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const stock = parseInt(card.dataset.stock);
        const warnLabel = document.getElementById(`stock-warn-${id}`);
        
        const existingItem = cart.find(i => i.id === id);
        const currentQtyInCart = existingItem ? existingItem.qty : 0;

        if (currentQtyInCart >= stock) {
            showStockWarning(warnLabel);
            return; 
        }

        if (existingItem) {
            existingItem.qty++;
        } else {
            cart.push({ id, name, price, qty: 1, maxStock: stock });
        }
        renderCart();
    }

    function showStockWarning(label) {
        if(!label) return;
        label.classList.remove('opacity-0');
        label.classList.add('opacity-100');
        setTimeout(() => {
            label.classList.remove('opacity-100');
            label.classList.add('opacity-0');
        }, 1500);
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id == id);
        if (item) {
            if (delta > 0 && item.qty >= item.maxStock) {
                showStockWarning(document.getElementById(`stock-warn-${id}`));
                return;
            }
            item.qty += delta;
            if (item.qty <= 0) cart = cart.filter(i => i.id != id);
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
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-100">
                    <div class="flex-1 truncate mr-2">
                        <p class="font-bold text-slate-800 truncate">${item.name}</p>
                        <p class="text-xs font-black text-indigo-600">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl">
                        <button onclick="updateQty('${item.id}', -1)" class="text-slate-400 hover:text-red-500"><i data-lucide="minus" class="w-4 h-4"></i></button>
                        <span class="font-black text-sm">${item.qty}</span>
                        <button onclick="updateQty('${item.id}', 1)" class="text-slate-400 hover:text-indigo-500"><i data-lucide="plus" class="w-4 h-4"></i></button>
                    </div>
                </div>`;
        });

        document.getElementById('cart-total').innerText = `$${total.toFixed(2)}`;
        document.getElementById('cart-data-input').value = JSON.stringify(cart);
        document.getElementById('total-amount-input').value = total.toFixed(2);
        localStorage.setItem('pos_cart', JSON.stringify(cart));
        lucide.createIcons();
    }

    function clearCart() { cart = []; renderCart(); }

    function openCheckout() { 
        if(cart.length > 0) {
            document.getElementById('modal-total').innerText = document.getElementById('cart-total').innerText;
            document.getElementById('checkout-modal').classList.remove('hidden'); 
        }
    }

    function closeCheckout() { document.getElementById('checkout-modal').classList.add('hidden'); }

    // Event Listeners
    document.getElementById('product-grid').addEventListener('click', e => {
        const card = e.target.closest('.product-card');
        if (card) addToCart(card);
    });

    document.getElementById('pos-search').addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = card.dataset.name.toLowerCase().includes(term) ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const cat = this.dataset.category;
            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = (cat === 'all' || card.dataset.category === cat) ? 'block' : 'none';
            });
        });
    });

    window.onload = renderCart;
</script>
@endpush