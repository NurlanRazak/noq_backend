<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Menu;
use App\Models\Place;

class MenuCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
        $user = backpack_user();
        if ($user->place_id) {
            $this->crud->addClause("where", "place_id", $user->place_id);
        }
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select',
                'entity' => 'place',
                'attribute' => 'name',
                'model' => Place::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Menu::getStatusOptions(),
            ],
        ]);

        parent::setUpListOperation();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MenuRequest::class);

        CRUD::addFields([
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select2',
                'entity' => 'place',
                'attribute' => 'name',
                'model' => Place::class,
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
                'type' => 'text',
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Menu::getStatusOptions(),
            ],
        ]);
    }
}
