<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OfferRequest;
use App\Models\Admin\Offer;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::paginate(20);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::with('offers')->get(); // استيراد جميع المنتجات
        return view('admin.offers.create',compact('products'));
    }

    public function store(OfferRequest $request)
    {
        try{
            Offer::create($request->all());
            return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()
               ->with('error', $e->getMessage())
                ->withInput();
        }

    }

    public function edit(Offer $offer)
    {
        $products = Product::with('offers')->get(); // استيراد جميع المنتجات
        return view('admin.offers.edit', compact('offer','products'));
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        $offer->update($request->all());
        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }
}
