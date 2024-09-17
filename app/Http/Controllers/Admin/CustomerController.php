<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Admin\UserAddress;
use Illuminate\Http\Request;
use App\Models\User;
class CustomerController extends Controller
{


    public function index()
    {
        $users = User::paginate(10);
        return view('admin.customers.index',compact('users'));
    }

    public function show(User $user)
    {
        $totalSpending = $user->orders?->sum('total_after_discount'); // مثال لحساب الانفاق الكلي
        $lastMonthSpending = $user->orders?->whereMonth('created_at', now()->subMonth()->month)->sum('total_after_discount'); // مثال لحساب انفاق آخر شهر

        return view('admin.customers.show', compact('user','totalSpending','lastMonthSpending'));

    }

    public function edit(User $user){

    }

    public function update(CustomerRequest $request ,User $user)
    {
       $updated= $user->update([
            'customer_type'=>$request->customer_type,
            'name'=>$request->name,
            'status'=>$request->status,
        ]);

       if($updated){
         return response()->json([
             'success'=>'تم تحديث البيانات بنجاح',
             'is_vip'=>$user->is_vip,
             'userType'=>$user->customer_type,
             'userName'=>$user->name,
             'status'=>$user->status,
         ]);
       }else{
           return response()->json(['error'=>'حدث خطأ اثناء التعديل حاول مرة اخرى']);

       }
    }


    public function vipCustomer(CustomerRequest $request,User $user)
    {
    $update =  $user->update([
        'is_vip' =>1,
        'vip_start_date' =>$request->input('start_date'),
        'vip_end_date'   =>$request->input('end_date'),
        'discount'       =>$request->input('discount'),
     ]);


    if($update){

        return response()->json(['success'=>'تم تمييز السمتخدم عميل vip']);
    }
        return response()->json(['error'=>'حدث خطأ اثناء التعديل']);

    }

    public function vipDisable( User $user){

        $user->update([
            'is_vip' => 0,
            'vip_start_date'=>null,
            'vip_end_date'=>null,
            'discount'=>null,

        ]);

        return response()->json(['success'=> 'تم إلغاء تفعيل ميزة VIP بنجاح']);
    }
    public function vipAll(CustomerRequest $request)
    {
     $ids = $request->ids;

        $updated = User::whereIn('id', $ids)
                   ->update([
                       'is_vip'=>1,
                       'vip_start_date'=>$request->start_date,
                       'vip_end_date' =>$request->end_date,
                       'discount' =>$request->discount,

                   ]);

        if ($updated) {
            return response()->json(['success' => 'تم تمييز المستخدمين المحددين vip']);
        }

        return response()->json(['error' => 'حدث خطأ أثناء التعديل تأكد من قيمة الخصم والتواريخ'], 500);
    }

    public function changeStatus(User $user)
    {
        // تبديل حالة المستخدم من 0 إلى 1 أو من 1 إلى 0
        $user->status = $user->status ? 0 : 1;
        $user->save();

        // إعادة الاستجابة بالنجاح
        return response()->json([
            'message' => $user->status ? 'تم تفعيل المستخدم بنجاح' : 'تم تعطيل المستخدم بنجاح',
            'is_active' => $user->status,
        ]);
    }

    public function getUserAddress($id)
    {
        // جلب العنوان للمستخدم بناءً على الـ id
        $address = UserAddress::where('user_id', $id)->first();

        if ($address) {
            return response()->json([
                'success' => true,
                'address' => $address
            ]);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
}

