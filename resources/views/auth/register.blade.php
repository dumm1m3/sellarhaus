<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white-600">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-black mb-6">Create Your Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-black">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Contact -->
                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium text-black">Contact Number</label>
                    <input id="contact" type="text" name="contact" value="{{ old('contact') }}" required class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('contact') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Refer Code -->
                <div class="mb-4">
                    <label for="refer_code" class="block text-sm font-medium text-black">Referral Code (optional)</label>
                    <input id="refer_code" type="text" name="refer_code" value="{{ old('refer_code') }}" class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-black">Password</label>
                    <input id="password" type="password" name="password" required class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-black">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 w-full border-red-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <!-- Register Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        Create Account
                    </button>
                </div>

                <!-- OR Divider -->
                <div class="flex items-center justify-center mb-4">
                    <span class="text-gray-500 text-sm">OR</span>
                </div>

                <!-- Google Button 
                <div class="text-center">
                    <a href="{{ url('/auth/google') }}" class="flex items-center justify-center w-full border border-red-300 text-black bg-white hover:bg-red-100 py-2 px-4 rounded-md shadow-sm transition duration-200">
                        <img src="https://developers.google.com/identity/images/g-logo.png" class="w-5 h-5 mr-2" alt="Google logo">
                        Sign Up with Google
                    </a>
                </div>-->
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-red-600 hover:underline font-medium">Log in</a>
            </p>
        </div>
    </div>
</x-guest-layout>
