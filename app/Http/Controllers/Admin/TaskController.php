<?php

namespace App\Http\Controllers\Admin;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchasedTask;
use App\Http\Controllers\PurchasedTaskController;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('admin.tasks.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'response_type' => 'required|string',
        'expires_at' => 'required|date',
        'price' => 'required|numeric',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('task_images', 'public');
    }

    $validated['created_by'] = auth()->id();

    Task::create($validated);

    return redirect()->route('admin.tasks.index')->with('status', 'Task created successfully.');
}
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approveTask(PurchasedTask $purchasedTask)
{
    $purchasedTask->update(['status' => 'approved']);

    $amount = $purchasedTask->task->price + 0.01;

    $purchasedTask->user->transactions()->create([
        'wallet_number' => 'internal',
        'wallet_name' => 'Task Reward',
        'transaction_id' => uniqid('reward_'),
        'amount' => $amount,
        'type' => 'deposit',
        'status' => 'approved',
        'date' => now(),
    ]);

    return back()->with('status', 'Task approved and reward given.');
}



}
