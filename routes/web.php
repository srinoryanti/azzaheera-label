<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

use App\Http\Middleware\AuthAdmin;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
| Route autentikasi bawaan Laravel
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home.index');

Route::get('/tentang-kami', function () {
    return view('about');
})->name('about');

Route::get('/shop', [ShopController::class, 'index'])
    ->name('shop.index');

Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])
    ->name('shop.product.details');

Route::post('/ulasan', [ShopController::class, 'store_review'])
    ->middleware('auth')
    ->name('reviews.store');

Route::get('/search', [HomeController::class, 'search'])
    ->name('home.search');

Route::get('/hubungi-kami', [HomeController::class, 'contact'])
    ->name('home.contact');

Route::post('/contact/store', [HomeController::class, 'contact_store'])
    ->name('home.contact.store');


/*
|--------------------------------------------------------------------------
| CART ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('keranjang')->group(function () {

    Route::get('/', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/add', [CartController::class, 'add_items'])
        ->name('cart.add');

    Route::put('/increase-quantity/{rowId}', [CartController::class, 'increase_quantity'])
        ->name('cart.quantity.increase');

    Route::put('/decrease-quantity/{rowId}', [CartController::class, 'decrease_quantity'])
        ->name('cart.quantity.decrease');

    Route::delete('/remove/{rowId}', [CartController::class, 'remove_item'])
        ->name('cart.remove');

    Route::delete('/clear', [CartController::class, 'clear_cart'])
        ->name('cart.clear');

    Route::post('/apply-coupon', [CartController::class, 'apply_coupon'])
        ->name('cart.coupon.apply');

    Route::delete('/remove-coupon', [CartController::class, 'remove_coupon'])
        ->name('cart.coupon.remove');
});


/*
|--------------------------------------------------------------------------
| WISHLIST / FAVORIT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('favorit')->group(function () {

    // Halaman daftar produk favorit
    Route::get('/', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    // Menambahkan produk ke favorit
    Route::post('/add', [WishlistController::class, 'add_to_wishlist'])
        ->name('wishlist.add');

    // Menghapus satu produk dari favorit
    Route::delete('/item/remove/{rowId}', [WishlistController::class, 'remove_item'])
        ->name('wishlist.item.remove');

    // Menghapus semua produk favorit
    Route::delete('/clear', [WishlistController::class, 'empty_wishlist'])
        ->name('wishlist.items.clear');

    // Memindahkan produk favorit ke keranjang
    Route::post('/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])
        ->name('wishlist.move.to.cart');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/akun-dashboard', [UserController::class, 'index'])
        ->name('user.index');

    Route::get('/akun-orders', [UserController::class, 'orders'])
        ->name('user.orders');

    Route::get('/akun-order/{order_id}/details', [UserController::class, 'order_details'])
        ->name('user.order.details');

    Route::put('/akun-order/cancel-order', [UserController::class, 'order_cancel'])
        ->name('user.order.cancel');

    Route::get('/akun/password', [UserController::class, 'editPassword'])
        ->name('account.password.edit');

    Route::put('/akun/password', [UserController::class, 'updatePassword'])
        ->name('account.password.update');

    // Halaman checkout
    Route::get('/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');

    // Menyimpan pesanan
    Route::post('/place-an-order', [CartController::class, 'place_an_order'])
        ->name('cart.place.an.order');

    // Halaman pembayaran
    Route::get('/checkout/payment', [CartController::class, 'payment'])
        ->name('checkout.payment');

    // Halaman konfirmasi pesanan
    Route::get('/order-confirmation/{order}', [CartController::class, 'orderConfirmation'])
        ->name('cart.order.confirmation');
});

Route::prefix('admin')
    ->middleware(['auth', AuthAdmin::class])
    ->group(function () {


        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.index');

        Route::get('/brands', [AdminController::class, 'brands'])
            ->name('admin.brands');

        Route::get('/brand/add', [AdminController::class, 'add_brand'])
            ->name('admin.brand.add');

        Route::post('/brand/store', [AdminController::class, 'brand_store'])
            ->name('admin.brand.store');

        Route::get('/brand/edit/{id}', [AdminController::class, 'brand_edit'])
            ->name('admin.brand.edit');

        Route::put('/brand/update', [AdminController::class, 'brand_update'])
            ->name('admin.brand.update');

        Route::delete('/brand/{id}/delete', [AdminController::class, 'brand_delete'])
            ->name('admin.brand.delete');

        Route::get('/categories', [AdminController::class, 'categories'])
            ->name('admin.categories');

        Route::get('/category/add', [AdminController::class, 'add_category'])
            ->name('admin.category.add');

        Route::post('/category/store', [AdminController::class, 'category_store'])
            ->name('admin.category.store');

        Route::get('/category/edit/{id}', [AdminController::class, 'category_edit'])
            ->name('admin.category.edit');

        Route::put('/category/update', [AdminController::class, 'category_update'])
            ->name('admin.category.update');

        Route::delete('/category/{id}/delete', [AdminController::class, 'category_delete'])
            ->name('admin.category.delete');


        Route::get('/products', [AdminController::class, 'products'])
            ->name('admin.products');

        Route::get('/product/add', [AdminController::class, 'add_product'])
            ->name('admin.product.add');

        Route::post('/product/store', [AdminController::class, 'product_store'])
            ->name('admin.product.store');

        Route::get('/product/edit/{id}', [AdminController::class, 'product_edit'])
            ->name('admin.product.edit');

        Route::put('/product/update', [AdminController::class, 'product_update'])
            ->name('admin.product.update');

        Route::delete('/product/{id}/gallery', [AdminController::class, 'product_gallery_delete'])
            ->name('admin.product.gallery.delete');

        Route::delete('/product/{id}/delete', [AdminController::class, 'product_delete'])
            ->name('admin.product.delete');



        Route::get('/coupons', [AdminController::class, 'coupons'])
            ->name('admin.coupons');

        Route::get('/coupon/add', [AdminController::class, 'coupon_add'])
            ->name('admin.coupon.add');

        Route::post('/coupon/store', [AdminController::class, 'coupon_store'])
            ->name('admin.coupon.store');

        Route::get('/coupon/{id}/edit', [AdminController::class, 'coupon_edit'])
            ->name('admin.coupon.edit');

        Route::put('/coupon/update', [AdminController::class, 'coupon_update'])
            ->name('admin.coupon.update');

        Route::delete('/coupon/{id}/delete', [AdminController::class, 'coupon_delete'])
            ->name('admin.coupon.delete');

        Route::get('/orders', [AdminController::class, 'orders'])
            ->name('admin.orders');

        Route::get('/order/{order_id}/details', [AdminController::class, 'order_details'])
            ->name('admin.order.details');

        Route::put('/order/update-status', [AdminController::class, 'update_order_status'])
            ->name('admin.order.status.update');

            Route::delete('/order/{id}/delete', [AdminController::class, 'order_delete'])
             ->name('admin.order.delete');


        Route::get('/slides', [AdminController::class, 'slides'])
            ->name('admin.slides');

        Route::get('/slide/add', [AdminController::class, 'add_slide'])
            ->name('admin.slide.add');

        Route::post('/slide/store', [AdminController::class, 'slide_store'])
            ->name('admin.slide.store');

        Route::get('/slide/{id}/edit', [AdminController::class, 'slide_edit'])
            ->name('admin.slide.edit');

        Route::put('/slide/update', [AdminController::class, 'slide_update'])
            ->name('admin.slide.update');

        Route::delete('/slide/{id}/delete', [AdminController::class, 'slide_delete'])
            ->name('admin.slide.delete');



        Route::get('/contact', [AdminController::class, 'contacts'])
            ->name('admin.contacts');

        Route::delete('/contact/{id}/delete', [AdminController::class, 'contact_delete'])
            ->name('admin.contact.delete');

        Route::get('/reviews', [AdminController::class, 'reviews'])
            ->name('admin.reviews');

        Route::get('/review/add', [AdminController::class, 'add_review'])
            ->name('admin.review.add');

        Route::post('/review/store', [AdminController::class, 'review_store'])
            ->name('admin.review.store');

        Route::get('/review/edit/{id}', [AdminController::class, 'review_edit'])
            ->name('admin.review.edit');

        Route::put('/review/update', [AdminController::class, 'review_update'])
            ->name('admin.review.update');

        Route::delete('/review/{id}/delete', [AdminController::class, 'review_delete'])
            ->name('admin.review.delete');

        Route::get('/search', [AdminController::class, 'search'])
            ->name('admin.search');


        Route::get('/akun/password', [AdminController::class, 'editPassword'])
            ->name('account.admin.password.edit');

        Route::put('/akun/password', [AdminController::class, 'updatePassword'])
            ->name('account.admin.password.update');
    });
