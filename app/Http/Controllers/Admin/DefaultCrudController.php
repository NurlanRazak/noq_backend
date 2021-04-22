<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

class DefaultCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $class = str_replace('CrudController', '', class_basename(static::class));
        $this->crud->setModel("App\Models\\{$class}");
        $class = strtolower($class);
        $this->crud->setRoute(config('backpack.base.route_prefix'). "/{$class}");
        $this->crud->setEntityNameStrings(trans_choice('admin.' . Str::plural($class), 1), trans_choice('admin.' . Str::plural($class), 2));
        $this->attributes = \DB::getSchemaBuilder()->getColumnListing($this->crud->model->getTable());

        if (in_array('lft', $this->attributes)) {
            $this->crud->addClause('orderBy', 'lft');
            $this->crud->addClause('orderBy', 'updated_at', 'desc');
        }
    }

    protected function setUpListOperation()
    {
        $this->crud->addColumn([
            'name' => 'row_number',
            'label' => '#',
            'type' => 'row_number',
        ])->makeFirstColumn();

        $this->crud->addColumns([
           [
               'name' => 'updated_at',
               'label' => trans('admin.updated_at'),
               'type' => 'datetime',
           ],
           [
               'name' => 'created_at',
               'label' => trans('admin.created_at'),
               'type' => 'datetime',
           ],
       ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setUpListOperation();
    }
}
