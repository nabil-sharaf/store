<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Setting;
class SettingContoller extends Controller
{
    public function edit()
    {
        $settings = Setting::all();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        // التعامل مع روابط السوشيال ميديا
        $socialKeys = ['social_link', 'social_link_2'];

        foreach($socialKeys as $socialKey) {
            if ($request->has('social_type_' . $socialKey)) {
                $updateData = [
                    'setting_value' => $request->$socialKey ?: null,
                    'social_type' => $request->input('social_type_' . $socialKey) ?: null
                ];

                Setting::where('setting_key', $socialKey)
                    ->update($updateData);
            }
        }

        // تحديث باقي الإعدادات
        foreach ($data as $key => $value) {
            if (!in_array($key, $socialKeys) && !str_starts_with($key, 'social_type_')) {
                Setting::where('setting_key', $key)->update(['setting_value' => $value]);
            }
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }


}
