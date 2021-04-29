<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubcategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryCrudController extends DefaultCrudController
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
                'name' => 'category_id',
                'label' => trans('admin.category'),
                'type' => 'select',
                'entity' => 'category',
                'attribute' => 'name',
                'model' => Category::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Subcategory::getStatusOptions(),
            ],
        ]);
        parent::setupListOperation();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubcategoryRequest::class);

        CRUD::addFields([
            [
                'name' => 'category_id',
                'label' => trans('admin.category'),
                'type' => 'select2',
                'entity' => 'category',
                'attribute' => 'name',
                'model' => Category::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Subcategory::getStatusOptions(),
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
