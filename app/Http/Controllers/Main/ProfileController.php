<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\User\UserAddress;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $addresses = UserAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $transactions = Transaction::where('user_id', $user->id)
            ->withCount('transactionDetails')
            ->latest()
            ->fastPaginate(15);

        return inertia('main/Profile', [
            'user' => $user,
            'addresses' => $addresses,
            'transactions' => $transactions,
        ]);
    }

    public function update()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return back();
    }
}
