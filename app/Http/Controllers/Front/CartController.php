<?php

namespace App\Http\Controllers\Front;

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
        // التأكد إذا كان المنتج يحتوي على عرض
        if ($product->offer_quantity && $product->free_quantity) {
            // حساب عدد المنتجات المجانية التي يستحقها العميل
            $freeProducts = floor($quantity / $product->offer_quantity) * $product->free_quantity;

            // تحديث إجمالي الكمية بإضافة المنتجات المجانية
            $totalQuantity = $quantity + $freeProducts;
        } else {
            $totalQuantity = $quantity;
        }


        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->discounted_price,
            'quantity' => $totalQuantity,
            'attributes' => [
                'url' => route('product.show', $product->id),
                'image' => $product?->images?->first()?->path,
                'free_quantity' => $freeProducts, // إضافة عدد المنتجات المجانية

            ]
        ]);

        return response()->json(['message' => 'تم اضافة المنتج لسلة الشراء']);
    }

    // تحديث كمية المنتج في السلة
    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);

        Cart::update($productId, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
            'price' => $product->discounted_price,

        ]);

        return response()->json(['success' => true, 'message' => 'Cart updated successfully.']);
    }

    // حذف المنتج من السلة
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        Cart::remove($productId);

        return response()->json(['success' => true, 'message' => 'Product removed from cart successfully.']);
    }

    // الحصول على تفاصيل السلة (عدد المنتجات والإجمالي)
    public function getCartDetails()
    {
        $items = Cart::getContent()->map(function ($item) {
            $product = Product::find($item->id);
            $item->price = $product->discounted_price;
            $item->id = $product->id;
            $item->name = $product->name;
            $item->attributes['url'] = route('product.show', $product->id);
            $item->attributes['image'] = $product?->images?->first()?->path;

            return $item;
        });

        $totalQuantity = Cart::getTotalQuantity();

        $totalPrice = $items->sum(function ($item) {
            return $item->price * ($item->quantity - $item->attributes['free_quantity']); // خصم أي كمية مجانية
        });
        return response()->json([
            'items' => $items->values()->toArray(), // تأكيد تحويل العناصر إلى Array
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice,
        ]);
    }

    // دالة لتحديث أسعار السلة
    public function refreshCartPrices()
    {
        $userType = Auth::user()?->customer_type;

        if($userType=='goomla'){
            $this->clear();
        }
    }


// تفريغ العربة
    public function clear()
    {
        Cart::clear();
    }


    public function shoppingCartDetails()
    {
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

        return view('front.shop-cart', compact(['items', 'totalPrice', 'totalQuantity']));
    }


}



