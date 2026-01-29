@extends('dashboard.point_of_sale')

@section('content')
<div class="flex h-[calc(100vh-73px)] overflow-hidden bg-[#F1F5F9]">
    
    <aside class="w-24 bg-white border-r border-slate-200 flex flex-col items-center py-6 gap-6 overflow-y-auto custom-scrollbar">
        <button class="cat-btn active flex flex-col items-center gap-1 group" data-category="all">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center transition-all shadow-md">
                <i data-lucide="layout-grid" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-black uppercase text-indigo-600">All</span>
        </button>

        @foreach(\App\Models\Category::all() as $cat)
        <button class="cat-btn flex flex-col items-center gap-1 group text-slate-400" data-category="{{ $cat->id }}">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center group-hover:bg-indigo-100 transition-all">
                @if(strtolower($cat->name) == 'drink' || strtolower($cat->name) == 'drinks')
                    <i data-lucide="cup-soda" class="w-6 h-6"></i>
                @elseif(strtolower($cat->name) == 'food')
                    <i data-lucide="utensils" class="w-6 h-6"></i>
                @else
                    <i data-lucide="package" class="w-6 h-6"></i>
                @endif
            </div>
            <span class="text-[10px] font-bold uppercase truncate w-16 text-center">{{ $cat->name }}</span>
        </button>
        @endforeach
    </aside>

    <div class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-black text-slate-800">Menu Selection</h1>
            <div class="bg-indigo-100 text-indigo-700 px-4 py-1.5 rounded-full text-xs font-bold">
                {{ count($products) }} Products Available
            </div>
        </div>

        <div id="product-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($products as $product)
            <div class="product-card bg-white rounded-[1.5rem] p-5 shadow-sm border-2 border-transparent hover:border-indigo-500 hover:shadow-xl transition-all cursor-pointer group"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->sale_price }}"
                 data-category="{{ $product->category_id }}">
                
                <div class="aspect-square bg-slate-50 rounded-2xl mb-4 flex items-center justify-center overflow-hidden group-hover:scale-105 transition-transform">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="object-cover h-full w-full">
                    @else
                        <i data-lucide="package" class="w-12 h-12 text-slate-200 group-hover:text-indigo-300"></i>
                    @endif
                </div>
                
                <h3 class="font-bold text-slate-700 text-sm truncate">{{ $product->name }}</h3>
                <div class="flex justify-between items-center mt-3">
                    <span class="text-lg font-black text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                    <div class="bg-slate-900 text-white p-2 rounded-xl group-hover:bg-indigo-600 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="w-[400px] bg-white border-l border-slate-200 flex flex-col shadow-2xl">
        <div class="p-8 border-b flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black text-slate-800">Current Order</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Checkout Station</p>
            </div>
            <button onclick="clearCart()" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </div>

        <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30">
            <div id="empty-cart-msg" class="h-full flex flex-col items-center justify-center text-slate-300">
                <i data-lucide="shopping-basket" class="w-16 h-16 mb-4 opacity-20"></i>
                <p class="font-bold">Cart is empty</p>
            </div>
        </div>

        <div class="p-8 bg-white border-t border-slate-100 space-y-4">
            <div class="flex justify-between items-center pt-2">
                <span class="text-slate-400 font-bold">Total Amount</span>
                <span id="cart-total" class="text-3xl font-black text-slate-900">$0.00</span>
            </div>
            
            <button onclick="openCheckout()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-[1.5rem] shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-3 mt-4">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                Review & Checkout
            </button>
        </div>
    </div>
</div>

<div id="checkout-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b text-center relative">
            <button onclick="closeCheckout()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
            <h2 class="text-2xl font-black text-slate-800">Finalize Order</h2>
            <p class="text-slate-400 text-sm font-medium">Select payment method below</p>
        </div>

        <div class="p-8 space-y-6">
            <div class="bg-slate-50 rounded-2xl p-6">
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500 font-medium">Subtotal</span>
                    <span id="modal-subtotal" class="font-bold">$0.00</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                    <span class="text-slate-800 font-black text-lg">Amount to Pay</span>
                    <span id="modal-total" class="text-3xl font-black text-indigo-600">$0.00</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <button type="button" onclick="setPayment('Cash', this)" class="pay-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-indigo-600 bg-indigo-50 text-indigo-600 font-bold active-pay">
                    <i data-lucide="banknote" class="w-6 h-6"></i> Cash
                </button>
                <button type="button" onclick="setPayment('Card', this)" class="pay-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-slate-100 hover:border-indigo-600 transition-all text-slate-500 font-bold">
                    <i data-lucide="credit-card" class="w-6 h-6"></i> Card
                </button>
                <button type="button" onclick="setPayment('QR', this)" class="pay-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-slate-100 hover:border-indigo-600 transition-all text-slate-500 font-bold">
                    <i data-lucide="smartphone" class="w-6 h-6"></i> QR
                </button>
            </div>

            <form action="{{ route('point_of_sale.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_data" id="cart-data-input">
                <input type="hidden" name="payment_type" id="payment-type-input" value="Cash">
                
                <button type="submit" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-indigo-600 transition-all shadow-xl">
                    Pay & Print Receipt
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let cart = [];
let currentCategory = 'all';

