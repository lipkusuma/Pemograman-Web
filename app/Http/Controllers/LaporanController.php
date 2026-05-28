<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        // Determine date range based on filter
        $now = Carbon::now();
        $startDate = null;
        $prevStartDate = null;
        $prevEndDate = null;

        switch ($filter) {
            case 'hari':
                $startDate = $now->copy()->startOfDay();
                $prevStartDate = $now->copy()->subDay()->startOfDay();
                $prevEndDate = $now->copy()->subDay()->endOfDay();
                break;
            case 'minggu':
                $startDate = $now->copy()->startOfWeek();
                $prevStartDate = $now->copy()->subWeek()->startOfWeek();
                $prevEndDate = $now->copy()->subWeek()->endOfWeek();
                break;
            case 'bulan':
                $startDate = $now->copy()->startOfMonth();
                $prevStartDate = $now->copy()->subMonth()->startOfMonth();
                $prevEndDate = $now->copy()->subMonth()->endOfMonth();
                break;
            default: // 'semua'
                $startDate = null;
                break;
        }

        // ── Current Period Queries ─────────────────────────────────────

        $transactionsQuery = Transaction::query();
        if ($startDate) {
            $transactionsQuery->where('created_at', '>=', $startDate);
        }

        // Total Transaksi (all statuses)
        $totalTransaksi = (clone $transactionsQuery)->count();

        // Total Pendapatan (only completed/'Lunas' transactions)
        $totalPendapatan = (clone $transactionsQuery)
            ->where('status', 'Lunas')
            ->sum('total');

        // Produk Terjual (sum qty from transaction_items of completed transactions)
        $completedTransactionIds = (clone $transactionsQuery)
            ->where('status', 'Lunas')
            ->pluck('id');

        $totalProdukTerjual = TransactionItem::whereIn('transaction_id', $completedTransactionIds)
            ->sum('qty');

        // Total Pelanggan (unique users who have transactions in this period)
        $totalPelanggan = (clone $transactionsQuery)
            ->distinct('user_id')
            ->count('user_id');

        // ── Previous Period Queries (for percentage change) ────────────

        $prevTransaksi = 0;
        $prevPendapatan = 0;
        $prevProdukTerjual = 0;
        $prevPelanggan = 0;

        if ($prevStartDate && $prevEndDate) {
            $prevQuery = Transaction::whereBetween('created_at', [$prevStartDate, $prevEndDate]);

            $prevTransaksi = (clone $prevQuery)->count();
            $prevPendapatan = (clone $prevQuery)->where('status', 'Lunas')->sum('total');

            $prevCompletedIds = (clone $prevQuery)->where('status', 'Lunas')->pluck('id');
            $prevProdukTerjual = TransactionItem::whereIn('transaction_id', $prevCompletedIds)->sum('qty');

            $prevPelanggan = (clone $prevQuery)->distinct('user_id')->count('user_id');
        }

        // Calculate percentage changes
        $changeTransaksi = $this->calcChange($totalTransaksi, $prevTransaksi);
        $changePendapatan = $this->calcChange($totalPendapatan, $prevPendapatan);
        $changeProdukTerjual = $this->calcChange($totalProdukTerjual, $prevProdukTerjual);
        $changePelanggan = $this->calcChange($totalPelanggan, $prevPelanggan);

        // ── Transaction Detail Table ───────────────────────────────────

        $laporanQuery = Transaction::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc');

        if ($startDate) {
            $laporanQuery->where('created_at', '>=', $startDate);
        }

        $transactions = $laporanQuery->get();

        // Map transactions to a flat list per item for the table
        $laporan = [];
        foreach ($transactions as $trx) {
            foreach ($trx->items as $item) {
                $laporan[] = [
                    'id'        => $trx->invoice_number,
                    'tanggal'   => $trx->created_at,
                    'pelanggan' => $trx->user->name ?? 'Pelanggan',
                    'produk'    => $item->product->name ?? 'Produk Dihapus',
                    'qty'       => $item->qty,
                    'total'     => $item->price * $item->qty * $item->duration_days,
                    'status'    => $trx->status,
                ];
            }
        }

        return view('laporan.index', compact(
            'laporan',
            'totalTransaksi',
            'totalPendapatan',
            'totalProdukTerjual',
            'totalPelanggan',
            'changeTransaksi',
            'changePendapatan',
            'changeProdukTerjual',
            'changePelanggan',
            'filter'
        ));
    }

    /**
     * Calculate the percentage change between current and previous values.
     * Returns an array with 'value' (float) and 'direction' ('positive', 'negative', or 'neutral').
     */
    private function calcChange(int|float $current, int|float $previous): array
    {
        if ($previous == 0 && $current == 0) {
            return ['value' => 0, 'direction' => 'neutral'];
        }

        if ($previous == 0) {
            return ['value' => 100, 'direction' => 'positive'];
        }

        $change = (($current - $previous) / $previous) * 100;

        return [
            'value'     => round(abs($change), 1),
            'direction' => $change >= 0 ? 'positive' : 'negative',
        ];
    }
}
