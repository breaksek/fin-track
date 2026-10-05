<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Facade Auth

class TransactionController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transactions = $user->transactions()
            ->orderBy('waktu', 'desc')
            ->get();

        // Hitung total ringkasan
        $totalPemasukkan = $transactions->where('tipe', 'pemasukkan')->sum('nominal');
        $totalPengeluaran = $transactions->where('tipe', 'pengeluaran')->sum('nominal');
        $totalSaldo = $totalPemasukkan - $totalPengeluaran;

        return view('dashboard', compact('transactions', 'totalPemasukkan', 'totalPengeluaran', 'totalSaldo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waktu' => 'required|date',
            'tipe' => 'required|in:pemasukkan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->transactions()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil ditambahkan!');
    }
}