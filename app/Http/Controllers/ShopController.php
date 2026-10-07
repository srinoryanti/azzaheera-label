<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Pagination size default 12
        $size = $request->query('size', 12);

        // Sorting
        $order = (int) $request->query('order', -1);
        $order_column = 'id';
        $order_order = 'DESC';

        switch ($order) {
            case 1:
                $order_column = 'created_at';
                $order_order = 'DESC';
                break;
            case 2:
                $order_column = 'created_at';
                $order_order = 'ASC';
                break;
            case 3:
                $order_column = 'sale_price';
                $order_order = 'ASC';
                break;
            case 4:
                $order_column = 'sale_price';
                $order_order = 'DESC';
                break;
            case 5:
                $order_column = 'name';
                $order_order = 'ASC';
                break;
            case 6:
                $order_column = 'name';
                $order_order = 'DESC';
                break;
        }

        // Filter
        $filter_brands = $request->query('brands', '');
        $filter_categories = $request->query('categories', '');
        $min_price = max(0, (float) $request->query('min', 0));
        $max_price = (float) $request->query('max', 100000000);
        if ($max_price < $min_price) { [$min_price, $max_price] = [$max_price, $min_price]; }

        // Brand & category untuk filter — cache 10 menit (jarang berubah)
        $brands = Cache::remember('shop.brands', 600, function () {
            return Brand::select('id', 'name', 'slug')->orderBy('name', 'ASC')->get();
        });
        $categories = Cache::remember('shop.categories', 600, function () {
            return Category::select('id', 'name', 'slug')->orderBy('name', 'ASC')->get();
        });

        // Validasi size agar tidak bisa request 10000 rows
        $size = max(1, min((int) $size, 48));

        // Query products — select kolom penting + simplePaginate lebih cepat untuk dataset besar
        $products = Product::query()
            ->select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image', 'category_id', 'brand_id', 'created_at')
            ->when($filter_brands !== '', function ($query) use ($filter_brands) {
                $ids = array_filter(explode(',', $filter_brands), fn($v) => is_numeric($v));
                if (!empty($ids)) $query->whereIn('brand_id', $ids);
            })
            ->when($filter_categories !== '', function ($query) use ($filter_categories) {
                $ids = array_filter(explode(',', $filter_categories), fn($v) => is_numeric($v));
                if (!empty($ids)) $query->whereIn('category_id', $ids);
            })
            ->where(function ($query) use ($min_price, $max_price) {
                $query->whereBetween('regular_price', [$min_price, $max_price])
                      ->orWhereBetween('sale_price', [$min_price, $max_price]);
            })
            ->orderBy($order_column, $order_order)
            ->paginate($size)
            ->withQueryString();

        return view('shop', compact(
            'products',
            'size',
            'order',
            'brands',
            'categories',
            'filter_brands',
            'filter_categories',
            'min_price',
            'max_price'
        ));
    }

    public function product_details($product_slug)
    {
        $product = Cache::remember('product.slug.' . $product_slug, 600, function () use ($product_slug) {
            return Product::with(['category:id,name,slug', 'brand:id,name,slug', 'colors', 'reviews'])
                ->where('slug', $product_slug)->firstOrFail();
        });

        // Produk terkait — cache singkat
        $related = Cache::remember('product.related.' . $product->id, 300, function () use ($product) {
            $base = fn () => Product::select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image');

            // 1) satu kategori
            $items = $base()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->inRandomOrder()->limit(4)->get();

            // 2) fallback: satu brand bila kategori belum cukup
            if ($items->count() < 4 && $product->brand_id) {
                $items = $items->concat(
                    $base()
                        ->where('brand_id', $product->brand_id)
                        ->where('id', '!=', $product->id)
                        ->whereNotIn('id', $items->pluck('id'))
                        ->inRandomOrder()->limit(4 - $items->count())->get()
                );
            }

            // 3) fallback: produk lain yang masih stok
            if ($items->count() < 4) {
                $items = $items->concat(
                    $base()
                        ->where('id', '!=', $product->id)
                        ->whereNotIn('id', $items->pluck('id'))
                        ->where('quantity', '>', 0)
                        ->inRandomOrder()->limit(4 - $items->count())->get()
                );
            }

            return $items->values();
        });

        // Cek kelayakan ulasan: hanya pembeli yang order-nya sudah delivered
        $canReview = false;
        $userReview = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $userId = \Illuminate\Support\Facades\Auth::id();
            $canReview = \App\Models\Order::where('user_id', $userId)
                ->where('status', 'delivered')
                ->whereHas('orderItems', fn ($q) => $q->where('product_id', $product->id))
                ->exists();
            $userReview = \App\Models\Review::where('product_id', $product->id)
                ->where('user_id', $userId)->latest()->first();
        }

        return view('details', compact('product', 'related', 'canReview', 'userReview'));
    }

    /**
     * Ulasan pembeli — hanya untuk produk yang sudah dibeli & diterima (status delivered).
     * Satu ulasan per pembeli per produk (diperbarui bila sudah pernah menilai).
     */
    public function store_review(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $purchased = \App\Models\Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereHas('orderItems', fn ($q) => $q->where('product_id', $request->product_id))
            ->exists();

        if (!$purchased) {
            return back()->with('error', 'Anda hanya bisa menilai produk yang sudah dibeli dan diterima.');
        }

        \App\Models\Review::updateOrCreate(
            ['product_id' => $request->product_id, 'user_id' => $user->id],
            ['name' => $user->name, 'email' => $user->email,
             'rating' => $request->rating, 'review' => $request->review]
        );

        \Illuminate\Support\Facades\Cache::forget('product.slug.' . \App\Models\Product::find($request->product_id)?->slug);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}