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

        foreach ($data as $key => $value) {
            Setting::where('setting_key', $key)->update(['setting_value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }



}
