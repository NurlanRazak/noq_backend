<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TableRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TableCrudController extends DefaultCrudController
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
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'code',
                'label' => trans('admin.code'),
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(TableRequest::class);

        CRUD::addFields([
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select2',
                'entity' => 'place',
                'attribute' => 'name',
            ],
            [
                'name' => 'name',
                'label' => trans('admin.name'),
                'type' => 'text',
            ],
            [
                'name' => 'code',
                'label' => trans('admin.code'),
                'type' => 'text',
            ],
        ]);
    }
}
