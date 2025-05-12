@php
    use Carbon\Carbon;
    $now = Carbon::now();
@endphp
<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ editMode: false }">

        {{-- Toggle success message --}}
        @if (session('status'))
            <div class="mb-4 text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif
        <form id="statusUpdateForm" method="POST" action="{{ route('user.profile.updateStatusAuto') }}">@csrf</form>
        {{-- PROFILE VIEW MODE --}}
        <div x-show="!editMode" class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-full object-cover border" alt="Profile Image">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">
                            {{ Auth::user()->name }}
                            <button @click="editMode = true" class="ml-3 text-sm text-red-600 hover:text-red-800">
                                ✏️ Edit
                            </button>
                        </h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            @php
                $balance = auth()->user()->balance;
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                <div><strong>Contact:</strong> {{ Auth::user()->contact }}</div>
                <div><strong>Date of Birth:</strong> {{ Auth::user()->dob ?? 'N/A' }}</div>
                <div><strong>Gender:</strong> {{ Auth::user()->gender ?? 'N/A' }}</div>
                <div><strong>Country:</strong> {{ Auth::user()->country ?? 'N/A' }}</div>
                <div><strong>Division:</strong> {{ Auth::user()->division ?? 'N/A' }}</div>
                <div><strong>District:</strong> {{ Auth::user()->district ?? 'N/A' }}</div>
                <div><strong>City:</strong> {{ Auth::user()->city ?? 'N/A' }}</div>
                <div><strong>Road:</strong> {{ Auth::user()->road ?? 'N/A' }}</div>
                <div class="sm:col-span-2"><strong>Address Note:</strong> {{ Auth::user()->address_note ?? 'N/A' }}</div>
                <div class="flex items-center space-x-2">
                <strong>Refer Code:</strong>
                    @if (Auth::user()->account_status === 'inactive')
                        <input type="text" id="referCode" value="{{ Auth::user()->refer_code ?? 'N/A' }}" readonly class="border px-2 py-1 text-sm rounded w-32 opacity-50 cursor-not-allowed">
                        <button class="bg-gray-400 text-white px-3 py-1 rounded text-sm cursor-not-allowed" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-4 4h6a2 2 0 012 2v6a2 2 0 01-2 2h-8a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                            </svg>
                        </button>
                    @else
                        <input type="text" id="referCode" value="{{ Auth::user()->refer_code }}" readonly class="border px-2 py-1 text-sm rounded w-32">
                        <button onclick="copyReferCode()" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm" id="cbtn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-4 4h6a2 2 0 012 2v6a2 2 0 01-2 2h-8a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                            </svg>
                        </button>
                        <p class="text-sm font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full inline-block animate-pulse shadow-sm">
                            👥 Referred: {{ Auth::user()->referredUsersCount() }} users
                        </p>
                    @endif
                </div>

                <div><strong>Account Level:</strong>
                    @php
                        $statusColors = [
                            'NULL' => 'bg-red-100 text-red-800',
                            'VIP1' => 'bg-yellow-100 text-yellow-800',
                            'VIP2' => 'bg-green-100 text-green-800',
                            'VIP3' => 'bg-blue-100 text-blue-800',
                            'VIP4' => 'bg-indigo-100 text-indigo-800',
                            'VIP5' => 'bg-purple-100 text-purple-800',
                        ];
                    @endphp

                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColors[Auth::user()->account_status] ?? 'bg-gray-100 text-gray-800' }}">
                        @if( Auth::user()->account_status != 'inactive' )
                        {{ Auth::user()->account_status }} 
                        </span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800"> Inactive </span>
                        @endif
                    <button type="button" id="viewLevels"
                            class="bg-blue-600 text-white px-2 py-2 mx-2 my-2 rounded hover:bg-blue-700">
                                Update Level?
                        </button>
                    <form method="POST" action="{{ route('user.profile.updateStat') }}" id="updateStatusForm">@csrf</form>
                        
                    </div>
                @php
                    $statusColor = [
                        NULL => 'bg-red-100 text-red-800',
                        1 => 'bg-green-100 text-green-800',
                    ];  
                @endphp
 
                <div class="sm:col-span-2">Verification Status:</strong> 
                <span class = "{{ $statusColor[Auth::user()->verification] ?? 'text-gray-500' }}">
            @if(Auth::user()->verification === NULL && Auth::user()->ver_doc === NULL)
            Not Verified
            <button onclick="document.getElementById('verificationModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-2 py-2 mx-2 my-2 rounded-lg shadow-md transition duration-200 ease-in-out">
                ✅ Verify Account
            </button>
            @elseif(Auth::user()->verification === NULL && Auth::user()->ver_doc != NULL)
            Pending
            @elseif(Auth::user()->verification != NULL)
            Verified       
            @endif </span>
            </div>
                </div>
        

