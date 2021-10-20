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
        $user = backpack_user();
        if ($user->place_id) {
            $this->crud->addClause("where", "place_id", $user->place_id);
            $orders = Order::where('place_id', $user->place_id)->get();
            $all_orders_cnt = $orders->count();
            $today_orders_cnt = $orders->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();
            $all_revenue = $orders->where('payment_status', 1)->sum('total_amount');
            $day_revenue = $orders->where('payment_status', 1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->sum('total_amount');
            $cash_cnt = $orders->where('payment_type', 0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();
            $card_cnt = $orders->where('payment_type', 1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();

            $this->data['widgets']['before_content'][] = [ 'type' => 'div', 'class' => 'row', 'content' => [
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Всего заказов',
                    'description' => "{$all_orders_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Заказы за день',
                    'description' => "{$today_orders_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Доход',
                    'description' => "{$all_revenue}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Доход за день',
                    'description' => "{$day_revenue}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Оплатили картой',
                    'description' => "{$card_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Оплатили наличкой',
                    'description' => "{$cash_cnt}",
                ],
            ]];
        } else {
            $orders = Order::all();
            $all_orders_cnt = $orders->count();
            $today_orders_cnt = $orders->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();
            $all_revenue = $orders->where('payment_status', 1)->sum('total_amount');
            $day_revenue = $orders->where('payment_status', 1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->sum('total_amount');
            $cash_cnt = $orders->where('payment_type', 0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();
            $card_cnt = $orders->where('payment_type', 1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->count();

            $this->data['widgets']['before_content'][] = [ 'type' => 'div', 'class' => 'row', 'content' => [
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Всего заказов',
                    'description' => "{$all_orders_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Заказы за день',
                    'description' => "{$today_orders_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Доход',
                    'description' => "{$all_revenue}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Доход за день',
                    'description' => "{$day_revenue}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Оплатили картой',
                    'description' => "{$card_cnt}",
                ],
                [
                    'type'        => 'progress_white',
                    'class'       => 'card mb-2',
                    'value'       => 'Оплатили наличкой',
                    'description' => "{$cash_cnt}",
                ],
            ]];
        }
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'email',
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
                'name' => 'to_time',
                'label' => trans('admin.to_time'),
            ],
            [
                'name' => 'total_amount',
                'label' => trans('admin.total_amount'),
                'suffix' => ' тг',
            ],
            [
                'name' => 'comment',
                'label' => 'Комментарий',
            ],
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select_from_array',
                'options' => Order::getStatusOptions(),
            ],
            [
                'name' => 'payment_type',
                'label' => trans('admin.payment_type'),
                'type' => 'select_from_array',
                'options' => Order::getPaymentTypeOptions(),
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
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select',
                'entity' => 'place',
                'attribute' => "name",
            ],
            [
                'name' => 'table_id',
                'label' => trans('admin.table'),
                'type' => 'select',
                'entity' => 'table',
                'attribute' => 'name',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);

        CRUD::addFields([
            [
                'name' => 'status',
                'label' => trans('admin.status'),
                'type' => 'select2_from_array',
                'options' => Order::getStatusOptions(),
            ],
            [
                'name' => 'place_id',
                'label' => trans('admin.place'),
                'type' => 'select2',
                'entity' => 'place',
                'attribute' => "name",
            ],
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select2',
                'entity' => 'user',
                'attribute' => 'email',
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
                ],
            ],
            [
                'name' => 'table_id',
                'label' => trans('admin.table'),
                'type' => 'select2',
                'entity' => 'table',
                'attribute' => 'name',
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
                ],
            ],
            [
                'name' => 'to_time',
                'label' => trans('admin.to_time'),
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
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
                'name' => 'payment_type',
                'label' => trans('admin.payment_type'),
                'type' => 'select2_from_array',
                'options' => Order::getPaymentTypeOptions(),
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
                ],
            ],
            [
                'name' => 'delivery_method',
                'label' => trans('admin.order_method'),
                'type' => 'select2_from_array',
                'options' => Order::getDeliveryMethods(),
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
                ],
            ],
            [
                'name' => 'delivery_status',
                'label' => trans('admin.order_status'),
                'type' => 'select2_from_array',
                'options' => Order::getDeliveryStatus(),
                'wrapper' => [
                    'class' => 'form-group col-sm-4',
                ],
            ],
            [
                'name' => 'comment',
                'label' => 'Комментарий',
            ],
        ]);
    }
}
