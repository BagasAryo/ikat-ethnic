<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;

class CheckPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:check-pending';

    /**
     * The console command description.
     */
    protected $description = 'Cek status order Pending ke Midtrans dan cancel otomatis jika expired/tidak pernah dibayar';

    public function handle()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        $expiredOrders = Order::with('payment')
            ->where('status', 'Pending')
            ->where('created_at', '<', now()->subHours(1)) // sesuaikan toleransi waktu di sini
            ->get();

        $this->info("Ditemukan {$expiredOrders->count()} order pending yang akan dicek.");

        foreach ($expiredOrders as $order) {
            try {
                $status = Transaction::status($order->order_number);
                
                /** @var object $status */
                if (in_array($status->transaction_status, ['expire', 'cancel', 'deny'])) {
                    $this->cancelOrder($order);
                    $this->info("Order {$order->order_number}: dibatalkan (status Midtrans: {$status->transaction_status})");
                } elseif (in_array($status->transaction_status, ['settlement', 'capture'])) {
                    $order->update(['status' => 'Processing']);
                    $order->payment?->update(['status' => 'paid']);
                    $this->info("Order {$order->order_number}: ditandai paid (status Midtrans: {$status->transaction_status})");
                } elseif ($status->transaction_status == 'pending') {
                    // Transaksi ADA di Midtrans, user sudah pilih metode bayar, tapi belum bayar
                    // dan sudah lewat batas waktu toleransi kita. Kita pilih cancel juga di sini
                    // supaya order tidak menggantung selamanya di web meski di Midtrans belum expired.
                    $this->cancelOrder($order);
                    $this->info("Order {$order->order_number}: dibatalkan (masih pending di Midtrans tapi sudah lewat batas waktu)");
                }
            } catch (\Exception $e) {
                // 404 = transaksi tidak pernah tercipta di Midtrans (user tidak sempat pilih metode bayar)
                $this->cancelOrder($order);
                $this->info("Order {$order->order_number}: dibatalkan (tidak ditemukan di Midtrans / 404)");
                Log::info("CheckPendingOrders: order {$order->order_number} 404 di Midtrans, di-cancel otomatis.");
            }
        }

        $this->info('Selesai.');
    }

    private function cancelOrder(Order $order)
    {
        $order->update(['status' => 'Cancelled']);
        $order->payment?->update(['status' => 'failed']);
    }
}
