<?php

namespace App\Http\Controllers;

use App\Model\DeleveryCharge;
use App\Model\DeleveryZone;
use App\Model\LandingPage;
use App\Model\LandingPageFaq;
use App\Model\LandingPageReview;
use App\Model\LandingPageSection;
use App\Model\PageSetup;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use App\CPU\ImageManager;


class LandingPageController extends Controller
{
    public function listLandingPage()
    {
        $pages = LandingPage::latest()->get();
        return view('admin-views.landing-page.lists', [
            'pages' => $pages
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        LandingPage::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
        Toastr::success('Added successfully!');
        return back();

    }

    public function setupLandingPage($id)
    {
        $deleveryOptions = DeleveryZone::all();
        $landingPageData = PageSetup::where('landing_page_id', $id)->with('sections', 'faqs')->first();
        // return $landingPageData;
        return view('admin-views.landing-page.setup', [
            'deleveryOptions' => $deleveryOptions,
            'landing_page_id' => $id,
            'data' => $landingPageData
        ]);
    }

    public function setupStore(Request $request)
    {

        // return $request->all();
        // foreach ($request->section as $section) {
        //     return ImageManager::upload('landing-page/', 'png', $section['banner']);
        // }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'section.*.title' => 'required|string|max:255',
            'section.*.button_text' => 'string|max:255',
            'section.*.button_link' => 'max:255',
            'section.*.banner' => 'mimes:jpg,png,jpeg,gif,svg',
            'customer_review.*' => 'mimes:jpg,png,jpeg,gif,svg'
        ]);


        $page = PageSetup::updateOrCreate(['landing_page_id' => $request->landing_page_id], [
            'title' => $request->title,
            'description' => $request->description,
            'offer_time' => $request->offer_time,
            'product_id' => $request->product_id,
            'delevery_id' => $request->delevery_option,
            'landing_page_id' => $request->landing_page_id
        ]);
        if (is_array($request->section)) {

            LandingPageSection::where('page_id', $page->id)->delete();

            foreach ($request->section as $section) {
                if (isset($section['banner'])) {
                    $image = ImageManager::upload('landing-page/', 'png', $section['banner']);
                } else {
                    $image = null;
                }
                LandingPageSection::create([
                    'page_id' => $page->id,
                    'title' => $section['title'],
                    'description' => $section['description'],
                    'button_text' => $section['button_text'],
                    'button_link' => $section['button_link'],
                    'banner' => $image
                ]);
            }
        }

        if ($request->hasFile('customer_review.*')) {
            LandingPageReview::where('page_id', $page->id)->delete();
            foreach ($request->customer_review as $banner) {
                $image = ImageManager::upload('landing-page/', 'png', $banner);
                LandingPageReview::create([
                    'page_id' => $page->id,
                    'banner' => $image
                ]);
            }
        }

        if (is_array($request->faq)) {
            LandingPageFaq::where('page_id', $page->id)->delete();
            foreach ($request->faq as $faq) {

                LandingPageFaq::create([
                    'page_id' => $page->id,
                    'question' => $faq['question'],
                    'answare' => $faq['answare']
                ]);
            }
        }



        Toastr::success('Added successfully!');
        return back();
    }














    // Delevery Zone

    public function zoneAll()
    {
        $zones = DeleveryZone::all();
        return view('admin-views.landing-page.deleveryZone', [
            'zones' => $zones
        ]);
    }

    public function zoneStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        DeleveryZone::create([
            'name' => $request->name
        ]);

        Toastr::success('Added successfully!');
        return back();

    }


    public function zoneEdit($id)
    {
        $deleveryCharges = DeleveryCharge::latest()->get();
        return view('admin-views.landing-page.editdelevery-zone', [
            'deleveryCharges' => $deleveryCharges,
            'id' => $id
        ]);
    }


    public function zoneDelete(Request $request)
    {
        DeleveryZone::findOrFail($request->id)->delete();
    }

    public function deleveryChargeEdit($id)
    {
        $charge = DeleveryCharge::findOrFail($id);
        return view('admin-views.landing-page.edit-delevery-charge-edit', [
            'charge' => $charge
        ]);
    }

    public function deleveryChargeUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required'
        ]);

        DeleveryCharge::updateOrCreate(['id' => $request->id], [
            'name' => $request->name,
            'charge' => $request->value
        ]);

        Toastr::success('Updated successfully!');
        return back();
    }

    public function deleveryChargeDelete(Request $request)
    {
        DeleveryCharge::findOrFail($request->id)->delete();

        Toastr::success('Deleted successfully!');
        return back();
    }

    public function deleveryChargeStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'zone_id' => 'required',
            'value' => 'required'
        ]);

        DeleveryCharge::create([
            'name' => $request->name,
            'charge' => $request->value
        ]);
        Toastr::success('Save successfully!');
        return back();

    }


    // Landing Page view

    public function landingPage($slug)
    {
        $page = LandingPage::where('slug', $slug)->first();
        if (!$page) {
            return abort(404);
        }

        $pageInfo = PageSetup::where('landing_page_id', $page->id)->with('product', 'delivery', 'customer_reviews', 'delivery')->first();
        return Response()->json($pageInfo);
        return view('admin-views.landing-page.view', [
            'pageInfo' => $pageInfo
        ]);
    }


}
