<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageStaticController extends Controller
{
    public function about()
    {
        $metaSeo           = [
            'meta_title' => '運営会社情報'
        ];
        $viewData          = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
        ];
        return view('pages.static.about')->with($viewData);
    }

    public function privacyPolicy()
    {
        $metaSeo           = [
            'meta_title' => 'プライバシーポリシー'
        ];
        $viewData          = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
        ];
        return view('pages.static.privacy_policy')->with($viewData);
    }

    public function transactionLaw()
    {
        $metaSeo           = [
            'meta_title' => '特定商取引法に基づく表記'
        ];
        $viewData          = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
        ];
        return view('pages.static.transaction_law')->with($viewData);
    }

    public function termsOfService()
    {
        $metaSeo           = [
            'meta_title' => '利用規約'
        ];
        $viewData          = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
        ];
        return view('pages.static.terms_of_service')->with($viewData);
    }

    public function labelConvention()
    {
        $metaSeo           = [
            'meta_title' => '特定商取引法に基づく表記'
        ];
        $viewData          = [
            'meta_seo' => MetaSeoRender::setMetaSeo($metaSeo),
        ];
        return view('pages.static.label_convention')->with($viewData);
    }
}
