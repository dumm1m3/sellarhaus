@extends('layouts.admin-navigation')
<x-app-layout>
    @if(Auth::check() && Auth::user()->role === 'admin')
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-white mb-6">📊 Admin Dashboard</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('user.profile') }}" class="block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-700">👤 Admin Profile</h3>
                <p class="text-gray-500 text-sm mt-2">{{ Auth::user()->name }}, {{ Auth::user()->email }}</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-700">👥 Manage Users</h3>
                <p class="text-gray-500 text-sm mt-2">View and manage all registered users</p>
            </a>

            <a href="{{ route('admin.tasks.index') }}" class="block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-700">📋 Manage Tasks</h3>
                <p class="text-gray-500 text-sm mt-2">Assign and track user tasks</p>
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="relative block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
            @if( $pending )    
            <p class="absolute -top-1 -right-1 w-10 h-10 bg-red-500 rounded-full border-2 border-white flex items-center justify-center text-white font-semibold">{{ $pending }}</p>
            @endif
                <h3 class="text-lg font-semibold text-gray-700">💳 Manage Transactions</h3>
                <p class="text-gray-500 text-sm mt-2">View, approve, or reject transactions</p>
            </a>


            <a href="{{ route('admin.notifications.index') }}" class="block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-700">📣 Manage Announcement</h3>
                <p class="text-gray-500 text-sm mt-2">Send and delete notification to users</p>
            </a>


            <a href="{{ route('admin.userverification.index') }}" class="relative block bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
            @if( $verification )    
            <p class="absolute -top-1 -right-1 w-10 h-10 bg-red-500 rounded-full border-2 border-white flex items-center justify-center text-white font-semibold">{{ $verification }}</p>
            @endif
                <h3 class="text-lg font-semibold text-gray-700">✅👤 User Verification</h3>
                <p class="text-gray-500 text-sm mt-2">Verify users</p>
            </a>
            
        </div>
    </div>
    @else
    <?php abort(403, 'Unauthorized access – Admins only.'); ?>
    @endif
</x-app-layout>
