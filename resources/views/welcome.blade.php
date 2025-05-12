<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Rules</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl p-10 lg:p-16">
            <div class="text-center mb-10">
                <h1 class="text-4xl md:text-5xl font-extrabold text-red-600 mb-4">📜 Business Rules & Guidelines</h1>
                <p class="text-gray-500 text-lg">Read these carefully before getting started with your journey.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-lg">
                <div>
                    <h3 class="text-xl font-bold mb-2">✅ 1. User Verification</h3>
                    <p>Provide valid contact info and verify your identity to activate your account.</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-2">💼 2. Task Completion</h3>
                    <p>All tasks must be submitted before the deadline with proper proof.</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-2">💸 3. Transaction Policy</h3>
                    <p>All transactions are reviewed within 48 hours by the admin team.</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-2">🔗 4. Referral System</h3>
                    <p>Earn bonuses by inviting others using your referral code after activation.</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-2">🚫 5. Account Termination</h3>
                    <p>Only one account per user. Fraud or spamming leads to instant termination.</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-2">📆 6. Stay Active</h3>
                    <p>Inactive users may lose task access. Stay consistent to earn rewards.</p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('login') }}"
                   class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-lg font-semibold px-10 py-4 rounded-full shadow-lg transition">
                    🚀 Start Now
                </a>
                <p class="text-sm text-gray-400 mt-4">Clicking “Start Now” means you accept all the above rules.</p>
            </div>
        </div>
    </div>
</body>
</html>
