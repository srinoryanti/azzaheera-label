<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::withCount('orderItems')
            ->select('id','name','phone','subtotal','total','status','created_at','delivered_date')
            ->where('user_id', Auth::user()->id)->latest()->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function order_details($order_id){
        $order = Order::withCount('orderItems')->where('user_id', Auth::user()->id)->where('id', $order_id)->first();
        if($order)
        {
            $orderItems = OrderItem::with(['product' => function($q){ $q->select('id','name','slug','image','SKU','brand_id','category_id'); }, 'product.brand:id,name', 'product.category:id,name'])
                ->where('order_id', $order->id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order->id)->first();
            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        }else{
            abort(404);
        }
    }

    public function order_cancel(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->first();
        if (!$order || $order->status === "canceled") {
            return back()->with('status', "Order has been cancelled successfully!");
        }
        if (!in_array($order->status, ["pending_payment", "waiting_verification"])) {
            return back()->with('status', "Pesanan tidak dapat dibatalkan pada status ini.");
        }
        // Kembalikan stok yang dulu dikurangi saat order dibuat
        $order->load('orderItems');
        $order->restoreStock();
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        Transaction::where('order_id', $order->id)->update(['status' => 'declined']);
        return back()->with('status', "Order has been cancelled successfully!");
    }

    public function editPassword()
    {
        return view('auth.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:15',
            // Password hanya diverifikasi jika user ingin menggantinya
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update profil
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        // Jika ingin mengubah password
        if ($request->filled('new_password')) {
            // Cek kecocokan kata sandi saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('status', 'Akun berhasil diperbarui.');
    }
}
