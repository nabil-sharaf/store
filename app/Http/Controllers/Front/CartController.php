<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Admin\Product;

class CartController extends Controller
{
    // إضافة المنتج إلى السلة
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);

        // استرجاع خصم المنتج من قاعدة البيانات
        $productDiscount = $product->discount;
        if(auth()->user()){

        $productPrice = auth()->user()->customer_type == 'goomla' ? $product->goomla_price : $product->price;
        }else{
            $productPrice = $product->price;
        }

        $discountAmount = 0;
        if ($productDiscount) {
            if ($productDiscount->discount_type === 'percentage') {
                $discountAmount = ($productPrice * $productDiscount->discount) / 100;
            } elseif ($productDiscount->discount_type === 'fixed') {
                $discountAmount = $productDiscount->discount;
            }
        }

        $priceAfterDiscount = $productPrice - $discountAmount;



        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $priceAfterDiscount,
            'quantity' => $request->quantity,
            'attributes' => [
                        'url' => route('product.show',$product->id),
                        'image'=>$product?->images?->first()?->path,
                         ]
        ]);

        return response()->json(['message' => 'تم اضافة المنتج لسلة الشراء']);
    }

    // تحديث كمية المنتج في السلة
    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        Cart::update($productId, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity
            ],
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
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

        return response()->json([
            'items' => $items,
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice
        ]);
    }


// تفريغ العربة
    public function clear()
    {
        Cart::clear();
        return redirect()->route('cart.index')->with('success', 'تمت تفريغ العربة بنجاح');
    }


    public function shoppingCartDetails()
    {
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();

            return view('front.shop-cart',compact(['items','totalPrice','totalQuantity']));
    }
}



