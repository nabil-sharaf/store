<?php

namespace App\Observers;

use App\Models\Admin\SiteImage;

class SiteImageObserver
{
    public function saved(SiteImage $siteImage)
    {
        cache()->forget('siteImages');
    }

    public function deleted(SiteImage $siteImage)
    {
        cache()->forget('siteImages');
    }
}
