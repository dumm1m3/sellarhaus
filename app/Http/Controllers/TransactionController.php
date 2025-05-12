<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProfileController;


class TransactionController extends Controller
{
    public function create()
{
    return view('transactions');
}

public function store(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'wallet_name'     => 'required|string|max:255',
            'wallet_number'   => 'required',
            'transaction_id'  => 'required|unique:transactions',
            'amount'          => 'required|numeric|min:0.01',
            'type'            => 'required|in:deposit,withdrawal',
            'remarks'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('transactions.index')
                             ->withErrors($validator)
                             ->withInput()
                             ->with('error', 'Transaction not submitted! Try Again!');
        }

        Transaction::create([
            'user_id'         => auth()->id(),
            'wallet_number'   => $request->wallet_number,
            'wallet_name'     => $request->wallet_name,
            'transaction_id'  => $request->transaction_id,
            'amount'          => $request->amount,
            'type'            => $request->type,
            'status'          => 'pending',
            'date'            => now(),
        ]);

        return redirect()->route('user.profile')->with('status', 'Transaction submitted and is pending.');

    } catch (\Exception $e) {
        return redirect()->route('transactions.index')->with('error', 'Transaction not submitted! Try Again!');
    }
}

public function adminIndex(Request $request)
{
    $transactions = Transaction::query()
        ->with('user')
        ->when($request->input('user'), function ($q) use ($request) {
            $q->whereHas('user', function ($u) use ($request) {
                $u->where('name', 'like', '%' . $request->user . '%');
            });
        })
        ->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })
        ->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        })
        ->latest()
        ->paginate(15);

    return view('admin.transactions.index', compact('transactions'));
}

public function approve(Transaction $transaction)
{
    $transaction->update(['status' => 'accepted']);
    return back()->with('status', 'Transaction Approved.');
}

public function reject(Transaction $transaction)
{
    $transaction->update(['status' => 'rejected']);
    return back()->with('status', 'Transaction Rejected.');
}

public function remarks(Request $request, Transaction $transaction)
{
    $transaction->remarks = $request->remarks;
    $transaction->save();
    return back();
}
}
