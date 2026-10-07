<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /* ===================== CART ===================== */

    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_items(Request $request)
    {
        $request->validate([
            'id'       => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'color'    => 'nullable|string|max:50',
            'size'     => 'nullable|string|max:50',
        ]);

        $options = [];

        // Ambil produk dari DB — harga & nama TIDAK dipercaya dari request (anti price-tampering)
        $product = Product::with('colors')->find($request->id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }
        $price = ($product->sale_price && $product->sale_price < $product->regular_price)
            ? $product->sale_price : $product->regular_price;
        if ($product->quantity < $request->quantity) {
            return back()->with('error', "Stok produk \"{$product->name}\" tidak mencukupi (sisa {$product->quantity}).");
        }
        if ($product && $product->colors->count() > 0) {
            $selected = $product->colors->firstWhere('color', $request->color)
                ?? $product->colors->firstWhere('color', trim((string) $request->color));

            if (!$selected) {
                return back()->with('error', 'Silakan pilih warna produk terlebih dahulu.');
            }

            $options = [
                'color'      => $selected->color,
                'color_code' => $selected->color_code,
                'image'      => $selected->image,
            ];
        }

        // Jika produk punya varian ukuran, ukuran wajib dipilih dari daftar admin
        if ($product && $product->sizes) {
            $availableSizes = array_filter(array_map('trim', explode(',', $product->sizes)), fn ($v) => $v !== '');
            $selectedSize = trim((string) $request->size);

            if ($selectedSize === '' || !in_array($selectedSize, $availableSizes, true)) {
                return back()->with('error', 'Silakan pilih ukuran produk terlebih dahulu.');
            }

            $options['size'] = $selectedSize;
        }

        Cart::instance('cart')->add(
            $product->id,
            $product->name,
            $request->quantity,
            (float) $price,
            $options
        )->associate('App\Models\Product');

        return back()->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }

    public function increase_quantity($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        if (!$item) {
            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }
        // Batasi dengan stok produk agar tidak over-sell
        $product = Product::select('id', 'quantity', 'name')->find($item->id);
        $maxQty = $product ? max(1, (int) $product->quantity) : 100;
        $maxQty = min($maxQty, 100);
        if ($item->qty + 1 > $maxQty) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia (' . $maxQty . ').');
        }
        Cart::instance('cart')->update($rowId, $item->qty + 1);
        return back();
    }

    public function decrease_quantity($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        if (!$item) {
            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }
        Cart::instance('cart')->update($rowId, max(1, $item->qty - 1));
        return back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return back();
    }

    public function clear_cart()
    {
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        return back();
    }

    /* ===================== CHECKOUT STEP 1 ===================== */

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Cart::instance('cart')->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $address = Address::where('user_id', Auth::id())
            ->where('isdefault', 1)
            ->first();

        return view('checkout', compact('address'));
    }

    /* ===================== PLACE ORDER ===================== */

    public function place_an_order(Request $request)
    {
        $user_id = Auth::id();

        $address = Address::where('user_id', $user_id)
            ->where('isdefault', 1)
            ->first();

        if (!$address) {
            $request->validate([
                'name'      => 'required|string|max:255',
                'phone'     => 'required|string|max:15',
                'zip'       => 'required|string|max:20',
                'state'     => 'required|string|max:255',
                'city'      => 'required|string|max:255',
                'address'   => 'required|string|max:500',
                'locality'  => 'required|string|max:255',
                'landmark' => 'nullable|string|max:255',
            ]);

            $address = Address::create([
                'user_id'   => $user_id,
                'name'      => $request->name,
                'phone'     => $request->phone,
                'zip'       => $request->zip,
                'state'     => $request->state,
                'city'      => $request->city,
                'address'   => $request->address,
                'locality'  => $request->locality,
                'landmark'  => $request->landmark,
                'country'   => 'Indonesia',
                'isdefault' => 1,
            ]);
        }

        if (Cart::instance('cart')->count() == 0) {
            return redirect()->route('cart.index')->with('error','Keranjang kosong, tidak bisa membuat pesanan.');
        }

        /* === CEK KETERSEDIAAN STOK (sebelum order dibuat) === */
        $cartItems = Cart::instance('cart')->content();
        $productsById = Product::whereIn('id', $cartItems->pluck('id')->all())->get()->keyBy('id');
        foreach ($cartItems as $item) {
            $product = $productsById->get($item->id);
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Ada produk di keranjang yang sudah tidak tersedia.');
            }
            if ($product->quantity < $item->qty) {
                return redirect()->route('cart.index')->with('error', "Stok produk \"{$product->name}\" tidak mencukupi (sisa {$product->quantity}).");
            }
        }

        /* === HITUNG TOTAL === */
        $this->setAmountforCheckout();
        $checkout = Session::get('checkout');

        try {
            $order = DB::transaction(function () use ($user_id, $address, $checkout) {
                /* === SIMPAN ORDER === */
                $order = Order::create([
                    'user_id'   => $user_id,
                    'subtotal'  => $checkout['subtotal'],
                    'discount'  => $checkout['discount'],
                    'tax'       => $checkout['tax'],
                    'total'     => $checkout['total'],
                    'name'      => $address->name,
                    'phone'     => $address->phone,
                    'locality'  => $address->locality,
                    'address'   => $address->address,
                    'city'      => $address->city,
                    'state'     => $address->state,
                    'country'   => $address->country,
                    'landmark'  => $address->landmark,
                    'zip'       => $address->zip,
                    'status'    => 'pending_payment',
                ]);

                /* === SIMPAN ORDER ITEM === */
                foreach (Cart::instance('cart')->content() as $item) {
                    $itemOptions = null;
                    $color = $item->options->color ?? null;
                    $colorImage = $item->options->image ?? null;
                    $size = $item->options->size ?? null;
                    if ($color || $colorImage || $size) {
                        $itemOptions = json_encode([
                            'color' => $color,
                            'image' => $colorImage,
                            'size'  => $size,
                        ]);
                    }

                    OrderItem::create([
                        'order_id'  => $order->id,
                        'product_id'=> $item->id,
                        'price'     => $item->price,
                        'quantity'  => $item->qty,
                        'options'   => $itemOptions,
                    ]);
                }

                /* === TRANSAKSI TRANSFER === */
                Transaction::create([
                    'user_id'  => $user_id,
                    'order_id' => $order->id,
                    'mode'     => 'transfer',
                    'status'   => 'pending',
                ]);

                /* === KURANGI STOK PRODUK (langsung saat dibeli) === */
                $order->load('orderItems');
                $order->deductStock();

                return $order;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);

        return redirect()->route('checkout.payment');
    }

    /* ===================== STEP 2: PAYMENT INFO ===================== */

    public function paymentConfirmation()
{
    if (!Session::has('order_id')) {
        return redirect()->route('cart.index');
    }

    $order = Order::findOrFail(Session::get('order_id'));

    $bank = [
        'nama'      => 'BCA',
        'rekening'  => '1234567890',
        'pemilik'   => 'CV Azzahera Label',
    ];

    return view('payment-confirmation', compact('order', 'bank'));
}
    public function payment()
{
    if (!Session::has('order_id')) {
        return redirect()->route('cart.index');
    }

    $order = Order::with('orderItems.product:id,name,image')
        ->select('id','user_id','total','subtotal','discount','status','name','phone','address','city','created_at')
        ->findOrFail(Session::get('order_id'));

    if ($order->user_id !== Auth::id()) {
        Session::forget('order_id');
        return redirect()->route('cart.index');
    }

    $bank = [
        'nama'      => 'BCA',
        'rekening'  => '1234567890',
        'pemilik'   => 'CV Azzahera Label',
        // Ganti dengan path QRIS Anda jika sudah ada
        'qris'       => 'images/qris.png',
    ];

    return view('payment', compact('order', 'bank'));
}

 /* ===================== HELPER ===================== */

public function setAmountforCheckout()
{
    $subtotal = 0;

    foreach (Cart::instance('cart')->content() as $item) {
        $subtotal += $item->price * $item->qty;
    }

    Session::put('checkout', [
        'subtotal' => $subtotal,
        'discount' => 0,
        'tax'      => 0,
        'total'    => $subtotal,
    ]);
}
public function orderConfirmation(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }
    return view('order-confirmation', compact('order'));
}
}