<!-- Modal Backdrop -->
<div id="verificationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        
        <!-- Close Button -->
        <button type="button" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-2xl font-bold"
            onclick="document.getElementById('verificationModal').classList.add('hidden')">
            &times;
        </button>

        <!-- Form Title -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4">📎 Submit Verification Document</h2>

        <!-- Form -->
        <form method="POST" action="{{ route('user.profile.insertVerificationData') }}" enctype="multipart/form-data">
            @csrf

            <label class="block text-sm text-gray-700 mb-2">
                Submit an Official Document (e.g. NID, Driving License, Birth Certificate):
            </label>

            <input type="file" name="verification_document" required
                class="w-full border border-gray-300 rounded px-3 py-2 mb-4">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Submit
            </button>
        </form>
    </div>
</div>

<div id="levelPlate" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 space-y-4">
        <p class="text-sm font-semibold text-green-600 bg-green-100 px-3 py-1 rounded-full inline-block animate-pulse shadow-sm">
            Balance: {{ auth()->user()->balance }} USD, Refered: {{ Auth::user()->referredUsersCount() }} users.
        </p>
        <!-- VIP Levels -->
        @php
            $levels = [
                ['bg' => 'bg-yellow-100', 'label' => 'VIP1', 'amount' => '30.00', 'commission' => '0.26', 'order' => '20', 'ref' => '0'],
                ['bg' => 'bg-green-100', 'label' => 'VIP2', 'amount' => '500.00', 'commission' => '0.28', 'order' => '25', 'ref' => '2'],
                ['bg' => 'bg-blue-100', 'label' => 'VIP3', 'amount' => '3000.00', 'commission' => '0.30', 'order' => '30', 'ref' => '5'],
                ['bg' => 'bg-indigo-100', 'label' => 'VIP4', 'amount' => '10000.00', 'commission' => '0.35', 'order' => '35', 'ref' => '10'],
                ['bg' => 'bg-purple-100', 'label' => 'VIP5', 'amount' => '30000.00', 'commission' => '0.40', 'order' => '40', 'ref' => '20'],
            ];
        @endphp

        @foreach($levels as $level)
            <div class="flex items-center justify-between">
                <!-- Diamond Circle & Info -->
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 {{ $level['bg'] }} rounded-full flex items-center justify-center text-white text-2xl">
                        💎
                    </div>
                    <div>
                        <div class="font-bold text-lg text-black">{{ $level['label'] }}</div>
                        <div class="text-black">Amount: {{ $level['amount'] }}$</div>
                        <div class="text-black text-sm">Active Refers: {{ $level['ref'] }}</div>
                        <div class="text-gray-800 text-sm">Comission: {{ $level['commission'] }}% | {{ $level['order'] }} orders</div>
                    </div>
                </div>
                <!-- Go Button -->
                @if($level['amount'] <= $balance && $level['ref'] <= Auth::user()->referredUsersCount())
                    @if($level['label'] === Auth::user()->account_status)
                        <p class="{{ $level['bg'] }} text-black font-semibold px-4 py-2 rounded-lg shadow">
                            ✅ Your Level
                        </p>
                    @else
                        <button name="{{ $level['label'] }}" class="takeMembership bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                            Go
                        </button>
                    @endif
                @else
                <button class="bg-gray-700 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg shadow" disabled>
                    🚫
                </button>
                @endif

            </div>
        @endforeach

        <!-- Cancel Button -->
        <div class="flex justify-end">
            <button id="cancelLevelPlate" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-900">
                Cancel
            </button>
        </div>
    </div>
</div>
</div>


