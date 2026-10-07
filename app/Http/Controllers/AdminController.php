<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Slide;
use App\Models\Contact;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class AdminController extends Controller
{
    public function index(){
        // Cache dashboard 5 menit — query berat tidak perlu dijalankan tiap klik menu
        $cacheKey = 'admin.dashboard.v2';
        $data = Cache::remember($cacheKey, 300, function () {
            // Fix: limit di DB, eager load count, tanpa get()->take()
            $orders = Order::withCount('orderItems')
                ->select('id','name','phone','subtotal','total','status','created_at','delivered_date')
                ->latest()->limit(10)->get();

            $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
            sum(if(status IN ('ordered','pending_payment','waiting_verification','processing','shipped'),total,0)) As TotalOrderedAmount,
            sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
            sum(if(status='canceled',total,0)) As TotalCanceledAmount,
            Count(*) As Total,
            sum(if(status IN ('ordered','pending_payment','waiting_verification','processing','shipped'),1,0)) As TotalOrdered,
            sum(if(status='delivered',1,0)) As TotalDelivered,
            sum(if(status='canceled',1,0)) As TotalCanceled
            From orders
            ");

            $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
            IFNULL(D.TotalAmount,0) As TotalAmount,
            IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount, 0) As TotalCanceledAmount FROM month_names M
            LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
            MONTH(created_at) As MonthNo,
            sum(total) As TotalAmount,
            sum(if (status='ordered', total,0)) As TotalOrderedAmount,
            sum(if (status='delivered', total,0)) As TotalDeliveredAmount,
            sum(if (status='canceled', total,0)) As TotalCanceledAmount
            From orders GROUP BY YEAR (created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
            Order By MONTH(created_at)) D On D.MonthNo=M.id");

            $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
            $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
            $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
            $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

            $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
            $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
            $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
            $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

            return compact('orders','dashboardDatas','AmountM','OrderedAmountM','DeliveredAmountM','CanceledAmountM','TotalAmount','TotalOrderedAmount','TotalDeliveredAmount','TotalCanceledAmount');
        });

        return view('admin.index', $data);
    }

    public function brands(){

        $brands = Brand::orderBy('id', 'ASC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand(){

        return view('admin.brand-add');
    }

    public function brand_store(Request $request){

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            Storage::disk('public')->putFileAs('brands', $image, $file_name);

            $brand->image = $file_name;
        }

        $brand->save();
        Cache::forget('shop.brands');

        return redirect()->route('admin.brands')->with('status', 'Brand has been added succesfully!');
    }

    public function brand_edit($id){

        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact("brand"));
    }
    public function brand_update(Request $request){

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);

        if($request->hasFile('image')){

            if ($brand->image && Storage::disk('public')->exists('brands/'.$brand->image)) {
                Storage::disk('public')->delete('brands/'.$brand->image);
            }

            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;

            // Simpan gambar baru
            Storage::disk('public')->putFileAs('brands', $image, $file_name);

            $brand->image = $file_name;
        }

        $brand->save();
        Cache::forget('shop.brands');

        return redirect()->route('admin.brands')->with('status', 'Brand has been updated succesfully!');
    }
    public function brand_delete($id){

        $brand = Brand::findOrFail($id);

        if ($brand->image && Storage::disk('public')->exists('brands/'.$brand->image)) {
            Storage::disk('public')->delete('brands/'.$brand->image);
        }

        $brand->delete();
        Cache::forget('shop.brands');
        return redirect()->route('admin.brands')->with('status', 'Brand has been deleted successfully!');
    }

    public function reviews(){

        $reviews = Review::with('product:id,name')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.reviews', compact('reviews'));
    }

    public function add_review(){

        $products = Product::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('admin.review-add', compact('products'));
    }

    public function review_store(Request $request){

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        Review::create($request->only(['product_id', 'name', 'email', 'rating', 'review']));

        return redirect()->route('admin.reviews')->with('status', 'Ulasan berhasil ditambahkan!');
    }

    public function review_edit($id){

        $review = Review::findOrFail($id);
        $products = Product::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('admin.review-edit', compact('review', 'products'));
    }

    public function review_update(Request $request){

        $request->validate([
            'id' => 'required|exists:reviews,id',
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $review = Review::findOrFail($request->id);
        $review->update($request->only(['product_id', 'name', 'email', 'rating', 'review']));

        return redirect()->route('admin.reviews')->with('status', 'Ulasan berhasil diperbarui!');
    }

    public function review_delete($id){

        Review::findOrFail($id)->delete();
        return redirect()->route('admin.reviews')->with('status', 'Ulasan berhasil dihapus!');
    }

    public function categories(){
        $categories = Category::orderBy('id', 'asc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category(){
        return view('admin.category-add');
    }

    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            Storage::disk('public')->putFileAs('categories', $image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        Cache::forget('shop.categories'); Cache::forget('home.categories');

        return redirect()->route('admin.categories')->with('status', 'Category has been added successfully!');
    }

    public function category_edit($id){
        $category = Category::findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }
    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }

            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            Storage::disk('public')->putFileAs('categories', $image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        Cache::forget('shop.categories'); Cache::forget('home.categories');

        return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully!');
    }

    public function category_delete($id){
        $category = Category::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
            Storage::disk('public')->delete('categories/' . $category->image);
        }

        $category->delete();
        Cache::forget('shop.categories'); Cache::forget('home.categories');

        return redirect()->route('admin.categories')->with('status', 'Category has been deleted successfully!');
    }
    public function products()
    {
        $products = Product::with(['category:id,name', 'brand:id,name'])
            ->select('id','name','slug','regular_price','sale_price','SKU','category_id','brand_id','featured','stock_status','quantity','image','created_at')
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function add_product()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    protected function uniqueProductSlug(string $name, $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function product_store(Request $request)
    {
        $request->validate([
            "name" => 'required|string|max:255',
            "slug" => 'nullable|string',
            "short_description" => 'required',
            "description" => 'required',
            "regular_price" => 'required|numeric|min:0',
            "sale_price" => 'nullable|numeric|min:0|lt:regular_price',
            "SKU" => 'required|string|max:100|unique:products,SKU',
            "stock_status" => 'nullable',
            "featured" => 'required|boolean',
            "quantity" => 'required|integer|min:0',
            "image" => 'required|image|mimes:png,jpg,jpeg|max:2048',
            "images" => 'nullable|array|max:10',
            "images.*" => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            "category_id" => 'required|integer|exists:categories,id',
            "brand_id" => 'required|integer|exists:brands,id',
            "sizes" => 'nullable|string|max:255',
            "colors" => 'nullable|array|max:20',
            "colors.*.name" => 'nullable|string|max:50',
            "colors.*.image" => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $this->uniqueProductSlug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->filled('sale_price') ? $request->sale_price : null;
        $product->SKU = $request->SKU;
        // Status stok otomatis mengikuti quantity (bukan input manual).
        $product->stock_status = ((int) $request->quantity > 0) ? 'instock' : 'outofstock';
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->sizes = $this->normalizeSizes($request->input('sizes'));

        $current_timestamp = Carbon::now()->timestamp;

        // Upload main image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            // Simpan gambar di storage/app/public/products
            Storage::disk('public')->putFileAs('products', $image, $imageName);
            // Jika ada thumbnail function, bisa dipanggil di sini
            // $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        // Upload gallery images
        $gallery_arr = [];
        if ($request->hasFile('images')) {
            $allowedFileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            $counter = 1;
            foreach ($files as $file) {
                $ext = $file->getClientOriginalExtension();
                if (in_array(strtolower($ext), $allowedFileExtension)) {
                    $galleryFileName = $current_timestamp . "-" . $counter . "." . $ext;
                    Storage::disk('public')->putFileAs('products', $file, $galleryFileName);
                    // Jika buat thumbnail, panggil di sini juga
                    // $this->GenerateProductThumbnailsImage($file, $galleryFileName);
                    $gallery_arr[] = $galleryFileName;
                    $counter++;
                }
            }
        }
        $product->images = implode(',', $gallery_arr);

        $product->save();

        // Simpan varian warna (opsional — baris kosong diabaikan)
        $this->storeProductColors($product, $request->input('colors', []), $request->file('colors', []));

        Cache::forget('home.sproducts'); Cache::forget('home.fproducts'); Cache::forget('shop.brands'); Cache::forget('shop.categories');

        return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
    }

    public function product_edit($id)
    {
        $product = Product::with('colors')->findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('categories', 'brands', 'product'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            "name" => 'required|string|max:255',
            "slug" => 'nullable|string',
            "short_description" => 'required',
            "description" => 'required',
            "regular_price" => 'required|numeric|min:0',
           "sale_price" => 'nullable|numeric|min:0|lt:regular_price',
            "SKU" => 'required|string|max:100|unique:products,SKU,'.$request->id,
            "stock_status" => 'nullable',
            "featured" => 'required|boolean',
            "quantity" => 'required|integer|min:0',
            "image" => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            "images" => 'nullable|array|max:10',
            "images.*" => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            "category_id" => 'required|integer|exists:categories,id',
            "brand_id" => 'required|integer|exists:brands,id',
            "sizes" => 'nullable|string|max:255',
            "colors" => 'nullable|array|max:20',
            "colors.*.id" => 'nullable|integer|exists:product_colors,id',
            "colors.*.name" => 'nullable|string|max:50',
            "colors.*.image" => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            "delete_colors" => 'nullable|array',
            "delete_colors.*" => 'integer|exists:product_colors,id',
        ]);

        $product = Product::findOrFail($request->id);
        $product->name = $request->name;
        $product->slug = $this->uniqueProductSlug($request->name, $product->id);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->filled('sale_price') ? $request->sale_price : null;
        $product->SKU = $request->SKU;
        // Status stok otomatis mengikuti quantity (bukan input manual).
        $product->stock_status = ((int) $request->quantity > 0) ? 'instock' : 'outofstock';
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->sizes = $this->normalizeSizes($request->input('sizes'));

        $current_timestamp = Carbon::now()->timestamp;

        // Update main image jika ada file baru
if ($request->hasFile('image')) {
    // Hapus image lama jika ada
    if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
        Storage::disk('public')->delete('products/' . $product->image);
    }
    // Simpan image baru
    $image = $request->file('image');
    $imageName = $current_timestamp . '.' . $image->extension();
    Storage::disk('public')->putFileAs('products', $image, $imageName);
    $product->image = $imageName;
}

// Tambah gallery images baru ke koleksi lama (append, tidak menghapus yang lama)
if ($request->hasFile('images')) {
    $allowedFileExtension = ['jpg', 'png', 'jpeg'];
    $files = $request->file('images');
    $gallery_arr = $product->images ? explode(',', $product->images) : [];
    foreach ($files as $file) {
        if (!$file->isValid()) {
            continue;
        }
        $ext = strtolower($file->getClientOriginalExtension());
        if (in_array($ext, $allowedFileExtension)) {
            $galleryFileName = $current_timestamp . "-" . Str::random(6) . "." . $ext;
            Storage::disk('public')->putFileAs('products', $file, $galleryFileName);
            $gallery_arr[] = $galleryFileName;
        }
    }
    $product->images = implode(',', $gallery_arr);
}

        $product->save();

        // Hapus warna yang dicentang hapus
        if ($request->filled('delete_colors')) {
            $toDelete = ProductColor::where('product_id', $product->id)
                ->whereIn('id', $request->input('delete_colors', []))->get();
            foreach ($toDelete as $color) {
                if ($color->image && Storage::disk('public')->exists('products/' . $color->image)) {
                    Storage::disk('public')->delete('products/' . $color->image);
                }
                $color->delete();
            }
        }

        // Tambah / perbarui warna
        $this->storeProductColors($product, $request->input('colors', []), $request->file('colors', []), true);

        Cache::forget('home.sproducts'); Cache::forget('home.fproducts');
        Cache::forget('product.slug.'.$product->slug); Cache::forget('product.related.'.$product->id);

        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }

    /**
     * Simpan / perbarui varian warna produk.
     * Baris tanpa nama warna diabaikan. Baris ber-id diperbarui,
     * baris tanpa id dibuat baru. Gambar lama dipertahankan bila
     * tidak ada file baru (mode update).
     */
    protected function storeProductColors(Product $product, array $rows, array $files, bool $isUpdate = false)
    {
        $current_timestamp = Carbon::now()->timestamp;

        foreach ($rows as $index => $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $color = null;
            if ($isUpdate && !empty($row['id'])) {
                $color = ProductColor::where('product_id', $product->id)
                    ->where('id', $row['id'])->first();
            }
            if (!$color) {
                $color = new ProductColor();
                $color->product_id = $product->id;
            }

            $color->color = $name;
            // Kode hex tidak lagi dipakai — varian diwakili foto, bukan lingkaran warna.
            $color->color_code = null;

            $file = $files[$index]['image'] ?? null;
            if ($file && $file->isValid()) {
                if ($color->exists && $color->image && Storage::disk('public')->exists('products/' . $color->image)) {
                    Storage::disk('public')->delete('products/' . $color->image);
                }
                $colorFileName = $current_timestamp . '-color-' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());
                Storage::disk('public')->putFileAs('products', $file, $colorFileName);
                $color->image = $colorFileName;
            }

            $color->save();
        }
    }

    /**
     * Normalisasi daftar ukuran dari input admin ("S, M, L" -> "S,M,L").
     * Mengembalikan null bila kosong agar produk dianggap tanpa varian ukuran.
     */
    protected function normalizeSizes($sizes)
    {
        if (!is_string($sizes)) {
            return null;
        }

        $parts = array_filter(array_map('trim', explode(',', $sizes)), fn ($v) => $v !== '');

        if (!$parts) {
            return null;
        }

        return implode(',', array_slice(array_unique($parts), 0, 20));
    }

    public function product_gallery_delete(Request $request, $id)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $product = Product::findOrFail($id);
        $gallery = $product->images ? explode(',', $product->images) : [];
        $filename = basename($request->filename);

        if (in_array($filename, $gallery)) {
            if (Storage::disk('public')->exists('products/' . $filename)) {
                Storage::disk('public')->delete('products/' . $filename);
            }
            $gallery = array_values(array_diff($gallery, [$filename]));
            $product->images = implode(',', $gallery);
            $product->save();
            Cache::forget('product.slug.' . $product->slug);
            Cache::forget('product.related.' . $product->id);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'images' => $product->images]);
        }

        return redirect()->back()->with('status', 'Foto galeri berhasil dihapus!');
    }

    public function product_delete($id)
    {
        $product = Product::findOrFail($id);

        // Delete main image
        if ($product->image && Storage::disk('public')->exists('products/' . $product->image)) {
    Storage::disk('public')->delete('products/' . $product->image);
}

        // Delete gallery images
        if ($product->images) {
    foreach (explode(',', $product->images) as $galleryImage) {
        if (Storage::disk('public')->exists('products/' . $galleryImage)) {
            Storage::disk('public')->delete('products/' . $galleryImage);
        }
    }
}

        // Delete color variant images (rows ikut terhapus via cascade)
        foreach ($product->colors as $color) {
            if ($color->image && Storage::disk('public')->exists('products/' . $color->image)) {
                Storage::disk('public')->delete('products/' . $color->image);
            }
        }

        $product->delete();
        Cache::forget('home.sproducts'); Cache::forget('home.fproducts');

        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }


    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }

    public function coupon_add()
    {
        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->cart_value = $request->cart_value;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function coupon_update(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,'.$request->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        $coupon = Coupon::findOrFail($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->cart_value = $request->cart_value;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');

    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }

    public function orders()
    {
        $orders = Order::withCount('orderItems')
            ->select('id','user_id','name','phone','subtotal','total','status','created_at','delivered_date')
            ->orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::withCount('orderItems')->findOrFail($order_id);
        $orderItems = OrderItem::with(['product' => function($q){ $q->select('id','name','slug','image','SKU','brand_id','category_id'); }, 'product.brand:id,name', 'product.category:id,name'])
            ->where('order_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first();
        return view('admin.order-details', compact('order','orderItems', 'transaction'));
    }

public function update_order_status(Request $request)
{
    $request->validate([
        'order_id' => 'required|integer|exists:orders,id',
        'order_status' => 'required|in:waiting_verification,processing,shipped,delivered,canceled',
    ]);

    $order = Order::findOrFail($request->order_id);

    $oldStatus = $order->status;

    $order->status = $request->order_status;


    if ($request->order_status === 'delivered') {

        $order->delivered_date = Carbon::now();
        $order->canceled_date = null;

    } elseif ($request->order_status === 'canceled') {

        $order->canceled_date = Carbon::now();
        $order->delivered_date = null;

    } else {

        $order->delivered_date = null;
        $order->canceled_date = null;
    }

    $order->save();
    Cache::forget('admin.dashboard.v2');

    // Sinkron stok mengikuti perubahan status:
    // - dibatalkan → kembalikan stok; - dibatalkan lalu diaktifkan lagi → kurangi stok lagi.
    try {
        $order->load('orderItems');
        if ($request->order_status === 'canceled' && $oldStatus !== 'canceled') {
            $order->restoreStock();
        } elseif ($oldStatus === 'canceled' && $request->order_status !== 'canceled') {
            $order->deductStock();
        }
    } catch (\RuntimeException $e) {
        // Gagal sinkron stok (mis. stok habis saat order diaktifkan lagi):
        // kembalikan status semula agar data konsisten.
        $order->status = $oldStatus;
        $order->save();
        return back()->with('error', $e->getMessage());
    }

    $transaction = Transaction::where('order_id', $order->id)->first();

    if ($transaction) {

        switch ($request->order_status) {

            case 'waiting_verification':
                $transaction->status = 'pending';
                break;

            case 'processing':
                $transaction->status = 'approved';
                break;

            case 'shipped':
                $transaction->status = 'approved';
                break;

            case 'delivered':
                $transaction->status = 'approved';
                break;

            case 'canceled':
                $transaction->status = 'declined';
                break;

            default:
                $transaction->status = 'pending';
                break;
        }

        $transaction->save();
    }

    return back()->with(
        'status',
        'Status pesanan berhasil diperbarui!'
    );
}
    public function order_delete($id)
    {
        $order = Order::findOrFail($id);
        // Kembalikan stok dulu sebelum order + item-nya dihapus (cascade)
        $order->load('orderItems');
        $order->restoreStock();
        $order->delete();
        Cache::forget('admin.dashboard.v2');
        return redirect()->route('admin.orders')->with('status','Order has been deleted successfully!');
    }
    public function slides()
    {
        $slides = Slide::orderBy('id', 'DESC')->paginate(12);
        return view('admin.slides', compact('slides'));
    }

    public function add_slide()
    {
        return view('admin.slide-add');
    }

    public function slide_store(Request $request)
{
    $request->validate([
        'tagline' => 'required',
        'title' => 'required',
        'subtitle' => 'required',
        'status' => 'required',
        'image' => 'required|mimes:png,jpg,jpeg|max:2048',
    ]);

    $slide = new Slide();
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->status = $request->status;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $fileName = Carbon::now()->timestamp . '.' . $image->extension();

        // Simpan gambar ke storage/app/public/slides
        Storage::disk('public')->putFileAs('slides', $image, $fileName);

        $slide->image = $fileName;
    }

    $slide->save();
    Cache::forget('home.slides');

    return redirect()->route('admin.slides')->with("status", "Slide has been added successfully!");
}

    public function slide_edit($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function slide_update(Request $request)
{
    $request->validate([
        'tagline' => 'required',
        'title' => 'required',
        'subtitle' => 'required',
        'status' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
    ]);

    $slide = Slide::findOrFail($request->id);
    $slide->tagline = $request->tagline;
    $slide->title = $request->title;
    $slide->subtitle = $request->subtitle;
    $slide->link = $request->link;
    $slide->status = $request->status;

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($slide->image && Storage::disk('public')->exists('slides/' . $slide->image)) {
            Storage::disk('public')->delete('slides/' . $slide->image);
        }

        $image = $request->file('image');
        $fileName = Carbon::now()->timestamp . '.' . $image->extension();

        // Simpan gambar baru
        Storage::disk('public')->putFileAs('slides', $image, $fileName);

        // Optional: Thumbnail
        // $this->GenerateSlideThumbnailsImage($image, $fileName);

        $slide->image = $fileName;
    }

    $slide->save();
    Cache::forget('home.slides');

    return redirect()->route('admin.slides')->with("status", "Slide has been updated successfully!");
}


    public function slide_delete($id)
{
    $slide = Slide::findOrFail($id);

    // Hapus gambar dari storage jika ada
    if ($slide->image && Storage::disk('public')->exists('slides/' . $slide->image)) {
        Storage::disk('public')->delete('slides/' . $slide->image);
    }

    $slide->delete();
    Cache::forget('home.slides');

    return redirect()->route('admin.slides')->with("status", "Slide has been deleted successfully!");
}


    public function contacts()
    {
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.contacts', compact('contacts'));
    }

    public function contact_delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with('status', 'Message has been deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));
        if (strlen($query) < 2) return response()->json([]);
        $results = Product::select('id','name','image','slug')
            ->where('name', 'LIKE', "%{$query}%")->limit(8)->get();
        return response()->json($results);
    }

   public function GenerateProductThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/products');
    $destinationPathThumbnail = public_path('uploads/products/thumbnails');

    // Pastikan folder ada
    if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);
    if (!file_exists($destinationPathThumbnail)) mkdir($destinationPathThumbnail, 0777, true);

    $img = Image::make($image->path());

    // Simpan ukuran utama
    $img->fit(540, 689, function ($constraint) {
        $constraint->upsize();
    })->save($destinationPath.'/'.$imageName);

    // Simpan thumbnail
    $img->fit(104, 104, function ($constraint) {
        $constraint->upsize();
    })->save($destinationPathThumbnail.'/'.$imageName);
}

public function GenerateBrandThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/brands');
    if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

    $img = Image::make($image->path());

    $img->fit(124, 124, function ($constraint) {
        $constraint->upsize();
    })->save($destinationPath.'/'.$imageName);
}

public function GenerateCategoryThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/categories');
    if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

    $img = Image::make($image->path());

    $img->fit(124, 124, function ($constraint) {
        $constraint->upsize();
    })->save($destinationPath.'/'.$imageName);
}

public function GenerateSlideThumbnailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/slides');
    if (!file_exists($destinationPath)) mkdir($destinationPath, 0777, true);

    $img = Image::make($image->path());

    $img->fit(400, 690, function ($constraint) {
        $constraint->upsize();
    })->save($destinationPath.'/'.$imageName);
}
    public function editPassword()
    {
        return view('admin.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:15',
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('status', 'Akun berhasil diperbarui.');
    }
}
