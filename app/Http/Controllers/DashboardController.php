<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // total pendapatan (hanya transaksi selesai/Lunas)
        $totalRevenueThisMonth = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->where('status', 'Lunas')
            ->sum('total');

        // total transaksi (semua status untuk laporan transaksi)
        $totalTransactionsThisMonth = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->count();

        // Total produk terjual (hanya transaksi selesai/Lunas)
        $totalItemsSoldThisMonth = TransactionItem::whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$currentMonth, $endOfMonth])
                ->where('status', 'Lunas');
        })->sum('qty');

        // 4. KPI - Low Stock Products (stok <= 10)
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // 5. KPI - Pending Transactions
        $pendingTransactions = Transaction::where('status', 'Menunggu Pembayaran')
            ->count();

        // 6. KPI - Active Customers This Month
        $activeCustomers = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->distinct('user_id')
            ->count('user_id');

        // 7. Sales Data Last 7 Days (hanya transaksi selesai/Lunas)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Transaction::whereDate('created_at', $date)
                ->where('status', 'Lunas')
                ->sum('total');
            $last7Days[] = [
                'date' => $date->format('d/m'),
                'total' => $sales ?? 0
            ];
        }

        // 8. Top 5 Products by Quantity Sold This Month (hanya transaksi selesai/Lunas)
        $topProducts = TransactionItem::whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$currentMonth, $endOfMonth])
                ->where('status', 'Lunas');
        })
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        // 9. Payment Method Breakdown This Month (hanya transaksi selesai/Lunas)
        $paymentMethods = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->where('status', 'Lunas')
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // 10. Recent 10 Transactions
        $recentTransactions = Transaction::orderByDesc('created_at')
            ->limit(10)
            ->with('user')
            ->get();

        // 11. Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // 12. Monthly comparison for growth
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $revenueLastMonth = Transaction::whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->where('status', 'Lunas')
            ->sum('total');

        $revenueGrowth = $revenueLastMonth > 0
            ? (($totalRevenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100
            : 0;

        return view('dashboard.index', [
            'totalRevenueThisMonth' => $totalRevenueThisMonth,
            'totalTransactionsThisMonth' => $totalTransactionsThisMonth,
            'totalItemsSoldThisMonth' => $totalItemsSoldThisMonth,
            'lowStockCount' => $lowStockCount,
            'pendingTransactions' => $pendingTransactions,
            'activeCustomers' => $activeCustomers,
            'last7Days' => $last7Days,
            'topProducts' => $topProducts,
            'paymentMethods' => $paymentMethods,
            'recentTransactions' => $recentTransactions,
            'lowStockProducts' => $lowStockProducts,
            'revenueGrowth' => $revenueGrowth,
        ]);
    }
}
