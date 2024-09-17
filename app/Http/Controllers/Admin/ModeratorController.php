<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ModeratorController extends Controller
{
   public function index(){

       // جلب المشرفين مع كل الأدوار المرتبطة بهم وتفعيل الـ Pagination
       $moderators = Admin::with('roles') // لجلب كل الأدوار المرتبطة بكل مشرف
       ->paginate(10);

       return view('admin.moderators.index',compact('moderators'));

   }

    public function show($id)
    {
        // البحث عن المشرف باستخدام ID
        $admin = Admin::with('roles')->findOrFail($id);

        return view('admin.moderators.show', compact('admin'));
    }

   public function create(){
       $roles = Role::all();
       return view('admin.moderators.add',compact('roles'));

   }
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,name',
        ], [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصًا.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'role_id.required' => 'يجب اختيار الرول.',
            'role_id.exists' => 'الرول المحددة غير صالحة.',
        ]);

        // 2. إنشاء المشرف الجديد
        $supervisor = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. تعيين الرول للمشرف
        $role = Role::where('name',$request->role_id)->first();
        $supervisor->assignRole($role->name);

        // 4. إعادة توجيه مع رسالة نجاح
        return redirect()->route('admin.moderators.index')->with('success', 'تم إضافة المشرف بنجاح.');
    }

    public function edit($id)
    {
        $moderator = Admin::findOrFail($id); // جلب المشرف باستخدام الـ ID
        $roles = Role::all(); // جلب جميع الرول المتاحة
        return view('admin.moderators.edit', compact('moderator', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // 1. التحقق من صحة البيانات
        $request->validate([
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:8|confirmed', // كلمة المرور ليست مطلوبة للتحديث
            'role_id' => 'required|exists:roles,name',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'role_id.required' => 'يجب اختيار الرول.',
            'role_id.exists' => 'الرول المحددة غير صالحة.',
        ]);

        // 2. جلب المشرف المطلوب
        $moderator = Admin::findOrFail($id);

        // 3. تحديث البريد الإلكتروني
        $moderator->email = $request->email;

        // 4. إذا تم إدخال كلمة مرور جديدة، نقوم بتحديثها
        if ($request->filled('password')) {
            $moderator->password = Hash::make($request->password);
        }

        // 5. حفظ التغييرات
        $moderator->save();

        // 6. تحديث الرول
        $moderator->syncRoles([$request->role_id]);

        // 7. إعادة توجيه مع رسالة نجاح
        return redirect()->route('admin.moderators.index')->with('success', 'تم تحديث بيانات المشرف بنجاح.');
    }

    public function destroy($id)
    {
        try {
            // البحث عن المشرف
            $admin = Admin::findOrFail($id);

            // تنفيذ الحذف
            $admin->delete();

            // إعادة توجيه مع رسالة نجاح
            return redirect()->route('admin.moderators.index')
                ->with('success', 'تم حذف المشرف بنجاح');
        } catch (\Exception $e) {
            // في حال حدوث خطأ، يمكن التعامل معه هنا
            return redirect()->route('admin.moderators.index')
                ->with('error', 'حدث خطأ أثناء محاولة الحذف');
        }
    }


    public function myAccount($id)
    {
        // البحث عن المشرف باستخدام ID
        $admin = Admin::with('roles')->findOrFail($id);

        return view('admin.moderators.account', compact('admin'));
    }

    public function editAccount($id)
    {
        $moderator = Admin::findOrFail($id); // جلب المشرف باستخدام الـ ID
        return view('admin.moderators.edit-account', compact('moderator'));
    }
    public function updateAccount(Request $request, $id)
    {
        // 1. التحقق من صحة البيانات
        $request->validate([
            'password' => 'nullable|min:8|confirmed', // كلمة المرور ليست مطلوبة للتحديث
        ], [
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        // 2. جلب المشرف المطلوب
        $moderator = Admin::findOrFail($id);


        // 4. إذا تم إدخال كلمة مرور جديدة، نقوم بتحديثها
        if ($request->filled('password')) {
            $moderator->password = Hash::make($request->password);
        }

        // 5. حفظ التغييرات
        $moderator->save();


        // 7. إعادة توجيه مع رسالة نجاح
        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث البيانات بنجاح.');
    }



}
