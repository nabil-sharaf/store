<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\Admin\GuestAddress;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Product;
use App\Models\Admin\ProductDiscount;
use App\Models\Admin\PromoCode;
use App\Models\Admin\Setting;
use App\Models\Admin\ShippingRate;
use App\Models\Admin\UserAddress;
use App\Models\Admin\Variant;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use mysql_xdevapi\Exception;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status_id', $request->status);
        }

        if ($request->has('sort')) {
            $query->orderBy($request->sort, 'asc');
        } else {
            $query->orderBy('id', 'desc');

        }
        // البحث بالتاريخ
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }
        // البحث باسم العميل
        if ($request->has('customer_name') && $request->customer_name != '') {
            $query->where(function ($q) use ($request) {
                $q->whereHas('userAddress', function ($subQuery) use ($request) {
                    $subQuery->where('full_name', 'like', '%' . $request->customer_name . '%');
                })
                    ->orWhereHas('guestAddress', function ($subQuery) use ($request) {
                        $subQuery->where('full_name', 'like', '%' . $request->customer_name . '%');
                    });
            });
        }

        $orders = $query->with('orderDetails', 'user')->paginate(get_pagination_count());

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::with('discount')->get();
        $states = ShippingRate::all();
        return view('admin.orders.create', compact('users', 'products', 'states'));
    }

    public function store(OrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = null;
            $isGuest = false;

            if ($request->user_id) {
                $user = User::findOrFail($request->user_id);
            } else {
                $isGuest = true;
            }

            $order = new Order();

            if ($isGuest) {
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
                $userAddress = UserAddress::updateOrCreate(
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

            foreach ($request->products as $productData) {

                $product = Product::findOrFail($productData['id']);
                $variant = null;
                $quantity = $productData['quantity'];
                $freeProducts = 0;

                $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular';
                $offer = $product->getOfferDetails($customerOfferType);

                if ($offer && $quantity >= $offer->offer_quantity) {
                    $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
                }


                if (isset($productData['variant_id'])) {
                    $variant = Variant::findOrFail($productData['variant_id']);

                    if ($variant->product_id !== $product->id) {
                        throw new \Exception('الفاريانت غير متوافق مع المنتج المحدد');
                    }

                    if ($variant->quantity < ($quantity + $freeProducts)) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name . ' - ' . $variant->name);
                    }

                    $variant->quantity -= ($quantity + $freeProducts);
                    $variant->save();
                } else {
                    if ($product->quantity < ($quantity + $freeProducts)) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);
                    }

                    $product->quantity -= ($quantity + $freeProducts);
                    $product->save();
                }


                $all_order_quantity += $quantity;

                $productPrice = $user && $user->customer_type == 'goomla'
                    ? ($variant ? $variant->goomla_price : $product->goomla_price)
                    : ($variant ? $variant->price : $product->price);

                $productDiscount = $product->discount;
                $discountAmount = 0;

                if ($productDiscount) {
                    $discountAmount = $productDiscount->discount_type === 'percentage'
                        ? ($productPrice * $productDiscount->discount) / 100
                        : $productDiscount->discount;
                }

                $priceAfterDiscount = $productPrice - $discountAmount;
                $priceForProduct = $priceAfterDiscount * $quantity;

                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->variant_id = $variant ? $variant->id : null;
                $orderDetail->Product_quantity = $quantity;
                $orderDetail->price = $priceAfterDiscount;
                $orderDetail->free_quantity = $freeProducts;
                $orderDetail->save();

                $totalPrice += $priceForProduct;
            }


            // التحقق من كود الخصم

            if ($request->filled('promo_code')) {

                if(!$user){
                    throw new \Exception('كوبونات الخصم للأعضاء المسجلين فقط');
                }

                if($user->customer_type !='regular'){
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


            $vip_discount = $user ? $this->calculateDiscount($user, $totalPrice) : 0;

            if ($user && $user->customer_type == 'goomla') {
                $this->validateGoomlaOrder($all_order_quantity, $totalPrice);
            }

            $order->total_price = $totalPrice;
            $order->vip_discount = $vip_discount;
            $order->promo_discount = $promoDiscount;
            $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
            $order->final_total = $order->total_after_discount + $shippingCost;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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

    public function edit(Order $order)
    {
        if ($order->status->id == 1) { // تعديل الطلب في حالة اذا لم  يتم شحنه


            $products = Product::with('variants.optionValues')->get();

            $order->load('orderDetails', 'user', 'promocode', 'userAddress', 'guestAddress');
            $user = $order->user;
            $address = $order->userAddress ?? $order->guestAddress;

            $states = ShippingRate::all();

            return view('admin.orders.edit', compact('order', 'products', 'user', 'address', 'states'));
        }
        return redirect(route('admin.orders.index'));
    }

    public function getProductField(Request $request)
    {
        $index = $request->get('index');
        $products = Product::all();
        return view('admin.orders.partials.product-field', compact('index', 'products'));
    }

    public function update(OrderRequest $request, Order $order)
    {
        try {
            DB::beginTransaction();

            $user = null;
            $isGuest = false;

            if ($request->user_id) {
                $user = User::findOrFail($request->user_id);
            } else {
                $isGuest = true;
            }

            // تحديث بيانات العنوان
            if ($isGuest) {
                if ($order->guest_address_id) {
                    $guest = GuestAddress::findOrFail($order->guest_address_id);
                    $guest->update([
                        'full_name' => $request->full_name,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                    ]);
                } else {
                    $guest = GuestAddress::create([
                        'full_name' => $request->full_name,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                    ]);
                    $order->guest_address_id = $guest->id;
                }
            } else {
                $userAddress = UserAddress::updateOrCreate(
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

            // إعادة الكميات القديمة للمخزون
            foreach ($order->orderDetails as $oldDetail) {

                if ($oldDetail->variant_id) {

                    $variant = Variant::findOrFail($oldDetail->variant_id);
                    $variant->quantity += ($oldDetail->product_quantity + $oldDetail->free_quantity);
                    $variant->save();
                } else {
                    $product = Product::findOrFail($oldDetail->product_id);
                    $product->quantity += ($oldDetail->product_quantity + $oldDetail->free_quantity);
                    $product->save();
                }
            }

            // حذف التفاصيل القديمة
            $order->orderDetails()->delete();

            // تحديث تكلفة الشحن
            $shippingCost = ShippingRate::where('state', $request->state)->first()->shipping_cost;
            $order->shipping_cost = $shippingCost;

            $totalPrice = 0;
            $all_order_quantity = 0;

            // إضافة المنتجات الجديدة
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $variant = null;
                $quantity = $productData['quantity'];
                $freeProducts = 0;

                $customerOfferType = auth()->check() ? auth()->user()->customer_type : 'regular';
                $offer = $product->getOfferDetails($customerOfferType);

                if ($offer && $quantity >= $offer->offer_quantity) {
                    $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
                }

                if (isset($productData['variant_id'])) {
                    $variant = Variant::findOrFail($productData['variant_id']);

                    if ($variant->product_id !== $product->id) {
                        throw new \Exception('الفاريانت غير متوافق مع المنتج المحدد');
                    }

                    if ($variant->quantity < ($quantity + $freeProducts)) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name . ' - ' . $variant->name);
                    }

                    $variant->quantity -= ($quantity + $freeProducts);
                    $variant->save();
                } else {
                    if ($product->quantity < ($quantity + $freeProducts)) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);
                    }

                    $product->quantity -= ($quantity + $freeProducts);
                    $product->save();
                }

                $all_order_quantity += $quantity;

                $productPrice = $user && $user->customer_type == 'goomla'
                    ? ($variant ? $variant->goomla_price : $product->goomla_price)
                    : ($variant ? $variant->price : $product->price);

                $productDiscount = $product->discount;
                $discountAmount = 0;

                if ($productDiscount) {
                    $discountAmount = $productDiscount->discount_type === 'percentage'
                        ? ($productPrice * $productDiscount->discount) / 100
                        : $productDiscount->discount;
                }

                $priceAfterDiscount = $productPrice - $discountAmount;
                $priceForProduct = $priceAfterDiscount * $quantity;

                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product->id;
                $orderDetail->variant_id = $variant ? $variant->id : null;
                $orderDetail->Product_quantity = $quantity;
                $orderDetail->price = $priceAfterDiscount;
                $orderDetail->free_quantity = $freeProducts;
                $orderDetail->save();

                $totalPrice += $priceForProduct;
            }

            // حساب الخصم الترويجي
            $promoDiscount = 0;
            if ($request->filled('promo_code')) {
                $promoCode = PromoCode::where('code', $request->promo_code)
                    ->where('active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();

                if (!$promoCode) {
                    throw new \Exception('كود الخصم غير صالح');
                }

                if ($promoCode->minimum_order > $totalPrice) {
                    throw new \Exception('الحد الأدنى للطلب لاستخدام هذا الكود هو ' . $promoCode->minimum_order);
                }

                if ($promoCode->usage_limit !== null) {
                    $usageCount = Order::where('promo_code', $request->promo_code)->count();
                    if ($usageCount >= $promoCode->usage_limit) {
                        throw new \Exception('تم استنفاد الحد الأقصى لاستخدام هذا الكود');
                    }
                }

                if ($user && $promoCode->user_usage_limit !== null) {
                    $userUsageCount = Order::where('user_id', $user->id)
                        ->where('promo_code', $request->promo_code)
                        ->count();
                    if ($userUsageCount >= $promoCode->user_usage_limit) {
                        throw new \Exception('لقد تجاوزت الحد الأقصى لاستخدام هذا الكود');
                    }
                }

                if ($promoCode->customer_type && $user && $user->customer_type !== $promoCode->customer_type) {
                    throw new \Exception('هذا الكود غير صالح لنوع حسابك');
                }

                $promoDiscount = $promoCode->discount_type === 'percentage'
                    ? ($totalPrice * $promoCode->discount) / 100
                    : $promoCode->discount;
            }

            // حساب خصم ال vip
            if ($request->user_id) {
                $vip_discount = $this->calculateDiscount($user, $totalPrice);
            } else {
                $vip_discount = 0;
            }

            // التحقق من شروط الجملة
            if ($user && $user->customer_type == 'goomla') {
                $minGoomlaQuantity = config('shop.min_goomla_quantity', 12);
                $minGoomlaAmount = config('shop.min_goomla_amount', 1000);

                if ($all_order_quantity < $minGoomlaQuantity) {
                    throw new \Exception("الحد الأدنى لطلبات الجملة هو {$minGoomlaQuantity} قطعة");
                }

                if ($totalPrice < $minGoomlaAmount) {
                    throw new \Exception("الحد الأدنى لإجمالي طلبات الجملة هو {$minGoomlaAmount} جنيه");
                }
            }

            $order->total_price = $totalPrice;
            $order->vip_discount = $vip_discount;
            $order->promo_discount = $promoDiscount;
            $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
            $order->final_total = $order->total_after_discount + $shippingCost;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الطلب بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Order $order)
    {
        $order->load('orderDetails.product','orderDetails.variant.optionValues'
            , 'user', 'userAddress', 'guestAddress');
        $address = $order->userAddress ?? $order->guestAddress;
        return view('admin.orders.show', compact('order', 'address'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate([
            'status' => 'required|integer|in:1,2,3,4',
        ]);

        // Update the order status
        $order->status_id = $request->input('status');
        $order->save();

        if ($request->input('status') == 4) {

            foreach ($order->orderDetails as $orderDetail) {
                if ($orderDetail->variant_id) {
                    $variant = $orderDetail->variant;
                    $variant->quantity += $orderDetail->product_quantity;
                    $variant->save();
                } else {
                    $product = $orderDetail->product;
                    $product->quantity += $orderDetail->product_quantity;
                    $product->save();
                }
            }

        }

        return response()->json(['success' => 'تم تعديل حالة الطلب بنجاح']);
    }

    // حساب نسبة الخصم عند اضافة اوردر
    public function getUserDiscount(User $user)
    {
        $discount = 0;
        if ($user->is_vip && $user->vip_end_date >= now()) {
            $discount = $user->discount;
        }

        return response()->json(['discount' => $discount]);
    }

    public function checkCoupon(Request $request)
    {

        $couponCode = $request->input('promo_code');
        $orderTotal = $request->input('total_order'); // إجمالي الطلب
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return response()->json(['error' => 'كوبونات الخصم للأعضاء المسجلين فقط'], 400);
        }

        $type = User::where('id', $user_id)->first()->customer_type;

        if ($type != 'regular') {
            return Response()->json(['error' => 'كوبونات الخصم لعملاء القطاعي فقط'], 400);
        }

        // ابحث عن الكوبون بناءً على الكود المدخل
        $coupon = PromoCode::where('code', $couponCode)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();


        // التحقق من وجود الكوبون
        if (!$coupon) {
            return response()->json(['error' => 'كود الخصم غير صحيح أو غير صالح'], 400);
        }

        // تحقق إذا كان المستخدم قد استخدم الكوبون من قبل
        $couponUsed = DB::table('user_promocode')
            ->where('user_id', $user_id)
            ->where('promo_code_id', $coupon->id)
            ->exists();

        if ($couponUsed) {
            return response()->json(['valid' => false, 'error' => 'لقد قمت باستخدام هذا الكوبون من قبل'], 400);
        }

        // التحقق من الحد الأدنى لقيمة الطلب
        if ($orderTotal < $coupon->min_amount) {
            return response()->json(['error' => 'إجمالي الطلب أقل من الحد الأدنى لتطبيق الكوبون'], 400);
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

    public function updateCopoun(Request $request)
    {

        $couponCode = $request->input('promo_code');
        $orderTotal = $request->input('total_order'); // إجمالي الطلب

        // ابحث عن الكوبون بناءً على الكود المدخل
        $coupon = PromoCode::where('code', $couponCode)
            ->where('active', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();


        // التحقق من وجود الكوبون
        if (!$coupon) {
            return response()->json(['error' => 'كود الخصم غير صحيح او غير صالح'], 400);
        }

        // التحقق من الحد الأدنى لقيمة الطلب
        if ($orderTotal < $coupon->min_amount) {
            return response()->json(['error' => 'إجمالي الطلب أقل من الحد الأدنى لتطبيق الكوبون'], 400);
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
            'success' => 'تم تعديل قيمة خصم الكوبون ',
            'discount' => $discount,
        ]);
    }

    public function getShippingCost($state)
    {
        $shippingCost = ShippingRate::where('state', $state)->first()->shipping_cost;

        return response()->json(['shipping_cost' => $shippingCost]);
    }

    public function getFreeQuantity(Request $request)
    {
        $id = $request->input('productId');
        $quantity = $request->input('quantity'); // الكمية المدخلة من المستخدم

        // جلب نوع العميل
        $customerOfferType = $request->input('customer_type') ?? 'regular'; // نوع العميل الافتراضي هو "reqular"

        $product = Product::findOrfail($id);
        $freeProducts = 0;


        // الحصول على العرض المناسب من الـ Accessor
        $offer = $product->getOfferDetails($customerOfferType);

        // التأكد إذا كان المنتج يحتوي على عرض
        if ($offer && $quantity >= $offer->offer_quantity) {
            // حساب عدد المنتجات المجانية التي يستحقها العميل
            $freeProducts = floor($quantity / $offer->offer_quantity) * $offer->free_quantity;
        }

        return response()->json(['success' => true,
            'message' => 'quantity fetched successfully',
            'free_quantity' => $freeProducts,
        ]);
    }

    public function getVariants($productId)
    {
        $product = Product::with('variants.optionValues')->find($productId);
        if (!$product) {
            return response()->json([], 404);
        }

        // إضافة حقول جديدة خاصة بخصم المنتج باستخدام الماب لكل فاريانت
        $variants = $product->variants->map(function ($variant) use ($product) {
            $variant->discount_type = $product->discount?->discount_type; // نوع الخصم من المنتج
            $variant->discount_value = $product->discount?->discount; // قيمة الخصم من المنتج
            return $variant;
        });
        return response()->json(['variants' => $product->variants]);
    }

}


