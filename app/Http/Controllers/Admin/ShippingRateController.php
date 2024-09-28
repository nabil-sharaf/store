<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    // عرض صفحة المحافظات وأسعار الشحن
    public function index()
    {
        $shippingRates = ShippingRate::all();
        return view('admin.shipping.index', compact('shippingRates'));
    }

    // إضافة محافظة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'state' => 'required|string|max:255|unique:shipping_rates,state',
            'shipping_cost' => 'required|numeric|min:0',
        ], [
                'state.required' => 'يرجى إدخال اسم المحافظة.',
                'state.unique' => '  اسم المحافظة موجود مسبقا.',
                'state.string' => 'يجب أن يكون اسم المحافظة نصًا.',
                'state.max' => 'اسم المحافظة لا يمكن أن يتجاوز 255 حرفًا.',
                'shipping_cost.required' => 'يرجى إدخال تكلفة الشحن.',
                'shipping_cost.numeric' => 'يجب أن تكون تكلفة الشحن رقمًا.',
                'shipping_cost.min' => 'يجب أن تكون تكلفة الشحن أكبر من أو تساوي 0.',
            ]
        );

        ShippingRate::create([
            'state' => $request->input('state'),
            'shipping_cost' => $request->input('shipping_cost'),
        ]);

        return redirect()->back()->with('success', 'تمت إضافة المحافظة بنجاح');
    }



    // تحديث سعر الشحن لمحافظة معينة
    public function update(Request $request, $id)
    {
        $rate = ShippingRate::find($id);
        $rate->update([
            'shipping_cost' => $request->input('shipping_cost'),
        ]);

        return redirect()->back()->with('success', 'تم تحديث تكلفة الشحن بنجاح');
    }

    // حذف محافظة
    public function destroy($id)
    {
        $rate = ShippingRate::findOrFail($id);
        $rate->delete();

        return redirect()->back()->with('success', 'تم حذف المحافظة بنجاح');
    }


}
