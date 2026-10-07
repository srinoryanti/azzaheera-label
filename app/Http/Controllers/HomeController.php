<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache 10 menit untuk mengurangi query berulang tiap pindah menu
        $slides = Cache::remember('home.slides', 600, function () {
            return Slide::where('status', 1)
                ->select('id', 'title', 'subtitle', 'tagline', 'image', 'link', 'status')
                ->latest('id')->limit(8)->get();
        });

        $categories = Cache::remember('home.categories', 600, function () {
            return Category::select('id', 'name', 'slug', 'image')
                ->orderBy('name')->get();
        });

        // Slider atas: khusus produk promo (sale_price < regular_price), diskon terbesar dulu
        $sproducts = Cache::remember('home.sproducts', 300, function () {
            return Product::select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image', 'created_at')
                ->whereNotNull('sale_price')
                ->whereColumn('sale_price', '<', 'regular_price')
                ->orderByRaw('(regular_price - sale_price) DESC')
                ->limit(8)->get();
        });

        $fproducts = Cache::remember('home.fproducts', 300, function () {
            return Product::select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image', 'created_at')
                ->latest()->limit(16)->get();
        });

        return view('index', compact('slides', 'categories', 'sproducts', 'fproducts'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9+\-\s]+$/',
            'comment' => 'required|string|max:2000'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));

        // Live-search AJAX dari header (layout app.blade.php) butuh JSON ringan, bukan halaman HTML
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            if (strlen($query) < 2) return response()->json([]);
            $results = Product::select('id', 'name', 'image', 'slug', 'regular_price', 'sale_price')
                ->where('name', 'LIKE', "%{$query}%")->limit(8)->get();
            return response()->json($results);
        }

        // Batasi panjang query & hindari LIKE '%...%' tanpa limit
        if (strlen($query) < 2) {
            $products = Product::select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image')
                ->latest()->paginate(12);
            return view('search', compact('products', 'query'));
        }

        $products = Product::select('id', 'name', 'slug', 'regular_price', 'sale_price', 'image', 'created_at')
            ->where('name', 'LIKE', "%{$query}%")
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search', compact('products', 'query'));
    }
}