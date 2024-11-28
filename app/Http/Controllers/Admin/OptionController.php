<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OptionRequest;
use App\Models\Admin\Option;
use App\Models\Admin\OptionValue;
use Illuminate\Http\Request;


class OptionController extends Controller
{
    public function index()
    {
        $options = Option::with('optionValues')->get();
        return view('admin.options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.options.create');
    }

    public function store(OptionRequest $request)
    {


        $option = Option::create(['name' => $request->name]);

        foreach ($request->values as $value) {
            $option->optionValues()->create(['value' => $value]);
        }

        return redirect()->route('admin.options.index')->with('success', 'تمت إضافة الخيار بنجاح.');
    }

    public function edit(Option $option)
    {
        return view('admin.options.edit', compact('option'));
    }

    public function update(OptionRequest $request, $id)
    {

        $option = Option::findOrFail($id);

        $existingIds = $option->optionValues->pluck('id')->toArray();
        $submittedIds = collect($request->values)->pluck('id')->filter()->toArray();

        // 1. حذف القيم الغير موجودة في الطلب
        $idsToDelete = array_diff($existingIds, $submittedIds);
        OptionValue::whereIn('id', $idsToDelete)->delete();

        // 2. تحديث أو إضافة القيم
        foreach ($request->values as $valueData) {
            if (isset($valueData['id'])) {
                // تعديل
                OptionValue::where('id', $valueData['id'])->update([
                    'value' => [
                        'ar' => $valueData['ar'],
                        'en' => $valueData['en'],
                    ],
                ]);
            } else {
                // إضافة
                $option->optionValues()->create([
                    'value' => [
                        'ar' => $valueData['ar'],
                        'en' => $valueData['en'],
                    ],
                ]);
            }
        }

        return redirect()->route('admin.options.index')->with('success', 'تم تحديث الخيارات بنجاح!');
    }

    public function destroy(Option $option)
    {
        // Check if option has associated values
        if ($option->optionValues()->count() > 0) {
            return redirect()
                ->route('admin.options.index')
                ->with('error', 'لا يمكن حذف الأوبشن لأنه يحتوي على قيم. برجاء حذف القيم أولاً.');
        }

        $option->delete();
        return redirect()
            ->route('admin.options.index')
            ->with('success', 'تم حذف الأوبشن بنجاح.');
    }
    public function destroyOptionVAlue(OptionValue $value)
    {
        $value->delete();
        return back()->with('success', 'تم حذف القيمة بنجاح');    }
    public function updateOptionVAlue(Request $request , OptionValue $value)
    {
        $value->setTranslations('value', $request->value);
        $value->save();
        return back()->with('success', 'تم تعديل القيمة بنجاح');
    }
}