// --- Category Filtering Logic ---
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Reset styles
        document.querySelectorAll('.cat-btn').forEach(b => {
            b.classList.remove('active', 'text-indigo-600');
            b.classList.add('text-slate-400');
            b.querySelector('div').className = 'w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center transition-all';
        });

        // Set active
        this.classList.add('active', 'text-indigo-600');
        this.querySelector('div').className = 'w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center transition-all shadow-md';
        
        currentCategory = this.dataset.category;
        filterMenu();
    });
});

function filterMenu() {
    const cards = document.querySelectorAll('.product-card');
    cards.forEach(card => {
        const catId = card.dataset.category;
        if (currentCategory === 'all' || catId === currentCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// --- Payment Selection Logic ---
function setPayment(type, element) {
    document.getElementById('payment-type-input').value = type;
    
    // Reset all buttons
    document.querySelectorAll('.pay-btn').forEach(btn => {
        btn.className = "pay-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-slate-100 hover:border-indigo-600 transition-all text-slate-500 font-bold";
    });

    // Style active button
    element.className = "pay-btn flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-indigo-600 bg-indigo-50 text-indigo-600 font-bold";
}

// --- Cart Logic ---
document.getElementById('product-grid').addEventListener('click', function(e) {
    const card = e.target.closest('.product-card');
    if (!card) return;
    addToCart(card.dataset.id, card.dataset.name, parseFloat(card.dataset.price));
});

function addToCart(id, name, price) {
    const existing = cart.find(item => String(item.id) === String(id));
    if (existing) { existing.qty++; } 
    else { cart.push({ id, name, price, qty: 1 }); }
    renderCart();
}

function updateQty(id, delta) {
    const item = cart.find(item => String(item.id) === String(id));
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => String(i.id) !== String(id));
    }
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');
    const inputEl = document.getElementById('cart-data-input');
    const emptyMsg = document.getElementById('empty-cart-msg');
    
    let total = 0;
    container.innerHTML = '';

    if(cart.length === 0) {
        container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-slate-300">
                <i data-lucide="shopping-basket" class="w-16 h-16 mb-4 opacity-20"></i>
                <p class="font-bold">Cart is empty</p>
            </div>`;
    } else {
        cart.forEach(item => {
            total += item.price * item.qty;
            container.innerHTML += `
                <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex-1">
                        <p class="text-sm font-black text-slate-800">${item.name}</p>
                        <p class="text-xs text-indigo-500 font-bold">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center bg-slate-50 rounded-xl p-1 px-2 gap-3">
                        <button type="button" onclick="updateQty('${item.id}', -1)" class="text-slate-400 hover:text-red-500"><i data-lucide="minus-circle" class="w-4 h-4"></i></button>
                        <span class="font-black text-sm w-4 text-center">${item.qty}</span>
                        <button type="button" onclick="updateQty('${item.id}', 1)" class="text-slate-400 hover:text-indigo-500"><i data-lucide="plus-circle" class="w-4 h-4"></i></button>
                    </div>
                </div>
            `;
        });
    }

    totalEl.innerText = `$${total.toFixed(2)}`;
    inputEl.value = JSON.stringify(cart);
    lucide.createIcons();
}

function openCheckout() {
    if (cart.length === 0) return alert('Cart is empty!');
    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    document.getElementById('modal-subtotal').innerText = `$${total.toFixed(2)}`;
    document.getElementById('modal-total').innerText = `$${total.toFixed(2)}`;
    document.getElementById('checkout-modal').classList.remove('hidden');
}

function closeCheckout() {
    document.getElementById('checkout-modal').classList.add('hidden');
}
</script>
@endsection