<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessOrderSend;

class Order extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const NOT_PAID = 0;
    const PAID = 1;

    const WITHSELF = 1;
    const INSIDE = 0;

    const SUCCESS = 2;
    const CANCELED = 3;
    const ON_PROCESS = 1;


    const NEW_ORDER = 1;
    const APPROVED = 2;
    const IN_PROCESS = 3;

    const CASH=0;
    const CARD=1;

    protected $table = 'orders';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'products' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::updated(function ($order) {
            if ($order->delivery_status == self::SUCCESS) {
                $user = $order->user;
                if ($user) {
                    Mail::to($user->email)->send(new SuccessOrderSend($order));
                }
            }
        });
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public static function getPaymentStatus()
    {
        return [
            self::NOT_PAID => trans('admin.not_paid'),
            self::PAID => trans('admin.paid'),
        ];
    }

    public static function getStatusOptions()
    {
        return [
            self::NEW_ORDER => trans('admin.new_order'),
            self::APPROVED => trans('admin.approved'),
            self::IN_PROCESS => trans('admin.in_process'),
            self::CANCELED => trans('admin.canceled'),   
        ];
    }

    public static function getDeliveryMethods()
    {
        return [
            self::WITHSELF => trans('admin.withself'),
            self::INSIDE => trans('admin.inside'),
        ];
    }

    public static function getDeliveryStatus()
    {
        return [
            self::ON_PROCESS => trans('admin.on_process'),
            self::SUCCESS => trans('admin.success'),
            self::CANCELED => trans('admin.canceled'),
        ];
    }

    public static function getPaymentTypeOptions()
    {
        return [
            self::CASH => trans('admin.cash'),
            self::CARD => trans('admin.card'),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
