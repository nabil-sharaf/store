<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    public function index()
    {
        $popup = Popup::first(); // جلب بيانات أول بوب أب
        return view('admin.popup.index', compact('popup'));
    }

    public function update(Request $request)
    {
        // التحقق من المدخلات
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|url',
            'footer_link_text' => 'nullable|string|max:255',
            'footer_link_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // للتحقق من الصورة
            'status'=>'required'
        ]);

        // محاولة الحصول على أول سجل
        $popup = Popup::first();

        // إذا لم يكن موجودًا، يتم إنشاء سجل جديد
        if (!$popup) {
            $popup = new Popup();
        }

        // تحديث بيانات البوب أب
        $popup->title = $validated['title'];
        $popup->text = $validated['text'];
        $popup->button_text = $validated['button_text'];
        $popup->button_link = $validated['button_link'];
        $popup->footer_link_text = $validated['footer_link_text'] ?? null;
        $popup->footer_link_url = $validated['footer_link_url'] ?? null;
        $popup->status = $validated['status'] ?? 0;

        // التحقق من وجود صورة جديدة وتحميلها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($popup->image_path) {
                Storage::disk('public')->delete($popup->image_path);
            }

            // رفع الصورة الجديدة وتخزين المسار
            $imagePath = $request->file('image')->store('popups', 'public');
            $popup->image_path = $imagePath;
        }

        // حفظ التعديلات
        $popup->save();

        return redirect()->back()->with('success', 'تم تحديث الـ pop-up بنجاح');
    }
}
