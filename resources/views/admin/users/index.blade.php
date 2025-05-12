<x-app-layout>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto px-6 py-8 text-lg">
    <div class="w-full flex justify-end items-center p-4 shadow">
        <a href="{{ route('dashboard') }}" class="text-3xl">
            📊
        </a>
    </div>
        <h2 class="text-2xl font-bold text-white mb-6">👥 User Management</h2>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex items-center gap-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or contact"
        class="w-full sm:w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm" />

    <button type="submit"
        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-md">
        Search
    </button>

    @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-red-600 underline">
            Clear
        </a>
    @endif
</form>

            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Contact</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @foreach ($users as $index => $user)
                    @if($user->role != "admin")
                        <tr>
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->contact }}</td>
                            <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
