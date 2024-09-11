<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;

// تأكد من استيراد موديل المستخدمين

class RevertVipCustomers extends Command
{
    // اسم الأمر
    protected $signature = 'customers:update-status';

    // وصف الأمر
    protected $description = 'Update customers status from VIP to regular if their VIP period has expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // جلب العملاء الذين انتهت فترة الـ VIP الخاصة بهم
        $expiredVipCustomers = User::where('customer_type', 'vip')
            ->where('vip_end_date', '<', now())
            ->get();

        foreach ($expiredVipCustomers as $customer) {
            $customer->update([
                'customer_type' => 'normal', // تحويل الحالة إلى عميل عادي
                'discount' => 0, // إزالة أي خصم خاص
                'vip_start_date' => null,
                'vip_end_date' => null,
            ]);
        }

        $this->info('تم تحديث حالة العملاء المنتهية فترة VIP الخاصة بهم.');
    }
}
