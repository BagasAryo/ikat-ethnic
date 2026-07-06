<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy("id", "desc")->paginate(5);
        return view("admin.orders.index", compact("orders"));
    }

    public function show(string $id)
    {
        $order = Order::with(['user', 'orderItems.product.images', 'orderItems.size'])->findOrFail($id);
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

        AdminLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_ORDER',
            'description' => "Memperbarui status pesanan #{$order->order_number} menjadi {$request->status}",
        ]);

        return back()->with("success", "Status order berhasil diubah");
    }
}
