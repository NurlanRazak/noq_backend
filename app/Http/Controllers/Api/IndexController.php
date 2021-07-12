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
use App\Models\Table;

class IndexController extends Controller
{
    use ApiResponser;

    public function getPlaces(Request $request)
    {
        if ($request->place_id) {
            $place = Place::findOrFail($request->place_id);

            return $this->success($place);
        }

        $places = Place::select('id', 'name', 'image', 'status')->with('menus', 'tables')->active()->get();

        return $this->success($places);
    }

    public function getMenu(Request $request, $placeId)
    {
        $menu = Menu::where('place_id', $placeId)->firstOrFail();
        $categories = Category::where("menu_id", $menu->id)->get()->pluck('id');
        if ($request->category_id) {
            $categories = Category::where("menu_id", $menu->id)->where('id', $request->category_id)->get()->pluck('id');
        }
        $subcategories = Subcategory::whereHas('categories', function ($query) use ($categories) {
            $query->where('category_id', $categories);
        })->get()->pluck('id');
        $products = Product::whereIn("subcategory_id", $subcategories)->get();

        return $this->success($products);
    }

    public function getCategories(Request $request, $menuId)
    {
        $categories = Category::select('id', 'menu_id', 'name')->where("menu_id", $menuId)->with(['subcategories' => function ($query) {
            $query->select('subcategories.id', 'subcategories.category_id', 'subcategories.name');
        }])->orderBy('lft')->active()->get();

        return $this->success($categories);
    }

    public function getTables(Request $request)
    {
        $tables = Table::with('place')->get();

        return $this->success($tables);
    }
}
