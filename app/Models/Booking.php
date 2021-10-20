<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\MainTrait;
use App\Models\Interfaces\StatusInterface;

class Booking extends Model implements StatusInterface
{
    use CrudTrait, MainTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'bookings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'available_times' => 'array',
    ];

	public $appends = ['current_available_times'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
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
    public static function getTerraceOptions(): array
    {
        return [
            self::YES => trans('admin.yes'),
            self::NO => trans('admin.no'),
        ];
    }

	public function getCurrentAvailableTimesAttribute()
	{
		$times = json_decode($this->attributes['available_times']);
		$available_times = [];
		foreach($times as $time) {
			if (time() <= strtotime($time->time)) {
				$available_times[] = $time;
			}
		}
		return $available_times;
	}
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