{{-- PROFILE EDIT MODE --}}
        <div x-show="editMode" x-cloak class="bg-white shadow-md rounded-lg p-6 mb-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="flex items-center space-x-4 mb-6">
                    <img src="{{ Auth::user()->profile_image ?? 'https://via.placeholder.com/100' }}" class="w-20 h-20 rounded-full object-cover border" alt="Profile Image">
                    <div class="w-full">
                        <label class="block text-gray-700 text-sm font-semibold">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="w-full mt-1 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Email (read-only)</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Contact</label>
                        <input type="text" name="contact" value="{{ old('contact', Auth::user()->contact) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', Auth::user()->dob) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Gender</label>
                        <select name="gender" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="">Select</option>
                            <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Country</label>
                        <input type="text" name="country" value="{{ old('country', Auth::user()->country) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Division</label>
                        <input type="text" name="division" value="{{ old('division', Auth::user()->division) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">District</label>
                        <input type="text" name="district" value="{{ old('district', Auth::user()->district) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">City</label>
                        <input type="text" name="city" value="{{ old('city', Auth::user()->city) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-semibold">Road</label>
                        <input type="text" name="road" value="{{ old('road', Auth::user()->road) }}" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold">Address Note</label>
                        <textarea name="address_note" rows="2" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('address_note', Auth::user()->address_note) }}</textarea>
                    </div>
                    
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md">
                        Save Changes
                    </button>
                    <button type="button" @click="editMode = false" class="text-gray-600 hover:text-red-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        
    @auth
        @if (auth()->user()->role === 'user')

        <div class="text-xl font-semibold text-[#01132b] mb-4">
        🎁 Referal Bonus: {{ \App\Models\Transaction::referralBonusEarned() }} USD <br />
        💵 Your Transactions: {{ auth()->user()->balance - \App\Models\PurchasedTask::totalCommissionEarned() - \App\Models\Transaction::referralBonusEarned() }} USD <br />
        🛍️ Product Purchase: {{ \App\Models\PurchasedTask::totalActiveSpentByUser() }} USD <br />
        📈 Comission Earned: {{ \App\Models\PurchasedTask::totalCommissionEarned() }} USD <br />
        💰 Grand Account Balance: {{ auth()->user()->balance - \App\Models\PurchasedTask::totalActiveSpentByUser() }} USD <br />
        </div>

        {{-- Section: Transaction History --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Transaction History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Transaction ID</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Transaction Amount</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Wallet Name</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Wallet Number</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Transaction Request Date</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Admin Review Date</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Transaction Type</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($transactions as $index => $transaction)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $transaction->transaction_id }}</td>
                                <td class="px-4 py-2">{{ $transaction->amount }}</td>
                                <td class="px-4 py-2">{{ $transaction->wallet_name }}</td>
                                <td class="px-4 py-2">{{ $transaction->wallet_number }}</td>
                                <td class="px-4 py-2">{{ $transaction->created_at }}</td>
                                <td class="px-4 py-2 capitalize">
                                @if($transaction->type === 'pending')
                                    ---
                                @else
                                     {{ $transaction->updated_at }}
                                @endif
                                </td>
                                <td class="px-4 py-2 capitalize">{{ $transaction->type }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 capitalize">{{ $transaction->remarks }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 bg-white shadow rounded p-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">📦 Purchased Tasks</h3>
        <div class = "overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Task ID</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Task Title</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Price ($)</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Comission ($)</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Remaining Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach(auth()->user()->purchasedTasks as $purchase)
                    <tr>
                        <td class="px-4 py-2">{{ $purchase->task_id }}</td>
                        <td class="px-4 py-2">{{ $purchase->task_title }}</td>
                        <td class="px-4 py-2">{{ $purchase->price }}</td>
                        <td class="px-4 py-2">{{ $purchase->comission }}</td>
                        <td class="px-4 py-2">
                        @if(Carbon::parse($purchase->remaining_time)->lt($now))    
                            {{ $purchase->status }}
                        @else
                            Task Completing
                        @endif
                        </td>
                        <td class="px-4 py-2 countdown" data-expiry='{{ $purchase->remaining_time->toIso8601String() }}'>Calculating...</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        </div>
        @endif
    @endauth
    </div>
</div>
    <script>
    function copyReferCode() {
        const input = document.getElementById('referCode');
        input.select();
        input.setSelectionRange(0, 99999); // For mobile
        document.execCommand('copy');
        document.getElementById("cbtn").innerHTML = "✅";
    }
    
    document.addEventListener('DOMContentLoaded', function () {
        const countdownElements = document.querySelectorAll('.countdown');

        countdownElements.forEach(el => {
            const expiryTime = new Date(el.dataset.expiry).getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiryTime - now;

                if (distance <= 0) {
                    el.textContent = 'Expired';
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                el.textContent = `${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000); // updates every second
        });
    });
    
</script>
<script>
    updateBtn = document.getElementById('viewLevels');
    updateBtn.addEventListener('click', () => {
        levelPlate.classList.remove('hidden');
    });
    cancelBtn = document.getElementById('cancelLevelPlate');
    cancelBtn.addEventListener('click', () => {
        levelPlate.classList.add('hidden');
    });
    

    const goBtns = document.querySelectorAll(".takeMembership");

goBtns.forEach(goBtn => {
    goBtn.addEventListener("click", () => {
        const form = document.getElementById("updateStatusForm");

        // Create a hidden input to hold the button's name
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "membership_level"; // this is the name you want to pass
        input.value = goBtn.name; // or a custom value like goBtn.value

        form.appendChild(input);
        form.submit();

        const levelPlate = document.querySelector('.levelPlate'); // Assuming it's a single element
        if (levelPlate) {
            levelPlate.classList.add('hidden');
        }
    });
});


</script>

<script>
    fetch("{{ route('user.profile.updateStatusAuto') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => console.log("Account status:", data))
    .catch(error => console.error("Error:", error));
</script>

</x-app-layout>
