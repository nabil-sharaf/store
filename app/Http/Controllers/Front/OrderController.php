<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Admin\GuestAddress;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Product;
use App\Models\Admin\PromoCode;
use App\Models\Admin\Setting;
use App\Models\Admin\ShippingRate;
use App\Models\Front\UserAddress;
use App\Models\User;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        $states = ShippingRate::all();
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = floatval(Cart::getTotal());

        if (Auth::user() && Auth::user()->isVip()) {
            $discount_percentage = (Auth::user()->discount) / 100;
            $vipDiscount = round($totalPrice * $discount_percentage, 2);
        } else {
            $vipDiscount = 0;
        }


        return view('front.checkout', compact(['items', 'totalPrice', 'totalQuantity', 'vipDiscount', 'states']));
    }

    public function indexEdit(Order $order)
    {

        // التحقق من أن المستخدم الحالي هو صاحب الطلب
        if ($order->user_id) {
            if ($order->user_id !== auth()->user()?->id) {

                session()->forget('editing_order_id');
                // منع التعديل وإرجاع رسالة
                return redirect()->back()->with('error', 'لا يمكنك تعديل هذا الطلب.');
            }
        }
        if ($order->status->id != 1) {
            session()->forget('editing_order_id');
            return redirect()->route('home.index')->with('error', 'لا يمكنك تعديل الطلب حاليا.');
        }

        $states = ShippingRate::all();
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = floatval(Cart::getTotal());

        if (Auth::user() && Auth::user()->isVip()) {
            $discount_percentage = (Auth::user()->discount) / 100;
            $vipDiscount = round($totalPrice * $discount_percentage, 2);
        } else {
            $vipDiscount = 0;
        }


        return view('front.checkout', compact(['items', 'totalPrice', 'totalQuantity', 'vipDiscount', 'order', 'states']));
    }


    public function show(Order $order)
    {
        $id = \auth()->user()?->id ?? '';

        // التحقق من أن المستخدم الحالي هو صاحب الطلب
        if ($order->user_id) {
            if ($order->user_id !== $id) {
                // منع التعديل وإرجاع رسالة
                return redirect()->route('home.index')->with('error', 'لا يمكنك عرض هذا الطلب.');
            }
        }

        $address = UserAddress::where('user_id', $id)->first();
        return view('front.show-order', compact('order', 'address'));
    }

    public function store(CheckoutRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = null;
                $isGuest = false;

                if (auth()->check()) {
                    $user = auth()->user();
                } else {
                    $isGuest = true;
                }

                $order = new Order();

                if ($isGuest) {
                    // حفظ معلومات الزائر في الطلب
                    $guest = new GuestAddress();
                    $guest->full_name = $request->full_name;
                    $guest->phone = $request->phone;
                    $guest->address = $request->address;
                    $guest->city = $request->city;
                    $guest->state = $request->state;
                    $guest->save();
                    $order->guest_address_id = $guest->id;
                } else {
                    $order->user_id = $user->id;
                    // انشاء  أو تحديث عنوان المستخدم
                    $userAddress = \App\Models\Admin\UserAddress::updateOrCreate(

                        ['user_id' => $user->id],
                        [
                            'full_name' => $request->full_name,
                            'phone' => $request->phone,
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                        ]
                    );
                    $order->user_address_id = $userAddress->id;


                }


                // إضافة تكلفة الشحن
                $shippingCost = ShippingRate::where('state', $request->state)->first()->shipping_cost;

                $order->shipping_cost = $shippingCost;


                $order->total_price = 0;
                $order->vip_discount = 0;
                $order->promo_discount = 0;
                $order->total_after_discount = 0;
                $order->final_total = 0;
                $order->save();

                $totalPrice = 0;
                $all_order_quantity = 0;

                $items = Cart::getContent();

                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    if ($product->quantity < ($item['quantity'] + $item->attributes['free_quantity'])) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);

                    }

                    if ($item['quantity'] < 1) {
                        throw new \Exception('الكمية لابد ان تكون واحد على الأقل.');
                    }

                    $all_order_quantity += $item['quantity'];

                    $priceAfterDiscount = $product->discounted_price;
                    $priceForProduct = $priceAfterDiscount * $item['quantity'];

                    // حساب كمية المنتجات المجانية اذا وجدت
                    $quantity = $item['quantity'];
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

                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item['id'];
                    $orderDetail->Product_quantity = $item['quantity'];
                    $orderDetail->free_quantity = $freeProducts;
                    $orderDetail->price = $priceAfterDiscount;
                    $orderDetail->save();

                    $totalPrice += $priceForProduct;
                    $product->quantity -= ($item['quantity'] + $freeProducts);
                    $product->save();
                }

                // التحقق من كود الخصم
                if ($request->filled('promo_code')) {

                    if (!$user) {
                        throw new \Exception('كوبونات الخصم للأعضاء المسجلين فقط');
                    }

                    if ($user->customer_type != 'regular') {
                        throw new \Exception('كوبونات الخصم للعملاء القطاعي فقط  فقط');
                    }

                    $promoCode = PromoCode::where('code', $request->promo_code)
                        ->where('active', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if (!$promoCode) {
                        throw new \Exception('كود الخصم غير صالح أو منتهي الصلاحية.');
                    }

                    $promoCodeUsed = DB::table('user_promocode')
                        ->where('user_id', $user ? $user->id : null)
                        ->where('promo_code_id', $promoCode->id)
                        ->exists();

                    if ($promoCodeUsed) {
                        throw new \Exception('لقد استخدمت هذا الكود من قبل.');
                    }

                    if ($totalPrice < $promoCode->min_amount) {
                        throw new \Exception('يجب أن يكون إجمالي الطلب أكبر من ' . $promoCode->min_amount . ' لاستخدام هذا الكوبون.');
                    }

                    $promoDiscount = $promoCode->discount_type === 'percentage'
                        ? ($totalPrice * $promoCode->discount) / 100
                        : $promoCode->discount;
                } else {
                    $promoDiscount = 0;
                }

                // حساب خصم vip إذا كان المستخدم مسجلاً
                $vip_discount = $user ? $this->calculateDiscount($user, $totalPrice) : 0;

                // شرط الحد الأدنى للطلب في حالة عميل الجملة
                if ($user && $user->customer_type == 'goomla') {
                    $minProductsPrice = Setting::getValue('goomla_min_prices');
                    $minQuantity = Setting::getValue('goomla_min_number');

                    if ($all_order_quantity < $minQuantity && $totalPrice < $minProductsPrice) {
                        throw new \Exception("يجب أن يكون عدد القطع على الأقل " . $minQuantity . " أو يكون إجمالي السعر على الأقل " . $minProductsPrice . " لعميل الجملة.");
                    }
                }

                if (($totalPrice - $vip_discount - $promoDiscount) < 1) {
                    throw new \Exception("تأكد من اجمالي الاوردر قبل عمل اتمام الطلب");

                }

                $order->total_price = $totalPrice;
                $order->vip_discount = $vip_discount;
                $order->promo_discount = $promoDiscount;
                $order->shipping_cost = $shippingCost;
                $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
                $order->final_total = $order->total_after_discount + $shippingCost;
                $order->save();

                if ($promoDiscount) {
                    DB::table('user_promocode')->insert([
                        'user_id' => $user ? $user->id : null,
                        'promo_code_id' => $promoCode->id,
                        'order_id' => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $order->update(['promocode_id' => $promoCode->id]);
                }

                Cart::clear();
                session()->flash('success', 'تم انشاء طلبك بنجاح');
                return response()->json([
                    'success' => true,
                    'route' => route('home.index'),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function edit(Order $order)
    {

        // تفريغ السلة الحالية
        Cart::clear();


        if (!$order) {
            return redirect()->route('cart.show')->with('error', 'الطلب غير موجود.');
        }


        // التحقق من أن المستخدم الحالي هو صاحب الطلب
        if ($order->user_id) {
            if ($order->user_id !== auth()->user()?->id) {
                // منع التعديل وإرجاع رسالة
                return redirect()->back()->with('error', 'لا يمكنك تعديل هذا الطلب.');
            }
        }

        if ($order->status->id != 1) {
            session()->forget('editing_order_id');
            return redirect()->route('home.index')->with('error', 'لا يمكنك تعديل الطلب حاليا.');
        }


        // إضافة المنتجات من الطلب إلى السلة
        foreach ($order->orderDetails as $detail) {
            Cart::add([
                'id' => $detail->product->id,
                'name' => $detail->product->name,
                'price' => $detail->price,
                'quantity' => $detail->product_quantity,
                'attributes' => [
                    'url' => route('product.show', $detail->product->id),
                    'image' => $detail->product?->images?->first()?->path,
                ]
            ]);
        }

        // حفظ معرّف الطلب الجاري تعديله في الجلسة
        session()->put('editing_order_id', $order->id);

        // إعادة التوجيه إلى صفحة سلة التسوق مع تخزين الطلب في سلة البيانات
        return redirect()->route('home.shop-cart', $order->id);

    }

    public function update(CheckoutRequest $request, Order $order)
    {
        // التحقق من أن المستخدم الحالي هو صاحب الطلب
        if ($order->user_id) {
            if ($order->user_id !== \auth()->user()?->id) {
                // منع التعديل وإرجاع رسالة
                return redirect()->route('home.index')->with('error', 'لا يمكنك عرض هذا الطلب.');
            }
        }
        if ($order->status->id != 1) {
            session()->forget('editing_order_id');
            return redirect()->route('home.index')->with('error', 'لا يمكنك تعديل الطلب حاليا.');
        }


        try {
            return DB::transaction(function () use ($request, $order) {
                $user = null;
                $isGuest = false;

                if (auth()->check()) {
                    $user = auth()->user();
                } else {
                    $isGuest = true;
                }

                // تحديث معلومات الزائر أو المستخدم
                if ($isGuest) {
                    $guest = GuestAddress::find($order->guest_address_id);
                    if (!$guest) {
                        $guest = new GuestAddress();
                    }
                    $guest->full_name = $request->full_name;
                    $guest->phone = $request->phone;
                    $guest->address = $request->address;
                    $guest->city = $request->city;
                    $guest->state = $request->state;
                    $guest->save();
                    $order->guest_address_id = $guest->id;
                } else {
                    $order->user_id = $user->id;
                    $userAddress = \App\Models\Admin\UserAddress::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'full_name' => $request->full_name,
                            'phone' => $request->phone,
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                        ]
                    );
                    $order->user_address_id = $userAddress->id;
                }

                // إعادة حساب الكميات والأسعار للطلب الحالي
                $totalPrice = 0;
                $all_order_quantity = 0;

                // استرجاع الكميات للمنتج  والكميات الفري اذا وجدت
                foreach ($order->orderDetails as $orderDetail) {
                    $product = $orderDetail->product;
                    $product->quantity = $product->quantity + $orderDetail->product_quantity + ($orderDetail->free_quantity ?? 0);
                    $product->save();
                }
                $items = Cart::getContent();

                $order->orderDetails()->delete();

                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    if ($product->quantity < ($item['quantity'] + $item->attributes['free_quantity'])) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);
                    }

                    if ($item['quantity'] < 1) {
                        throw new \Exception('الكمية لابد أن تكون واحد على الأقل.');
                    }

                    $all_order_quantity += $item['quantity'];

                    $priceAfterDiscount = $product->discounted_price;
                    $priceForProduct = $priceAfterDiscount * $item['quantity'];

                    // حساب كمية المنتجات المجانية اذا وجدت
                    $quantity = $item['quantity'];
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

                    // إضافة تفاصيل جديدة للطلب
                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item['id'];
                    $orderDetail->Product_quantity = $item['quantity'];
                    $orderDetail->free_quantity = $freeProducts;
                    $orderDetail->price = $priceAfterDiscount;
                    $orderDetail->save();

                    $totalPrice += $priceForProduct;
                    $product->quantity -= ($item['quantity'] + $freeProducts);
                    $product->save();
                }

                // حذف كود الخصم المرتبط بهذا الاوردر اذا وجد
                DB::table('user_promocode')
                    ->where('order_id', $order->id)
                    ->delete();

                // التحقق من كود الخصم
                if ($request->filled('promo_code')) {
                    $promoCode = PromoCode::where('code', $request->promo_code)
                        ->where('active', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if (!$promoCode) {
                        throw new \Exception('كود الخصم غير صالح أو منتهي الصلاحية.');
                    }

                    $promoCodeUsed = DB::table('user_promocode')
                        ->where('user_id', $user ? $user->id : null)
                        ->where('promo_code_id', $promoCode->id)
                        ->exists();

                    if ($promoCodeUsed) {
                        throw new \Exception('لقد استخدمت هذا الكود من قبل.');
                    }

                    $promoDiscount = $promoCode->discount_type === 'percentage'
                        ? ($totalPrice * $promoCode->discount) / 100
                        : $promoCode->discount;
                } else {
                    $promoDiscount = 0;
                }

                // حساب خصم VIP
                $vip_discount = $user ? $this->calculateDiscount($user, $totalPrice) : 0;

                // شرط الحد الأدنى لعميل الجملة
                if ($user && $user->customer_type == 'goomla') {
                    $minProductsPrice = Setting::getValue('goomla_min_prices');
                    $minQuantity = Setting::getValue('goomla_min_number');

                    if ($all_order_quantity < $minQuantity && $totalPrice < $minProductsPrice) {
                        throw new \Exception("يجب أن يكون عدد القطع على الأقل " . $minQuantity . " أو يكون إجمالي السعر على الأقل " . $minProductsPrice . " لعميل الجملة.");
                    }
                }

                if (($totalPrice - $vip_discount - $promoDiscount) < 1) {
                    throw new \Exception("تأكد من اجمالي الاوردر قبل عمل اتمام الطلب.");
                }


                // إضافة تكلفة الشحن
                $shippingCost = ShippingRate::where('state', $request->state)->first()->shipping_cost;

                $order->shipping_cost = $shippingCost;

                // تحديث إجمالي الطلب والخصومات
                $order->total_price = $totalPrice;
                $order->vip_discount = $vip_discount;
                $order->promo_discount = $promoDiscount;
                $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
                $order->final_total = $totalPrice - $vip_discount - $promoDiscount + $shippingCost;
                $order->save();

                // تحديث كود الخصم إذا كان موجودًا
                if ($promoDiscount) {

                    DB::table('user_promocode')->insert([
                        'user_id' => $user ? $user->id : null,
                        'promo_code_id' => $promoCode->id,
                        'order_id' => $order->id,
                        'created_at' => now(), // لا تنسى إضافة timestamps إذا كانت مطلوبة
                        'updated_at' => now(),
                    ]);

                    $order->update(['promocode_id' => $promoCode->id]);
                } else {
                    $order->update(['promocode_id' => null]);

                }

                Cart::clear();

                // اكتمال الطلب
                session()->forget('editing_order_id');

                session()->flash('success', 'تم تحديث طلبك بنجاح');
                return response()->json([
                    'success' => true,
                    'route' => route('home.index'),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function calculateDiscount(User $user, $totalPrice)
    {
        if ($user->is_vip) {
            // تحقق من صلاحية فترة VIP
            if ($user->vip_start_date <= now() && $user->vip_end_date >= now()) {
                return $totalPrice * ($user->discount / 100);
            }
            // إذا انتهت فترة VIP، قم بتحديث نوع العميل
            $user->is_vip = null;
            $user->discount = null;
            $user->save();
            return 0;
        }
        return 0;
    }

    public function checkCoupon(Request $request)
    {

        $couponCode = $request->input('promo_code');
        $orderTotal = $request->input('total_order'); // إجمالي الطلب
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'كوبونات الخصم للأعضاء المسجلين فقط']);
        }

        // ابحث عن الكوبون بناءً على الكود المدخل
        $coupon = PromoCode::where('code', $couponCode)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // التحقق من وجود الكوبون
        if (!$coupon) {
            return response()->json(['valid' => false, 'error' => 'كود الخصم غير صحيح أو غير صالح']);
        }

        // تحقق إذا كان المستخدم قد استخدم الكوبون من قبل
        $couponUsed = DB::table('user_promocode')
            ->where('user_id', $user_id)
            ->where('promo_code_id', $coupon->id)
            ->first();

        // إذا كان المستخدم يقوم بتعديل الطلب
        if (session()->has('editing_order_id')) {
            $editingOrderId = session('editing_order_id');

            if ($couponUsed && $couponUsed->order_id != $editingOrderId) {
                return response()->json(['valid' => false, 'error' => 'لقد قمت باستخدام هذا الكوبون في طلب آخر من قبل']);
            }
        } else {
            // إذا كان المستخدم يقوم بإنشاء طلب جديد
            if ($couponUsed) {
                return response()->json(['valid' => false, 'error' => 'لقد قمت باستخدام هذا الكوبون من قبل']);
            }
        }


        // التحقق من الحد الأدنى لقيمة الطلب
        if ($orderTotal < $coupon->min_amount) {
            return response()->json(['error' => 'إجمالي الطلب أقل من الحد الأدنى لتطبيق الكوبون']);
        }

        // حساب الخصم بناءً على نوع الكوبون
        $discount = 0;
        if ($coupon->discount_type == 'percentage') {
            $discount = ($coupon->discount / 100) * $orderTotal;
        } elseif ($coupon->discount_type == 'fixed') {
            $discount = $coupon->discount;
        }

        // نرجع إجمالي الطلب بعد الخصم
        return response()->json([
            'success' => 'تم تطبيق الكوبون بنجاح',
            'discount' => $discount,
        ]);
    }

    public function getShippingCost($state)
    {
        $shippingCost = ShippingRate::where('state', $state)->first()->shipping_cost;

        return response()->json(['shipping_cost' => $shippingCost]);
    }


    public function clearCartSession()
    {
        Cart::clear();
        \session()->forget('editing_order_id');
        return redirect()->route('home.index');
    }
}
