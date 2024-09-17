<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\Product;
use App\Models\Admin\Image;
use App\Models\Admin\Category;
use App\Models\Admin\ProductDiscount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [

            new Middleware('checkRole:superAdmin', only: ['destroy','deleteAll']),
        ];
    }
    public function index()
    {
        $products = Product::with('categories', 'discount')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.add', compact('categories'));
    }

    public function store(ProductRequest $request)
    {

        try {
            // بدء الترانزكشن
            DB::beginTransaction();
            // إنشاء المنتج
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'goomla_price' => $request->goomla_price,
            ]);

            if($request->discount_type && $request->discount > 0){

                ProductDiscount::create([
                    'product_id' => $product->id,
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);
            }


            // ربط الفئات بالمنتج
            $this->syncCategories($product, $request->categories);

            // التعامل مع الصور
            $this->handleImages($request, $product);

            // تأكيد الترانزكشن
            DB::commit();

            return response()->json([
                'success' => 'تم إضافة المنتج بنجاح',
            ]);

        } catch (ValidationException $e) {
            // إرجاع الأخطاء كـ JSON
            return response()->json([
                'errors' => $e->validator->errors()
            ], 422); // كود 422 يعني أخطاء في الفاليديشان

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' =>'حدث خطأ أثناء اضافة المنتج تأكد من ملئ جميع الحقول والتواريخ بصورة صحيحة'], 500);
        }
    }


    public function show($id)
    {
        $product = Product::with(['images', 'categories'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {


        try {
            return DB::transaction(function () use ($request, $product) {
                $product->update([
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'price'=> $request->price,
                    'quantity'=>$request->quantity,
                    'goomla_price' => $request->goomla_price,

                ]);

                if($request->discount_type && $request->discount > 0){

                    ProductDiscount::updateOrCreate(
                    // الشرط لتحديد إذا كان الريكورد موجودًا
                        ['product_id' => $product->id],

                        // البيانات التي سيتم تحديثها أو إنشاؤها
                        [
                            'discount' => $request->discount,
                            'discount_type' => $request->discount_type,
                            'start_date' => $request->start_date,
                            'end_date' => $request->end_date,

                ]);
                }
                else{
                    // البحث عن الريكورد الذي يحتوي على المنتج المحدد
                    $productDiscount = ProductDiscount::where('product_id', $product->id)->first();

// التحقق من وجود الريكورد ثم حذفه
                    if ($productDiscount) {
                        $productDiscount->delete();
                    }
                }


                $this->syncCategories($product, $request->categories);
                $this->handleImages($request, $product);

                return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');


            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $this->deleteProductImages($product);
        $product->delete();

        return response()->json(['success' => 'تم حذف المنتج بنجاح']);
    }


    private function syncCategories(Product $product, array $categories)
    {
        $product->categories()->sync($categories);
    }

    private function handleImages(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $randomName = uniqid() . '.' . $image->getClientOriginalExtension();
                // تخزين الصورة في المسار المحدد
                $path = $image->storeAs('products', $randomName, 'public');
                Image::create([
                    'product_id' => $product->id,
                    'name' => $randomName,
                    'path' => $path
                ]);
            }
        }
    }

    private function deleteProductImages(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    public function removeImage($id)
    {
        try {
            $image = Image::findOrFail($id);

            // تحقق من وجود الملف قبل محاولة حذفه
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            } else {
                Log::warning("File not found: {$image->path}");
            }

            $image->delete();

            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {

        $this->deleteProductImages($product);
        $product->delete();
        }

        return response()->json(['success' => 'تم حذف العناصر المختارة بنجاح']);
    }
    public function trendAll(Request $request)
    {
        $ids = $request->ids;
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {


       $product->update([
           'is_trend'=>true,
       ]);

        }

        return response()->json(['success' => 'تم جعل المنتجات المختارة ترند بنجاح']);
    }
    public function bestSellerAll(Request $request)
    {
        $ids = $request->ids;
        $products = Product::whereIn('id', $ids)->get();
        $allUpdated = true; // متغير للتحقق من نجاح التحديث لجميع المنتجات

        foreach ($products as $product) {

            $updated = $product->update([
                'is_best_seller' => true,
            ]);


            if (!$updated) {
                $allUpdated = false; // إذا فشل التحديث لأي منتج
                break; // إيقاف الحلقة إذا كان هناك فشل
            }
        }

        if ($allUpdated) {
            return response()->json(['success' => 'تم جعل المنتجات المحددة كالأفضل']);
        } else {
            return response()->json(['error' => 'حدث خطأ أثناء تحديث بعض المنتجات'], 500);
        }
    }


}

