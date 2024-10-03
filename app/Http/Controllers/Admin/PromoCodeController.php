<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\PromoCode;


class PromoCodeController extends Controller
{

    public function index()
    {
        $promoCodes = PromoCode::paginate(10);
        return view('admin.promo_codes.index', compact('promoCodes'));
    }

    public function show($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return view('admin.promo_codes.show', compact('promoCode'));
    }

    public function create()
    {
        return view('admin.promo_codes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'min_amount' => 'numeric|min:0',
            'active' => 'nullable|boolean',
        ]);

        PromoCode::create($validated);

        return redirect()->route('admin.promo-codes.index')->with('success', 'تم إضافة البرومو كود بنجاح.');
    }
    public function edit($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return view('admin.promo_codes.edit', compact('promoCode'));
    }

    public function update(Request $request, $id)
    {

        $promoCode = PromoCode::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code,' . $promoCode->id,
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'min_amount' => 'min:0|numeric',
            'active' => 'nullable|in:0,1',
        ]);

        $promoCode->update($validated);

        return redirect()->route('admin.promo-codes.index')->with('success', 'تم تحديث البرومو كود بنجاح.');
    }



    public function applyPromoCode(Request $request)
    {
        $promoCode = PromoCode::where('code', $request->input('promo_code'))->first();

        if (!$promoCode) {
            return response()->json(['error' => 'Invalid promo code'], 400);
        }

        $orderTotal = $request->input('order_total');

        // تحقق من أن إجمالي مبلغ الطلب يساوي أو يتجاوز الحد الأدنى
        if ($orderTotal < $promoCode->min_amount) {
            return response()->json(['error' => 'The order total does not meet the minimum amount for this promo code'], 400);
        }

        // حساب قيمة الخصم بناءً على نوع الخصم
        $discount = 0;
        if ($promoCode->discount_type === 'percentage') {
            $discount = ($promoCode->discount / 100) * $orderTotal;
        } elseif ($promoCode->discount_type === 'fixed') {
            $discount = $promoCode->discount;
        }

        // تطبيق الخصم على الطلب
        $finalTotal = $orderTotal - $discount;

        return response()->json([
            'success' => true,
            'final_total' => $finalTotal,
            'discount' => $discount,
        ]);
    }

    public function destroy(PromoCode $promoCode) {
        $promoCode->delete();

        return redirect()->route('admin.promo-codes.index')->with('success', 'تم حذف البرومو بنجاح');
    }

}
