<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TaskPurchaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\IsAdmin;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Task;
use Mockery\VerificationDirector;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


Route::get('/', function () {
    return view('welcome');
});


Route::post('/user/profile/update-status-auto', [ProfileController::class, 'updateStatOnLoad'])->name('user.profile.updateStatusAuto');


Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/dashboard', [DashboardController::class, "dashboardValues"])->name('dashboard');

Route::post('/user/profile/update-status', [ProfileController::class, 'updateStat'])->name('user.profile.updateStat');
Route::post('/user/profile/insert-verification', [ProfileController::class, 'insertVerificationData'])->name('user.profile.insertVerificationData');


Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::view('/transactions', 'admin.transactions.index')->name('transactions.index');
    Route::view('/tasks', 'admin.tasks.index')->name('tasks.index');
    Route::view('/notifications', 'admin.notifications.index')->name('notifications.index');
    Route::view('/verifcation', 'admin.userverification.index')->name('userverification.index');
});


Route::get('/admin/verification', [VerificationController::class, 'index'])->name('admin.userverification.index');
Route::post('/admin/verification/verify', [VerificationController::class, 'verifyUser'])->name('admin.userverification.verify');


Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', function () {
        $tasks = Task::where('expires_at', '>', now())->latest()->get();
        return view('tasks', compact('tasks'));
    })->name('users.tasks.index');
});

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions.index');
    Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::patch('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::patch('/transactions/{transaction}/remarks', [TransactionController::class, 'remarks'])->name('transactions.remarks');
});

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/store', [NotificationController::class, 'store'])->name('notifications.store'); 
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');   
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', function () {
        $notifications = Notification::latest()->paginate(15);
        return view('notifications', compact('notifications'));
    })->name('notifications.index');
});


Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::resource('tasks', TaskController::class);
    });


Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', function () {
        return view('profile');
    })->name('user.profile');
});

Route::middleware('auth')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'create'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
});

Route::middleware(['auth'])->group(function () {
    //Route::view('/tasks', 'tasks')->name('tasks.index');
    Route::view('/transactions', 'transactions')->name('transactions.index');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', function () {
        $transactions = Transaction::where('user_id', auth()->id())->latest()->get();
        return view('profile', compact('transactions'));
    })->name('user.profile');
});


Route::post('/tasks/{task}/buy', [TaskPurchaseController::class, 'buy'])->name('tasks.buy');


Route::middleware(['auth'])->group(function () {
    Route::post('/tasks/{task}/purchase', [TaskPurchaseController::class, 'purchase'])->name('tasks.purchase');
    Route::post('/purchased-tasks/{purchasedTask}/submit', [TaskPurchaseController::class, 'submit'])->name('tasks.submit');
});

// ✅ Google login routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
