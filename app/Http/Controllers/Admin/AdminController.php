<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques de base
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
        ];

        // Statistiques avec comparaison mensuelle
        $currentMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;
        
        $monthlyStats = [
            'users_this_month' => User::whereMonth('created_at', $currentMonth)->count(),
            'users_last_month' => User::whereMonth('created_at', $lastMonth)->count(),
            'orders_this_month' => Order::whereMonth('created_at', $currentMonth)->count(),
            'orders_last_month' => Order::whereMonth('created_at', $lastMonth)->count(),
        ];

        // Données récentes
        $recent_users = User::latest()->limit(5)->get();
        $recent_orders = Order::with('user')->latest()->limit(5)->get();

        // Données pour le graphique des commandes (7 derniers jours)
        $ordersChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Order::whereDate('created_at', $date->format('Y-m-d'))->count();
            $ordersChart[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        return view('admin.dashboard', compact('stats', 'monthlyStats', 'recent_users', 'recent_orders', 'ordersChart'));
    }
}
