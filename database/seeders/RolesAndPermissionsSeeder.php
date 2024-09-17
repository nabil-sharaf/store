<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إنشاء الأذونات مع الحارس المناسب
        $permissions = [
            'make_orders',
            'make_discounts',
            'edit_sections',
            'show_reports',
            'add_products'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // إنشاء الأدوار مع الحارس المناسب
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'superAdmin', 'guard_name' => 'admin']);
        $roleSupervisor = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'admin']);
        $roleEditor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'admin']);

        // إعطاء صلاحيات للأدوار
        $roleSuperAdmin->givePermissionTo(Permission::where('guard_name', 'admin')->get()); // جميع الأذونات
        $roleSupervisor->givePermissionTo(['make_orders', 'add_products']); // صلاحيات محددة لدور supervisor
        $roleEditor->givePermissionTo(['make_orders']); // صلاحيات محددة لدور editor

        // تعيين الأدوار لمستخدمين معينين
        $user = Admin::find(1); // افتراضياً المستخدم الأول
        if ($user) {
            $user->assignRole('superAdmin');
        }
    }
}
