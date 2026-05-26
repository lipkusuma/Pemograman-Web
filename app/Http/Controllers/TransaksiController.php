<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransaksiController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        
        // Eager load items and products to display them
        $transactions = Transaction::where('user_id', $userId)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksi.index', compact('transactions'));
    }
}
