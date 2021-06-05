<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Place;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;

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
        $menu = Menu::where('place_id', $placeId)->firstOrFail();
        $categories = Category::where("menu_id", $menu->id)->get()->pluck('id');
        $subcategories = Subcategory::whereIn('category_id', $categories)->get()->pluck('id');
        $products = Product::whereIn("subcategory_id", $subcategories)->get();

        return $this->success($products);
    }

    public function getCategories(Request $request, $menuId)
    {
        $categories = Category::select('id', 'menu_id', 'name')->where("menu_id", $menuId)->with(['subcategories' => function ($query) {
            $query->select('id', 'category_id', 'name');
        }])->orderBy('lft')->active()->get();

        return $this->success($categories);
    }
}
