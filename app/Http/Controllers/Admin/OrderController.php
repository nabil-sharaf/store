<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index(Request $request)
    {
            $query = Order::query();

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status_id',$request->status);
        }

        if ($request->has('sort')) {
            $query->orderBy($request->sort, 'asc');
        }

        $orders =$query->with('orderDetails', 'user')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(OrderRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $user = User::findOrFail($request->user_id);
                $order = new Order();
                $order->user_id = $user->id;
                $order->total_price = 0;
                $order->discount = 0;
                $order->total_after_discount = 0;
                $order->save();

                $totalPrice = 0;
                foreach ($request->products as $productData) {
                    $product = Product::find($productData['id']);
                    if ($product->quantity < $productData['quantity']) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة في مخزون: ' . $product->name);
                    }
                    if ($productData['quantity'] < 1) {
                        throw new \Exception('الكمية لابد ان تكون واحد على الأقل ');
                    }

                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $productData['id'];
                    $orderDetail->Product_quantity = $productData['quantity'];
                    $orderDetail->price = $product->price;
                    $orderDetail->save();

                    $totalPrice += $productData['quantity'] * $product->price;

                    $product->quantity -= $productData['quantity'];
                    $product->save();
                }

                // حساب الخصم
                $discount = $this->calculateDiscount($user, $totalPrice);
                $discountedTotal = $totalPrice - $discount;

                $order->total_price = $totalPrice;
                $order->discount = $discount;
                $order->total_after_discount = $discountedTotal;
                $order->save();

                return redirect()->route('admin.orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function calculateDiscount(User $user, $totalPrice)
    {
        switch($user->customer_type) {
            case 'vip':
                // تحقق من صلاحية فترة VIP
                if ($user->vip_start_date<=now() && $user->vip_end_date >= now() ) {
                    return $totalPrice * ($user->discount / 100);
                }
                // إذا انتهت فترة VIP، قم بتحديث نوع العميل
                $user->customer_type = 'normal';
                $user->save();
                return 0;
            case 'goomla':
                return $totalPrice * ($user->discount / 100);
            default:
                return 0;
        }
    }

    public function edit(Order $order)
    {
        if($order->status->id==1){ // تعديل الطلب في حالة اذا لم  يتم شحنه

        $users = User::all();
        $products = Product::all();
        $order->load('orderDetails');
        return view('admin.orders.edit', compact('order', 'users', 'products'));
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
                // Restore previous quantities
                foreach ($order->orderDetails as $orderDetail) {
                    $product = $orderDetail->product;
                    $product->quantity += $orderDetail->product_quantity;
                    $product->save();
                }

                $order->user_id = $request->user_id;
                $order->orderDetails()->delete();
                $totalPrice = 0;

                foreach ($request->products as $productData) {
                    $product = Product::findOrFail($productData['id']);
                    if ($product->quantity < $productData['quantity']) {
                        throw new \Exception('الكمية المطلوبة غير متوفرة للمنتج: ' . $product->name);
                    }

                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $productData['id'];
                    $orderDetail->Product_quantity = $productData['quantity'];
                    $orderDetail->price = $product->price;
                    $orderDetail->save();

                    $totalPrice += $productData['quantity'] * $product->price;

                    $product->quantity -= $productData['quantity'];
                    $product->save();
                }

                // حساب الخصم
                $user = User::findOrFail($request->user_id);
                $discount = $this->calculateDiscount($user, $totalPrice);
                $discountedTotal = $totalPrice - $discount;

                $order->total_price = $totalPrice;
                $order->discount = $discount;
                $order->total_after_discount = $discountedTotal;
                $order->save();

                return redirect()->route('admin.orders.index')->with('success', 'تم تحديث الطلب بنجاح');
           });
        }  catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
        return response()->json(['success' => 'تم تعديل حالة الطلب بنجاح']);
    }

    // حساب نسبة الخصم عند اضافة اوردر
    public function getUserDiscount(User $user)
    {
        $discount = 0;
        if ($user->customer_type == 'vip' && $user->vip_end_date >= now()) {
            $discount = $user->discount;
        } elseif ($user->customer_type == 'goomla') {
            $discount = $user->discount;
        }

        return response()->json(['discount' => $discount]);
    }
}

