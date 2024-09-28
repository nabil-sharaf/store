<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Admin\Order;
use App\Models\Admin\ShippingRate;
use App\Models\Front\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $id = \auth()->user()->id;
        $states = ShippingRate::get();
        $address = UserAddress::where('user_id',$id)->first();
        $orders = Order::where('user_id',$id)->get();
        return \view('front.profile',compact('address','orders','states'));
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        // تحديث بيانات المستخدم
        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');

        // تحديث كلمة المرور إذا تم إدخال كلمة مرور جديدة
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->input('new_password'));
        }

        $user->save();

        return redirect()->back()->with('success', __('profile.update_success'));
    }
    /**
     * Delete the user's account.
     */
    public function updateAddress(Request $request)
    {
        // قم بإجراء التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(01)[0-9]{9}$/'], // تحقق من أن الجوال يبدأ بـ 01 ومكون من 11 رقمًا
        ]);

        // إذا كانت البيانات غير صحيحة، قم بإعادة التوجيه مع الأخطاء
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator, 'addressErrors');
        }
        // احصل على المستخدم الحالي
        $user = auth()->user();

        // حاول الحصول على العنوان الحالي للمستخدم
        $address = $user->address; // نفترض أن العلاقة بين المستخدم والعنوان هي address

        if ($address) {
            // إذا كان العنوان موجودًا، قم بتحديثه
            $address->update([
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'full_name'=>$request->full_name,
                'phone'=>$request->phone,

            ]);
        } else {
            // إذا لم يكن العنوان موجودًا، قم بإنشائه
            $user->address()->create([
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'full_name'=>$request->full_name,
                'phone'=>$request->phone
            ]);
        }

        // قم بإعادة التوجيه مع رسالة النجاح
        return redirect()->back()->with('success', __('profile.address_updated'));
    }
}
