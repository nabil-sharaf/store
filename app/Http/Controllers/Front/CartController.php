<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    // عرض محتويات العربة
    public function index()
    {
        $cartItems = Cart::getContent();
        return view('cart.index', compact('cartItems'));
    }

    // إضافة منتج إلى العربة
    public function add(Request $request)
    {
        Cart::add(array(
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array()
        ));

        return redirect()->route('cart.index')->with('success', 'تمت إضافة المنتج إلى العربة بنجاح');
    }

    // تحديث منتج في العربة
    public function update(Request $request, $id)
    {
        Cart::update($id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $request->quantity
            ),
        ));

        return redirect()->route('cart.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    // حذف منتج من العربة
    public function remove($id)
    {
        Cart::remove($id);
        return redirect()->route('cart.index')->with('success', 'تمت إزالة المنتج من العربة بنجاح');
    }

    // تفريغ العربة
    public function clear()
    {
        Cart::clear();
        return redirect()->route('cart.index')->with('success', 'تمت تفريغ العربة بنجاح');
    }
}
