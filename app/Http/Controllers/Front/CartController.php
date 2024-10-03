<?php

namespace App\Http\Controllers\Front;

use App\Models\Admin\Order;
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
        $product = Product::find($productId);
        $quantity = $request->input('quantity');
        $freeProducts = 0;

        // التحقق مما إذا كان هناك طلب قيد التعديل
        $editingOrderId = session()->get('editing_order_id');

        // جلب نوع العميل
        $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular'; // نوع العميل الافتراضي هو "reqular"

        // الحصول على العرض المناسب من الـ Accessor
        $offer = $product->getOfferDetails($customerOfferType);

        // التأكد إذا كان المنتج يحتوي على عرض
        if ($offer && $quantity >= $offer->offer_quantity) {
            // حساب عدد المنتجات المجانية التي يستحقها العميل
            $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
        }


        // إضافة المنتج إلى السلة
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->discounted_price,
            'quantity' => $quantity,
            'attributes' => [
                'url' => route('product.show', $product->id),
                'image' => $product?->images?->first()?->path,
                'free_quantity' => $freeProducts, // إضافة عدد المنتجات المجانية
                'editing_order_id' => $editingOrderId, // حفظ معرف الطلب في السلة
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
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);


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

        // تحديث السلة بالمنتج والكميات المجانية
        Cart::update($productId, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'price' => $product->discounted_price,
            'attributes' => [
                'free_quantity' => $freeProducts // إضافة الكمية المجانية في attributes
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلة بنجاح.',
            'free_quantity' => $freeProducts,
        ]);
    }

    // تفاصيل محتويات السلة كاملة بصفحة الشوبينج كارت
    public function shoppingCartDetails(Order $order = null)
    {
        // الحصول على محتويات السلة
        $items = Cart::getContent()->map(function ($item) use ($order) {
            $product = Product::find($item->id);

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

            // تحديث معلومات المنتج
            $item->price = $product->discounted_price; // السعر للمنتج الواحد
            $item->name = $product->name;
            $item->attributes['url'] = route('product.show', $product->id);
            $item->attributes['image'] = $product?->images?->first()?->path;
            $item->attributes['free_quantity'] = $freeProducts; // إضافة عدد المنتجات المجانية

            return $item;
        });

        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

        return view('front.shop-cart', compact(['items', 'totalPrice', 'totalQuantity', 'order']));
    }


    // الحصول على تفاصيل عربة السلة في السايد مينيو
    public function getCartDetails()
    {
        // جلب نوع العميل
        $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular'; // نوع العميل الافتراضي هو "reqular"

        $items = Cart::getContent()->map(function ($item) use ($customerOfferType) {
            $product = Product::find($item->id);

            // تجاهل المنتجات التي لم يتم العثور عليها وحذفها من العربة
            if (!$product) {
                Cart::remove($item->id); // حذف المنتج من العربة
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

            // تحديث بيانات العنصر مع خصم السعر
            $item->price = $product->discounted_price;
            $item->id = $product->id;
            $item->name = $product->name;
            $item->attributes['url'] = route('product.show', $product->id);

            // التحقق من وجود صورة وإذا لم توجد إضافة صورة افتراضية
            $item->attributes['image'] = $product?->images?->first()?->path ?? 'path/to/default_image.png';
            $item->attributes['free_quantity'] = $freeProducts; // إضافة عدد المنتجات المجانية

            return $item;
        })->filter(); // فلترة العناصر التي تم حذفها (null)

        $totalQuantity = Cart::getTotalQuantity();

        // حساب الإجمالي مع التعامل الآمن مع المنتجات المحذوفة
        $totalPrice = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });


        // إذا كان هناك طلب مخزن في الجلسة
        $order = session('order_id') ? Order::find(session('order_id')) : null;

        return response()->json([
            'order' => $order,
            'items' => $items->values()->toArray(), // تحويل العناصر إلى Array
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice,
        ]);
    }



    // حذف المنتج من السلة
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        Cart::remove($productId);

        return response()->json(['success' => true, 'message' => 'Product removed from cart successfully.']);
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

        if($userType=='goomla'){
            $this->clear();
        }
    }


}



