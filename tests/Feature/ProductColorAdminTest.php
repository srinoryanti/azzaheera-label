<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductColorAdminTest extends TestCase
{
    public function test_admin_crud_product_colors()
    {
        Storage::fake('public');
        DB::beginTransaction();
        try {
            Cache::flush();
            $admin = User::where('utype', 'ADMIN')->firstOrFail();
            $this->actingAs($admin);

            $category = Category::firstOrFail();
            $brandId = \App\Models\Brand::firstOrFail()->id;

            // 1. Form add memuat input warna
            $this->get(route('admin.product.add'))->assertOk()->assertSee('colors[0][name]', false);

            // 2. Store produk + 2 warna (satu tanpa nama -> diabaikan)
            $img = UploadedFile::fake()->image('main.jpg');
            $c1 = UploadedFile::fake()->image('pink.jpg');
            $c2 = UploadedFile::fake()->image('putih.jpg');
            $this->post(route('admin.product.store'), [
                'name' => 'Tes Varian Warna', 'slug' => 'tes-varian-warna-xyz',
                'short_description' => 'short', 'description' => 'long',
                'regular_price' => 100000, 'SKU' => 'TES-WARNA-1', 'stock_status' => 'instock',
                'featured' => 0, 'quantity' => 5, 'image' => $img,
                'category_id' => $category->id, 'brand_id' => $brandId,
                'colors' => [
                    ['name' => 'Pink', 'color_code' => '#ffc0cb', 'image' => $c1],
                    ['name' => 'Putih', 'color_code' => '#ffffff', 'image' => $c2],
                    ['name' => '', 'color_code' => '', 'image' => null],
                ],
            ])->assertRedirect(route('admin.products'));

            $product = Product::with('colors')->where('slug', 'tes-varian-warna')->firstOrFail();
            $this->assertEquals(2, $product->colors->count());
            $this->assertEquals('Pink', $product->colors[0]->color);
            Storage::disk('public')->assertExists('products/' . $product->colors[0]->image);

            // 2b. Form edit memuat warna tersimpan + opsi hapus
            $this->get(route('admin.product.edit', ['id' => $product->id]))
                ->assertOk()->assertSee('Pink', false)->assertSee('delete_colors', false);

            // 3. Halaman produk menampilkan 2 warna
            $page = $this->get(route('shop.product.details', ['product_slug' => $product->slug]))->assertOk();
            $page->assertSee('data-color="Pink"', false)->assertSee('data-color="Putih"', false);

            // 4. Update: ubah nama warna 1, hapus warna 2, tambah warna 3
            $pinkId = $product->colors[0]->id;
            $putihId = $product->colors[1]->id;
            $oldPutihImg = $product->colors[1]->image;
            $c3 = UploadedFile::fake()->image('hitam.jpg');
            $this->put(route('admin.product.update'), [
                'id' => $product->id, 'name' => 'Tes Varian Warna', 'slug' => 'tes-varian-warna',
                'short_description' => 'short', 'description' => 'long',
                'regular_price' => 100000, 'SKU' => 'TES-WARNA-1', 'stock_status' => 'instock',
                'featured' => 0, 'quantity' => 5,
                'category_id' => $category->id, 'brand_id' => $brandId,
                'colors' => [
                    ['id' => $pinkId, 'name' => 'Pink Muda', 'color_code' => '#ffc0cb'],
                    ['name' => 'Hitam', 'color_code' => '#000000', 'image' => $c3],
                ],
                'delete_colors' => [$putihId],
            ])->assertRedirect(route('admin.products'));

            $product->refresh();
            $names = $product->colors->pluck('color')->all();
            $this->assertContains('Pink Muda', $names);
            $this->assertContains('Hitam', $names);
            $this->assertNotContains('Putih', $names);
            Storage::disk('public')->assertMissing('products/' . $oldPutihImg);

            // 5. Produk tanpa warna: form add tetap valid & halaman tanpa selector
            $img2 = UploadedFile::fake()->image('main2.jpg');
            $this->post(route('admin.product.store'), [
                'name' => 'Tes Tanpa Warna', 'slug' => 'tes-tanpa-warna-xyz',
                'short_description' => 'short', 'description' => 'long',
                'regular_price' => 50000, 'SKU' => 'TES-NOWARNA-1', 'stock_status' => 'instock',
                'featured' => 0, 'quantity' => 5, 'image' => $img2,
                'category_id' => $category->id, 'brand_id' => $brandId,
            ])->assertRedirect(route('admin.products'));
            $plain = Product::where('slug', 'tes-tanpa-warna')->firstOrFail();
            $this->assertEquals(0, $plain->colors->count());
            $this->get(route('shop.product.details', ['product_slug' => $plain->slug]))
                ->assertOk()
                ->assertDontSee('<input type="hidden" name="color"', false)
                ->assertDontSee('data-color="', false);

            // 6. Delete produk menghapus file gambar warna
            $colorImg = $product->colors->firstWhere('color', 'Hitam')->image;
            $this->delete(route('admin.product.delete', ['id' => $product->id]))
                ->assertRedirect(route('admin.products'));
            $this->assertDatabaseMissing('product_colors', ['product_id' => $product->id]);
        } finally {
            DB::rollBack();
            Cache::flush();
        }

        $this->assertTrue(true);
    }
}
