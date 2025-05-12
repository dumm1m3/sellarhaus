<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-8">
        <h2 class="text-2xl font-bold text-white mb-6">📢 Announcements</h2>

        @php
    $grouped = $notifications->groupBy(function ($item) {
        $date = \Carbon\Carbon::parse($item->created_at);
        if ($date->isToday()) return 'Today';
        if ($date->isYesterday()) return 'Yesterday';
        return $date->format('d.m.Y');
    });
@endphp


        @forelse($grouped as $group => $items)
            <h1 class="text-xl font-semibold text-gray-300 mt-1 mb-1">{{ $group }}</h1>
            <div class="space-y-1">
                @foreach($items as $notification)
                    <div class="bg-white p-4 rounded-md shadow">
                        <h4 class="text-lg font-bold text-black">{{ $notification->notification_subject }}</h4>
                        <p class="text-gray-800 mt-2">{{ $notification->notification_body }}</p>
                        <p class="text-sm text-gray-500 mt-1 text-right">
                            — {{ $notification->created_at->format('h:i A') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-gray-500">No announcements found.</p>
        @endforelse

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
