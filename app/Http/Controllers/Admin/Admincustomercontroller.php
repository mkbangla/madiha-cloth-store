<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        $orders = $user->orders()->with('items')->latest()->paginate(10);
        return view('admin.customers.show', compact('user', 'orders'));
    }
}