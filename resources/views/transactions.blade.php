<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">💸 Submit a Transaction</h2>

        @if(session('status'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @elseif(session('error'))
            <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">
                <marquee>{{ session('error') }}</marquee>
            </div>
        @endif

        @php $userId = Auth::user()->id; @endphp

        <form method="POST" action="{{ route('transactions.store') }}" id="transactionForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Amount</label>
                <input type="number" name="amount" step="0.01" id="amountInput" class="w-full border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Transaction Type</label>
                <select name="type" id="transactionType" class="w-full border-gray-300 rounded" required>
                    <option value="">Select</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Wallet Name</label>
                <input type="text" name="wallet_name" class="w-full border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Wallet Number</label>
                <input type="text" name="wallet_number" class="w-full border-gray-300 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Transaction ID</label>
                <input type="text" name="transaction_id" id="transactionId" class="w-full border-gray-300 rounded" required>
            </div>

            <button type="button" id="openConfirmModal" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Submit
            </button>
        </form>

        <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <div id="verifyWarning" class="hidden text-red-600 font-semibold text-center mb-4">
                    ⚠️ Please verify your account before making a withdrawal.
                </div>
                <div id="modalContent">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirm Transaction</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to submit this transaction?</p>
                    <div class="flex justify-end space-x-4">
                        <button id="confirmSubmit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Yes, Submit</button>
                    </div>
                </div>
                <button id="cancelModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const transactionType = document.getElementById('transactionType');
        const transactionIdInput = document.getElementById('transactionId');
        const amountInput = document.getElementById('amountInput');
        const transactionForm = document.getElementById('transactionForm');
        const openConfirmModal = document.getElementById('openConfirmModal');
        const confirmationModal = document.getElementById('confirmationModal');
        const cancelModal = document.getElementById('cancelModal');
        const confirmSubmit = document.getElementById('confirmSubmit');
        const verifyWarning = document.getElementById('verifyWarning');
        const modalContent = document.getElementById('modalContent');

        const userId = "{{ $userId }}";
        const isVerified = {{ Auth::user()->verification ? 'true' : 'false' }};

        function generateTransactionId() {
            const amount = amountInput.value || 0;
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const hour = String(now.getHours()).padStart(2, '0');
            const sum = String(Number(day) + Number(month) - Number(hour));
            return `w${userId}${amount}${sum}`;
        }

        function updateTransactionIdBehavior() {
            if (transactionType.value === 'withdrawal') {
                transactionIdInput.readOnly = true;
                transactionIdInput.value = generateTransactionId();
            } else {
                transactionIdInput.readOnly = false;
                transactionIdInput.value = '';
            }
        }

        transactionType.addEventListener('change', updateTransactionIdBehavior);
        amountInput.addEventListener('input', () => {
            if (transactionType.value === 'withdrawal') {
                transactionIdInput.value = generateTransactionId();
            }
        });

        openConfirmModal.addEventListener('click', () => {
            const type = transactionType.value;

            if (type === 'withdrawal' && !isVerified) {
                verifyWarning.classList.remove('hidden');
                modalContent.classList.add('hidden');
            } else {
                verifyWarning.classList.add('hidden');
                modalContent.classList.remove('hidden');
            }

            confirmationModal.classList.remove('hidden');
        });

        cancelModal.addEventListener('click', () => {
            confirmationModal.classList.add('hidden');
        });

        confirmSubmit.addEventListener('click', () => {
            confirmationModal.classList.add('hidden');
            transactionForm.submit();
        });

        updateTransactionIdBehavior();
    </script>
</x-app-layout>
