<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\Option;
use App\Models\Admin\Prefix;
use App\Models\Admin\Product;
use App\Models\Admin\Image;
use App\Models\Admin\Category;
use App\Models\Admin\ProductDiscount;
use App\Models\Admin\Variant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use OwenIt\Auditing\Models\Audit;

class ProductController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [

            new Middleware('checkRole:superAdmin', only: ['deleteAll']),
            new Middleware('checkRole:superAdmin&supervisor', only: ['destroy', 'bestSellerAll', 'trendAll']),
        ];
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(sku_code) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereHas('variants', function ($variantQuery) use ($searchTerm) {
                        $variantQuery->whereRaw('LOWER(sku_code) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                    });
            });
        }

        $products = $query->with([
            'categories',
            'discount',
            'images',
            'variants.optionValues.option',
            'variants.images'
        ])->paginate(get_pagination_count());

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        // تحميل العلاقات المطلوبة
        $product->load([
            'images',
            'categories',
            'discount',
            'variants.optionValues.option',
        ]);


        // ترتيب المتغيرات حسب السعر أو أي معيار آخر إذا كان مطلوباً
        $product->variants = $product->variants->sortBy('price');

        return view('admin.products.show', compact('product'));
    }


    public function create()
    {
        $categories = Category::all();
        $prefixes = Prefix::all();
        $options = Option::all();
        return view('admin.products.add', compact('categories', 'prefixes', 'options'));
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            // محاولة إنشاء المنتج
            try {
                $product = Product::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'info' => $request->info,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'goomla_price' => $request->goomla_price,
                    'prefix_id' => $request->prefix_id,
                ]);
            } catch (\Exception $e) {
                throw new \Exception('فشل في إنشاء المنتج: ' . $e->getMessage());
            }

            // إضافة الخصم إذا وجد
            if ($request->discount_type && $request->discount > 0) {
                try {
                    ProductDiscount::create([
                        'product_id' => $product->id,
                        'discount' => $request->discount,
                        'discount_type' => $request->discount_type,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                    ]);
                } catch (\Exception $e) {
                    throw new \Exception('فشل في إضافة الخصم: ' . $e->getMessage());
                }
            }

            // إضافة الفاريانت إذا وجد
            if ($request->variants) {
                try {
                    foreach ($request->variants as $index => $variantInput) {
                        try {
                            $productVariant = Variant::create([
                                'product_id' => $product->id,
                                'price' => $variantInput['price'],
                                'goomla_price' => $variantInput['goomla_price'],
                                'quantity' => $variantInput['quantity'],
                            ]);

                            // ربط قيم الخيارات
                            $productVariant->optionValues()->attach($variantInput['values']);

                            // إعادة تحميل البيانات من قاعدة البيانات بعد الحفظ
                            $productVariant->refresh()->loadMissing(['optionValues', 'optionValues.option']);
                            $productVariant->update(['sku_code' => $productVariant->generateSku()]);

                            // معالجة الصور
                            $this->handleVariantImages($variantInput, $productVariant, $product->id);
                        } catch (\Exception $e) {
                            throw new \Exception("فشل في إضافة الفاريانت رقم " . ($index + 1) . ": " . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    throw new \Exception('فشل في معالجة الفاريانت: ' . $e->getMessage());
                }
            }

            // ربط الفئات
            try {
                $this->syncCategories($product, $request->categories);
            } catch (\Exception $e) {
                throw new \Exception('فشل في ربط الفئات: ' . $e->getMessage());
            }

            // معالجة الصور
            try {
                $this->handleImages($request, $product);
            } catch (\Exception $e) {
                throw new \Exception('فشل في معالجة الصور: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => 'تم اضافة المنج بنجاح',
                'message' => 'تم إضافة المنتج بنجاح',
                'product_id' => $product->id
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'type' => 'validation',
                'errors' => $e->validator->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            // تحليل نص الخطأ للحصول على رسالة مناسبة
            $errorMessage = $this->getReadableErrorMessage($e->getMessage());

            return response()->json([
                'success' => false,
                'type' => 'system',
                'message' => $errorMessage,
                'debug_message' => config('app.debug') ? $e->getMessage() : null,
                'errors' => $this->getErrorLocation($e->getMessage())
            ], 422);
        }
    }

    public function showProduct(Product $product)
    {
        $product->load('variants.optionValues.option'); // تحميل كل التفاصيل المرتبطة
        return view('front.product-show', [
            'product' => $product,
            'locale' => app()->getLocale(),
        ]);
    }

    public function edit(Product $product)

    {
        $categories = Category::all();
        $prefixes = Prefix::all();
        $options = Option::all();
        return view('admin.products.edit', compact('product', 'categories', 'prefixes', 'options'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // تحديث المنتج الأساسي
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'info' => $request->info,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'goomla_price' => $request->goomla_price,
                'prefix_id' => $request->prefix_id,
            ]);

            // تحديث الخصم
            $this->handleDiscount($product, $request);

            // معالجة الصور
            $this->handleImages($request, $product);

            // تحديث ربط الفئات
            $this->syncCategories($product, $request->categories);

            // تحديث الفاريانت
            if ($request->has('variants')) {
                // احصل على معرفات الفاريانت الحالية في الطلب
                $currentVariantIds = collect($request->variants)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // احصل على الفاريانت التي سيتم حذفها
                $variantsToDelete = Variant::where('product_id', $product->id)
                    ->whereNotIn('id', $currentVariantIds)
                    ->get();

                // احذف العلاقات والفاريانت
                foreach ($variantsToDelete as $variant) {
                    // حذف العلاقات في جدول option_value_variant
                    $variant->optionValues()->detach();

                    // حذف الصور المرتبطة بالفاريانت إذا كان لديك جدول للصور
                    $this->deleteVariantImages($variant);

                    // حذف الفاريانت نفسه
                    $variant->delete();
                }

                // تحديث أو إنشاء الفاريانت الجديدة
                foreach ($request->variants as $variantInput) {
                    $productVariant = Variant::updateOrCreate(
                        [
                            'id' => $variantInput['id'] ?? null,
                            'product_id' => $product->id
                        ],
                        [
                            'price' => $variantInput['price'],
                            'goomla_price' => $variantInput['goomla_price'],
                            'quantity' => $variantInput['quantity'],
                        ]
                    );

                    $productVariant->optionValues()->sync($variantInput['values']);
                    $productVariant->refresh()->loadMissing(['optionValues', 'optionValues.option']);
                    $productVariant->update(['sku_code' => $productVariant->generateSku()]);
                    $this->handleVariantImages($variantInput, $productVariant, $product->id);
                }
            } else {
                // إذا لم يتم إرسال أي فاريانت، احذف جميع الفاريانت الموجودة
                $variants = Variant::where('product_id', $product->id)->get();
                foreach ($variants as $variant) {
                    $variant->optionValues()->detach();
                    if (method_exists($variant, 'images')) {
                        $variant->images()->delete();
                    }
                    $variant->delete();
                }
            }

            DB::commit();

            session()->flash('success', 'تم تحديث المنتج بنجاح');
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
    protected function handleDiscount($product, $request)
    {
        if ($request->discount_type && $request->discount > 0) {
            ProductDiscount::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'discount' => $request->discount,
                    'discount_type' => $request->discount_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]
            );
        } else {
            ProductDiscount::where('product_id', $product->id)->delete();
        }
    }

    protected function handleException($e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'type' => 'validation',
                'errors' => $e->validator->errors()
            ], 422);
        }

        $errorMessage = $this->getReadableErrorMessage($e->getMessage());
        return response()->json([
            'success' => false,
            'type' => 'system',
            'message' => $errorMessage,
            'debug_message' => config('app.debug') ? $e->getMessage() : null,
            'errors' => $this->getErrorLocation($e->getMessage())
        ], 422);
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            // حذف الصور المرتبطة بالمنتج
            $this->deleteProductImages($product);

            // حذف الفاريانتس مع الصور والروابط المرتبطة
            foreach ($product->variants as $variant) {
                $this->deleteVariantImages($variant); // حذف الصور المرتبطة بالفاريانت
                $variant->optionValues()->detach(); // فك الربط مع قيم الخيارات
                $variant->delete(); // حذف الفاريانت
            }

            // حذف المنتج
            $product->delete();
            DB::commit();
            return redirect()->route('admin.products.index')->with(['success' => 'تم حذف المنتج وجميع التفاصيل الخاصة به بنجاح']);
