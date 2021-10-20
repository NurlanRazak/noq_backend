<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\BookingList;

class BookingListCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
        CRUD::setModel(\App\Models\BookingList::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bookinglist');
        CRUD::setEntityNameStrings(trans_choice('admin.bookinglists', 1), trans_choice('admin.bookinglists', 2));
    }


    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'name',
            ],
            [
                'name' => 'terrace',
                'label' => trans('admin.terrace'),
                'type' => 'select_from_array',
                'options' => BookingList::getTerraceOptions(),
            ],
            [
                'name' => 'people',
                'label' => trans('admin.people'),
            ],
            [
                'name' => 'at_time',
                'label' => trans('admin.at_time'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => BookingList::getStatusOptions(),
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookingListRequest::class);

        CRUD::addFields([
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'name',
            ],
            [
                'name' => 'terrace',
                'label' => trans('admin.terrace'),
                'type' => 'select2_from_array',
                'options' => BookingList::getTerraceOptions(),
            ],
            [
                'name' => 'people',
                'label' => trans('admin.people'),
            ],
            [
                'name' => 'at_time',
                'label' => trans('admin.at_time'),
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => BookingList::getStatusOptions(),
            ],
        ]);
    }

}
