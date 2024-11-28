<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteImagesController extends Controller
{
    public function images(Request $request)
    {
        $siteImages = SiteImage::first();
        return view('admin.site_images.index', compact('siteImages'));
    }

    public function updateImages(Request $request)
    {
        $siteImages = SiteImage::first() ?? new SiteImage();

        // Validation
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'slider_image' => 'nullable|image|max:2048',
            'car_icon'=>'nullable|image|max:2048',
            'default_image'=>'nullable|image|max:2048',
            'offer_one' => 'nullable|image|max:2048',
            'offer_two' => 'nullable|image|max:2048',
            'footer_image' => 'nullable|image|max:2048',
            'about_us_image' => 'nullable|image|max:2048',
            'payment_image' => 'nullable|image|max:2048',
            'sponsor_images.*' => 'nullable|image|max:2048',
        ]);

        $images = [
            'logo' => 'logo',
            'slider_image' => 'slider',
            'car_icon'=>'car',
            'offer_one' => 'offer_one',
            'offer_two' => 'offer_two',
            'footer_image' => 'footer',
            'about_us_image' => 'about',
            'payment_image' => 'payment',
            'default_image'=>'Default Image'


        ];

        $updated = false;

        foreach ($images as $inputName => $prefix) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $imageName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('site', $imageName, 'public');

                if ($imagePath) {
                    if ($siteImages?->$inputName && Storage::disk('public')->exists($siteImages->$inputName)) {
                        Storage::disk('public')->delete($siteImages->$inputName);
                    }
                    $siteImages->$inputName = $imagePath;
                    $updated = true;
                }
            }
        }

        // Handle sponsor images as array
        if ($request->hasFile('sponsor_images')) {
            $oldImages = $siteImages->sponsor_images ?? [];
            $sponsorImages = $request->file('sponsor_images');
            $imagePaths = [];

            foreach ($sponsorImages as $image) {
                $imageName = 'sponsor_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('site', $imageName, 'public');
                $imagePaths[] = $imagePath;
            }

            if (count($imagePaths) > 0) {
                $siteImages->sponsor_images = $imagePaths;

                foreach ($oldImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
                $updated = true;
            } else {
                return redirect()->back()->with('error', 'فشل رفع الصور الجديدة');
            }
        }

        if ($updated) {
            $siteImages->save();
            return redirect()->back()->with('success', 'تم تحديث الصور بنجاح');
        } else {
            return redirect()->back()->with('error', 'لم يتم تحديث أي صورة');
        }
    }
}


