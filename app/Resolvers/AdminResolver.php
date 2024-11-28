<?php

namespace App\Resolvers;

use OwenIt\Auditing\Resolvers\UserResolver as BaseUserResolver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminResolver extends BaseUserResolver
{
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function resolve()
    {
        // تعديل حراس المصادقة (guards) بحيث يتم فحص حارس "admin" أولاً
        $guards = Config::get('audit.user.guards', [
            'admin', // الأولوية لحارس الإدمن
            config('auth.defaults.guard') // ثم الحارس الافتراضي
        ]);

        foreach ($guards as $guard) {
            try {
                $authenticated = Auth::guard($guard)->check();
            } catch (\Exception $exception) {
                continue;
            }

            if (true === $authenticated) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
