<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Variant;
use OwenIt\Auditing\Models\Audit;

class AuditLogController extends Controller
{
    public function index()
    {
        // جلب السجلات الأحدث أولاً
        $logs = Audit::with('user')->latest()->paginate(get_pagination_count());

        return view('admin.logs.last_all', compact('logs'));
    }
    public function showProductLogs($productId)
    {
        // جلب سجلات التعديلات الخاصة بالمنتج
        $productLogs = \OwenIt\Auditing\Models\Audit::where('auditable_id', $productId)
            ->where('auditable_type', Product::class)
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب سجلات التعديلات الخاصة بالفاريانتات المرتبطة بالمنتج
        $variantLogs = \OwenIt\Auditing\Models\Audit::whereIn('auditable_id', Variant::where('product_id', $productId)->pluck('id'))
            ->where('auditable_type', Variant::class)
            ->orderBy('created_at', 'desc')
            ->get();

        // دمج السجلات من أجل عرضها في صفحة واحدة
        $logsAll = $productLogs->merge($variantLogs)->sortByDesc('created_at');

        $logs= paginateProducts($logsAll,'25');
        return view('admin.logs.products', compact('logs'));
    }

    public function showVariantLogs($variantId)
    {
        $logs = Audit::where('auditable_id', $variantId)
            ->where('auditable_type', Variant::class)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.logs.variants', compact('logs'));
    }
}
