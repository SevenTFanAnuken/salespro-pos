<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SalesPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
  });
</script>

<body class="bg-slate-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Welcome Back</h1>
            <p class="text-slate-500 mt-2">Log in to manage your sales</p>
        </div>

        @if (session('success'))
        <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition"
                    placeholder="example@gmail.com"
                    autocomplete="email" required>
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative group">
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 pr-10 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="Enter your password"
                        autocomplete="current-password" required>

                    <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                        <i id="eye-icon" data-lucide="eye" class="w-5 h-5"></i>
                    </button>
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between py-1">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer select-none">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-600">
            New here? <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Create an account</a>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-icon');

            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = "password";
                icon.setAttribute('data-lucide', 'eye');
            }

            // Re-render icons
            lucide.createIcons();
        }
    </script>
</body>

</html>