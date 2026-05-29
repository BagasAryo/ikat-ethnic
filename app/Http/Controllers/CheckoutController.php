<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        $cartItems = $cart ? $cart->cartItems()->with(['product.images', 'product.sizes'])->get() : collect();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Validate stock for all items in the cart
        foreach ($cartItems as $item) {
            $size = $item->product->sizes->where('id', $item->product_size_id)->first();
            if (!$size || $size->stock < $item->quantity) {
                return redirect()->route('cart')->with('error', "Stok untuk produk {$item->product->name} (Ukuran: " . ($size->name ?? '-') . ") tidak mencukupi.");
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shippingCost = 20000; // Flat shipping rate
        $total = $subtotal + $shippingCost;

        return view('pages.checkout', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = $user->cart;
        
        $cartItems = $cart ? $cart->cartItems()->with(['product.sizes', 'product_size'])->get() : collect();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Validate stock again before order creation
        foreach ($cartItems as $item) {
            $size = $item->product->sizes->where('id', $item->product_size_id)->first();
            if (!$size || $size->stock < $item->quantity) {
                return response()->json([
                    'message' => "Stok untuk produk {$item->product->name} (Ukuran: " . ($size->name ?? '-') . ") tidak mencukupi."
                ], 400);
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shippingCost = 20000;
        $total = $subtotal + $shippingCost;

        // Generate Unique Order Number
        $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(100, 999);

        // Database Transaction
        try {
            DB::beginTransaction();

            // 1. Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $total,
                'status' => 'Pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            // 2. Create Order Items
            $itemDetails = [];
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_size_id' => $item->product_size_id,
                    'product_name' => $item->product->name,
                    'product_size' => $item->product_size->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                $itemDetails[] = [
                    'id' => 'prod-' . $item->product_id . '-' . $item->product_size_id,
                    'price' => (int) $item->product->price,
                    'quantity' => (int) $item->quantity,
                    'name' => substr(ucwords($item->product->name) . ' (' . $item->product_size->name . ')', 0, 50),
                ];
            }

            if ($shippingCost > 0) {
                $itemDetails[] = [
                    'id' => 'shipping',
                    'price' => (int) $shippingCost,
                    'quantity' => 1,
                    'name' => 'Shipping Cost',
                ];
            }

            // 3. Create Payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'midtrans',
                'amount' => $total,
                'status' => 'pending',
            ]);

            // 4. Configure Midtrans Snap
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $total,
                ],
                'customer_details' => [
                    'first_name' => $order->shipping_name,
                    'email' => $user->email,
                    'phone' => $order->shipping_phone,
                    'billing_address' => [
                        'first_name' => $order->shipping_name,
                        'email' => $user->email,
                        'phone' => $order->shipping_phone,
                        'address' => $order->shipping_address,
                    ],
                    'shipping_address' => [
                        'first_name' => $order->shipping_name,
                        'email' => $user->email,
                        'phone' => $order->shipping_phone,
                        'address' => $order->shipping_address,
                    ]
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // 5. Update Payment with snap_token
            $payment->update([
                'snap_token' => $snapToken
            ]);

            // 6. Clear Cart Items
            $cart->cartItems()->delete();

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error placing order: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with(['orderItems.product', 'payment'])->where('id', $orderId)->firstOrFail();

        // Check if user is authorized to view this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.checkout-success', compact('order'));
    }

    public function callback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification: ' . $e->getMessage()], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $orderId = $notif->order_id;
        $transactionId = $notif->transaction_id;
        $statusCode = $notif->status_code;
        $grossAmount = $notif->gross_amount;
        $signatureKey = $notif->signature_key;

        // Verify signature
        $localSignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . config('services.midtrans.server_key'));

        if ($signatureKey !== $localSignatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::with('orderItems.size')->where('order_number', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = $order->payment ?? Payment::firstOrCreate([
            'order_id' => $order->id,
        ], [
            'amount' => $order->total_amount,
            'payment_method' => 'midtrans',
        ]);

        $payment->update([
            'transaction_id' => $transactionId,
            'payment_method' => $paymentType,
            'raw_response' => (array) $notif,
        ]);

        // Only handle status transition if the payment is not already marked paid
        if ($payment->status !== 'paid') {
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($notif->fraud_status == 'challenge') {
                        $payment->update(['status' => 'pending']);
                        $order->update(['status' => 'Pending']);
                    } else {
                        $payment->update(['status' => 'paid']);
                        $order->update(['status' => 'Processing']);
                        $this->decrementStock($order);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $payment->update(['status' => 'paid']);
                $order->update(['status' => 'Processing']);
                $this->decrementStock($order);
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
                $order->update(['status' => 'Pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'Cancelled']);
            }
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }

    private function decrementStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            $size = $item->size;
            if ($size) {
                $size->decrement('stock', $item->quantity);
            }
        }
    }
}
