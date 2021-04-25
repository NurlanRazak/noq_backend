<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Menu;
use App\Models\Category;

class CategoryCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'menu_id',
                'label' => trans('admin.menu'),
                'type' => 'select',
                'entity' => 'menu',
                'attribute' => 'name',
                'model' => Menu::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Category::getStatusOptions(),
            ],
        ]);

        parent::setupListOperation();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        CRUD::addFields([
            [
                'name' => 'menu_id',
                'label' => trans('admin.menu'),
                'type' => 'select2',
                'entity' => 'menu',
                'attribute' => 'name',
                'model' => Menu::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Category::getStatusOptions(),
            ],
        ]);
    }
}
