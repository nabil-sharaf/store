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
        Prefix::create($request->validated());
        return redirect()->route('admin.prefixes.index')->with('success', 'تمت إضافة البريفكس بنجاح.');
    }

    public function edit(Prefix $prefix)
    {
        return view('admin.prefixes.edit', compact('prefix'));
    }

    public function update(PrefixRequest $request, Prefix $prefix)
    {
        $prefix->update($request->validated());
        return redirect()->route('admin.prefixes.index')->with('success', 'تم تعديل البريفكس بنجاح.');
    }

    public function destroy(Prefix $prefix)
    {
        $prefix->delete();
        return redirect()->route('admin.prefixes.index')->with('success', 'تم حذف البريفكس بنجاح.');
    }
}
