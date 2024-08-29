<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Front\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    public function store(Request $request, $productId)
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'يجب تسجيل الدخول أولاً.'], 401);
        }

        $user = Auth::user();

        if (Wishlist::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'المنتج موجود بالفعل في قائمة الأمنيات']);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json(['message' => 'تم إضافة المنتج إلى قائمة الأمنيات']);
    }

    public function index()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)->with('product')->get();

        return view('front.wishlist', compact('wishlists'));
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'تم إزالة المنتج من قائمة الأمنيات']);
    }
}
