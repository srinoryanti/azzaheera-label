<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Cart::instance('wishlist')->content();

        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request)
    {
        $request->validate([
            'id'       => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $product = \App\Models\Product::select('id', 'name', 'regular_price', 'sale_price')->find($request->id);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }
        $price = ($product->sale_price && $product->sale_price < $product->regular_price)
            ? $product->sale_price : $product->regular_price;

        // Cegah duplikat: jika sudah ada, jangan tambah baris baru
        $exists = Cart::instance('wishlist')->content()->where('id', $product->id)->first();
        if ($exists) {
            return redirect()->back()->with('success', 'Produk sudah ada di favorit.');
        }

        Cart::instance('wishlist')
            ->add($product->id, $product->name, (int) ($request->quantity ?? 1), (float) $price)
            ->associate('App\Models\Product');

        return redirect()
            ->back()
            ->with('success', 'Produk berhasil ditambahkan ke favorit.');
    }

    public function remove_item($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        if (!$item) {
            return redirect()->back()->with('error', 'Produk favorit tidak ditemukan.');
        }
        Cart::instance('wishlist')->remove($rowId);

        return redirect()
            ->back()
            ->with('success', 'Produk berhasil dihapus dari favorit.');
    }

    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();

        return redirect()
            ->back()
            ->with('success', 'Semua produk favorit berhasil dibersihkan.');
    }

    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        if (!$item) {
            return redirect()->back()->with('error', 'Produk favorit tidak ditemukan.');
        }

        // Harga selalu dari DB + variant options ikut dipertahankan
        $product = \App\Models\Product::with('colors')->find($item->id);
        if (!$product) {
            Cart::instance('wishlist')->remove($rowId);
            return redirect()->back()->with('error', 'Produk sudah tidak tersedia.');
        }
        $price = ($product->sale_price && $product->sale_price < $product->regular_price)
            ? $product->sale_price : $product->regular_price;

        // Tambahkan produk ke keranjang (options bawaan wishlist ikut pindah)
        Cart::instance('cart')
            ->add($product->id, $product->name, $item->qty, (float) $price, $item->options->toArray())
            ->associate('App\Models\Product');

        // Hapus produk dari favorit
        Cart::instance('wishlist')->remove($rowId);

        // Arahkan langsung ke halaman keranjang
        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk berhasil dimasukkan ke keranjang.');
    }
}