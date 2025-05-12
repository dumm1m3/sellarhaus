@php
    use Carbon\Carbon;
    $status = Auth::user()->account_status;
    $now = Carbon::now();
    $hasMatchingTask = false;
@endphp

<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📋 Product List</h2>

            @if($tasks->isEmpty())
                <marquee><p class="text-red-600 text-3xl">Coming Soon!!!</p></marquee>
            @else
                @if ($status === 'Inactive')
                    <marquee><p class="text-red-600 text-3xl">You have to be at least VIP1 to view the tasks!</p></marquee>
                @elseif(\App\Models\PurchasedTask::hasReachedTaskLimit())
                    <marquee><p class="text-red-600 text-3xl">❌ You’ve reached your task limit for your current account level ({{ $status }}).</p></marquee>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tasks as $task)
                            @if($task->category === $status && Carbon::parse($task->expires_at)->gt($now))
                                @php $hasMatchingTask = true; @endphp
                                <div class="relative flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 overflow-hidden">

                                    <!-- 💰 Price Tag -->
                                    <span class="absolute top-2 left-2 bg-green-600 text-white text-xl font-semibold px-3 py-1 rounded shadow">
                                        ${{ number_format($task->price, 2) }}
                                    </span>

                                    <!-- 🔰 Category Tag -->
                                    <span class="absolute top-2 right-2 bg-blue-600 text-white text-xl font-semibold px-3 py-1 rounded shadow">
                                        {{ $task->category }}
                                    </span>

                                    <!-- 📦 Image -->
                                    <img class="object-cover w-full h-48 rounded-t-lg" src="{{ asset('storage/' . $task->image) }}" alt="Task Image">

                                    <!-- 📄 Task Details -->
                                    <div class="flex flex-col justify-between p-4 leading-normal relative">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            {{ $task->title }}
                                        </h5>

                                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                            {{ $task->description }}
                                        </p>

                                        <!-- 🧾 Action -->
                                        <div class="flex space-x-4 mb-4">
                                            @if(!$task->purchases->where('user_id', auth()->id())->count())
                                                <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg cursor-not-allowed opacity-50" disabled>
                                                    🔒 Locked
                                                </button>
                                                <form method="POST" action="{{ route('tasks.buy', $task->id) }}">
                                                    @csrf
                                                    <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        🔓 Unlock?
                                                    </button>
                                                </form>
                                            @else
                                                <button class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg cursor-not-allowed opacity-50">
                                                    💳 Purchased
                                                </button>
                                            @endif
                                        </div>

                                        <!-- ⏰ Expiry -->
                                        <div class="flex justify-end text-right">
                                            <div class="text-xs text-gray-500 dark:text-white italic">
                                                <span class="countdown font-semibold" data-expiry="{{ $task->expires_at->toIso8601String() }}">Loading...</span>
                                                <span> left to expire</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if (!$hasMatchingTask)
                        <p class="text-red-500 text-lg mt-6">No tasks available for your current status ({{ $status }})</p>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <script>
        function updateCountdowns() {
            document.querySelectorAll('.countdown').forEach(el => {
                const expiry = new Date(el.getAttribute('data-expiry'));
                const now = new Date();
                const diff = expiry - now;

                if (diff <= 0) {
                    el.textContent = "Expired";
                    return;
                }

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                el.textContent = `${hours}h ${minutes}m ${seconds}s`;
            });
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>
</x-app-layout>