//            return response()->json(['success' => 'تم حذف المنتج وجميع التفاصيل المرتبطة به بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()], 500);
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
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الصورة حاول مرة أخرى: ' . $e->getMessage()], 500);
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
                'is_trend' => true,
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

    public function getOptionValues(Request $request)
    {
//        $currentLang = app()->getLocale(); // الحصول على اللغة الحالية
        $currentLang = 'ar';
        $optionId = $request->input('option_id');
        $optionValues = Option::where('id', $optionId)->first()->optionValues;

        return response()->json($optionValues->map(function ($value) use ($currentLang) {
            return [
                'id' => $value->id,
                'value' => $value->getTranslation('value', $currentLang), // ترجمة بناءً على اللغة الحالية
            ];
        }));
    }

    //------------------------ private Functions ---------------------

    private function syncCategories(Product $product, array $categories)
    {
        $product->categories()->sync($categories);
    }

    private function handleImages(Request $request, Product $product)
    {
        // معالجة صور المنتج إذا كانت موجودة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $randomName = uniqid() . '.' . $image->getClientOriginalExtension();
                $productFolder = 'products/' . $product->id;
                $path = $image->storeAs($productFolder, $randomName, 'public');
                $product->images()->create([
                    'path' => $path,
                ]);
            }
        }
    }

    private function handleVariantImages($variantInput, $productVariant, $productId)
    {
        // التحقق من وجود صور لهذا الفاريانت
        if (isset($variantInput['images'])) {
            // معالجة الصور للفاريانت
            foreach ($variantInput['images'] as $image) {
                $randomName = uniqid() . '.' . $image->getClientOriginalExtension();
                $variantFolder = 'products/' . $productId . '/' . $productVariant->id;
                $path = $image->storeAs($variantFolder, $randomName, 'public');
                $productVariant->images()->create([
                    'path' => $path,
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

    private function deleteVariantImages(Variant $variant)
    {
        // التحقق من وجود صور مرتبطة بالمنتج المتغير
        if ($variant->images && $variant->images->count() > 0) {
            foreach ($variant->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
    }


    /**
     * تحويل رسالة الخطأ إلى رسالة مفهومة للمستخدم
     */
    private function getReadableErrorMessage($error)
    {
        // التحقق من أنواع محددة من الأخطاء وإرجاع رسائل مناسبة
        if (str_contains($error, 'Duplicate entry')) {
            return 'هذا العنصر موجود مسبقاً';
        }

        if (str_contains($error, 'foreign key constraint fails')) {
            return 'فشل في الربط مع عنصر غير موجود';
        }

        if (str_contains($error, 'Data too long')) {
            return 'البيانات المدخلة أطول من الحد المسموح';
        }

        // إرجاع الرسالة الأصلية إذا كانت تبدأ بنص عربي (رسائلنا المخصصة)
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $error)) {
            return $error;
        }

        // رسالة افتراضية
        return 'حدث خطأ أثناء معالجة طلبك. الرجاء المحاولة مرة أخرى';
    }

    /**
     * تحديد موقع الخطأ في النظام
     */
    private function getErrorLocation($error)
    {
        $locations = [
            'فشل في إنشاء المنتج' => 'product_creation',
            'فشل في إضافة الخصم' => 'discount_creation',
            'فشل في إضافة الفاريانت' => 'variant_creation',
            'فشل في ربط الفئات' => 'categories_sync',
            'فشل في معالجة الصور' => 'images_handling'
        ];

        foreach ($locations as $errorText => $location) {
            if (str_contains($error, $errorText)) {
                return $location;
            }
        }

        return 'unknown';
    }

    public function deleteVariant(Product $product, Variant $variant)
    {
        try {
            DB::beginTransaction();

            // التأكد من أن المتغير ينتمي للمنتج المحدد
            if ($variant->product_id !== $product->id) {
                return redirect()
                    ->back()
                    ->with('error', 'هذا المتغير لا ينتمي للمنتج المحدد');
            }

            $variant->optionValues()->detach();

            $this->deleteVariantImages($variant);

            // حذف المتغير
            $variant->delete();

            DB::commit();

            return redirect()
                ->route('admin.products.show', $product->id)
                ->with('success', 'تم حذف المتغير بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف المتغير. برجاء المحاولة مرة أخرى.');
        }
    }

}

