@php
    use Carbon\Carbon;
    $now = Carbon::now();
@endphp

<x-app-layout>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto p-6">
        <!-- 📊 Dashboard Logo -->
        <div class="w-full flex justify-end items-center p-4 shadow">
            <a href="{{ route('dashboard') }}" class="text-3xl">📊</a>
        </div>

        <!-- 📝 Create New Task Form -->
        <div class="max-w-3xl mx-auto p-6 bg-white rounded-md shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 Create New Task</h2>

            @if(session('status'))
                <div class="flex items-center justify-between mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                    <span class="font-medium">{{ session('status') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-900">&times;</button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tasks.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Category</label>
                    <select name="category" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Select Category --</option>
                        <option value="VIP1" {{ old('category') == 'VIP1' ? 'selected' : '' }}>VIP1</option>
                        <option value="VIP2" {{ old('category') == 'VIP2' ? 'selected' : '' }}>VIP2</option>
                        <option value="VIP3" {{ old('category') == 'VIP3' ? 'selected' : '' }}>VIP3</option>
                        <option value="VIP4" {{ old('category') == 'VIP4' ? 'selected' : '' }}>VIP4</option>
                        <option value="VIP5" {{ old('category') == 'VIP5' ? 'selected' : '' }}>VIP5</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Description</label>
                    <textarea name="description" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full" required>
                </div>

                <!-- Response Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Expected User Response Type</label>
                    <select name="response_type" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Select one</option>
                        <option value="text" {{ old('response_type') == 'text' ? 'selected' : '' }}>Text Response</option>
                        <option value="file" {{ old('response_type') == 'file' ? 'selected' : '' }}>File Upload</option>
                    </select>
                </div>

                <!-- Expiration Time -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Expiration Time</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Price (in USD)</label>
                    <input type="number" name="price" step="0.01" class="w-full border border-gray-300 rounded mt-1" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-md">
                        Post Task
                    </button>
                </div>
            </form>
        </div>

        <!-- 🔴 Active Product List -->
        <div class="overflow-x-auto bg-white rounded-lg shadow mt-10">
            <h2 class="text-2xl font-bold text-black mb-6">📋 Active Product List</h2>

            @php $hasActive = false; @endphp

            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Response Type</th>
                        <th class="px-4 py-3 text-left">Expires At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @foreach($tasks as $task)
                        @if(Carbon::parse($task->expires_at)->gt($now))
                            @php $hasActive = true; @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $task->title }}</td>
                                <td class="px-4 py-2">{{ $task->category }}</td>
                                <td class="px-4 py-2">{{ ucfirst($task->response_type) }}</td>
                                <td class="px-4 py-2">{{ Carbon::parse($task->expires_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            @if (!$hasActive)
                <marquee><p class="text-red-600 text-3xl mt-4">No active product available!!!</p></marquee>
            @endif
        </div>

        <!-- ⚫ Expired Product List -->
        <div class="overflow-x-auto bg-white rounded-lg shadow mt-10">
            <h2 class="text-2xl font-bold text-black mb-6">📦 Expired Product List</h2>

            @php $hasExpired = false; @endphp

            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Response Type</th>
                        <th class="px-4 py-3 text-left">Expired At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @foreach($tasks as $task)
                        @if(Carbon::parse($task->expires_at)->lte($now))
                            @php $hasExpired = true; @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $task->title }}</td>
                                <td class="px-4 py-2">{{ $task->category }}</td>
                                <td class="px-4 py-2">{{ ucfirst($task->response_type) }}</td>
                                <td class="px-4 py-2 text-red-600">{{ Carbon::parse($task->expires_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            @if (!$hasExpired)
                <marquee><p class="text-red-600 text-3xl mt-4">No expired products found!</p></marquee>
            @endif
        </div>
    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
