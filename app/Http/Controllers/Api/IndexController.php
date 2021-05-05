<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Place;
use App\Models\Menu;

class IndexController extends Controller
{
    use ApiResponser;

    public function getPlaces(Request $request)
    {
        if ($request->place_id) {
            $place = Place::findOrFail($request->place_id);

            return $this->success($place);
        }

        $places = Place::select('id', 'name', 'image', 'status')->active()->get();

        return $this->success($places);
    }

    public function getMenu(Request $request, $placeId)
    {
        $menu = Menu::where('place_id', $placeId)->with(['categories' => function ($query) {
            $query->with(['subcategories' => function ($query) {
                $query->with('products');
            }]);
        }])->first();

        return $this->success($menu);
    }
}
