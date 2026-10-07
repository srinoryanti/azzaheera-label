<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Tests\TestCase;

class StockDeductionTest extends TestCase
{
    public function test_place_order_decrements_and_cancel_restores()
    {
        DB::beginTransaction();
        try {
            Cache::flush();
            Cart::instance('cart')->destroy();

            $user = User::where('utype', 'USR')->first()
                ?? User::where('utype', '!=', 'ADMIN')->first()
                ?? User::firstOrFail();
            $admin = User::where('utype', 'ADMIN')->firstOrFail();
            $catId = Category::firstOrFail()->id;
            $brandId = Brand::firstOrFail()->id;

            $suffix = uniqid();
            $product = new Product();
            $product->name = 'Stock Tes ' . $suffix;
            $product->slug = 'stock-tes-' . strtolower($suffix);
            $product->short_description = 'short';
            $product->description = 'long';
            $product->regular_price = 50000;
            $product->sale_price = null;
            $product->SKU = 'STOCK-' . strtoupper(substr($suffix, -6));
            $product->stock_status = 'instock';
            $product->featured = 0;
            $product->quantity = 10;
            $product->category_id = $catId;
            $product->brand_id = $brandId;
            $product->save();

            Address::updateOrCreate(
                ['user_id' => $user->id, 'isdefault' => 1],
                ['name' => 'Tester', 'phone' => '0811', 'zip' => '59333', 'state' => 'Jawa Tengah',
                 'city' => 'Kudus', 'address' => 'Jl Tes', 'locality' => 'Gebog',
                 'landmark' => '-', 'country' => 'Indonesia']
            );

            $this->actingAs($user);

            // 1. Place order qty 2 -> stok berkurang 10 -> 8
            $this->post(route('cart.add'), [
                'id' => $product->id, 'name' => $product->name,
                'quantity' => 2, 'price' => 50000,
            ])->assertRedirect();
            $this->post(route('cart.place.an.order'))->assertRedirect(route('checkout.payment'));

            $order = Order::latest('id')->firstOrFail();
            $this->assertTrue((bool) $order->stock_deducted);
            $this->assertEquals(8, $product->fresh()->quantity);
            $this->assertEquals('instock', $product->fresh()->stock_status);

            // 2. User cancel -> stok kembali 10
            $this->put(route('user.order.cancel'), ['order_id' => $order->id])
                ->assertRedirect();
            $this->assertEquals(10, $product->fresh()->quantity);
            $this->assertFalse((bool) $order->fresh()->stock_deducted);
            $this->assertEquals('canceled', $order->fresh()->status);

            // 3. Order baru qty 3 -> stok 10 -> 7, lalu admin cancel -> kembali 10
            Cart::instance('cart')->destroy();
            $this->post(route('cart.add'), [
                'id' => $product->id, 'name' => $product->name,
                'quantity' => 3, 'price' => 50000,
            ])->assertRedirect();
            $this->post(route('cart.place.an.order'))->assertRedirect(route('checkout.payment'));
            $order2 = Order::latest('id')->firstOrFail();
            $this->assertEquals(7, $product->fresh()->quantity);

            $this->actingAs($admin);
            $this->put(route('admin.order.status.update'), [
                'order_id' => $order2->id, 'order_status' => 'canceled',
            ])->assertRedirect();
            $this->assertEquals(10, $product->fresh()->quantity);
            $this->assertFalse((bool) $order2->fresh()->stock_deducted);

            // 4. Admin aktifkan lagi (canceled -> processing) -> stok dipotong lagi 10 -> 7
            $this->put(route('admin.order.status.update'), [
                'order_id' => $order2->id, 'order_status' => 'processing',
            ])->assertRedirect();
            $this->assertEquals(7, $product->fresh()->quantity);
            $this->assertTrue((bool) $order2->fresh()->stock_deducted);

            // 5. Stok tidak cukup -> place order ditolak, stok tidak berubah
            $this->actingAs($user);
            Cart::instance('cart')->destroy();
            $product->quantity = 1;
            $product->save();
            $this->post(route('cart.add'), [
                'id' => $product->id, 'name' => $product->name,
                'quantity' => 5, 'price' => 50000,
            ])->assertRedirect();
            $this->post(route('cart.place.an.order'))->assertRedirect(route('cart.index'));
            $this->assertEquals(1, $product->fresh()->quantity);
        } finally {
            DB::rollBack();
            Cart::instance('cart')->destroy();
            Cache::flush();
        }

        $this->assertTrue(true);
    }
}
