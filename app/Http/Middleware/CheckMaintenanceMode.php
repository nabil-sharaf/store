<?php

namespace App\Http\Middleware;

use App\Models\Admin\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // تحقق من حالة وضع الصيانة (من قاعدة البيانات أو ملف .env أو ملف config)
        $maintenanceMode = Setting::getValue('Maintenance_mode'); // يمكن استبدالها بجلب القيمة من قاعدة البيانات

        if ($maintenanceMode == 1) {
            // إذا كان وضع الصيانة مفعلاً، أعرض صفحة الصيانة
            return response()->view('maintenance');
        }

        return $next($request);
    }
}
