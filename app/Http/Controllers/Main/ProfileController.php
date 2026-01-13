<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
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

        // Dummy transaction data
        $transactions = collect([
            [
                'id' => 'TRX-001',
                'date' => '2026-01-10',
                'total' => 250000,
                'status' => 'completed',
                'items_count' => 3,
            ],
            [
                'id' => 'TRX-002',
                'date' => '2026-01-08',
                'total' => 150000,
                'status' => 'processing',
                'items_count' => 2,
            ],
            [
                'id' => 'TRX-003',
                'date' => '2026-01-05',
                'total' => 450000,
                'status' => 'completed',
                'items_count' => 5,
            ],
        ]);

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
