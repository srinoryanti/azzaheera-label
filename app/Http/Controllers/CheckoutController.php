<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * STEP 3: Tampilkan halaman konfirmasi pembayaran (WhatsApp)
     */
    public function step3()
    {
        // Ambil order_id dari session
        if (!Session::has('order_id')) {
            return redirect()->route('cart.index')
                ->with('error', 'Order tidak ditemukan, silakan checkout terlebih dahulu.');
        }

        $order = Order::findOrFail(Session::get('order_id'));

        // pastikan user hanya bisa akses order miliknya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment-step3', compact('order'));
    }

    /**
     * Konfirmasi pembayaran (update status)
     */
    public function confirmPayment(Request $request)
    {
        if (!Session::has('order_id')) {
            return redirect()->route('cart.index')
                ->with('error', 'Order tidak ditemukan.');
        }

        $order = Order::findOrFail(Session::get('order_id'));

        // pastikan user hanya bisa konfirmasi order miliknya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // update status order menjadi menunggu konfirmasi admin
        $order->update([
            'status' => 'waiting_confirmation',
        ]);

        // redirect ke STEP 3
        return redirect()->route('checkout.step3')
            ->with('success', 'Silakan kirim bukti transfer via WhatsApp');
    }
}
