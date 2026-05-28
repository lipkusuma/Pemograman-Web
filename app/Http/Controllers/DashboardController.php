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

        // total pendapatan
        $totalRevenueThisMonth = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->sum('total');

        // total transaksi
        $totalTransactionsThisMonth = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->count();

        // Total produk terjual 
        $totalItemsSoldThisMonth = TransactionItem::whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$currentMonth, $endOfMonth]);
        })->sum('qty');

        // 4. KPI - Low Stock Products (stok <= 10)
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // 5. KPI - Pending/Unpaid Transactions
        $pendingTransactions = Transaction::where('status', 'pending')
            ->orWhere('status', 'unpaid')
            ->count();

        // 6. KPI - Active Customers This Month
        $activeCustomers = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
            ->distinct('user_id')
            ->count('user_id');

        // 7. Sales Data Last 7 Days (for chart)
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Transaction::whereDate('created_at', $date)->sum('total');
            $last7Days[] = [
                'date' => $date->format('d/m'),
                'total' => $sales ?? 0
            ];
        }

        // 8. Top 5 Products by Quantity Sold This Month
        $topProducts = TransactionItem::whereHas('transaction', function ($query) use ($currentMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$currentMonth, $endOfMonth]);
        })
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        // 9. Payment Method Breakdown This Month
        $paymentMethods = Transaction::whereBetween('created_at', [$currentMonth, $endOfMonth])
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
