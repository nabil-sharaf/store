<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\Product;
use App\Models\Admin\Image;
use App\Models\Admin\Category;
use App\Models\Admin\ProductDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

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
            ]);

                ProductDiscount::create([
                    'product_id' => $product->id,
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

            // ربط الفئات بالمنتج
            $this->syncCategories($product, $request->categories);

            // التعامل مع الصور
            $this->handleImages($request, $product);

            // تأكيد الترانزكشن
            DB::commit();

            return response()->json([
                'success' => 'تم إضافة المنتج بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'حدث خطأ أثناء إضافة المنتج.'], 500);
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
                ]);
                ProductDiscount::where('product_id', $product->id)->update([
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

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
}

