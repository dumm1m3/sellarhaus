<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">
        <div class="bg-white p-8 shadow-lg rounded-lg flex flex-col md:flex-row gap-6">
            <!-- Profile Picture Section -->
            <div class="w-full md:w-1/3 flex flex-col items-center">
                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://via.placeholder.com/150' }}" 
                     alt="User Profile Image" 
                     class="w-48 h-48 rounded-full shadow-md object-cover">
                <h2 class="mt-4 text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->role }}</p>
            </div>

            <!-- Editable Details Section -->
            <div class="w-full md:w-2/3">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Loop through all fields dynamically -->
                    @foreach ($user->getFillable() as $field)
                        @if ($field === 'account_status')
                            <!-- Account Status: Read-Only -->
                            <div class="mb-6">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $field) }}</label>
                                <input type="text" id="{{ $field }}" value="{{ $user->$field }}" disabled
                                       class="mt-2 block w-full border-gray-300 bg-gray-100 rounded-lg shadow-sm cursor-not-allowed">
                            </div>
                        @elseif ($field === 'profile_image')
                            <!-- Profile Image Upload -->
                            <div class="mb-6">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 capitalize">Profile Image</label>
                                <input type="file" id="{{ $field }}" name="{{ $field }}" accept="image/*"
                                       class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                            </div>
                        @elseif ($field === 'password' || $field === 'refer_code' || $field === 'role' ||  $field === 'verification' ||  $field === 'refered_by')
                            <div class="mb-6">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $field) }}</label>
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}" id="{{ $field }}" name="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}" 
                                       class="mt-2 block w-full border-gray-300 bg-gray-100 rounded-lg shadow-sm cursor-not-allowed" disabled>
                            </div>
                        @else
                            <!-- General Editable Fields -->
                            <div class="mb-6">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $field) }}</label>
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}" id="{{ $field }}" name="{{ $field }}"
                                       value="{{ old($field, $user->$field) }}" 
                                       class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                                @error($field)
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endforeach

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-gray-600 hover:underline px-4 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
