<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Popup;
use App\Models\Admin\Product;
use App\Models\Admin\Setting;
use App\Models\Admin\SiteImage;
use App\Models\Admin\Variant;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        // تحميل المنتجات مع الفاريانت والأوبشنز
        $newProducts = Product::with([
            'variants' => function($query) {
                $query->with([
                    'images',
                    'optionValues' => function($query) {
                        $query->with(['option' => function($query) {
                            $query->where('name->en', 'color'); // نفترض أن اسم الأوبشن "color"
                        }]);
                    }
                ]);
            },
            'images'
        ])
            ->orderBy('created_at', 'desc')
            ->take(get_last_added_count())
            ->get();

        // نفس التحميل للمنتجات الأخرى
        $bestProducts = Product::with([
            'variants.images',
            'variants.optionValues.option' => function($query) {
                $query->where('name->en', 'color');
            },
            'images'
        ])
            ->where('is_best_seller', 1)
            ->orderBy('updated_at', 'desc')
            ->take(get_best_seller_count())
            ->get();

        $trendingProducts = Product::with([
            'variants.images',
            'variants.optionValues.option' => function($query) {
                $query->where('name->en', 'color');
            },
            'images'
        ])
            ->where('is_trend', 1)
            ->orderBy('updated_at', 'desc')
            ->take(get_trending_count())
            ->get();

        $siteImages = SiteImage::first();
        $popup = Popup::first();

        return view('front.index', compact(
            'categories',
            'bestProducts',
            'newProducts',
            'siteImages',
            'trendingProducts',
            'popup'
        ));
    }
    public function productDetails($id)
    {
        $product = Product::with([
            'variants' => function($query) {
                $query->where('quantity', '>', 0)
                    ->orderBy('price', 'asc');
            },
            'variants.images',
            'variants.optionValues.option',
            'images',
            'categories'
        ])->findOrFail($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // تجهيز الأوبشنز المتاحة فقط إذا كان هناك variants
        $availableOptions = collect();
        $variants = collect();

        if ($product->variants->isNotEmpty()) {
            // نجمع الأوبشنز المتاحة من الفاريانت
            $product->variants->each(function($variant) use (&$availableOptions) {
                $variant->optionValues->each(function($optionValue) use (&$availableOptions) {
                    $option = $optionValue->option;
                    if (!$availableOptions->has($option->id)) {
                        $availableOptions[$option->id] = [
                            'id' => $option->id,
                            'name' => $option->name,
                            'values' => collect()
                        ];
                    }

                    if (!$availableOptions[$option->id]['values']->contains('id', $optionValue->id)) {
                        $availableOptions[$option->id]['values']->push([
                            'id' => $optionValue->id,
                            'value' => $optionValue->value
                        ]);
                    }
                });
            });

            // تجميع الفاريانت مع قيم الأوبشنز والصور
            $variants = $product->variants->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'sku_code' => $variant->sku_code,
                    'price' => $variant->variant_price,
                    'discounted_price' => $variant->getDiscountedPriceAttribute(),
                    'quantity' => $variant->quantity,
                    'images' => $variant->images->map(function($image) {
                        return [
                            'id' => $image->id,
                            'path' => $image->path
                        ];
                    }),
                    'option_values' => $variant->optionValues->map(function($value) {
                        return [
                            'option_id' => $value->option->id,
                            'id' => $value->id,
                            'value' => $value->value
                        ];
                    })
                ];
            });
        }

        // تحديد الصور التي سيتم عرضها
        $displayImages = $product->variants->isNotEmpty() && $product->variants->first()->images->isNotEmpty()
            ? $product->variants->first()->images
            : $product->images;

        $response = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            // إضافة السعر الأساسي للمنتج والسعر بعد الخصم
            'price' => $product->price,
            'discounted_price' => $product->getDiscountedPriceAttribute(),
            'images' => $displayImages->map(function($image) {
                return [
                    'id' => $image->id,
                    'path' => $image->path
                ];
            }),
            'categories' => $product->categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            })
        ];

        // إضافة الأوبشنز والفاريانت فقط إذا كانت موجودة
        if ($product->variants->count() > 1) {
            $response['available_options'] = $availableOptions->values()->toArray();
            $response['variants'] = $variants;
        }
        if($product->hasVariants()){
            $response['warning_message']=true;
        }else{
            $response['warning_message']=false;
        }

        return response()->json($response);
    }
    public function contact()
    {
        $setting = Setting::all();
        return view('front.contact',compact('setting'));
    }

    public function aboutUs()
    {
        $siteImages = SiteImage::first();
        return view('front.about',compact('siteImages'));
    }

    public function getVariantDetails($variantId)
    {
        try {
            // البحث عن الـ variant في قاعدة البيانات
            $variant = Variant::findOrFail($variantId);

            $varianImage = $variant->images?->first()?->path ?? $variant->product->images?->first()->path;
            // تجهيز البيانات المطلوبة
            $responseData = [
                'success' => true,
                'image_path' => $varianImage,
                'price_html' => view('components.product-price', [
                    'productPrice' => $variant->variant_price,
                    'discountedPrice' => $variant->discounted_price
                ])->render()
            ];

            return response()->json($responseData);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' =>  $variant->images?->first()?->path
            ], 422);
        }
    }
}
