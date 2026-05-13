<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy("id", "asc")->paginate(5);
        return view("admin.orders.index", compact("orders"));
    }

    public function show(string $id)
    {
        $order = Order::with("orderItems.product.images")->findOrFail($id);
        return view("admin.orders.show", compact("order"));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => [
                "required", Rule::in(['Pending', 'Processing', 'Shipped', 'Completed'])
            ],
        ]);
        $order->update(['status' => $request->status]);
        return back()->with("success", "Status order berhasil diubah");
    }
}
