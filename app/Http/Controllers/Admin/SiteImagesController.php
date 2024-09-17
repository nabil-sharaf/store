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
        $siteImages = SiteImage::first();
        if (!$siteImages) {
            $siteImages = new SiteImage();
        }

        // Validation
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'slider_image' => 'nullable|image|max:2048',
            'offer_one' => 'nullable|image|max:2048',
            'offer_two' => 'nullable|image|max:2048',
            'footer_image' => 'nullable|image|max:2048',
        ]);

        $updated = false;

        // Handle Logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('site', $logoName, 'public');

            if ($logoPath) {
                if ($siteImages?->logo && Storage::disk('public')->exists($siteImages->logo)) {
                    Storage::disk('public')->delete($siteImages->logo);
                }
                $siteImages->logo = $logoPath;
                $updated = true;
            }
        }

        // Handle Slider Image
        if ($request->hasFile('slider_image')) {
            $sliderImage = $request->file('slider_image');
            $sliderImageName = 'slider_' . time() . '.' . $sliderImage->getClientOriginalExtension();
            $sliderImagePath = $sliderImage->storeAs('site', $sliderImageName, 'public');

            if ($sliderImagePath) {
                if ($siteImages?->slider_image && Storage::disk('public')->exists($siteImages->slider_image)) {
                    Storage::disk('public')->delete($siteImages->slider_image);
                }
                $siteImages->slider_image = $sliderImagePath;
                $updated = true;
            }
        }

        // Handle Offer One
        if ($request->hasFile('offer_one')) {
            $offerOneImage = $request->file('offer_one');
            $offerOneImageName = 'offer_one_' . time() . '.' . $offerOneImage->getClientOriginalExtension();
            $offerOneImagePath = $offerOneImage->storeAs('site', $offerOneImageName, 'public');

            if ($offerOneImagePath) {
                if ($siteImages?->offer_one && Storage::disk('public')->exists($siteImages->offer_one)) {
                    Storage::disk('public')->delete($siteImages->offer_one);
                }
                $siteImages->offer_one = $offerOneImagePath;
                $updated = true;
            }
        }

        // Handle Offer Two
        if ($request->hasFile('offer_two')) {
            $offerTwoImage = $request->file('offer_two');
            $offerTwoImageName = 'offer_two_' . time() . '.' . $offerTwoImage->getClientOriginalExtension();
            $offerTwoImagePath = $offerTwoImage->storeAs('site', $offerTwoImageName, 'public');

            if ($offerTwoImagePath) {
                if ($siteImages?->offer_two && Storage::disk('public')->exists($siteImages->offer_two)) {
                    Storage::disk('public')->delete($siteImages->offer_two);
                }
                $siteImages->offer_two = $offerTwoImagePath;
                $updated = true;
            }
        }

        // Handle Footer Image
        if ($request->hasFile('footer_image')) {
            $footerImage = $request->file('footer_image');
            $footerImageName = 'footer_' . time() . '.' . $footerImage->getClientOriginalExtension();
            $footerImagePath = $footerImage->storeAs('site', $footerImageName, 'public');

            if ($footerImagePath) {
                if ($siteImages?->footer_image && Storage::disk('public')->exists($siteImages->footer_image)) {
                    Storage::disk('public')->delete($siteImages->footer_image);
                }
                $siteImages->footer_image = $footerImagePath;
                $updated = true;
            }
        }

        if ($updated) {
            // Save the updated paths
            $siteImages->save();
            return redirect()->back()->with('success', 'تم تحديث الصور بنجاح');
        } else {
            return redirect()->back()->with('error', 'لم يتم تحديث أي صورة');
        }
    }
}
