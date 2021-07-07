<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Menu;
use App\Models\Category;

class CategoryCrudController extends DefaultCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup()
    {
        parent::setup();
        $user = backpack_user();
        if ($user->place_id) {
            $this->crud->addClause("whereHas", "menu", function($query) use($user) {
                $query->where('place_id', $user->place_id);
            });
        }
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
                'type' => 'select_grouped',
                'entity' => 'menu',
                'attribute' => 'name',
                'group_by'  => 'place', // the relationship to entity you want to use for grouping
                'group_by_attribute' => 'name', // the attribute on related model, that you want shown
                'group_by_relationship_back' => 'menus',
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'opening_hours',
                'label' => trans('admin.opening_hours'),
                'type' => 'table',
                // 'entity_singular' => trans('admin.add'),
                'columns' => [
                    'opens' => trans('admin.opens'),
                    'closes' => trans('admin.closes'),
                ],
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Category::getStatusOptions(),
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
