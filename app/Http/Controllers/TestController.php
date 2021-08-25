<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    public function test(Request $request)
    {
        $categories = \DB::connection('production')->table('categories')->get();
        $products = \DB::connection('production')->table('menus')->get();
        $prod_cats = \DB::connection('production')->table('menu_categories')->get();

        $place = \App\Models\Place::create([
            'name' => 'Oblaka Bar',
            'address' => 'Panfilova, 92',
            'phone' => '+77013995034',
            'status' => 1,
        ]);

        foreach ($categories as $category) {
            $subcategory = \App\Models\Subcategory::create([
                'category_id' => 8,
                'name' => $category->name,
                'status' => 1,
            ]);

            \DB::table('category_subcategory')->create([
                'category_id' => 8,
                'subcategory_id' => $subcategory->id,
            ]);

            foreach ($prod_cats as $rel) {
                if ($rel->category_id == $category->category_id) {
                    foreach($products as $product) {
                        if ($product->menu_id == $rel->menu_id) {
                            \App\Models\Product::create([
                                'subcategory_id' => $subcategory->id,
                                'name' => $product->menu_name,
                                'description' => $product->menu_description,
                                'price' => floatval($product->menu_price),
                                'quantity' => $product->stock_qty,
                                'status' => 1,
                            ]);
                        }
                    }
                }
            }
        }
        dd('ok');

        return view('cloudpayments');
    }

}
