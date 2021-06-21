<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Product;
use App\Models\Subcategory;

class ProductCrudController extends DefaultCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup()
    {
        parent::setup();
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'subcategory_id',
                'label' => trans('admin.subcategory'),
                'type' => 'select',
                'entity' => 'subcategory',
                'attribute' => 'name',
                'model' => Subcategory::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'image',
                'label' => trans('admin.image'),
                'type' => 'image',
                'prefix' => 'uploads/',
                'width' => '150px',
                'height' => '150px',
            ],
            [
                'name' => 'description',
                'label' => trans('admin.description'),
                'limit' => 30,
            ],
            [
                'name' => 'price',
                'label' => trans('admin.price'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Product::getStatusOptions(),
            ],
        ]);

        parent::setupListOperation();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::addFields([
            [
                'name' => 'image',
                'label' => trans('admin.image'),
                'type' => 'image',
                'disk' => 'uploads',
                'crop' => true,
                'aspect_ratio' => 0,
            ],
            [
                'name' => 'subcategory_id',
                'label' => trans('admin.subcategory'),
                'type' => 'select2',
                'entity' => 'subcategory',
                'attribute' => 'name',
                'model' => Subcategory::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
                'type' => 'text',
            ],
            [
                'name' => 'description',
                'label' => trans('admin.description'),
                'type' => 'textarea',
            ],
            [
                'name' => 'options',
                'label' => trans('admin.options'),
                'type' => 'repeatable',
                'fields' => [
                    [
                        'name' => 'name',
                        'label' => trans('admin.name'),
                        'type' => 'text',
                        'wrapper' => [
                            'class' => 'form-group col-sm-6',
                        ],
                    ],
                    [
                        'name' => 'price',
                        'label' => trans('admin.price'),
                        'type' => 'number',
                        'wrapper' => [
                            'class' => 'form-group col-sm-6',
                        ],
                    ]
                ],
            ],
            [
                'name' => 'price',
                'label' => trans('admin.price'),
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'suffix'     => " тг",
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'quantity',
                'label' => trans('admin.quantity'),
                'type' => 'number',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'cooking_time',
                'label' => trans('admin.cooking_time'),
                'type' => 'number',
                'suffix'     => " мин",
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'calories',
                'label' => trans('admin.calories'),
                'type' => 'number',
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'weight',
                'label' => trans('admin.weight'),
                'type' => 'number',
                'suffix'     => " грамм",
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Product::getStatusOptions(),
            ],
        ]);
    }

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        $this->crud->set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 1);
    }
}
