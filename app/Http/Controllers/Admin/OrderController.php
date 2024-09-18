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
use App\Models\Admin\UserAddress;
use App\Models\User;
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
        }else{
            $query->orderBy('id', 'desc');

        }

        $orders = $query->with('orderDetails', 'user')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::with('discount')->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(OrderRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = null;
                $isGuest = false;

                if ($request->user_id) {
                    $user = User::findOrFail($request->user_id);
                } else {
                    $isGuest = true;
                }

                $order = new Order();

                if ($isGuest) {
                    // حفظ معلومات الزائر في الطلب
                    $guest = new GuestAddress();
                    $guest->full_name =  $request->full_name;
                    $guest->phone     =  $request->phone;
                    $guest->address =  $request->address;
                    $guest->city    =  $request->city;
                    $guest->state    =  $request->state;
                    $guest->save();
                    $order->guest_address_id = $guest->id;
                } else {
                    $order->user_id = $user->id;
                    // انشاء  أو تحديث عنوان المستخدم
                    UserAddress::updateOrCreate(

                        ['user_id' => $user->id],
                        [
                            'full_name' => $request->full_name,
                            'phone'     => $request->phone,
                            'address'   => $request->address,
                            'city'      => $request->city,
                            'state'     => $request->state,
                        ]
                    );


                }

                $order->total_price = 0;
                $order->vip_discount = 0;
                $order->promo_discount = 0;
                $order->total_after_discount = 0;
                $order->save();

                $totalPrice = 0;
                $all_order_quantity = 0;


                foreach ($request->products as $productData) {
                    $product = Product::find($productData['id']);
                    if ($product->quantity < $productData['quantity']) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);
                    }

                    if ($productData['quantity'] < 1) {
                        throw new \Exception('الكمية لابد ان تكون واحد على الأقل.');
                    }

                    $all_order_quantity += $productData['quantity'];
                    $productDiscount = $product->discount;
                    $productPrice = $user && $user->customer_type == 'goomla' ? $product->goomla_price : $product->price;

                    $discountAmount = 0;
                    if ($productDiscount) {
                        if ($productDiscount->discount_type === 'percentage') {
                            $discountAmount = ($productPrice * $productDiscount->discount) / 100;
                        } elseif ($productDiscount->discount_type === 'fixed') {
                            $discountAmount = $productDiscount->discount;
                        }
                    }

                    $priceAfterDiscount = $productPrice - $discountAmount;
                    $priceForProduct = $priceAfterDiscount * $productData['quantity'];

                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $productData['id'];
                    $orderDetail->Product_quantity = $productData['quantity'];
                    $orderDetail->price = $priceAfterDiscount;
                    $orderDetail->save();

                    $totalPrice += $priceForProduct;
                    $product->quantity -= $productData['quantity'];
                    $product->save();
                }

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

                $order->total_price = $totalPrice;
                $order->vip_discount = $vip_discount;
                $order->promo_discount = $promoDiscount;
                $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
                $order->save();

                if ($promoDiscount) {
                    DB::table('user_promocode')->insert([
                        'user_id' => $user ? $user->id : null,
                        'promo_code_id' => $promoCode->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $order->update(['promocode_id' => $promoCode->id]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الطلب بنجاح'
                ]);
            });
        } catch (\Exception $e) {
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


            $products = Product::all();
            $order->load('orderDetails','user','promocode');
            $user  = $order->user;
            return view('admin.orders.edit', compact('order', 'products','user'));
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
            return DB::transaction(function () use ($request, $order) {

                $user = null;
                $isGuest = false;

                if ($request->user_id) {
                    $user = User::findOrFail($request->user_id);
                } else {
                    $isGuest = true;
                }

                // Restore previous quantities
                foreach ($order->orderDetails as $orderDetail) {
                    $product = $orderDetail->product;
                    $product->quantity += $orderDetail->product_quantity;
                    $product->save();
                }

                if ($isGuest) {
                    // حفظ معلومات الزائر في الطلب
                    $guest = new GuestAddress();
                    $guest->full_name =  $request->full_name;
                    $guest->phone     =  $request->phone;
                    $guest->address =  $request->address;
                    $guest->city    =  $request->city;
                    $guest->state    =  $request->state;
                    $guest->save();
                    $order->guest_address_id = $guest->id;
                } else {
                    $order->user_id = $user->id;
                    // انشاء  أو تحديث عنوان المستخدم
                    UserAddress::updateOrCreate(

                        ['user_id' => $user->id],
                        [
                            'full_name' => $request->full_name,
                            'phone'     => $request->phone,
                            'address'   => $request->address,
                            'city'      => $request->city,
                            'state'     => $request->state,
                        ]
                    );


                }

                $order->orderDetails()->delete();
                $totalPrice = 0;
                $all_order_quantity=0;

                foreach ($request->products as $productData) {
                    $product = Product::findOrFail($productData['id']);
                    if ($product->quantity < $productData['quantity']) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة للمنتج: ' . $product->name);
                    }



                    if ($productData['quantity'] < 1) {
                        throw new \Exception('الكمية لابد أن تكون واحد على الأقل.');
                    }


                    // استرجاع خصم المنتج من قاعدة البيانات
                    $productDiscount = $product->discount;

                    if($request->user_id){
                    $productPrice = $user?->customer_type == 'goomla' ? $product->goomla_price : $product->price;
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
                    $priceForProduct = $priceAfterDiscount * $productData['quantity'];


                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $productData['id'];
                    $orderDetail->Product_quantity = $productData['quantity'];
                    $orderDetail->price = $priceAfterDiscount;
                    $orderDetail->save();

                    $totalPrice += $priceForProduct;

                    $product->quantity -= $productData['quantity'];
                    $product->save();

                    // اضافة الكمية إلى اجمالي عدد القطع في الاوردر
                    $all_order_quantity += $productData['quantity'];
                }

                // التحقق من كود الخصم
                if ($request->filled('promo_code')) {
                    $promoCode = PromoCode::where('code', $order->promocode->code)
                        ->where('active', 1)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if (!$promoCode) {
                        throw new \Exception('كود الخصم غير صالح أو منتهي الصلاحية.');
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

                // حساب خصم ال vip
                if($request->user_id){
                    $vip_discount = $this->calculateDiscount($user, $totalPrice);
                }else{
                    $vip_discount = 0;
                }

                // شرط الحد الأدنى لإجمالي الطلب إذا كان العميل جملة
                $minProductsPrice = Setting::getValue('goomla_min_prices');

                // شرط للحد الأدنى للكمية إذا كان العميل جملة
                $minQuantity = Setting::getValue('goomla_min_number');

                if($request->user_id){
                    if ($user?->customer_type == 'goomla') {
                        if ($all_order_quantity < $minQuantity && $totalPrice < $minProductsPrice) {
                            throw new \Exception("يجب أن يكون عدد القطع على الأقل " . $minQuantity . " أو يكون إجمالي السعر على الأقل " . $minProductsPrice . " لعميل الجملة.");
                        }
                    }
                }

                $order->total_price = $totalPrice;
                $order->vip_discount = $vip_discount;
                $order->promo_discount = $promoDiscount;
                $order->total_after_discount = $totalPrice - $vip_discount - $promoDiscount;
                $order->save();

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الطلب بنجاح'
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Order $order)
    {
        $order->load('orderDetails', 'user');
        return view('admin.orders.show', compact('order'));
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
            $product = $orderDetail->product;
            $product->quantity += $orderDetail->product_quantity;
            $product->save();
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

        if(!$user_id){
            return response()->json(['error' => 'كوبونات الخصم للأعضاء فقط'], 400);
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



}


