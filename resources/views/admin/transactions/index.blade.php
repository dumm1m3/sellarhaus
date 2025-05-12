<x-app-layout>
@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto p-6">

    <div class="w-full flex justify-end items-center p-4 shadow">
        <a href="{{ route('dashboard') }}" class="text-3xl">
            📊
        </a>
    </div>

        <h2 class="text-2xl font-semibold mb-6 text-white">🛠 Transaction Management</h2>

        <!-- Search & Filters -->
        <form method="GET" class="flex flex-wrap gap-4 mb-6">
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Search by user"
                   class="border-gray-300 rounded w-48" />

            <input type="text" name="user_id" value="{{ request('user_id') }}" placeholder="Search by user ID"
                   class="border-gray-300 rounded w-48" />

            <select name="type" class="border-gray-300 rounded w-40">
                <option value="">All Types</option>
                <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
            </select>

            <select name="status" class="border-gray-300 rounded w-40">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filter
            </button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">User Id</th>
                        <th class="px-4 py-2">User Name</th>
                        <th class="px-4 py-2">Wallet Name</th>
                        <th class="px-4 py-2">Wallet Number</th>
                        <th class="px-4 py-2">Transaction ID</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Request Date</th>
                        <th class="px-4 py-2">Admin Review Date</th>
                        <th class="px-4 py-2">Actions</th>
                        <th class="px-4 py-2">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $index => $txn)
                        <tr>
                            <td class="px-4 py-2">
                            @if($txn->user)
                                {{ $txn->user->id }}
                            @else
                                <span class="text-red-600">{{ $txn->user_id }}</span>
                            @endif
                            </td>
                            <td class="px-4 py-2">
                            @if($txn->user)
                                {{ $txn->user->name }}
                            @else
                                <span class="text-red-600">Deleted User</span>
                            @endif
                            </td>

                            <td class="px-4 py-2">{{ $txn->wallet_name }}</td>
                            <td class="px-4 py-2">{{ $txn->wallet_number }}</td>
                            <td class="px-4 py-2">{{ $txn->transaction_id }}</td>
                            <td class="px-4 py-2">{{ $txn->amount }}</td>
                            <td class="px-4 py-2 capitalize">{{ $txn->type }}</td>
                            <td class="px-4 py-2 capitalize">
                                <span class="inline-block px-2 py-1 text-xs rounded
                                    @if($txn->status == 'approved') bg-green-200 text-green-800
                                    @elseif($txn->status == 'rejected') bg-red-200 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $txn->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">{{ $txn->updated_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 space-x-2">
                                @if($txn->status === 'pending')
                                    <form action="{{ route('admin.transactions.approve', $txn->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="bg-green-500 text-white px-2 py-1 text-xs rounded">Approve</button>
                                    </form>

                                    <form action="{{ route('admin.transactions.reject', $txn->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="bg-red-500 text-white px-2 py-1 text-xs rounded">Reject</button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-xs italic">Reviewed</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 capitalize">
                                @if($txn->status === 'pending')
                                    {{ $txn->remarks = "Transaction Pending" }}
                                @elseif($txn->status === 'accepted')
                                    {{ $txn->remarks = "Transaction Successfull" }}
                                @elseif($txn->status === 'rejected' && $txn->remarks === NULL)
                                    <form action="{{ route('admin.transactions.remarks', $txn->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="remarks" placeholder="Enter remarks"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm w-32"
                                            value="{{ $txn->remarks }}">
                                            <button type="submit" class="ml-2 bg-blue-500 text-white px-2 py-1 text-xs rounded">Save</button>
                                    </form>
                                @elseif($txn->status === 'rejected' && !($txn-> remarks === NULL)) 
                                    {{ $txn->remarks }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center px-4 py-4 text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
