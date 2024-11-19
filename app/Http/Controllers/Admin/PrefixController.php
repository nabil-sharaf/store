<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PrefixRequest;
use App\Models\Admin\Prefix;
use Illuminate\Http\Request;
class PrefixController extends Controller
{
    public function index()
    {
        $prefixes = Prefix::paginate(get_pagination_count());
        return view('admin.prefixes.index', compact('prefixes'));
    }

    public function create()
    {
        return view('admin.prefixes.create');
    }

    public function store(PrefixRequest $request)
    {
        $data = $request->validated();

        // التأكد من أن الترجمة محفوظة لحقل 'name' باللغتين
        $prefixData = [
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'],
            ],
            'prefix_code' => $data['prefix_code']
        ];

        Prefix::create($prefixData);

        return redirect()->route('admin.prefixes.index')->with('success', 'تمت إضافة البريفكس بنجاح.');
    }

    public function edit(Prefix $prefix)
    {
        return view('admin.prefixes.edit', compact('prefix'));
    }

    public function update(PrefixRequest $request, Prefix $prefix)
    {
        $data = $request->validated();

        $prefixData = [
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'],
            ],
            'prefix_code' => $data['prefix_code']
        ];

        $prefix->update($prefixData);

        return redirect()->route('admin.prefixes.index')->with('success', 'تم تعديل البريفكس بنجاح.');
    }

    public function destroy(Prefix $prefix)
    {
        $prefix->delete();
        return redirect()->route('admin.prefixes.index')->with('success', 'تم حذف البريفكس بنجاح.');
    }
}
