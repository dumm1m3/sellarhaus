<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{

    public function dashboardValues(){
        $verification = User::whereNull('verification')
                    ->whereNotNull('ver_doc')
                    ->count();
        $pending = Transaction::where("status", "pending")->count();
        return view('dashboard', [
            'verification' => $verification,
            'pending' => $pending,
        ]);
    }

}
