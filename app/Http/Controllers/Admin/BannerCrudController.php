<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BannerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Banner;

class BannerCrudController extends DefaultCrudController
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
                'options' => Banner::getStatusOptions(),
            ],
        ]);

        parent::setupListOperation();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BannerRequest::class);

        CRUD::addFields([
            [
                'name' => 'name',
                'label' => trans('admin.name'),
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'link',
                'label' => trans('admin.link'),
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
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
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Banner::getStatusOptions(),
            ],
        ]);
    }
}
