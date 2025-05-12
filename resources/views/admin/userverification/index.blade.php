<x-app-layout>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto p-6">

        <div class="w-full flex justify-end items-center p-4 shadow">
            <a href="{{ route('dashboard') }}" class="text-3xl">📊</a>
        </div>

        <h2 class="text-2xl font-semibold mb-6 text-white">✅👤 User Verification</h2>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">🔍 User Verification Table</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-left text-sm font-semibold text-gray-600">
                            <tr>
                                <th class="px-4 py-2">Serial</th>
                                <th class="px-4 py-2">User Name</th>
                                <th class="px-4 py-2">Account Balance</th>
                                <th class="px-4 py-2">Verification Document</th>
                                <th class="px-4 py-2">Verification</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 text-gray-700">
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">${{ number_format($user->balance, 2) }}</td>
                                    <td class="px-4 py-2">
                                        @if ($user->ver_doc)
                                            @if (Str::endsWith($user->ver_doc, ['.jpg', '.jpeg', '.png']))
                                                <img src="{{ asset('storage/' . $user->ver_doc) }}" alt="Document" class="h-150 rounded shadow" />
                                            @elseif (Str::endsWith($user->ver_doc, '.pdf'))
                                                <a href="{{ asset('storage/' . $user->ver_doc) }}" target="_blank" class="text-blue-600 underline">View</a>
                                            @else
                                                <span class="text-gray-500 italic">Unsupported file</span>
                                            @endif
                                        @else
                                            <span class="text-red-500 italic">Not submitted</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('admin.userverification.verify') }}">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded shadow text-sm">
                                                Verify
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
