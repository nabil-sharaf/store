<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(20);
        return view('front.products-offers',compact('products'));
    }
    public function showProduct(Product $product)
    {
        $product->load('categories','images');
        return view('front.product-show',compact('product'));
    }


    public function search(Request $request)
    {
        // احفظ القيم في الجلسة
        session([
            'search' => $request->input('search'), // حفظ قيمة البحث في الجلسة
        ]);

        $query = $request->input('search');

        // البحث في المنتجات
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        // عرض النتائج في عرض مخصص
        return view('front.search-results', compact('products', 'query'));
    }
    public function filterProducts(Request $request, $category_id = null)
    {

        // احفظ القيم في الجلسة
        session([
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'sort_by' => $request->input('sort_by'),
            'search' => $request->input('search'), // حفظ قيمة البحث في الجلسة

        ]);

        $query = Product::query();


        // فلترة المنتجات بناءً على القسم الحالي
        if ($category_id) {
            $query->whereHas('categories', function ($q) use ($category_id) {
                $q->where('category_id', $category_id);
            });
        }
        // تحقق من وجود قيمة في حقل البحث
        if (session()->has('search') &&  session('search') !== '') {
            $query->where('name', 'like', '%' . session('search') . '%')
                  ->orWhere('description', 'LIKE', "%".session('search')."%")
            ;
        }


        // استرجاع المنتجات
        $products = $query->get();

        // تطبيق الفلترة بعد استرجاع المنتجات بناءً على السعر المخصوم
        if ($request->filled('min_price')) {
            $products = $products->filter(function ($product) use ($request) {
                return $product->discounted_price >= $request->min_price;
            });
        }

        if ($request->filled('max_price')) {
            $products = $products->filter(function ($product) use ($request) {
                return $product->discounted_price <= $request->max_price;
            });
        }

        // تطبيق الترتيب بناءً على اختيار المستخدم
        switch ($request->sort_by) {
            // الفرز هنا سيتم من خلال الاكسيسورز وليس قيمة مباشرة في الداتابيز
            case 'price-asc':
                $products = $products->sortBy('discounted_price'); // فرز حسب السعر المخصوم تصاعديًا
                break;
            case 'price-desc':
                $products = $products->sortByDesc('discounted_price'); // فرز حسب السعر المخصوم تنازليًا
                break;
            case 'latest':
                $products = $products->sortByDesc('created_at'); // فرز حسب أحدث المنتجات
                break;
            default:
                $products = $products->sortBy('id'); // الفرز الافتراضي
                break;
        }


        // الاحتفاظ بالنتائج واستخدام redirect()->back()
        return redirect()->back()->with('filteredProducts', $products);
    }
}
