@if(session('success'))
    <div id="alert-success" class="mb-6 p-4 flex items-center bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm animate-fade-in-down">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-green-500 hover:text-green-700">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
        <div class="flex items-center mb-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3"></i>
            <p class="text-sm font-bold text-red-800">Please check the errors below:</p>
        </div>
        <ul class="list-disc list-inside text-sm text-red-700 ml-8">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif