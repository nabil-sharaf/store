<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class CustomerController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.customers.index',compact('users'));
    }

    public function show()
    {

    }

    public function changeStatus()
    {

    }

    public function vipCustomer(CustomerRequest $request,User $user)
    {
    $update =  $user->update([
        $user->customer_type =>'vip',
        $user->vip_start_date =>$request->start_date,
        $user->vip_end_date =>$request->end_date,
        $user->discount =>$request->discount,
     ]);

    if($update){

        return response()->json(['success'=>'تم تمييز السمتخدم  vip']);
    }
        return response()->json(['error'=>'حدث خطأ اثناء التعديل']);

    }

    public function vipAll()
    {

    }

}

