<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $data = [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'totalOrders' => $totalOrders,
        ];
        return view('admin.index', $data);
    }
}