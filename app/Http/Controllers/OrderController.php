<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;

class OrderController extends Controller
{
    /**
     * Menampilkan semua order.
     */
    public function updateStatus(Request $request)
{
    $request->validate([
        'order_id' => 'required|integer',
        'order_status' => 'required|in:ordered,delivered,canceled',
    ]);

    $order = Order::findOrFail($request->order_id);

    // Update status order
    $order->status = $request->order_status;

    // Update tanggal
    if ($request->order_status === 'delivered') {
        $order->delivered_date = now();
        $order->canceled_date = null;
    }

    if ($request->order_status === 'canceled') {
        $order->canceled_date = now();
        $order->delivered_date = null;
    }

    if ($request->order_status === 'ordered') {
        $order->delivered_date = null;
        $order->canceled_date = null;
    }

    // Ambil transaksi
    $transaction = Transaction::where('order_id', $order->id)->first();

    // Update status transaksi otomatis
    if ($transaction) {

        if ($order->status === 'delivered') {
            $transaction->status = 'approved';
        }

        if ($order->status === 'canceled') {
            $transaction->status = 'declined';
        }

        if ($order->status === 'ordered') {
            $transaction->status = 'pending';
        }

        $transaction->save();
    }

    // Simpan order
    $order->save();

    return redirect()->back()->with('status', 'Status pesanan berhasil diperbarui!');
}
}