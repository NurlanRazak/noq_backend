<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'name',
                'label' => trans("admin.name"),
            ],
            [
                'name' => 'email',
                'label' => trans("admin.email"),
            ],
            [
                'name' => 'place_id',
                'label' => trans("admin.place"),
                'type' => 'select',
                'entity' => 'place',
                'attribute' => "name",
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

         CRUD::addFields([
             [
                 'name' => 'name',
                 'label' => trans("admin.name"),
             ],
             [
                 'name' => 'email',
                 'label' => trans("admin.email"),
             ],

             [
                 'name' => 'place_id',
                 'label' => trans("admin.place"),
                 'type' => 'select2',
                 'entity' => 'place',
                 'attribute' => "name",
             ],
         ]);
    }
}
