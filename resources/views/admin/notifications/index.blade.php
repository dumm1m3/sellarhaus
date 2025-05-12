<x-app-layout>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto px-6 py-8 text-lg">
        <div class="w-full flex justify-end items-center p-4 shadow">
            <a href="{{ route('dashboard') }}" class="text-3xl">
                📊
            </a>
        </div>
        <h2 class="text-2xl font-bold text-white mb-6">📣 Announcement Management</h2> 
    </div>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-md shadow-md">
    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📣 Publish Announcement</h2>
            @csrf
            @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Announcement Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Announcement Body</label>
                <textarea name="notificationBody" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"
                          required>{{ old('notificationBody') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-md">
                    Send Announcement
                </button>
            </div>
        </form>
    </div>

    <div class="max-w-7xl mx-auto p-6">
            <div class="overflow-x-auto bg-white rounded-lg shadow">
            <h2 class="text-2xl font-bold text-black mb-6">📋 Announcement List</h2>
            @if($notifications->isEmpty())
                <p class="text-gray-500">No anouncement published yet.</p>
            @else
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                        <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Subject</th>
                            <th class="px-4 py-3 text-left">Body</th>
                            <th class="px-4 py-3 text-left">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800">
                        @foreach($notifications as $notification)
                            <tr>
                                <td class="px-4 py-2">{{ $notification->serial }}</td>
                                <td class="px-4 py-2">{{ $notification->notification_subject }}</td>
                                <td class="px-4 py-2">{{ ($notification->notification_body) }}</td>
                                <td class="px-4 py-2">{{ ($notification->created_at) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
