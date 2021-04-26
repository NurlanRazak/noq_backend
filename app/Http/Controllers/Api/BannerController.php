<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Banner;

class BannerController extends Controller
{
    use ApiResponser;

    public function getBanners(Request $request)
    {
        $banners = Banner::active()->orderBy('lft')->get();

        return $this->success($banners);
    }
}
