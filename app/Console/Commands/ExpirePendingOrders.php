<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending
        {--hours=24 : Batalkan order pending_payment yang lebih tua dari jumlah jam ini}';

    protected $description = 'Batalkan order yang tidak dibayar melewati batas waktu dan kembalikan stoknya';

    public function handle()
    {
        $hours = (int) $this->option('hours');

        $orders = Order::with('orderItems')
            ->where('status', 'pending_payment')
            ->where('created_at', '<', Carbon::now()->subHours($hours))
            ->get();

        foreach ($orders as $order) {
            $order->restoreStock();
            $order->status = 'canceled';
            $order->canceled_date = Carbon::now();
            $order->save();
            Transaction::where('order_id', $order->id)->update(['status' => 'declined']);
        }

        $this->info("Expired {$orders->count()} pending order(s).");

        return self::SUCCESS;
    }
}
