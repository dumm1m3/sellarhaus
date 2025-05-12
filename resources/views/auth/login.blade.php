<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white-100">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-black mb-6">Welcome Back</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-black">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4 flex items-center">
                    <input id="remember_me" type="checkbox" class="border-gray-300 rounded text-red-600 shadow-sm focus:ring-red-500" name="remember">
                    <label for="remember_me" class="ml-2 block text-sm text-black">Remember me</label>
                </div>

                <!-- Login Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Login
                    </button>
                </div>

                <!-- OR Divider -->
                <div class="flex items-center justify-center mb-4">
                    <span class="text-gray-500 text-sm">OR</span>
                </div>

                <!-- Google Login Button 
                <div class="text-center">
                    <a href="{{ url('/auth/google') }}" class="flex items-center justify-center w-full border border-gray-300 text-black bg-white hover:bg-gray-100 py-2 px-4 rounded-md shadow-sm transition duration-200">
                        <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5 mr-2" alt="Google logo">
                        Login with Google
                    </a>
                </div>-->
            </form>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <p class="mt-6 text-center text-sm text-gray-600">
                    <a class="text-red-600 hover:underline font-medium" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                </p>
            @endif

            <!-- Register Link -->
            <p class="mt-2 text-center text-sm text-gray-600">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-red-600 hover:underline font-medium">Register</a>
            </p>
        </div>
    </div>
</x-guest-layout>
