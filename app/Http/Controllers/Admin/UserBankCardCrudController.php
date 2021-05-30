<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserBankCardRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserBankCardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserBankCardCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\UserBankCard::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/userbankcard');
        CRUD::setEntityNameStrings(trans_choice('admin.user_bank_cards', 1), trans_choice('admin.user_bank_cards', 2));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'row_number',
                'label' => '#',
                'type' => 'row_number'
            ],
            [
                'name' => 'user_id',
                'label' => trans('admin.user'),
                'type' => 'select',
                'entity' => 'user',
                'attribute' => 'email',
                'model' => \App\Models\User::class,
            ],
            [
                'name' => 'card_num',
                'label' => trans('admin.card_num'),
            ],
            [
                'name' => 'card_name',
                'label' => trans('admin.card_name'),
                'type' => 'text'
            ],
			[
				'name' => 'country',
				'label' => trans('admin.card_country'),
				'type' => 'text'
			],
			[
				'name' => 'bank',
				'label' => trans('admin.card_bank'),
				'type' => 'text',
			],
			[
				'name' => 'year',
				'label' => trans('admin.card_year'),
			],
			[
				'name' => 'month',
				'label' => trans('admin.card_month'),
			],
            [
                'name'        => 'enabled',
                'label'       => trans('admin.status'),
                'type'        => 'select_from_array',
                'options'     => ['Не активен', 'Активен'],
                'allows_null' => false,
                'default'     => 1,
            ],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserBankCardRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
