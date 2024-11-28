<?php

namespace App\Http\Controllers\Front;

use App\Models\Admin\Order;
use App\Models\Admin\Variant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{   // إضافة المنتج إلى السلة
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id') ?? null;
        $quantity = $request->input('quantity');

        // التأكد من وجود المنتج
        $product = Product::findOrFail($productId);

        $freeProducts = 0;

        // Get variant with its option values and options
        $variant = null;
        if ($variantId) {
            $variant = $product->variants()
                ->with(['optionValues.option', 'images']) // تأكد من جلب الصور مع الفاريانت
                ->find($variantId);
        }

        $editingOrderId = session()->get('editing_order_id');
        $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular';
        $offer = $product->getOfferDetails($customerOfferType);

        if ($offer && $quantity >= $offer->offer_quantity) {
            $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
        }

        $price = $variant ? $variant->discounted_price : $product->discounted_price;
        $image = $variant && $variant->images->isNotEmpty()
            ? $variant->images->first()->path // صورة الفاريانت
            : $product->images->first()?->path; // صورة المنتج الأساسي

        // Prepare variant details in a more structured way
        $variantDetails = null;
        if ($variant) {
            $variantDetails = $variant->optionValues->mapWithKeys(function ($optionValue) {
                return [
                    $optionValue->option->name => [
                        'value' => $optionValue->value,
                        'option_name' => $optionValue->option->name,
                    ]
                ];
            });
        }

        Cart::add([
            'id' => $variant ? ($product->id . '-' . $variant->id) : $product->id,
            'name' => $product->name,
            'price' => $price,
            'quantity' => $quantity,
            'attributes' => [
                'url' => route('product.show', $product->id),
                'image' => $image,
                'variant_details' => $variant ? $variantDetails : null, // New structured variant details
                'free_quantity' => $freeProducts,
                'editing_order_id' => $editingOrderId,
                'variant_id' => $variant ? $variantId : null,
                'product_id' => $productId,
            ]
        ]);

        if ($editingOrderId) {
            return response()->json(['message' => 'تمت الإضافة في تعديل الأوردر']);
        } else {
            return response()->json(['message' => 'تم إضافة المنتج لسلة الشراء']);
        }
    }

    // تحديث كمية المنتج في السلة صفحة الشوبينج كارت
    public function updateCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $quantity = max(1, $request->input('quantity', 1));
            $variantId = $request->input('variant_id');

            // تحديد معرف العنصر في السلة
            $cartItemId = $variantId ? $productId . '-' . $variantId : $productId;

            // التأكد من وجود المنتج
            $product = Product::findOrFail($productId);

            // حساب العروض والكميات المجانية
            $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular';
            $offer = $product->getOfferDetails($customerOfferType);

            $freeProducts = 0;
            if ($offer && $quantity >= $offer->offer_quantity) {
                $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
            }

            // تحديث الكمية في السلة
            $cartItem = Cart::get($cartItemId);
            if ($cartItem) {
                Cart::update($cartItemId, [
                    'quantity' => [
                        'relative' => false,
                        'value' => $quantity
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'item_id' => $cartItemId,
                'free_quantity' => $freeProducts,
                'message' => __('cart.quantity_updated_successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // تفاصيل محتويات السلة كاملة بصفحة الشوبينج كارت
    public function shoppingCartDetails(Order $order = null)
    {
        // الحصول على محتويات السلة
        $items = Cart::getContent()->map(function ($item) use ($order) {
            // Extract product ID (in case it's a combined ID with variant)
            // التأكد من وجود المنتج
            $productId = $this->extractProductId($item->id);
            $product = Product::find($productId);

            if (!$product) {
                Cart::remove($item->id);
                return null;
            }
            $quantity = $item->quantity;
            $freeProducts = 0;

            // جلب نوع العميل
            $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular'; // نوع العميل الافتراضي هو "reqular"

            // الحصول على العرض المناسب من الـ Accessor
            $offer = $product->getOfferDetails($customerOfferType);

            // التأكد إذا كان المنتج يحتوي على عرض
            if ($offer && $quantity >= $offer->offer_quantity) {
                // حساب عدد المنتجات المجانية التي يستحقها العميل
                $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
            }
            if ($this->extractVariantId($item->id)) {
                $variant = Variant::find($this->extractVariantId($item->id));
            } else {
                $variant = null;
            }
            $variantDetails = null;
            if ($variant) {
                $variantDetails = $variant->optionValues->mapWithKeys(function ($optionValue) {
                    return [$optionValue->option->name => $optionValue->value];
                })->map(function ($value, $key) {
                    return "{$key}: {$value}";
                })->implode(' ، ');
                // تحديث معلومات المنتج
                $item->price = $variant->discounted_price; // السعر للمنتج الواحد
                $item->name = $product->name;
                $item->attributes['url'] = route('product.show', $product->id);
                $item->attributes['image'] = $variant?->images?->first()?->path;
                $item->attributes['free_quantity'] = $freeProducts; // إضافة عدد المنتجات المجانية
                $item->attributes['variant_id'] = $variant->id;
                $item->attributes['variant_details'] = $variantDetails;
                $item->attributes['product_id'] = $productId;
            } else {
                // تحديث معلومات المنتج
                $item->price = $product->discounted_price; // السعر للمنتج الواحد
                $item->name = $product->name;
                $item->attributes['url'] = route('product.show', $product->id);
                $item->attributes['image'] = $product?->images?->first()?->path;
                $item->attributes['free_quantity'] = $freeProducts; // إضافة عدد المنتجات المجانية
                $item->attributes['variant_id'] = null;
                $item->attributes['variant_details'] = null;
                $item->attributes['product_id'] = $productId;
            }

            return $item;
        })->filter(); // إزالة العناصر الفارغة

        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

        return view('front.shop-cart', compact(['items', 'totalPrice', 'totalQuantity', 'order']));
    }


    // الحصول على تفاصيل عربة السلة في السايد مينيو
    public function getCartDetails()
    {
        $items = Cart::getContent()->map(function ($item) {
            $productId = $this->extractProductId($item->id);
            $product = Product::find($productId);

            if (!$product) {
                Cart::remove($item->id);
                return null;
            }

            $quantity = $item->quantity;
            $freeProducts = 0;
            $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular';
            $offer = $product->getOfferDetails($customerOfferType);

            if ($offer && $quantity >= $offer->offer_quantity) {
                $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
            }

            $variant = $this->extractVariantId($item->id)
                ? Variant::find($this->extractVariantId($item->id))
                : null;

            $variantDetails = $variant ? $variant->optionValues->mapWithKeys(function ($optionValue) {
                return [
                    $optionValue->option->name => [
                        'name' => $optionValue->option->name,
                        'value' => $optionValue->value
                    ]
                ];
            }) : null;

            if ($variant) {
                $item->price = $variant->discounted_price;
                $item->name = $product->name;
                $item->attributes['url'] = route('product.show', $product->id);
                $item->attributes['image'] = $variant->images->first()->path ?? null;
                $item->attributes['free_quantity'] = $freeProducts;
                $item->attributes['variant_id'] = $variant->id;
                $item->attributes['variant_details'] = $variantDetails;
                $item->attributes['product_id'] = $productId;
            } else {
                $item->price = $product->discounted_price;
                $item->name = $product->name;
                $item->attributes['url'] = route('product.show', $product->id);
                $item->attributes['image'] = $product->images->first()->path ?? null;
                $item->attributes['free_quantity'] = $freeProducts;
                $item->attributes['variant_id'] = null;
                $item->attributes['variant_details'] = null;
                $item->attributes['product_id'] = $productId;
            }

            return $item;
        })->filter();

        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

        return response()->json([
            'items' => $items,
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice
        ]);
    }

    private function extractProductId($itemId)
    {
        return strpos($itemId, '-') !== false
            ? explode('-', $itemId)[0]
            : $itemId;
    }

    private function extractVariantId($itemId)
    {
        return strpos($itemId, '-') !== false
            ? explode('-', $itemId)[1]
            : null;
    }

    // حذف المنتج من  السلة aside menu
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        Cart::remove($productId);

        return response()->json(['success' => true, 'message' => 'Product removed from cart successfully.']);
    }

    public function removeShopingItemCart(Request $request)
    {
        $id = $request->input('id');
        Cart::remove($id);
        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully.',
            'cartTotal' => Cart::getTotal(),
            'cartCount' => Cart::getTotalQuantity()
        ]);
    }

// تفريغ العربة
    public function clear()
    {
        Cart::clear();
    }

    // دالة لتحديث أسعار السلة
    public function refreshCartPrices()
    {
        $userType = Auth::user()?->customer_type;

        if ($userType == 'goomla') {
            $this->clear();
        }
    }


}

