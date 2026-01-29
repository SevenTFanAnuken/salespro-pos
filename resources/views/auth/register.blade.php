<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SalesPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    lucide.createIcons();
  });
</script>

<body class="bg-slate-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-slate-900">Join SalesPro</h1>
            <p class="text-slate-500 mt-2">Create your admin or user account</p>
        </div>
        @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="relative group">
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 pr-10 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition shadow-sm" required>
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <i id="eye-password" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-slate-700">Confirm</label>
                    <div class="relative group">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 pr-10 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition shadow-sm" required>
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <i id="eye-password_confirmation" data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg mt-4">
                Register Now
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-600">
            Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Log in</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('eye-icon-' + inputId);

            if (input.type === "password") {
                input.type = "text";
                // Update icon to 'eye-off' if using Lucide
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = "password";
                // Update icon to 'eye'
                icon.setAttribute('data-lucide', 'eye');
            }

            // Refresh Lucide icons to show the change
            lucide.createIcons();
        }
    </script>
</body>

</html>