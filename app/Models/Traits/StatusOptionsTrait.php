<?php

namespace App\Models\Traits;

trait StatusOptionsTrait
{
    public static function getStatusOptions() : array
    {
        return [
            self::PUBLISHED => trans('admin.published'),
            self::DRAFT => trans('admin.draft'),
        ];
    }
}
