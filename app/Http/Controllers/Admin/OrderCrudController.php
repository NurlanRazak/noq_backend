<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Order;

class OrderCrudController extends DefaultCrudController
{

    public function setup()
    {
        parent::setup();
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'phone',
            ],
            [
                'name' => 'products',
                'label' => trans_choice('admin.products', 2),
            ],
            [
                'name' => 'to_time',
                'label' => trans('admin.to_time'),
            ],
            [
                'name' => 'total_amount',
                'label' => trans('admin.total_amount'),
                'suffix' => ' тг',
            ],
            [
                'name' => 'payment_status',
                'label' => trans('admin.payment_status'),
                'type' => 'select_from_array',
                'options' => Order::getPaymentStatus(),
            ],
            [
                'name' => 'delivery_method',
                'label' => trans('admin.delivery_method'),
                'type' => 'select_from_array',
                'options' => Order::getDeliveryMethods(),
            ],
            [
                'name' => 'delivery_status',
                'label' => trans('admin.delivery_status'),
                'type' => 'select_from_array',
                'options' => Order::getDeliveryStatus(),
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);

        CRUD::addFields([
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select2',
                'entity' => 'user',
                'attribute' => 'phone',
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
            [
                'name' => 'to_time',
                'label' => trans('admin.to_time'),
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
            [
                'name' => 'products',
                'label' => trans_choice('admin.products', 2),
                'type'            => 'table',
                'entity_singular' => trans('admin.product'), // used on the "Add X" button
                'columns'         => [
                    'product'  => trans('admin.product'),
                    'quantity'  => trans('admin.quantity'),
                    'price' => trans('admin.price'),
                ],
            ],
            [
                'name' => 'total_amount',
                'label' => trans('admin.total_amount'),
                'suffix' => ' тг',
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
            [
                'name' => 'payment_status',
                'label' => trans('admin.payment_status'),
                'type' => 'select2_from_array',
                'options' => Order::getPaymentStatus(),
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
            [
                'name' => 'delivery_method',
                'label' => trans('admin.delivery_method'),
                'type' => 'select2_from_array',
                'options' => Order::getDeliveryMethods(),
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
            [
                'name' => 'delivery_status',
                'label' => trans('admin.delivery_status'),
                'type' => 'select2_from_array',
                'options' => Order::getDeliveryStatus(),
                'wrapper' => [
                    'class' => 'form-group col-sm-6',
                ],
            ],
        ]);
    }
}