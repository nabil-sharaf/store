<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\Admin\GuestAddress;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Product;
use App\Models\Admin\PromoCode;
use App\Models\Admin\Setting;
use App\Models\Admin\UserAddress;
use App\Models\User;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Cart::getContent();
        $totalQuantity = Cart::getTotalQuantity();
        $totalPrice = Cart::getTotal();
        if(Auth::user() && Auth::user()->isVip()){
            $discount_percentage = (Auth::user()->discount)/100;
            $vipDiscount =  number_format(($totalPrice * $discount_percentage),2);
        }else{
            $vipDiscount = 0;
    }


        return view('front.checkout' ,compact(['items','totalPrice','totalQuantity','vipDiscount']));
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
                    $userAddress =  UserAddress::updateOrCreate(

                        ['user_id' => $user->id],
                        [
                            'full_name' => $request->full_name,
                            'phone'     => $request->phone,
                            'address'   => $request->address,
                            'city'      => $request->city,
                            'state'     => $request->state,
                        ]
                    );
                    $order->user_address_id = $userAddress->id;



                }


                $order->total_price = 0;
                $order->vip_discount = 0;
                $order->promo_discount = 0;
                $order->total_after_discount = 0;
                $order->save();

                $totalPrice = 0;
                $all_order_quantity = 0;

                $items = Cart::getContent();
                $totalProductPrices = Cart::getTotal();

                foreach($items as $item) {
                    $product = Product::find($item['id']);
                    if ($product->quantity < $item['quantity']) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);

                    }

                    if ($item['quantity'] < 1) {
                        throw new \Exception('الكمية لابد ان تكون واحد على الأقل.');
                    }

                    $all_order_quantity += $item['quantity'];

                    $priceAfterDiscount = $product->discounted_price;
                    $priceForProduct = $priceAfterDiscount * $item['quantity'];

                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item['id'];
                    $orderDetail->Product_quantity = $item['quantity'];
                    $orderDetail->price = $priceAfterDiscount;
                    $orderDetail->save();

                    $totalPrice += $priceForProduct;
                    $product->quantity -= $item['quantity'];
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

                if(($totalPrice - $vip_discount - $promoDiscount) < 1){
                    throw new \Exception("تأكد من اجمالي الاوردر قبل عمل اتمام الطلب");

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

                Cart::clear();
                session()->flash('success', 'تم انشاء طلبك بنجاح');
                return response()->json([
                    'success' => true,
                    'route'  => route('home.index'),
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

        public function checkCoupon(Request $request)
        {

            $couponCode = $request->input('promo_code');
            $orderTotal = $request->input('total_order'); // إجمالي الطلب
            $user_id = $request->input('user_id');

            if(!$user_id){
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
                return response()->json(['error' => 'كود الخصم غير صحيح أو غير صالح']);
            }

            // تحقق إذا كان المستخدم قد استخدم الكوبون من قبل
            $couponUsed = DB::table('user_promocode')
                ->where('user_id', $user_id)
                ->where('promo_code_id', $coupon->id)
                ->exists();

            if ($couponUsed) {
                return response()->json(['valid' => false, 'error' => 'لقد قمت باستخدام هذا الكوبون من قبل']);
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
}
