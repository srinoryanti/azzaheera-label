<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Tests\TestCase;

class ProductColorFlowTest extends TestCase
{
    private function priceOf($p)
    {
        return ($p->sale_price && $p->sale_price < $p->regular_price) ? $p->sale_price : $p->regular_price;
    }

    public function test_color_variant_cart_checkout_order_flow()
    {
        DB::beginTransaction();
        try {
            Cache::flush();
            $user = User::firstOrFail();
            $this->actingAs($user);

            // Data uji mandiri (di-rollback di akhir)
            $catId = Category::firstOrFail()->id;
            $brandId = Brand::firstOrFail()->id;
            $makeProduct = function (string $suffix) use ($catId, $brandId) {
                $p = new Product();
                $p->name = 'Flow Tes ' . $suffix;
                $p->slug = 'flow-tes-' . $suffix;
                $p->short_description = 'short';
                $p->description = 'long';
                $p->regular_price = 95000;
                $p->sale_price = 90000;
                $p->SKU = 'FLOW-' . strtoupper($suffix);
                $p->stock_status = 'instock';
                $p->featured = 0;
                $p->quantity = 10;
                $p->category_id = $catId;
                $p->brand_id = $brandId;
                $p->save();
                return $p;
            };
            $colorProduct = $makeProduct('warna');
            foreach ([['Pink', '#ffc0cb'], ['Putih', '#ffffff'], ['Hitam', '#000000']] as $c) {
                ProductColor::create(['product_id' => $colorProduct->id, 'color' => $c[0], 'color_code' => $c[1]]);
            }
            $colorProduct->load('colors');
            $this->assertEquals(3, $colorProduct->colors->count());

            $plainProduct = $makeProduct('polos');
            $this->assertEquals(0, $plainProduct->fresh()->colors->count());

            $price = $this->priceOf($colorProduct);

            // 1. Tambah varian Putih -> options tersimpan
            $this->post(route('cart.add'), [
                'id' => $colorProduct->id, 'name' => $colorProduct->name,
                'quantity' => 1, 'price' => $price, 'color' => 'Putih',
            ])->assertRedirect();
            $content = Cart::instance('cart')->content();
            $this->assertEquals(1, $content->count());
            $this->assertEquals('Putih', $content->first()->options->color);
            $this->assertNull($content->first()->options->image);

            // 2. Varian Hitam produk sama -> baris terpisah
            $this->post(route('cart.add'), [
                'id' => $colorProduct->id, 'name' => $colorProduct->name,
                'quantity' => 1, 'price' => $price, 'color' => 'Hitam',
            ])->assertRedirect();
            $this->assertEquals(2, Cart::instance('cart')->content()->count());

            // 3. Tanpa warna untuk produk berwarna -> ditolak
            $this->post(route('cart.add'), [
                'id' => $colorProduct->id, 'name' => $colorProduct->name,
                'quantity' => 1, 'price' => $price,
            ])->assertSessionHas('error');
            $this->assertEquals(2, Cart::instance('cart')->content()->count());

            // 4. Warna tidak terdaftar -> ditolak
            $this->post(route('cart.add'), [
                'id' => $colorProduct->id, 'name' => $colorProduct->name,
                'quantity' => 1, 'price' => $price, 'color' => 'Ungu',
            ])->assertSessionHas('error');
            $this->assertEquals(2, Cart::instance('cart')->content()->count());

            // 5. Produk tanpa warna -> tetap bisa, tanpa options
            $this->post(route('cart.add'), [
                'id' => $plainProduct->id, 'name' => $plainProduct->name,
                'quantity' => 1, 'price' => $this->priceOf($plainProduct),
            ])->assertRedirect();
            $plain = Cart::instance('cart')->content()->where('id', $plainProduct->id)->first();
            $this->assertNull($plain->options->color);

            // 6. Halaman cart menampilkan warna dinamis, tanpa hardcode lama
            $cartPage = $this->get(route('cart.index'))->assertOk();
            $cartPage->assertSee('Putih')->assertSee('Hitam');
            $cartPage->assertDontSee('Pink, Size L');

            // 7. Checkout menampilkan warna
            Address::updateOrCreate(
                ['user_id' => $user->id, 'isdefault' => 1],
                ['name' => 'Tester', 'phone' => '0811', 'zip' => '59333', 'state' => 'Jawa Tengah',
                 'city' => 'Kudus', 'address' => 'Jl Tes', 'locality' => 'Gebog',
                 'landmark' => '-', 'country' => 'Indonesia']
            );
            $checkoutPage = $this->get(route('cart.checkout'))->assertOk();
            $checkoutPage->assertSee('Putih')->assertSee('Hitam');

            // 8. Place order -> options JSON tersimpan per item
            $this->post(route('cart.place.an.order'))->assertRedirect(route('checkout.payment'));
            $order = Order::latest('id')->firstOrFail();
            $opts = $order->orderItems->map(fn($i) => $i->options ? json_decode($i->options, true)['color'] ?? null : null);
            $this->assertContains('Putih', $opts);
            $this->assertContains('Hitam', $opts);

            // 9. Halaman payment + detail pesanan menampilkan warna
            $this->get(route('checkout.payment'))->assertOk()->assertSee('Putih');
            $this->get(route('user.order.details', ['order_id' => $order->id]))->assertOk()->assertSee('Putih');
        } finally {
            DB::rollBack();
            Cart::instance('cart')->destroy();
            Cache::flush();
        }

        $this->assertTrue(true);
    }
}
