<?php

namespace App\Http\Controllers;
use App\Models\PurchasedTask;
use Illuminate\Http\Request;
use App\Models\Task;


class TaskPurchaseController extends Controller
{
    public function buy(Task $task)
{   
    $user = auth()->user();

    // Determine commission rate based on user's account_status
    $commissionRates = [
        'VIP1' => 0.0026,
        'VIP2' => 0.0028,
        'VIP3' => 0.0030,
        'VIP4' => 0.0035,
        'VIP5' => 0.0040,
    ];

    $rate = $commissionRates[$user->account_status] ?? 0; // Default 0 if no match
    $commission = $task->price * $rate;

    // Create purchased task record
    PurchasedTask::create([
        'user_id'        => $user->id,
        'task_id'        => $task->id,
        'price'          => $task->price,
        'comission'     => $commission*$task->price,
        'task_title'     => $task->title,
        'remaining_time' => $task->expires_at,
        'status' => "Task Completed!"
    ]);
    
    return redirect()->route('user.profile')->with('status', 'Task purchased successfully.');
}


}
