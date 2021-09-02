<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Booking;

class BookingCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
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
                'name' => 'terrace',
                'label' => trans('admin.terrace'),
                'type' => 'select_from_array',
                'options' => Booking::getTerraceOptions(),
            ],
            [
                'name' => 'max_people',
                'label' => trans('admin.max_people'),
            ],
            [
                'name' => 'available_times',
                'label' => trans('admin.available_times'),
                'type' => 'table',
                'columns' => [
                    'time' => 'Время'
                ],
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Booking::getStatusOptions(),
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookingRequest::class);

        CRUD::addFields([
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select2',
                'entity' => 'place',
                'attribute' => 'name',
            ],
            [
                'name' => 'terrace',
                'label' => trans('admin.terrace'),
                'type' => 'select2_from_array',
                'options' => Booking::getTerraceOptions(),
            ],
            [
                'name' => 'max_people',
                'label' => trans('admin.max_people'),
            ],
            [
                'name' => 'available_times',
                'label' => trans('admin.available_times'),
                'type'  => 'repeatable',
                'fields' => [
                    [
                        'name'    => 'time',
                        'type'  => 'time',
                        'label'   => 'Время',
                    ],
                ],

                // optional
                'new_item_label'  => 'Добавить время', // customize the text of the button
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Booking::getStatusOptions(),

            ],
        ]);
    }
}
