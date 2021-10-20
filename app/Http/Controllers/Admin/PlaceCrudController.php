<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlaceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Place;

class PlaceCrudController extends DefaultCrudController
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
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'city_id',
                'label' => trans('admin.city'),
                'type' => 'select',
                'entity' => 'city',
                'attribute' => 'name',
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
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Place::getStatusOptions(),
            ],
        ]);

        parent::setup();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PlaceRequest::class);

        CRUD::addFields([
            [
                'name' => 'name',
                'label' => trans('admin.name'),
            ],
            [
                'name' => 'city_id',
                'label' => trans('admin.city'),
                'type' => 'select2',
                'entity' => 'city',
                'attribute' => 'name',
            ],
            [
                'name' => 'image',
                'label' => trans('admin.image'),
                'type' => 'image',
                'disk' => 'uploads',
                'crop' => true,
                'aspect_ratio' => 0,
            ],
            [
                'name' => 'description',
                'label' => trans('admin.description'),
                'type' => 'textarea',
            ],
            [
                'name' => 'opening_hours',
                'label' => trans('admin.opening_hours'),
                'type' => 'table',
                // 'entity_singular' => trans('admin.add'),
                'columns' => [
                    'days' => trans('admin.days'),
                    'opens' => trans('admin.opens'),
                    'closes' => trans('admin.closes'),
                ],
            ],
            [
                'name' => 'atmosphere',
                'label' => trans('admin.atmosphere'),
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'prefix'     => "*",
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'food',
                'label' => trans('admin.food'),
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'prefix'     => "*",
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'services',
                'label' => trans('admin.services'),
                'type' => 'number',
                'attributes' => ["step" => "any"], // allow decimals
                'prefix'     => "*",
                'wrapper' => ['class' => 'form-group col-md-4'],
            ],
            [
                'name' => 'address',
                'label' => trans('admin.address'),
            ],
            [
                'name' => 'lat',
                'label' => trans('admin.lat'),
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'lng',
                'label' => trans('admin.lng'),
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'phone',
                'label' => trans('admin.phone'),
                'type' => 'number',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Place::getStatusOptions(),
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
        ]);
    }
}
